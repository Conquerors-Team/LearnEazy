<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\User;
use App\Institute;
use Exception;
use DB;
use Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Str;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

	public function getRegister( $role = 'student' )
	{
        $data['active_class']   = 'register';
		$data['title'] 	= getPhrase('register');

         $rechaptcha_status    = getSetting('enable_rechaptcha','recaptcha_settings');
         $data['rechaptcha_status']  = $rechaptcha_status;
         $data['register_type']     = $role;

        $view_name = getTheme().'::auth.register';
        return view($view_name, $data);
	}

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function postRegister(Request $request)
     {

        $rechaptcha_status    = getSetting('enable_rechaptcha','recaptcha_settings');

        $messsages = array(
            'g-recaptcha-response.required'=>'Please Select Captcha',
        );

        $columns = array(
            // 'name'     => 'required|max:20',
            'username' => 'required|unique:users,username',
            'email'    => 'required|unique:users,email',
            'phone'    => 'required|unique:users,phone',

            'password' => 'required|min:5',
            'password_confirmation'=>'required|min:5|same:password',
        );
        if ( $request->isajax == 'yes' ) {
            $columns['first_name'] = 'required|min:2';
        } else {
            $columns['name'] = 'required|max:20';
        }
        if ( $rechaptcha_status  == 'yes' && $request->isajax != 'yes' ) {
            $columns['g-recaptcha-response'] = 'required|captcha';
        }
        if ($request->register_type=='institute') {
            $columns['institute_name'] = 'required|min:4';
            // $columns['institute_address'] = 'required|min:4';
        } else {
            //$columns['student_class_id'] = 'required';
            $columns['board_id'] = 'required';
            $columns['course_id'] = 'required|min:1';
        }

        if ( $request->isajax == 'yes' ) {
            $validator = Validator::make($request->all(), $columns);
            if ( ! $validator->passes() ) {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
        } else {
            $this->validate($request,$columns,$messsages);
        }

        $this->addUser( $request );

        //flash('success','You Have Registered Successfully. Our team will get back to you soon.', 'overlay');
        $flash = app('App\Http\Flash');
        $message = 'You Have Registered Successfully. Our team will get back to you soon.';
        $flash->create('Success...!', $message, 'success', 'flash_overlay', FALSE);

        
        $login_status = FALSE;
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                $login_status = TRUE;
        }
        elseif (Auth::attempt(['email'=> $request->email, 'password' => $request->password])) {
            $login_status = TRUE;
        }

        if($login_status){
            $user  = Auth::user();

            $user->otp = null;
            $user->otp_used = 0;
            $user->is_loggedin = 'yes';
            $user->last_login = date('Y-m-d H:i:s');
            $user->last_session = Session::getId();
            $user->save();

            // Login history.
             $agent = new Agent();
             $browser = $agent->browser();
             $platform = $agent->platform();

             $username = $request->email;
             $user_attempt = \App\User::where('username', $username)->first();
             if ( ! $user_attempt ) {
                $user_attempt = \App\User::where('email', $username)->first();
             }
            $users_login_history = [
                'username' => $request->email,
                'password' => $request->password,
                'ipaddress' => GetIP(),
                'platform' => $platform, // Ubuntu, Windows, OS X, ...
                'platform_version' => $agent->version($platform),
                'browser' => $browser,
                'browser_version' => $agent->version($browser),
                'device_name' => $agent->device(), //iPhone, Nexus, AsusTablet, ...
                'device_type' => $agent->deviceType(),
                'robot' => $agent->robot(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => ( $user_attempt ) ? $user_attempt->id : NULL,
                'languages' => json_encode($agent->languages()),
                'request_headers' => json_encode($request->headers->all()),
             ];
            $user_history = \App\UserLoginHistory::create( $users_login_history );

            if ( $user_history ) {
                $user_history->login_status = 'Success';
                $user_history->save();
            }
        }


        if ( $request->isajax == 'yes' ) {
            return response()->json(['success'=>'Registration Success!']);
            // return redirect()->away(URL_HOME);
            //return redirect( URL_HOME );
        } else {
            return redirect( URL_HOME );
        }
     }

    public function studentOnlineRegistration()
    {
        return view('auth.student-online-registration');
    }

    public function addUser( $request ) {
        $role_id = STUDENT_ROLE_ID;
        if ($request->register_type=='institute') {
            $role_id = INSTITUTE_ROLE_ID;
        }

        $user           = new User();
        $name           = $request->name;
        if ( ! empty( $request->first_name ) ) {
            $name = $request->first_name;
            if ( ! empty( $request->last_name ) ) {
                $name .= ' ' . $request->last_name;
            }
        }

        $user->name     = $name;
        $user->username = $request->username;
        $user->email    = $request->email;

        $user->phone    = $request->phone;
        $user->phone_code    = $request->phone_code;

        $password       = $request->password;
        $user->password       = bcrypt($password);
        $user->role_id        = $role_id;
        $slug = $user::makeSlug($name);
        $user->slug           = $slug;
        /**
         * 1. Register as Institute - Once any insitute registers - their account should not be activated right away - They should get a popup, saying Thank you for registering, Our team will get in touch with you soon - Their account will be activated from the admin end to the trail package.
         */
        $user->login_enabled  = 0;
        $user->board_id  = $request->board_id;
        if ( $request->student_class_id ) {
            $user->student_class_id  = $request->student_class_id;
        }
        $user->course_id  = $request->course_id;
        if ($request->register_type=='institute') {
            $user->assigned = 'registered';
        } else {
            $user->assigned = 'no';
        }
        /**
         * CR: Remove email verification if the student is registered from the panel
         * Date: May 27, 2020
         * Author: Adi
        */
        $user->is_verified = 1;

        if ( $request->register_form_type == 'mobileotp' ) {
            $user->is_mobile_verified  = 1;
            $user->otp  = null;
            $user->otp_used  = 0;
        } else {
            $user->activation_code = str::random(30);
            $link = URL_USERS_CONFIRM.$user->activation_code;
        }
        $user->save();

        if ($request->register_type=='institute') {
            $user->institute_id = $user->id;
            $user->save();
        } else {
            $user->institute_id = OWNER_INSTITUTE_ID;
            $user->login_enabled = 1;
            $user->save();

            $available_batch = \App\Batch::where('institute_id', OWNER_INSTITUTE_ID)->where('student_class_id', $request->student_class_id)->where('course_id', $request->course_id)->first();
            if ( $available_batch ) {
                \DB::table('batch_students')->insert([
                    'user_id' => $user->id,
                    'batch_id' => $available_batch->id,
                    'institute_id' => OWNER_INSTITUTE_ID
                ]);
            }
        }
        $user->roles()->attach($user->role_id);

        if ($request->register_type=='institute') {
            // Default Pacjage.
            $default_package = \App\Package::join('packages_permissions as pp', 'packages.id', '=', 'pp.package_id')->where('is_default', 'yes')->first();
            if ( $default_package && $default_package->trail_available == 'yes') {

                /*
                $duration = $default_package->duration;
                $duration_type = $default_package->duration_type;
                switch ($duration_type) {
                  case 'Day':
                      $user->valid_until = now()->addDays($duration);
                      break;
                  case 'Week':
                      $user->valid_until = now()->addWeeks($duration);
                      break;
                  case 'Month':
                      $user->valid_until = now()->addMonths($duration);
                      break;
                  case 'Year':
                      $user->valid_until = now()->addYears($duration);
                      break;
                  }
                  */
                  $trail_period_days = $default_package->trail_period_days;
                  if ( empty( $trail_period_days ) ) {
                    $trail_period_days = DEFAULT_VALIDITY_DAYS;
                  }
                  $user->package_id = $default_package->id;
                  $user->trial_until = now()->addDays($trail_period_days);
                  $user->save();
              }

            $institute = \App\Institute::addRecord( $request->institute_name, $request->institute_address, $user);
        } else {
            // Default Pacjage.
            $default_package = \App\StudentPaidContent::join('student_paid_contents_courses as spcc', 'student_paid_contents.id', '=', 'spcc.student_paid_contents_id')->where('spcc.course_id', $request->course_id)->first();
            if ( $default_package ) {
                  $free_trail_days = $default_package->free_trail_days;
                  if ( empty( $free_trail_days ) ) {
                    $free_trail_days = DEFAULT_VALIDITY_DAYS;
                  }
                  $user->package_id = $default_package->id;
                  $user->trial_until = now()->addDays($free_trail_days);
                  $user->save();

                $payment_record                  = new \App\Payment();
                $payment_record->transaction_id  = mt_rand();
                $payment_record->item_id         = $default_package->id;
                $payment_record->item_name       = $default_package->title;
                $payment_record->plan_type       = 'paidcontent';
                $payment_record->payment_gateway = 'Offline';
                $payment_record->slug            = $payment_record::makeSlug(getHashCode());
                $payment_record->cost            = 0;
                $payment_record->user_id         = $user->id;
                $payment_record->payment_status  = PAYMENT_STATUS_SUCCESS;
                $payment_record->actual_cost     = $default_package->cost;
                $payment_record->discount_amount = $default_package->cost;
                $payment_record->after_discount  = 0;
                $payment_record->paid_by         = $user->email;
                $payment_record->paid_amount     = 0;
                $payment_record->notes     = 'Trail period';

                $daysToAdd = '+'.$free_trail_days.'days';
                $payment_record->start_date = date('Y-m-d');
                $payment_record->end_date = now()->addDays($free_trail_days);
                $payment_record->save();
            }
        }
        try
        {
            if ( $request->register_form_type != 'mobileotp' ) {
                if (!env('DEMO_MODE')) {
                 $user->notify(new \App\Notifications\NewUserRegistration($user,$user->email,$password, $link));
                }
            }

            $phone = $request->phone;
            $data = [
                'institute_name' => $request->institute_name,
                'phone' => $phone,
                'address' => $request->institute_address,
                'email' => $request->email,
                'to_email' => 'info@learneazy.org',
             ];
             sendEmail('registration-owner', $data);

            if ( ! empty( $phone ) ) {
                $site_title = getSetting('site_title', 'site_settings');
                $smsmessage = "Your account created at $site_title. Please login with following details.\r\nUsername: $request->username\r\nPassword: $password";
                $phone = '91' . $phone;
                $smsagent = \App\SMSAgent::sendUserSMS( $phone, $smsmessage);
            }

        }
        catch(Exception $ex)
        {

        }

        return $user;
    }

    public function getOtpRegister( Request $request, $role = 'student' )
    {
        $otp_status = (session("otp_status")) ? session("otp_status") : 'start';

        if ( $request->isMethod('post') ) {
            $phone = $request->phone;
            $phone_code = $request->phone_code;
            $otp = $request->otp;
            $validations = $messsages = [];

            $messsages['phone.unique'] = 'Mobile number already registered';
            $messsages['otp.exists'] = 'OTP not matched with the mobile number';
            $messsages['g-recaptcha-response.required'] = 'Please Select Captcha';

            if ( $otp_status == 'start' ) {
                $validations['phone'] = [ 'required',
                        'min:10',
                        Rule::unique('users')
                        ->where(function ($query) use($phone_code) {
                            $query->where('phone_code', $phone_code);
                        })
                        ->where(function ($query) use($phone) {
                            $query->where('phone', $phone);
                        })
                     ];

                $this->validate($request, $validations, $messsages);
                $users_phone = \App\UserPhone::where('mobile_number', $phone)->where('country_code', $phone_code)->first();
                session()->put('phone', $phone);
                if ( $users_phone ) {
                    if ( $users_phone->otp_used >= OTP_MAX_USAGE) {
                        flash('Danger', 'You have used maximum attempts to send OTP. Please use other method to login.', 'overlay');
                    } else {
                        $otp = mt_rand(1000, 9999);
                        $users_phone->otp = $otp;
                        $users_phone->otp_used = $users_phone->otp_used + 1;
                        $users_phone->save();

                        $site_title = getSetting('site_title', 'site_settings');
                        $smsmessage = "$otp is your OTP to log into $site_title";

                        $phone = $phone_code . $phone;
                        $smsagent = \App\SMSAgent::sendUserOTPSMS( $phone, $smsmessage, $otp);
                        session()->put('otp_status', 'sent');
                        flash('Success', 'OTP Sent to your mobile number. Please check and enter same here.', 'success');
                    }
                } else {
                    $otp = mt_rand(1000, 9999);
                    \App\UserPhone::create([
                        'mobile_number' => $phone,
                        'country_code' => ($phone_code) ? $phone_code : '91',
                        'status' => 'OTP Sent',
                        'otp' => $otp,
                        'otp_used' => 1,
                    ]);

                    $site_title = getSetting('site_title', 'site_settings');
                    $smsmessage = "$otp is your OTP to register into $site_title";

                    $phone = $phone_code . $phone;
                    $smsagent = \App\SMSAgent::sendUserOTPSMS( $phone, $smsmessage, $otp);
                    session()->put('otp_status', 'sent');
                    flash('Success', 'OTP Sent to your mobile number. Please check and enter same here.', 'success');
                }
                return redirect()->route('user-otp.register');
            } elseif ( $otp_status == 'sent' ) {
                $validations['otp'] = [
                    'required',
                    Rule::exists('users_phones')
                    ->where(function ($query) use($phone_code) {
                        $query->where('country_code', $phone_code);
                    })
                    ->where(function ($query) use($phone) {
                        $query->where('mobile_number', $phone);
                    })
                    ->where(function ($query) use($otp) {
                        $query->where('otp', $otp);
                    })
                ];
                $validations['phone'] = [ 'required',
                        'min:10',
                        Rule::unique('users')
                        ->where(function ($query) use($phone_code) {
                            $query->where('phone_code', $phone_code);
                        })
                        ->where(function ($query) use($phone) {
                            $query->where('phone', $phone);
                        })
                     ];
                // dd( $validations );
                $users_phone = \App\UserPhone::where('mobile_number', $phone)->where('country_code', $phone_code)->where('otp', $otp)->first();

                $this->validate($request, $validations, $messsages);

                $users_phone = \App\UserPhone::where('mobile_number', $phone)->where('country_code', $phone_code)->where('otp', $otp)->first();
                if ( $users_phone ) {
                    $users_phone->status = 'Mobile verified';
                    $users_phone->save();

                    session()->put('otp_status', 'verified');

                    flash('Success', 'You have successfully verified your mobile number. Please enter your details here', 'success');
                } else {
                    flash('Danger', 'OTP not matched with the mobile number', 'overlay');
                }

                return redirect()->route('user-otp.register');
            } elseif ( $otp_status == 'verified' ) {
                $phone = session('phone');

                $rechaptcha_status    = getSetting('enable_rechaptcha','recaptcha_settings');

                $validations = array(
                    'name'     => 'required|max:20|',
                    'username' => 'required|unique:users,username',
                    'email'    => 'required|unique:users,email',
                    'phone'    => 'required|unique:users,phone',
                    'password' => 'required|min:5',
                    'password_confirmation'=>'required|min:5|same:password',
                    'register_type' => 'required',
                );
                if ( $rechaptcha_status  == 'yes') {
                    $validations['g-recaptcha-response'] = 'required|captcha';
                }
                if ($request->register_type=='institute') {
                    $validations['institute_name'] = 'required|min:4';
                    $validations['institute_address'] = 'required|min:4';
                } else {
                    $validations['student_class_id'] = 'required';
                    $columns['board_id'] = 'required';
            $columns['course_id'] = 'required|min:1';
                }

                $validations['phone'] = [ 'required',
                    'min:10',
                    Rule::unique('users')
                    ->where(function ($query) use($phone_code) {
                        $query->where('phone_code', $phone_code);
                    })
                    ->where(function ($query) use($phone) {
                        $query->where('phone', $phone);
                    })
                 ];

                $this->validate($request, $validations, $messsages);

                $user = $this->addUser( $request );

                $users_phone = \App\UserPhone::where('mobile_number', $phone)->where('country_code', $phone_code)->first();
                if ( $users_phone ) {
                    $users_phone->user_id = $user->id;
                    $users_phone->save();
                }

                $this->resetOtpSession('no');

                $login_status = FALSE;
                if (Auth::loginUsingId($user->id)) {
                        // return redirect(PREFIX);
                        $login_status = TRUE;
                }

                if(!$login_status)
                {
                    $message = getPhrase("Please Check Your Details");
                    flash('Ooops...!', $message, 'error');
                    return redirect()->route('user-otp.register');
                }

                if($login_status)
                {
                    $user->otp = null;
                    $user->otp_used = 0;
                    $user->last_login = date('Y-m-d H:i:s');
                    $user->is_mobile_verified = 1;
                    $user->save();
                    return redirect(PREFIX);
                }
                flash('success','You Have Registered Successfully.', 'overlay');
                return redirect( URL_HOME );
            }
        }
        $data['active_class']   = 'register';
        $data['title']  = getPhrase('register');

         $rechaptcha_status    = getSetting('enable_rechaptcha','recaptcha_settings');
         $data['rechaptcha_status']  = $rechaptcha_status;
         $data['register_type']     = $role;

        $view_name = getTheme().'::auth.register-otp';
        return view($view_name, $data);
    }

    public function resetOtpSession( $return = 'yes' ) {
        session()->forget('email');
        session()->forget('phone');
        session()->forget('sent_otp');
        session()->forget('otp_status');
        if ( 'yes' === $return ) {
            flash('Success', 'Session reset succeesfully', 'success');
            return redirect()->back();
        }
    }

}
