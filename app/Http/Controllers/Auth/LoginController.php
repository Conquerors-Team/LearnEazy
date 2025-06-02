<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use \Auth;
use App\User;
use Socialite;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Validator;
use Jenssegers\Agent\Agent;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;
    use AuthenticatesUsers {
        logout as performLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
   protected $redirectTo = '/';
    protected $dbuser = '';
    protected $provider = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }





   /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255|unique:users',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        $type = 'student';
        if($data['is_student'])
            $type = 'parent';

        $role = getRoleData($type);

        $user           = new User();
        $user->name     = $data['name'];
        $user->username     = $data['username'];

        $user->email    = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->role_id  = $role;
        $user->slug     = $user->makeSlug($user->name);

        $user->save();

        $user->roles()->attach($user->role_id);
        try{
            $this->sendPushNotification($user);
        sendEmail('registration', array('user_name'=>$user->name, 'username'=>$data['username'], 'to_email' => $user->email, 'password'=>$data['password']));

          }
         catch(Exception $ex)
        {

        }

        flash('success','record_added_successfully', 'success');

        $options = array(
                            'name' => $user->name,
                            'image' => getProfilePath($user->image),
                            'slug' => $user->slug,
                            'role' => getRoleData($user->role_id),
                        );
        pushNotification(['owner','admin'], 'newUser', $options);
         return $user;
    }



      public function sendPushNotification($user)
     {
        if(getSetting('push_notifications', 'module')) {
          if(getSetting('default', 'push_notifications')=='pusher') {
              $options = array(
                    'name' => $user->name,
                    'image' => getProfilePath($user->image),
                    'slug' => $user->slug,
                    'role' => getRoleData($user->role_id),
                );

            pushNotification(['owner','admin'], 'newUser', $options);
          }
          else {
            $this->sendOneSignalMessage('New Registration');
          }
        }
     }


      //this view the login page
     public function getLogin($layout_type = '')
    {
        session()->forget('sent_otp');

        try{
         session()->put("layout_number",$layout_type);

         $data['active_class']       = 'login';
         $data['title']              = getPhrase('login');
         $rechaptcha_status          = getSetting('enable_rechaptcha','recaptcha_settings');
         $data['rechaptcha_status']  = $rechaptcha_status;

        // return view('auth.login',$data);
         $view_name = getTheme().'::auth.login';
        return view($view_name, $data);

        }catch (Exception $e) {

              return redirect( URL_UPDATE_DATABASE );
           }
    }


    /**
     * This is method is override from Authenticate Users class
     * This validates the user with username or email with the sent password
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postLogin(Request $request)
    {
        // dd($request->all());

        $columns = [
            'email' => 'required',
            'password' => 'required',
        ];
        $messsages = array(
            'g-recaptcha-response.required'=>'Please Select Captcha',
            );
        $rechaptcha_status    = getSetting('enable_rechaptcha','recaptcha_settings');

        if($rechaptcha_status == 'yes'){
             $columns['g-recaptcha-response'] = 'required|captcha';
         }

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

        if ( $request->isajax == 1 ) {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);
            if ( ! $validator->passes() ) {
                return response()->json(['error'=>$validator->errors()->all()]);
              }
        } else {
            $this->validate($request,$columns,$messsages);
        }

        $login_status = FALSE;
        if (Auth::attempt(['username' => $request->email, 'password' => $request->password])) {
                $login_status = TRUE;
        }
        elseif (Auth::attempt(['email'=> $request->email, 'password' => $request->password])) {
            $login_status = TRUE;
        }
// echo bcrypt('sprbhaskar'); echo '<br>';
        if(!$login_status)
        {
             $message = getPhrase("Please Check Your Details");
             if ( $user_history ) {
                $user_history->login_status = $message;
                $user_history->save();
             }
             if ( $request->isajax == 1 ) {
                return response()->json(['error'=>['customer' => $message]]);
             } else {
             flash('Ooops...!', $message, 'error');
             return redirect()->back();
            }
        }

        if($login_status){

            $user  = Auth::user();

            if($user->is_verified == 0){
                Auth::logout();
                $message = 'Please active your email verification to login into your account';
                if ( $user_history ) {
                    $user_history->login_status = $message;
                    $user_history->save();
                 }
                if ( $request->isajax == 1 ) {
                    return response()->json(['error'=>['customer' => $message]]);
                } else {
                    flash('Ooops...!', $message, 'overlay');
                    return redirect()->back();
                }
            }

            if($user->login_enabled == 0){
                Auth::logout();
                $message = 'Your account is blocked OR not yet activated. Please contact your administrator';
                if ( $user_history ) {
                    $user_history->login_status = $message;
                    $user_history->save();
                 }
                if ( $request->isajax == 1 ) {
                    return response()->json(['error'=>['customer' => $message]]);
                } else {
                flash('Ooops...!', $message, 'overlay');
                return redirect()->back();
                }
            }

            if ( ! $user->institute_id ) {
                Auth::logout();
                $message = 'your_institute_does_not_exist_please_contact_admin';
                if ( $user_history ) {
                $user_history->login_status = $message;
                $user_history->save();
             }
                if ( $request->isajax == 1 ) {
                    return response()->json(['error'=>['customer' => $message]]);
                } else {
                flash('Ooops...!', $message, 'overlay');
                return redirect()->back();
                }
            }elseif ( $user->institute_id ) {
                $institute = \App\Institute::find( $user->institute_id );

                if ( $institute && $institute->status == '0' ) {
                    Auth::logout();
                    $message = 'your_institute_is_inactive_please_contact_admin';
                    if ( $user_history ) {
                        $user_history->login_status = $message;
                        $user_history->save();
                     }
                    if ( $request->isajax == 1 ) {
                        return response()->json(['error'=>['customer' => $message]]);
                    } else {
                    flash('Ooops...!', $message, 'overlay');
                    return redirect()->back();
                    }
                }
            }
        }

        /**
         * The logged in user is student/admin/owner
         */
        if($login_status)
        {
            $user->otp = null;
            $user->otp_used = 0;
            $user->is_loggedin = 'yes';
            $user->last_login = date('Y-m-d H:i:s');
            $user->last_session = Session::getId();
            $user->save();

            if ( $user_history ) {
                $user_history->login_status = 'Success';
                $user_history->save();
             }
            $layout_num  = session()->get('layout_number');
            return redirect(PREFIX);
        }
}

    public function confirmUser($activation_code)
    {
        $record = User::where('activation_code', $activation_code)->first();

        if($isValid = $this->isValidRecord($record))
        return redirect($isValid);

        if($record->is_verified == 1){
             flash('Success', 'you_are_already_actived_your_account_you_can_login_into_your_account', 'success');
        } else {
             $record->is_verified  = 1;
             $record->save();

             if ( $record->institute_id ) {
                 $institute = \App\Institute::find( $record->institute_id );
                 if ( $institute ) {
                    $institute->status = 1;
                    $institute->save();
                 }
            }
             flash('Success', 'you_have_successfully_actived_your_account_you_can_login_into_your_account', 'success');
        }
        return redirect(URL_HOME);
    }

    public function isValidRecord($record)
    {
      if ($record === null) {
            flash('Ooops...!', 'account_is_not_existed_please_contact_your_admin', 'error');
             return URL_HOME;
        }
    }





     /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($logintype)
    {

        if(!getSetting($logintype.'_login', 'module'))
        {
            flash('Ooops..!', $logintype.'_login_is_disabled','error');
             return redirect(PREFIX);
        }
        $this->provider = $logintype;
        return Socialite::driver($this->provider)->redirect();

    }

     /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($logintype)
    {

        try{
        $user = Socialite::driver($logintype);


        if(!$user)
        {
            return redirect(PREFIX);
        }

        $user = $user->user();


         if($user)
         {

            if($this->checkIsUserAvailable($user)) {
                Auth::login($this->dbuser, true);
                flash('Success...!', 'log_in_success', 'success');
                return redirect(PREFIX);
            }
            flash('Ooops...!', 'faiiled_to_login', 'error');
            return redirect(PREFIX);
         }
     }
         catch (Exception $ex)
         {
            return redirect(PREFIX);
         }
    }

    public function checkIsUserAvailable($user)
    {

        $id         = $user->getId();
        $nickname   = $user->getNickname();
        $name       = $user->getName();
        $email      = $user->getEmail();
        $avatar     = $user->getAvatar();

        $this->dbuser = User::where('email', '=',$email)->first();

        if($this->dbuser) {
            //User already available return true
            return TRUE;
        }

        $newUser = array(
                            'name' => $name,
                            'email'=>$email,
                        );
        $newUser = (object)$newUser;

        $userObj = new User();
       $this->dbuser = $userObj->registerWithSocialLogin($newUser);
       $this->dbuser = User::where('slug','=',$this->dbuser->slug)->first();
       // $this->sendPushNotification($this->dbuser);
       return TRUE;

    }

    public function socialLoginCancelled(Request $request)
    {
         return redirect(PREFIX);
    }

    public function getLoginOtp($phone = '')
    {
         $data['active_class']       = 'login';
         $data['title']              = getPhrase('login');
         $rechaptcha_status          = getSetting('enable_rechaptcha','recaptcha_settings');
         $data['rechaptcha_status']  = $rechaptcha_status;

         $user = '';
         if ( ! empty( $phone ) ) {
            $user = \App\User::where('phone', $email)->first();
            if ( $user ) {
                session()->put("email", $user->email);
                session()->put("phone", $user->phone);
                // session()->put("sent_otp", 'yes');
            }
         }
         $data['user'] = $user;

         $sent_otp = (session("sent_otp")) ? session("sent_otp") : 'no';

         session()->put("sent_otp", $sent_otp);
         $data['sent_otp'] = $sent_otp;

         $data['email'] = (session("email")) ? session("email") : '';
         $data['phone'] = (session("phone")) ? session("phone") : '';
         $data['user_id'] = '';

         $view_name = getTheme().'::auth.login-otp';
        return view($view_name, $data);
    }


    /**
     * This is method is override from Authenticate Users class
     * This validates the user with username or email with the sent password
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postLoginOtp(Request $request)
    {
        // dd($request);
        $sent_otp = session("sent_otp");

        $messsages = array(
                'g-recaptcha-response.required'=>'Please Select Captcha',
            );
        if($sent_otp == 'no'){
            $rechaptcha_status    = getSetting('enable_rechaptcha','recaptcha_settings');

            $validations =  [
                // 'email' => 'required|email|max:255|exists:users,email',
                'phone' => 'required|max:255|exists:users,phone',
            ];
            if($rechaptcha_status == 'yes'){
                $validations['g-recaptcha-response'] = 'required|captcha';
            }
        }

        $phone = $request->phone;
        $phone_code = $request->phone_code;

        if($sent_otp == 'yes'){
            $validations =  [
                'phone' => 'required|max:255|exists:users,phone',
            ];
            // $validations['user_id'] = 'required|exists:users,id';
            $validations['otp'] = 'required|exists:users,otp';
            $messsages['otp.exists'] = getPhrase('OTP does not exists in our records');

            $otp = $request->otp;
            //dd($request->all());
            if ( ! empty( $phone ) && ! empty( $otp ) ) {
                $validations['otp'] = [
                    'required',
                    Rule::exists('users')
                    ->where(function ($query) use($phone_code) {
                        $query->where('phone_code', $phone_code);
                    })
                    ->where(function ($query) use($phone) {
                        $query->where('phone', $phone);
                    })
                    ->where(function ($query) use($otp) {
                        $query->where('otp', $otp);
                    }),
                ];
                $messsages['otp.exists'] = getPhrase('OTP does not match');
            } elseif ( ! empty( $phone ) ) {
                $validations['otp'] = [
                    'required',
                    Rule::exists('users')
                    ->where(function ($query) use($phone_code) {
                        $query->where('phone_code', $phone_code);
                    })
                    ->where(function ($query) use($phone) {
                        $query->where('phone', $phone);
                    }),
                ];
                $messsages['otp.exists'] = getPhrase('Please check phone');
            }
        }

        session()->put("phone", $phone);
        $this->validate($request, $validations, $messsages);

        $user = \App\User::where('phone', $phone)->first();

        if ( 'no' === $sent_otp ) {
            if ( $user->otp_used >= OTP_MAX_USAGE) {
                flash('Danger', 'You have used maximum attempts to send OTP. Please use other method to login.', 'error');
            } else {
                $otp = mt_rand(1000, 9999);
                $user->otp = $otp;
                $user->otp_used = $user->otp_used + 1;
                $user->save();

                $site_title = getSetting('site_title', 'site_settings');
                $smsmessage = "$otp is your OTP to log into $site_title";

                $phone = $phone_code . $phone;
                $smsagent = \App\SMSAgent::sendUserOTPSMS( $phone, $smsmessage, $otp);
                session()->put("sent_otp", 'yes');
                flash('Success', 'OTP Sent to your mobile number. Please check and enter same here.', 'success');
            }
            return redirect()->back();
        }

        $login_status = FALSE;
        if (Auth::loginUsingId($user->id)) {
                // return redirect(PREFIX);
                $login_status = TRUE;
        }

        if(!$login_status)
        {
            $message = getPhrase("Please Check Your Details");
            flash('Ooops...!', $message, 'error');
            return redirect()->back();
        }

        /**
         * Check if the logged in user is parent or student
         * if parent check if admin enabled the parent module
         * if not enabled show the message to user and logout the user
         */

        if($login_status){
            $user = Auth::user();
            if($user->login_enabled == 0){
                Auth::logout();
                flash('Ooops...!', 'your_account_is_inactive_please_contact_admin', 'overlay');
                return redirect()->back();
            }
        }

        /**
         * The logged in user is student/admin/owner
         */
        if($login_status)
        {
            $user->otp = null;
            $user->otp_used = 0;
            $user->is_loggedin = 'yes';
            $user->last_login = date('Y-m-d H:i:s');
            $user->is_mobile_verified = 1;
            $user->last_session = Session::getId();
            $user->save();

            $this->resetOtpSession('no');
            return redirect(PREFIX);
        }

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
