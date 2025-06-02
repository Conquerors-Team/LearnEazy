<?php

namespace App\Http\Controllers\PWA;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use \Auth;
use App\User;
use Socialite;
use Exception;
use App\LmsCategory;
use App\QuizCategory;
use Razorpay\Api\Api;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Validator;
use Jenssegers\Agent\Agent;
use App\Subject;
use App\Topic;
use App\Onlineclass;
use Carbon\Carbon;
use App\Quiz;
use Input;
use App\Instruction;
use DB;
use App\QuestionBank;
use App\QuizResult;
use Image;
use App\Payment;
use App\LmsSeries;
use App\LmsNote;
use App\LmsSeriesData;
use App\LmsContent;
use App\Notification;
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


        $login_status = FALSE;
        if (Auth::attempt(['username' => $request->email, 'password' => $request->password])) {
            $user = User::where('username',$request->email)->first();
                $login_status = TRUE;
        }
        elseif (Auth::attempt(['email'=> $request->email, 'password' => $request->password])) {
             $user = User::where('email',$request->email)->first();
            $login_status = TRUE;
        }

        if(!$login_status)
        {

             $message = getPhrase("Please Check Your Details");
             if ( $user_history ) {
                $user_history->login_status = $message;
                $user_history->save();
             }

             $resoponse = [ 'message' => 'Wrong credentials!'];
             return $resoponse;

        }


        if($login_status){

                                if($user->login_enabled == 0){
            $resoponse = [ 'message' => 'Your account is blocked'];
             return $resoponse;
            }

            $role = getRole($user->id);
           if ($role === 'student'){
            $user  = Auth::user();

            if($user->is_verified == 0){
                Auth::logout();
                $message = 'Please active your email verification to login into your account';
                if ( $user_history ) {
                    $user_history->login_status = $message;
                    $user_history->save();
                 }
                  $resoponse = "Active your email verification to login";
             return $resoponse;

            }

            if($user->login_enabled == 0){
                Auth::logout();
                $message = 'Your account is blocked OR not yet activated. Please contact your administrator';
                if ( $user_history ) {
                    $user_history->login_status = $message;
                    $user_history->save();
                 }
             $resoponse = "account is blocked";
             return $resoponse;

            }

            if ( ! $user->institute_id ) {
                Auth::logout();
                $message = 'your_institute_does_not_exist_please_contact_admin';
                if ( $user_history ) {
                $user_history->login_status = $message;
                $user_history->save();
             }
             $resoponse = "Institute does not exist";
             return $resoponse;

            }elseif ( $user->institute_id ) {
                $institute = \App\Institute::find( $user->institute_id );

                if ( $institute && $institute->status == '0' ) {
                    Auth::logout();
                    $message = 'your_institute_is_inactive_please_contact_admin';
                    if ( $user_history ) {
                        $user_history->login_status = $message;
                        $user_history->save();
                     }
                    $resoponse = "Institute is Inactive";
             return $resoponse;
                }
            }
        }else{
          $resoponse = [ 'message' => 'Only Student Can Login!'];
             return $resoponse;
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
            if(isOnlinestudentPWA($user->id)){
                $user->onlineStudent = '1';
            }else{
                $user->onlineStudent = '0';
            }

            if ( $user_history ) {
                $user_history->login_status = 'Success';
                $user_history->save();
             }

             $response = [ 'success' => true,
                            'user'   => $user ];
          return $response;

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


    public function dashboardDetails(Request $request){

     $user = \App\User::where('id', $request["0"])->first();



     $subjects = (Object)[];
                         $userSubjects = \App\User::getUserSeletedPWA('exam_subjects', $request["0"]);

                        if($userSubjects) {
                            $subjects  = \App\Subject::whereIn('id',$userSubjects)->paginate(getRecordsPerPage());
                        }
    //subjects
     $originalSubjects = (Object)[];
                        foreach($subjects as $subject){

                            $subject->image = trim(explode('-',  $subject->image)[1]);
                            $originalSubjects = $subjects;
                        }


    //todaysClasses
      $batches  = getStudentBatchesPWA($request["0"]);
       $todayclasses = \App\Onlineclass::whereNotNull('class_time')->whereNotNull('valid_from')->whereNotNull('valid_to')
      ->whereIn('batch_id', $batches)
      ->whereRaw("'" . date('Y-m-d') . "' BETWEEN DATE(valid_from) AND DATE(valid_to)")
       ->join('users','onlineclasses.created_by_id','users.id')
      ->join('subjects','onlineclasses.subject_id','subjects.id')->select('users.name','onlineclasses.id','onlineclasses.title',
        'onlineclasses.valid_from','onlineclasses.valid_to','onlineclasses.url','onlineclasses.class_time','subjects.subject_title',
        'subjects.color_code');



      // echo date('Y-m-d H:i:s', $newtimestamp_after_mins);
      //echo $onlineclasses->toSql();

    $onlineclassestoday = $todayclasses->orderBy('class_time')->get();
      $onlineclasses = [];
      foreach($onlineclassestoday as $onlineclass){
        $current_date_time = Carbon::now()->format('H:i:s');
        if($onlineclass->class_time > $current_date_time){
            if($onlineclass->class_time <= date("H:i:s" , strtotime("+5 minutes"))){
               $onlineclass->joinClass = 1;
            }else{
                 $onlineclass->joinClass = 0;
            }
             array_push($onlineclasses, $onlineclass);
        }
        else
        {
            if($onlineclass->class_time  > date("H:i:s" , strtotime("-30 minutes"))){
               $onlineclass->classRunning = 1;
               $onlineclass->joinClass = 1;
               array_push($onlineclasses, $onlineclass);
         }
      }
  }


      //liveQuizzes

                          $liveQuizzes = \App\Onlineclass::select(['quizzes.*', 'onlineclasses.live_quiz_popstatus'])
                          ->join('quizzes', 'quizzes.id', 'onlineclasses.live_quiz_id')
                          ->whereNotNull('class_time')->whereNotNull('valid_from')->whereNotNull('valid_to')
                          ->whereIn('batch_id', $batches)
                          ->whereRaw("'" . date('Y-m-d') . "' BETWEEN DATE(valid_from) AND DATE(valid_to)")
                          ->where('live_quiz_popstatus', 'yes')
                          ;
         // echo getEloquentSqlWithBindings( $onlineclasses );
     $live_quizzes = $liveQuizzes->orderBy('class_time')->get();

     foreach($live_quizzes as $quizzes){
     $quiz = Quiz::where('id',$quizzes["id"])->first();
     $quizsubject = $quiz->subjects()->first();


     if($quizsubject["subject_title"]!=null)
     $quizzes->subjectTitle = $quizsubject["subject_title"];
     $quizzes->color_code   = $quizsubject["color_code"];
    }


     //TEST SERIES
      $testSeries = Quiz::select(['title', 'dueration', 'is_paid', 'total_marks','exam_type','tags','quizzes.slug', 'category_id', 'id' ])
            ->whereNotNull('quizzes.display_type') // To differentiate from 'test series' quizzes
            ->where('quizzes.institute_id',$user->institute_id)->get();

               //STUDENT TRAIL PERIOD
            $enrolled = [];
      if(isOnlinestudentPWA($user->id)){
                                $payments = \App\Payment::where('user_id', $user->id)->where('notification_closed', '0')->get();
                        // $under_trailmain = [];
                        if( $payments->count() > 0 ) {
                            foreach( $payments as $payment ) {
                                $days = dateDiffInDays(date('Y-m-d'), $payment->end_date);
                                //$days = 0;
                                   $under_trailmain = [];
                                if ( $days < 30 ) {
                                    $package = \App\StudentPaidContent::find( $payment->item_id );
                                    if( $payment->notes == 'Trail period' ) {
                                        if ( $days > 0 ) {
                                            $under_trail = '<p style="color:red;">Trail period for <i>'.$package->title.'</i> ends in ' . $days . ' Day(s).';
                                            $under_trail .= '</p>';
                                            array_push($under_trailmain, $under_trail);

                                            if ( $days < 5 ) {
                                                $under_trailID = $package->id ;
                                                array_push($under_trailmain, $under_trailID);
                                            }

                                        } else {
                                            $under_trail = '<p style="color:red;">Trail expired for <i>'.$package->title.'</i>.';

                                            $under_trail .= '</p>';
                                            array_push($under_trailmain, $under_trail);
                                            $under_trailID = $package->id;
                                            array_push($under_trailmain, $under_trailID);
                                        }

                                    } else {
                                        if ( $days > 0 ) {
                                            $under_trail = '<p style="color:red;">Package validity <i>'.$package->title.'</i> ends in ' . $days . ' Day(s).';
                                           $under_trail .= '</p>';
                                           array_push($under_trailmain, $under_trail);
                                            if ( $days < 5 ) {
                                                $under_trailID =  $package->id;
                                                array_push($under_trailmain, $under_trailID);
                                            }

                                        } else {
                                            $under_trail = '<p style="color:red;">Package <i>'.$package->title.'</i> has been expired.';

                                            $under_trail .= '</p>';
                                            array_push($under_trailmain, $under_trail);
                                            $under_trailID =  $package->id;
                                            array_push($under_trailmain, $under_trailID);
                                        }
                                    }
                                   array_push($enrolled, $under_trailmain);
                                }
                            }
                        }

      }



            $resoponse = [ 'subjects'     => $originalSubjects ,
                           'todayClasses' => $onlineclasses ,
                           'liveQuizzes'  => $live_quizzes,
                            'trailPeriod'  => $enrolled];


             return $resoponse;

    }

        /**
     * List the categories available
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function subjectDetails(Request $request)
    {

      $category = FALSE;
      $user_id = $request["0"]["user_id"];
      $user = User::where('id',$request["0"]["user_id"])->first();
      $subject_id  = $request["1"]["subject_id"];
      $interested_categories      = null;
      $subjectDetails = Subject::where('id',$subject_id)->first();
      $subjectDetails->image = trim(explode('-',  $subjectDetails->image)[1]);


if(isOnlinestudentPWA($user_id)){

///CHPATERTESTS
 $chapterTests=[];
 $student_courses = getStudentClassesPWA('courses', $user_id);
 $date = date('Y-m-d');
      $subject = Subject::where('id',$subject_id)->first();
        $student_batches = getStudentBatchesPWA($user_id);

      $chapters = $subject->chapters()
                ->select(['chapters.*'])
                ->join('quizzes as q', 'q.chapter_id', '=', 'chapters.id')
                ->join('student_paid_contents_data as spcd', 'spcd.item_id', '=', 'q.id')
                ->join('student_paid_contents_courses as spcc', 'spcc.student_paid_contents_id', '=', 'spcd.student_paid_contents_id')
                ->join('student_paid_contents', 'student_paid_contents.id', '=', 'spcc.student_paid_contents_id')
                ->join('payments as p', 'p.item_id', '=', 'spcd.student_paid_contents_id')

                ->whereIn('spcc.course_id', $student_courses)
                ->where('student_paid_contents.total_items', '>', 0)
                ->where('student_paid_contents.status', 'active')
                ->where('spcd.item_type', 'chapter-exams')
                ->where('p.end_date','>=',$date)
                ->where('p.user_id','=',$user_id)
                ->where('p.plan_type','=','paidcontent')

                ->groupBy('spcd.item_id')
                ->groupBy('q.chapter_id')
                ->get();


$originalChapterTests = [];

 foreach($chapters as $chapter){
     $chaptertests  = array();
    $chaptertests[] =  \App\StudentPaidContent::select(['q.title', 'q.dueration', 'q.total_questions', 'q.start_date', 'q.end_date', 'q.is_paid', 'q.total_marks','q.slug', 'q.validity','q.cost','q.start_time', 'q.id'])
                  ->join('student_paid_contents_data as spcd', 'spcd.student_paid_contents_id', '=', 'student_paid_contents.id')
                  ->join('student_paid_contents_courses as spcc', 'spcc.student_paid_contents_id', '=', 'student_paid_contents.id')
                  ->join('quizzes as q', 'q.id', '=', 'spcd.item_id')
                  ->join('quizzes_subjects','quizzes_subjects.quiz_id','=','q.id')
                  ->join('payments as p', 'p.item_id', '=', 'spcd.student_paid_contents_id')

                  ->where('spcd.item_type', 'chapter-exams')
                  ->whereIn('spcc.course_id', $student_courses)
                  ->where('student_paid_contents.total_items', '>', 0)
                  ->where('student_paid_contents.status', 'active')
                  ->groupBy('spcd.item_id')
                  ->where('p.end_date','>=',$date)
                  ->where('p.user_id','=',$user_id)
                  ->where('p.plan_type','=','paidcontent')
                  ->where('q.chapter_id','=',$chapter->id)
                  ->get();
     array_push($chaptertests , $chapter);
     array_push($originalChapterTests , $chaptertests);

 }
     //LMSNOTES
      $lmsNotes =[];
     $chapters = $subject->chapters()
                                ->select(['chapters.*'])
                                ->join('lms_notes', 'lms_notes.chapter_id', '=', 'chapters.id')

                                ->join('student_paid_contents_data as spcd', 'spcd.item_id', '=', 'lms_notes.id')
                                ->join('student_paid_contents_courses as spcc', 'spcc.student_paid_contents_id', '=', 'spcd.student_paid_contents_id')
                                ->join('student_paid_contents', 'student_paid_contents.id', '=', 'spcc.student_paid_contents_id')
                                ->join('payments as p', 'p.item_id', '=', 'spcd.student_paid_contents_id')

                                ->whereIn('spcc.course_id', $student_courses)
                                ->where('student_paid_contents.total_items', '>', 0)
                                ->where('student_paid_contents.status', 'active')
                                ->where('spcd.item_type', 'lmsnotes')
                                ->where('p.end_date','>=',$date)
                                ->where('p.user_id','=',$user_id)
                                ->where('p.plan_type','=','paidcontent')

                                ->groupBy('spcd.item_id')
                                ->groupBy('lms_notes.chapter_id')->get();

    $originalLmsNotes = [];

 foreach($chapters as $chapter){
     $lmsNotes  = array();
    $lmsNotes[] =  $lmsseriesnotes = $chapter->topics()->select(['lms_notes.*'])
                                ->join('lms_notes', 'lms_notes.topic_id', '=', 'topics.id')
                                ->get();
     array_push($lmsNotes , $chapter);
     array_push($originalLmsNotes , $lmsNotes);

 }


 //LMSNOTES
      $lmsSeries =[];
$chapters = $subject->chapters()
                              ->select(['chapters.*'])
                              ->join('lms_notes', 'lms_notes.chapter_id', '=', 'chapters.id')

                              ->join('student_paid_contents_data as spcd', 'spcd.item_id', '=', 'lms_notes.id')
                              ->join('student_paid_contents_courses as spcc', 'spcc.student_paid_contents_id', '=', 'spcd.student_paid_contents_id')
                              ->join('student_paid_contents', 'student_paid_contents.id', '=', 'spcc.student_paid_contents_id')
                              ->join('payments as p', 'p.item_id', '=', 'spcd.student_paid_contents_id')

                              ->whereIn('spcc.course_id', $student_courses)
                              ->where('student_paid_contents.total_items', '>', 0)
                              ->where('student_paid_contents.status', 'active')
                              ->where('spcd.item_type', 'lmsnotes')
                              ->where('p.end_date','>=',$date)
                              ->where('p.user_id','=',$user_id)
                              ->where('p.plan_type','=','paidcontent')

                              ->groupBy('spcd.item_id')
                              ->groupBy('lms_notes.chapter_id')->get();

    $originalLmsSeries = [];

 foreach($chapters as $chapter){
     $lmsSeries  = array();
    $lmsSeries[] =  $chapter->topics()->select(['lmsseries.*'])
                              ->join('lmsseries', 'lmsseries.topic_id', '=', 'topics.id')
                              ->get()
                              ;
     array_push($lmsSeries , $chapter);
     array_push($originalLmsSeries , $lmsSeries);

 }

       $response = [ 'exams' => $originalChapterTests,
                     'lms' => $originalLmsSeries,
                     'subjectDetails'=> $subjectDetails,
                     'lmsNotes' => $originalLmsNotes ];
       return $response;


}else{
      //Exams PWA
      if ($subject_id)
      {
          if ($subject_id!='all')
          {
            $category = \App\Subject::getRecordWithId($subject_id);
              //check student quiz category
              if ($category)
              {
                if ($user->settings)
                {
                  $interested_categories =  json_decode($user->settings)->user_preferences;
                }
              }
          }
          else
          {
              $user = Auth::user();

              $role = getRole($user->id);

              if ($role === 'student')
              {
                  if ($user->settings)
                  {
                    $interested_categories =  json_decode($user->settings)->user_preferences;
                  }
              }
              else if($role === 'parent')
              {
                 $child = User::select(['settings'])->where('parent_id', '=', $user->id)->first();

                 if ($child)
                 {
                    if ($child->settings)
                    {
                      $interested_categories =  json_decode($child->settings)->user_preferences;
                    }
                 }
              }
              else
                redirect(URL_STUDENT_EXAM_CATEGORIES);

          }

            $data['categories'] = '';

              if ($interested_categories)
              {
                if (count($interested_categories->quiz_categories))
                {

                  if($category){
                    if (!in_array($category->id, $interested_categories->quiz_categories))
                          $data['categories']         = QuizCategory::
                                          whereIn('id',(array) $interested_categories->quiz_categories)
                                          ->paginate(getRecordsPerPage());


                  }
                }
              }


          $interested_categories = true;
      }



                  //LMS PWA
      $lmsseries=[];
      $subject = Subject::where('id',$subject_id)->first();
        $student_batches = getStudentBatchesPWA($user_id);

      $chapters = $subject->chapters()->select(['chapters.*'])->join('lmsseries', 'lmsseries.chapter_id', '=', 'chapters.id')->join('batch_lmsseries', 'batch_lmsseries.lms_series_id', '=', 'lmsseries.id')->whereIn('batch_lmsseries.batch_id', $student_batches)->groupBy('lmsseries.chapter_id')->get();

$originalLmsSeries = [];

 foreach($chapters as $chapter){
     $lmsseries  = array();
     $lmsseries[] = $chapter->topics()->select(['lmsseries.*'])->join('lmsseries', 'lmsseries.topic_id', '=', 'topics.id')->join('batch_lmsseries', 'batch_lmsseries.lms_series_id', '=', 'lmsseries.id')->whereIn('batch_lmsseries.batch_id', $student_batches)->get();
     array_push($lmsseries , $chapter);
     array_push($originalLmsSeries , $lmsseries);

 }

//EXAMS
 $exams = array();

          $category = \App\Subject::where('id',$subject_id)->first();
         $exams  = Quiz::join('quizcategories', 'quizzes.category_id', '=', 'quizcategories.id')
            ->join('batch_quizzes','batch_quizzes.quiz_id','=','quizzes.id')
            ->join('quizzes_subjects','quizzes_subjects.quiz_id','=','quizzes.id')
            ->select(['title', 'dueration', 'total_questions','quizzes.id', 'batch_id', 'start_date', 'end_date', 'is_paid', 'total_marks','quizzes.slug', 'quizzes.validity','quizzes.cost','start_time']);

              $exams->where('quizzes_subjects.subject_id', '=', $category->id);

          $exams = $exams->where('total_marks', '!=', 0)
          ->where('quizzes.category_id', QUIZTYPE_SUBJECT)
          ->whereIn('batch_quizzes.batch_id', $student_batches)
          ->groupBy('quizzes_subjects.quiz_id')
          ->get();





 //LMS NOTES

 $subject = \App\Subject::where('id',$subject_id)->first();
                $lmssnotes=[];

     $chapters = $subject->chapters()->select(['chapters.*'])->join('lms_notes', 'lms_notes.chapter_id', '=', 'chapters.id')->join('batch_lmsnotes', 'batch_lmsnotes.lms_note_id', '=', 'lms_notes.id')->whereIn('batch_lmsnotes.batch_id', $student_batches)->groupBy('lms_notes.chapter_id')->get();

$originalLmsNotes = [];

 foreach($chapters as $chapter){
     $lmsnotes = array();
     $lmsnotes[] = $chapter->topics()->select(['lms_notes.*'])->join('lms_notes', 'lms_notes.topic_id', '=', 'topics.id')->join('batch_lmsnotes', 'batch_lmsnotes.lms_note_id', '=', 'lms_notes.id')->whereIn('batch_lmsnotes.batch_id', $student_batches)->get();
     array_push($lmsnotes , $chapter);
     array_push($originalLmsNotes , $lmsnotes);
 }

       $response = [ 'exams' => $exams,
                     'lms' => $originalLmsSeries,
                     'subjectDetails'=> $subjectDetails,
                     'lmsNotes' => $originalLmsNotes ];
       return $response;

    }
}


    public function topicDetails(Request $request){

        $user_id = $request["0"]["user_id"];
        $user = User::where('id',$request["0"]["user_id"])->first();
        $topicId  = $request["1"]["topicId"];
        $series_slug = $request["2"]["series_slug"];

        $topicDetails = Topic::where("id",$topicId)->first();
        $subjectDetails = Subject::where("id",$topicDetails->subject_id)->first();
        $subjectDetails->image = trim(explode('-',  $subjectDetails->image)[1]);
        // $record = \App\Subject::getRecordWithId($subject_id);

        if ( ! empty( $series_slug ) ) {
          $series = \App\LmsSeries::getRecordWithSlug($series_slug);

           $contents = \App\LmsSeriesData::
  join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
 ->where('lmsseries_id', $series->id)
  ->whereNotNull('content_type')
 ->orderBy('display_order')
 ->get();

  foreach( $contents as $content){

    $content_type = $content->content_type;

  if ( empty( $content_type ) ) {
    $content_type = 'text';
  }


  if ( ! isOnlinestudentPWA($user_id) ) {
    $user = \App\User::with(['student_class'])->find( $user_id );
    if(count($user->batches) > 0) {
      $pop_quiz_batches = \DB::table('lmsseries_data_batch_popquiz')->select('batches.*')
      ->join('lmsseries', 'lmsseries.id', '=', 'lmsseries_data_batch_popquiz.lmsseries_id')
      ->join('batches', 'batches.id', '=', 'lmsseries_data_batch_popquiz.batch_id')
      ->where('lmsseries_id', $series->id)->where('lmscontent_id', $content->id)
      ->where('pop_quiz', 'yes')
      ->whereIn('batches.id', $user->batches->pluck('id')->toArray())
      ->get()->pluck('name')->toArray();
      if(count($pop_quiz_batches) == 0) {
        $is_pop_quiz = 'no';
          $content["is_pop_quiz"] =  $is_pop_quiz;
      }
    }
  }else{
    $pop_quiz_batches = \DB::table('lmsseries_data_batch_popquiz')->select('batches.*')
  ->join('lmsseries', 'lmsseries.id', '=', 'lmsseries_data_batch_popquiz.lmsseries_id')
  ->join('batches', 'batches.id', '=', 'lmsseries_data_batch_popquiz.batch_id')
  ->where('lmsseries_id', $series->id)->where('lmscontent_id', $content->id)->where('pop_quiz', 'yes')->get()->pluck('name')->toArray();
  $is_pop_quiz = 'yes';
  // array_push($content, $is_pop_quiz);
  $content["is_pop_quiz"] =  $is_pop_quiz;

  }

          $response = [ 'contents' => $contents ,
                        'subjectDetails'=> $subjectDetails ];
         return $response;
  }

          }

        }



         public function notestopicDetails(Request $request){

        $user_id = $request["0"]["user_id"];
        $user = User::where('id',$request["0"]["user_id"])->first();
        $topicId  = $request["1"]["topicId"];
        $series_slug = $request["2"]["series_slug"];

        $topicDetails = Topic::where("id",$topicId)->first();
        $subjectDetails = Subject::where("id",$topicDetails->subject_id)->first();
         $subjectDetails->image = trim(explode('-',  $subjectDetails->image)[1]);


               $series = \App\LmsNote::getRecordWithSlug($series_slug);

 $contents = \DB::table('lms_notes')->select(['lms_notes.*'])
 ->join('batch_lmsnotes', 'batch_lmsnotes.lms_note_id', '=', 'lms_notes.id')
 ->where('lms_note_id', $series->id)
  ->whereNotNull('lms_notes.content_type')
  ->where('lms_notes.subject_id',  $subjectDetails->id)
 ->get();

       $response = [ 'contents' => $contents ,
                        'subjectDetails'=> $subjectDetails ];
         return $response;

        }


        public function upcomingaNDcompleted(Request $request){
            $user = \App\User::where('id', $request["0"])->first();

            $batches  = getStudentBatchesPWA($request["0"]);


       $weekclasses = \App\Onlineclass::whereNotNull('class_time')->whereNotNull('valid_from')->whereNotNull('valid_to')
      ->whereIn('batch_id', $batches)
      ->whereRaw("'" . date('Y-m-d') . "' BETWEEN DATE(valid_from) AND DATE(valid_to)")
       ->join('users','onlineclasses.created_by_id','users.id')
      ->join('subjects','onlineclasses.subject_id','subjects.id')->select('users.name','onlineclasses.id','onlineclasses.title',
        'onlineclasses.valid_from','onlineclasses.valid_to','onlineclasses.url','onlineclasses.class_time','subjects.subject_title',
        'subjects.color_code');
      $onlineclasses = $weekclasses->orderBy('class_time')->get();


      $completedClasses = Onlineclass::select(['valid_from', 'class_time', 'title', 'batch_id', 'subject_id', 'topic',  'url', 'id','slug', 'lmsseries_id', 'lmsnotes_id', 'student_class_id', 'live_quiz_id', 'class_duration' ])
      ->whereIn('batch_id', $batches)
      ->join('subjects','onlineclasses.subject_id','subjects.id')
      ->join('users','onlineclasses.created_by_id','users.id')
      ->select('onlineclasses.id','onlineclasses.title','users.name',
        'onlineclasses.valid_from','onlineclasses.valid_to','onlineclasses.lmsseries_id','onlineclasses.lmsnotes_id','onlineclasses.url','onlineclasses.class_time','subjects.subject_title',
        'subjects.color_code')->get();


      foreach($completedClasses as $completed){
        $seriesSlug = LmsSeries::where('id' , $completed->lmsseries_id)->first();
        $slug = $seriesSlug["slug"];
        $completed->slug = $slug;

        $notesSlug = LmsNote::where('id' , $completed->lmsnotes_id)->first();
        $notes = $notesSlug["slug"];
        $completed->notes = $notes;
      }


         $response = ['weekclasses'=> $onlineclasses,
                      'completedClasses'=>$completedClasses];

         return $response;
        }


        public function quizInstructions(Request $request){

            $quizDetails = Quiz::where('id',$request["0"]["quiz_id"])->first();
            $quizInstructions = Instruction::where('id',  $quizDetails->instructions_page_id)->first();

            $response = ['quizDetails'=> $quizDetails ,
                         'quizInstructions' => $quizInstructions];

         return $response;
        }


        public function quizQuestions(Request $request){
        $animation = $request["2"]["animation"];
        $quizDetails =  Quiz::where('id',$request["0"]["quiz_id"])->first();
        $section_data    = (array)json_decode($quizDetails->section_data);
        $sections        = array();
        $section_names   = array();

        foreach ($section_data as $key => $value) {
          $sections[]  = $key;
          $section_names[]  = $value->section_name;

        }
         $prepared_records = null;
         //check if it is ST exam or another
        if($quizDetails->exam_type=='NSNT'){


          $questions   = $quizDetails->prepareQuestions($quizDetails->getQuestionsPWA());

           $questionColor = [];
        $time_spent = [];

        foreach($questions["questions"] as $question){
            $question->backgroundcolor = '#E5E5E5';
            $question->color = '#000000';
            $question->revise = '0';
            $time_spent[$question->id] = '0';
            array_push($questionColor , $question);
        }



         $quizsubject = $quizDetails->subjects()->first();
     if($quizsubject["subject_title"]!=null){
     $quizsubject = $quizsubject->subject_title;
    }



  $lmsContent = 'no';
  if($animation != 'Yes'){
    $lmsContent = LmsContent::where('id',$animation)->first();
    $response = ['lmsContent' => $lmsContent];
  }



        $response = ['quizDetails'=> $quizDetails ,
                     'quizQuestions' => $questions["questions"],
                     'questionsColor' => $questionColor,
                     'quizSubject' => $quizsubject,
                     'time_spent' =>  $time_spent,
                     'lmsContent' => $lmsContent];

         return $response;



      }else{

        $questions    = $quizDetails->getSectionQuestions($section_data);

         $questionColor = [];
        $time_spent = [];
        foreach($questions as $question){

            $question->backgroundcolor = '#E5E5E5';
            $question->color = '#000000';
            $question->revise = '0';
            $time_spent[$question->id] = '0';
            array_push($questionColor , $question);
        }



         $quizsubject = $quizDetails->subjects()->first();
     if($quizsubject["subject_title"]!=null){
     $quizsubject = $quizsubject->subject_title;
    }



  $lmsContent = 'no';
  if($animation != 'Yes'){
    $lmsContent = LmsContent::where('id',$animation)->first();
    $response = ['lmsContent' => $lmsContent];
  }



        $response = ['quizDetails'=> $quizDetails ,
                     'quizQuestions' => $questions,
                     'questionsColor' => $questionColor,
                     'quizSubject' => $quizsubject,
                     'time_spent' =>  $time_spent,
                     'lmsContent' => $lmsContent];

         return $response;


      }



        }

        public function finishQuiz(Request $request){


            $quiz = Quiz::find($request['0']['quiz_id']);

            $user_record = \App\User::where('id', $request['1']['user_id'])->first();

            $questions = DB::table('questionbank_quizzes')->select('questionbank_id', 'subject_id')
                     ->where('quize_id','=',$quiz->id)
                     ->get();

            $input_data = $request['2']['quizAnswers'];



            $answers = array();

            foreach ($input_data as $answer) {
                $answers[ $answer['quiz_id'] ] = $answer['answer'];
            }

            $time_spent = $request['3']['time_spent'];
            //dd( $time_spent );
            // $time_spent = 0;
            $subject                  = [];
            $time_spent_not_answered  = [];
            $not_answered_questions   = [];

            foreach($questions as $q)
            {

              $subject_id = $q->subject_id;
               if(! array_key_exists($q->subject_id, $subject)) {
                  $subject[$subject_id]['subject_id']       = $subject_id;
                  $subject[$subject_id]['correct_answers']  = 0;
                  $subject[$subject_id]['wrong_answers']    = 0;
                  $subject[$subject_id]['not_answered']     = 0;
                  $subject[$subject_id]['time_spent']       = 0;
                  $subject[$subject_id]['time_to_spend']       = 0;
                  $subject[$subject_id]['time_spent_correct_answers']    = 0;
                  $subject[$subject_id]['time_spent_wrong_answers']    = 0;
                }
                if(! array_key_exists($q->questionbank_id, $answers)){
                  $subject[$subject_id]['not_answered']     += 1;
                  $not_answered_questions[] = $q->questionbank_id;
                  $time_spent_not_answered[$q->questionbank_id]['time_to_spend'] = 0;
                  $time_spent_not_answered[$q->questionbank_id]['time_spent'] = $time_spent[$q->questionbank_id];
                  $subject[$subject_id]['time_spent']      += $time_spent[$q->questionbank_id];
                }
            }


            $result =   $this->processAnswers($answers, $subject, $time_spent, $quiz->negative_mark);
            $result['not_answered_questions'] = $not_answered_questions;
            $result['time_spent_not_answered_questions'] = $time_spent_not_answered;

            // dd($result);
            $result = (object) $result;

            //dd( $result );

            $record = new QuizResult();
            $record->quiz_id = $quiz->id;
            $record->institute_id = $quiz->institute_id;

            $record->user_id = $request['1']['user_id'];
            $record->marks_obtained = $result->marks_obtained;
            $record->total_marks = $quiz->total_marks;
            $record->percentage = $this->getPercentage($result->marks_obtained, $quiz->total_marks);

            $exam_status = 'pending';
            if($record->percentage >= $quiz->pass_percentage)
                $exam_status = 'pass';
            else
                $exam_status = 'fail';

            $record->exam_status = $exam_status;
            $record->answers = json_encode($answers);
            $record->subject_analysis = $result->subject_analysis;
            $record->correct_answer_questions = $result->correct_answer_questions;
            $record->wrong_answer_questions = $result->wrong_answer_questions;
            $record->not_answered_questions = json_encode($result->not_answered_questions);
            $record->time_spent_correct_answer_questions = $result->time_spent_correct_answer_questions;
            $record->time_spent_wrong_answer_questions = $result->time_spent_wrong_answer_questions;
            $record->time_spent_not_answered_questions = json_encode($result->time_spent_not_answered_questions);

            $record->slug = getHashCode();

            $content = 'You have attempted exam. The score percentage is '.formatPercentage($record->percentage);


            $record->save();

            if ( canSend('exam_result', $user_record->id, 'Email') ) {
              $template    = new EmailTemplate();
              $content_data =  $template->sendEmailNotification('exam-result',
               array('username'    =>$user_record->name,
                        'content'  => $content,
                        'to_email' => $user_record->email));

              try {
              $user_record->notify(new \App\Notifications\StudentExamResult($user_record,$exam_status,$quiz->title,$record->percentage));
              } catch (Exception $e) {
              // dd($e->getMessage());
              }
            }

            if ( canSend('exam_result', $user_record->id, 'SMS') ) {
              $test_date = date('Y-m-d');
              $smsmessage = "You are ucfirst($exam_status)ed in $quiz->title exam, conducted on $test_date and you got $record->percentage%";
              $phone = '91' . $phone;
              $smsagent = \App\SMSAgent::sendUserSMS( $phone, $smsmessage);
            }


         $response = ['success'=> 'quizFinished',
                      'resultId'=> $record->slug,
                      'quizDetails' => $quiz ];

         return $response;






        }



        public function quizAnswers(Request $request){
            $quizSlug = $request["0"];
            $resultSlug = $request["1"];
            $userId = $request["2"];
        $exam_record = Quiz::getRecordWithSlug($quizSlug);

        $result_record = QuizResult::getRecordWithSlug($resultSlug);
        $user_details   = User::where('id','=',$userId)->get()->first();


        $prepared_records        = (object) $exam_record
                                    ->prepareQuestions($exam_record->getQuestionsPWA(),'examcomplted');


        $bookmarks_array = (array)$prepared_records;

        $bookmarks                   = array_pluck($bookmarks_array['questions'], 'id');

        $quizQuestions = $prepared_records->questions;
        foreach($quizQuestions as $question){
            $questionID = $question->id;
            $answers = json_decode($result_record->answers);
            if ( empty( $answers ) ) {
                $answers = (object)[];
            }
            $correctAnswers = $result_record->correct_answer_questions;
            $wrongAnswers = $result_record->wrong_answer_questions;
            $notAnswers = $result_record->not_answered_questions;
             // dd($correctAnswers);
            $question->correct_answers = $question->correct_answers-1;
            if(property_exists($answers, $questionID)){
                if(in_array($questionID, json_decode($correctAnswers))){
                  $question->answerStatus = '1';
                }else{
                  $question->answerStatus = '0';
                }
            }else{
              $question->answerStatus = '2';
            }
           }

         $quizSubject = $exam_record->subjects()->first();
         if($quizSubject["subject_title"]!=null){
     $quizsubject = $quizSubject->subject_title;
    }
            $response = ['quizQuestions'=> $quizQuestions,
                         'quizDetails'=> $exam_record,
                          'quizSubject' => $quizsubject ];

         return $response;

       }




        public function quizDetailedReports(Request $request){

        $exam_slug = $request["0"]["slug"];
        $result_slug = $request["1"]["resultslug"];
        $userId = $request["2"]["user_id"];

        $exam_record = Quiz::getRecordWithSlug($exam_slug);


        $exam_questions_subjects = Quiz::where('quizzes.slug', $exam_slug)->rightjoin('questionbank_quizzes','questionbank_quizzes.quize_id','quizzes.id')->join('questionbank','questionbank.id','questionbank_quizzes.questionbank_id')->join('subjects','subjects.id','questionbank.subject_id')->get()->groupBy( function( $entry ) {
            return $entry->subject_title;
        });

        // dd($exam_questions_subjects);

        $exam_questions = $exam_record->getQuestionsPWA()->pluck('questionbank_id');


         $result_record = QuizResult::getRecordWithSlug($result_slug);


         $user_details   = User::where('id','=',$userId)->get()->first();

        $correct_answer_questions = json_decode($result_record->correct_answer_questions);

        $wrong_answer_questions = json_decode($result_record->wrong_answer_questions);

        $not_answered_questions = json_decode($result_record->not_answered_questions);

        $time_spent_correct_answer_questions = json_decode($result_record->time_spent_correct_answer_questions);

        $time_spent_wrong_answer_questions = json_decode($result_record->time_spent_wrong_answer_questions);

        $time_spent_not_answered_questions = json_decode($result_record->time_spent_not_answered_questions);

        $time_spent_correct_answers = 0;

        foreach ($time_spent_correct_answer_questions as $key => $value) {

                    $value = (array)$value;

                        $time_spent_correct_answers+=$value['time_spent'];
                }

        $time_spent_wrong_answers = 0;

        foreach ($time_spent_wrong_answer_questions as $key => $value) {

                    $value = (array)$value;

                        $time_spent_wrong_answers+=$value['time_spent'];
                }

        $time_spent_skipped_questions = 0;

        foreach ($time_spent_not_answered_questions as $key => $value) {

                    $value = (array)$value;

                        $time_spent_skipped_questions+=$value['time_spent'];
                }

        // dd($time_spent_wrong_answer_questions);

        foreach ($exam_questions as $key => $value) {

            if( !in_array( $value,$correct_answer_questions) && !in_array( $value,$wrong_answer_questions) &&
                !in_array( $value,$not_answered_questions) ){
                array_push($not_answered_questions, $value);
            }
        }

        $subjects_report = [];

        foreach ($exam_questions_subjects as $subject => $questions) {

            $correct_answers_marks = 0;

            $wrong_answers_marks = 0;

            $not_answered_marks = 0;

            $subject_answers_count = 0;

            $subject_total = 0;

            $time_spenton_correct_answers = 0;

            $time_spenton_wrong_answers = 0;

            $time_spenton_skipped_answers = 0;

            foreach ($questions as $key => $question) {

                // dd($question);

                $question_id = $question->questionbank_id;

                if( in_array( $question_id, $correct_answer_questions ) ) {

                    $correct_answers_marks += $question->marks;

                    $subject_answers_count++;

                    $subject_total += $question->marks;

                }

                if( in_array( $question_id, $wrong_answer_questions ) ) {

                    $wrong_answers_marks-=$question->negative_mark;

                    $subject_answers_count++;

                    $subject_total -= $question->negative_mark;

                }

                if( in_array( $question_id, $not_answered_questions ) ) {

                    $not_answered_marks+=$question->marks;

                }

                foreach ($time_spent_correct_answer_questions as $key => $value) {

                    $value = (array)$value;

                    // dd($value['time_spent']);

                    if( $key == $question_id){

                        $time_spenton_correct_answers+=$value['time_spent'];
                    }
                }

                foreach ($time_spent_wrong_answer_questions as $key => $value) {

                    $value = (array)$value;

                    if( $key == $question_id){

                        $time_spenton_wrong_answers+=$value['time_spent'];
                    }
                }

                foreach ($time_spent_not_answered_questions as $key => $value) {

                    $value = (array)$value;

                    if( $key == $question_id){

                        $time_spenton_skipped_answers+=$value['time_spent'];
                    }
                }
            }

            $subjects_report= [ 'correct_answers_marks' => $correct_answers_marks,
            'wrong_answers_marks' => $wrong_answers_marks,
            'not_answered_marks' => $not_answered_marks,
            'subject_answers_count' => $subject_answers_count,
            'subject_total' => $subject_total,
            'time_spenton_correct_answers' => $time_spenton_correct_answers,
            'time_spenton_wrong_answers' => $time_spenton_wrong_answers,
            'time_spenton_skipped_answers' => $time_spenton_skipped_answers
            ];
        }


         $summary_dataset = [
                'correctAnswers' => count($correct_answer_questions),
                'wrongAnswers'   => count($wrong_answer_questions),
                'notAnswered'    => count($not_answered_questions)];


        $QuizChart = array();
        $obj = new \stdClass();
        $obj->y = $summary_dataset['correctAnswers'];
        // $obj->label = 'correctAnswers';
        array_push($QuizChart , $obj);

        $obj = new \stdClass();
        $obj->y = $summary_dataset['wrongAnswers'];
        // $obj->label = 'wrongAnswers';
        array_push($QuizChart , $obj);

        $obj = new \stdClass();
        $obj->y = $summary_dataset['notAnswered'];
        // $obj->label = 'notAnswered';
        array_push($QuizChart , $obj);



         $response = ['quizReport'=> $subjects_report,
                      'subject' => $subject,
                      'summaryDataset'=> $QuizChart ];

         return $response;

        }


        /**
     * This below method process the submitted answers based on the
     * provided answers and quiz questions
     * @param  [type] $answers [description]
     * @return [type]          [description]
     */
    public function processAnswers($answers, $subject, $time_spent, $negative_mark = 0)
    {
        $obtained_marks     = 0;
        $correct_answers    = 0;
        $obtained_negative_marks = 0;

        $corrent_answer_question            = [];
        $wrong_answer_question              = [];
        $time_spent_correct_answer_question = [];
        $time_spent_wrong_answer_question   = [];

        foreach ($answers as $key => $answer) {
          if( is_numeric( $key))
         {
            $question_record  = $this->getQuestionRecord($key);
            $question_type    = $question_record->question_type;
            $actual_answer    = $question_record->correct_answers;

            $subject_id       = $question_record->subject_id;
            if(! array_key_exists($subject_id, $subject)) {
              $subject[$subject_id]['subject_id']       = $subject_id;
              $subject[$subject_id]['correct_answers']  = 0;
              $subject[$subject_id]['wrong_answers']    = 0;
              $subject[$subject_id]['time_spent_correct_answers']    = 0;
              $subject[$subject_id]['time_spent_wrong_answers']    = 0;
              $subject[$subject_id]['time_spent']       = 0;

            }

             $subject[$subject_id]['time_spent']       += $time_spent[$question_record->id];
             $subject[$subject_id]['time_to_spend']    += $question_record->time_to_spend;
            switch ($question_type) {
                case 'radio':
                                if($answer == $actual_answer)
                                {
                                    $correct_answers++;
                                    $obtained_marks                 += $question_record->marks;
                                    $corrent_answer_question[]       = $question_record->id;
                                    $subject[$subject_id]['correct_answers'] +=1;
                                    $subject[$subject_id]['time_spent_correct_answers'] += $time_spent[$question_record->id];

                                    $time_spent_correct_answer_question[$question_record->id]['time_to_spend']
                                                                    = $question_record->time_to_spend;
                                    $time_spent_correct_answer_question[$question_record->id]['time_spent']
                                                                    = $time_spent[$question_record->id];

                                }
                                else {

                                    $wrong_answer_question[]          = $question_record->id;
                                    $subject[$subject_id]['wrong_answers'] += 1;
                                    $obtained_marks                   -= $negative_mark;
                                    $obtained_negative_marks          += $negative_mark;
                                    $subject[$subject_id]['time_spent_wrong_answers']
                                                                += $time_spent[$question_record->id];
                                    $time_spent_wrong_answer_question[$question_record->id]['time_to_spend']
                                                                    = $question_record->time_to_spend;
                                    $time_spent_wrong_answer_question[$question_record->id]['time_spent']
                                                                     = $time_spent[$question_record->id];
                                }

                                break;

                case 'checkbox':
                                $actual_answer = json_decode($actual_answer);
                                $i=0;
                                $flag= 1;
                                foreach($value as $answer_key => $answer_value )
                                {
                                    if(isset($actual_answer[$answer_key]))
                                    {
                                        if( $actual_answer[$answer_key]->answer !=
                                            $answer_value )
                                        {
                                            $flag = 0; break;
                                        }
                                    }
                                    else {
                                        $flag = 0; break;
                                    }

                                }

                                if($flag)
                                {
                                    $correct_answers++;
                                    $obtained_marks += $question_record->marks;
                                    $corrent_answer_question[] = $question_record->id;
                                    $subject[$subject_id]['correct_answers'] +=1;
                                    $subject[$subject_id]['time_spent_correct_answers']
                                                                += $time_spent[$question_record->id];
                                    $time_spent_correct_answer_question[$question_record->id]['time_to_spend']
                                                                    = $question_record->time_to_spend;
                                    $time_spent_correct_answer_question[$question_record->id]['time_spent']
                                                                    = $time_spent[$question_record->id];

                                }
                                else {
                                    $wrong_answer_question[]          = $question_record->id;
                                    $subject[$subject_id]['wrong_answers'] += 1;
                                     $subject[$subject_id]['time_spent_wrong_answers']
                                                                += $time_spent[$question_record->id];
                                    $obtained_marks                   -= $negative_mark;
                                    $obtained_negative_marks          += $negative_mark;
                                    $time_spent_wrong_answer_question[$question_record->id]['time_to_spend']
                                                                       = $question_record->time_to_spend;
                                    $time_spent_wrong_answer_question[$question_record->id]['time_spent']
                                                                       = $time_spent[$question_record->id];
                                }

                                break;
                case 'blanks':
                                $actual_answer = json_decode($actual_answer);
                                 $i=0;
                                $flag= 1;
                                foreach($actual_answer as $answer)
                                {
                                    if(strcasecmp(
                                        trim($answer->answer),
                                        trim($value[$i++])) != 0)
                                    {
                                        $flag = 0; break;
                                    }
                                }

                                if($flag)
                                {
                                    $correct_answers++;
                                    $obtained_marks += $question_record->marks;
                                    $corrent_answer_question[] = $question_record->id;
                                    $subject[$subject_id]['correct_answers'] +=1;
                                     $subject[$subject_id]['time_spent_correct_answers']
                                                                += $time_spent[$question_record->id];
                                    $time_spent_correct_answer_question[$question_record->id]['time_to_spend']
                                                                    = $question_record->time_to_spend;
                                    $time_spent_correct_answer_question[$question_record->id]['time_spent']
                                                                    = $time_spent[$question_record->id];


                                }
                                else
                                {
                                    $wrong_answer_question[] = $question_record->id;
                                    $subject[$subject_id]['wrong_answers'] += 1;
                                    $subject[$subject_id]['time_spent_wrong_answers']
                                                                += $time_spent[$question_record->id];
                                    $obtained_marks                   -= $negative_mark;
                                    $obtained_negative_marks          += $negative_mark;
                                    $time_spent_wrong_answer_question[$question_record->id]['time_to_spend']
                                                                       = $question_record->time_to_spend;
                                    $time_spent_wrong_answer_question[$question_record->id]['time_spent']
                                                                       = $time_spent[$question_record->id];
                                }

                                break;
                    case (  $question_type == 'para'  ||
                            $question_type == 'audio' ||
                            $question_type == 'video'
                          ):
                                 $actual_answer = json_decode($actual_answer);
                                 $indidual_marks = $question_record->marks/$question_record->total_correct_answers;
                                $i=0;
                                $flag= 0;
                                foreach($value as $answer_key => $answer_value )
                                {
                                    if($actual_answer[$answer_key]->answer == $answer_value)
                                    {
                                        $flag=1;
                                        $obtained_marks += $indidual_marks;
                                    }
                                }

                                if($flag)
                                {
                                    $correct_answers++;
                                    $corrent_answer_question[] = $question_record->id;
                                    $subject[$subject_id]['correct_answers'] +=1;
                                    $subject[$subject_id]['time_spent_correct_answers']
                                                                += $time_spent[$question_record->id];
                                    $time_spent_correct_answer_question[$question_record->id]['time_to_spend']
                                                                    = $question_record->time_to_spend;
                                    $time_spent_correct_answer_question[$question_record->id]['time_spent']
                                                                    = $time_spent[$question_record->id];

                                }
                                else
                                {
                                    $wrong_answer_question[] = $question_record->id;
                                    $subject[$subject_id]['wrong_answers'] += 1;
                                     $subject[$subject_id]['time_spent_wrong_answers']
                                                                += $time_spent[$question_record->id];
                                    $obtained_marks                   -= $negative_mark;
                                    $obtained_negative_marks          += $negative_mark;
                                    $time_spent_wrong_answer_question[$question_record->id]['time_to_spend']
                                                                       = $question_record->time_to_spend;
                                    $time_spent_wrong_answer_question[$question_record->id]['time_spent']
                                                                       = $time_spent[$question_record->id];
                                }

                                break;
                case 'match':
                                $actual_answer = json_decode($actual_answer);
                                $indidual_marks = $question_record->marks/$question_record->total_correct_answers;
                                $i=0;
                                 $flag= 0;
                                foreach($actual_answer as $answer)
                                {
                                    if($answer->answer == $value[$i++])
                                    {
                                       $flag=1;
                                        $obtained_marks += $indidual_marks;
                                    }
                                }

                                if($flag)
                                {
                                    $correct_answers++;
                                    $corrent_answer_question[] = $question_record->id;
                                    $subject[$subject_id]['correct_answers'] +=1;
                                    $subject[$subject_id]['time_spent_correct_answers']
                                                                += $time_spent[$question_record->id];
                                    $time_spent_correct_answer_question[$question_record->id]['time_to_spend']
                                                                    = $question_record->time_to_spend;
                                    $time_spent_correct_answer_question[$question_record->id]['time_spent']
                                                                    = $time_spent[$question_record->id];

                                }
                                else
                                {
                                    $wrong_answer_question[] = $question_record->id;
                                    $subject[$subject_id]['wrong_answers'] += 1;
                                     $subject[$subject_id]['time_spent_wrong_answers']
                                                                += $time_spent[$question_record->id];
                                    $obtained_marks                   -= $negative_mark;
                                    $obtained_negative_marks          += $negative_mark;
                                    $time_spent_wrong_answer_question[$question_record->id]['time_to_spend']
                                                                       = $question_record->time_to_spend;
                                    $time_spent_wrong_answer_question[$question_record->id]['time_spent']
                                                                       = $time_spent[$question_record->id];
                                }
                    break;

            }

          }



    }
        // dd($time_spent_correct_answer_question);
          return array(
                        'total_correct_answers' => $correct_answers,
                        'marks_obtained'        => $obtained_marks,
                        'negative_marks'        => $obtained_negative_marks,
                        'subject_analysis'      => json_encode($subject),
                        'correct_answer_questions' => json_encode($corrent_answer_question),
                        'wrong_answer_questions' => json_encode($wrong_answer_question),
                        'time_spent_correct_answer_questions' => json_encode($time_spent_correct_answer_question),
                        'time_spent_wrong_answer_questions' => json_encode($time_spent_wrong_answer_question),
                        );

    }


      /**
     * Returns the specific question record based on question_id
     * @param  [type] $question_id [description]
     * @return [type]              [description]
     */
    function getQuestionRecord($question_id)
    {
        return QuestionBank::where('id','=',$question_id)->first();
    }


    /**
     * Returns the percentage of the number
     * @param  [type] $total [description]
     * @param  [type] $goal  [description]
     * @return [type]        [description]
     */
    public function getPercentage($total, $goal)
    {
        return ($total / $goal) * 100;
    }



    public function QuizReport(Request $request){


        $exam_slug = $request["0"]["slug"];
        $result_slug = $request["1"]["resultslug"];
        $userId = $request["2"]["user_id"];

        $exam_record = Quiz::getRecordWithSlug($exam_slug);


        $exam_questions_subjects = Quiz::where('quizzes.slug', $exam_slug)->rightjoin('questionbank_quizzes','questionbank_quizzes.quize_id','quizzes.id')->join('questionbank','questionbank.id','questionbank_quizzes.questionbank_id')->join('subjects','subjects.id','questionbank.subject_id')->get()->groupBy( function( $entry ) {
            return $entry->subject_title;
        });

        // dd($exam_questions_subjects);

        $exam_questions = $exam_record->getQuestionsPWA()->pluck('questionbank_id');


         $result_record = QuizResult::getRecordWithSlug($result_slug);


         $user_details   = User::where('id','=',$userId)->get()->first();

        $correct_answer_questions = json_decode($result_record->correct_answer_questions);

        $wrong_answer_questions = json_decode($result_record->wrong_answer_questions);

        $not_answered_questions = json_decode($result_record->not_answered_questions);

        $time_spent_correct_answer_questions = json_decode($result_record->time_spent_correct_answer_questions);

        $time_spent_wrong_answer_questions = json_decode($result_record->time_spent_wrong_answer_questions);

        $time_spent_not_answered_questions = json_decode($result_record->time_spent_not_answered_questions);

        $time_spent_correct_answers = 0;

        foreach ($time_spent_correct_answer_questions as $key => $value) {

                    $value = (array)$value;

                        $time_spent_correct_answers+=$value['time_spent'];
                }

        $time_spent_wrong_answers = 0;

        foreach ($time_spent_wrong_answer_questions as $key => $value) {

                    $value = (array)$value;

                        $time_spent_wrong_answers+=$value['time_spent'];
                }

        $time_spent_skipped_questions = 0;

        foreach ($time_spent_not_answered_questions as $key => $value) {

                    $value = (array)$value;

                        $time_spent_skipped_questions+=$value['time_spent'];
                }

        // dd($time_spent_wrong_answer_questions);

        foreach ($exam_questions as $key => $value) {

            if( !in_array( $value,$correct_answer_questions) && !in_array( $value,$wrong_answer_questions) &&
                !in_array( $value,$not_answered_questions) ){
                array_push($not_answered_questions, $value);
            }
        }

        $subjects_report = [];

        foreach ($exam_questions_subjects as $subject => $questions) {

            $correct_answers_marks = 0;

            $wrong_answers_marks = 0;

            $not_answered_marks = 0;

            $subject_answers_count = 0;

            $subject_total = 0;

            $time_spenton_correct_answers = 0;

            $time_spenton_wrong_answers = 0;

            $time_spenton_skipped_answers = 0;

            foreach ($questions as $key => $question) {

                // dd($question);

                $question_id = $question->questionbank_id;

                if( in_array( $question_id, $correct_answer_questions ) ) {

                    $correct_answers_marks += $question->marks;

                    $subject_answers_count++;

                    $subject_total += $question->marks;

                }

                if( in_array( $question_id, $wrong_answer_questions ) ) {

                    $wrong_answers_marks-=$question->negative_mark;

                    $subject_answers_count++;

                    $subject_total -= $question->negative_mark;

                }

                if( in_array( $question_id, $not_answered_questions ) ) {

                    $not_answered_marks+=$question->marks;

                }

                foreach ($time_spent_correct_answer_questions as $key => $value) {

                    $value = (array)$value;

                    // dd($value['time_spent']);

                    if( $key == $question_id){

                        $time_spenton_correct_answers+=$value['time_spent'];
                    }
                }

                foreach ($time_spent_wrong_answer_questions as $key => $value) {

                    $value = (array)$value;

                    if( $key == $question_id){

                        $time_spenton_wrong_answers+=$value['time_spent'];
                    }
                }

                foreach ($time_spent_not_answered_questions as $key => $value) {

                    $value = (array)$value;

                    if( $key == $question_id){

                        $time_spenton_skipped_answers+=$value['time_spent'];
                    }
                }
            }

            $subjects_report= [ 'correct_answers_marks' => $correct_answers_marks,
            'wrong_answers_marks' => $wrong_answers_marks,
            'not_answered_marks' => $not_answered_marks,
            'subject_answers_count' => $subject_answers_count,
            'subject_total' => $subject_total,
            'time_spenton_correct_answers' => $time_spenton_correct_answers,
            'time_spenton_wrong_answers' => $time_spenton_wrong_answers,
            'time_spenton_skipped_answers' => $time_spenton_skipped_answers
            ];
        }


         $summary_dataset = [
                'correctAnswers' => count($correct_answer_questions),
                'wrongAnswers'   => count($wrong_answer_questions),
                'notAnswered'    => count($not_answered_questions)];


        $QuizChart = array();
        $obj = new \stdClass();
        $obj->y = $summary_dataset['correctAnswers'];
        $obj->label = 'correctAnswers';
        array_push($QuizChart , $obj);

        $obj = new \stdClass();
        $obj->y = $summary_dataset['wrongAnswers'];
        $obj->label = 'wrongAnswers';
        array_push($QuizChart , $obj);

        $obj = new \stdClass();
        $obj->y = $summary_dataset['notAnswered'];
        $obj->label = 'notAnswered';
        array_push($QuizChart , $obj);



         $response = ['quizReport'=> $subjects_report,
                      'subject' => $subject,
                      'summaryDataset'=> $QuizChart,
                      'originalReport' => $result_record,
                      'quizDetails' => $exam_record ];

         return $response;
    }


    public function allQuizzes(Request $request)
    {
        $userID = $request["0"]["user_id"];

        $batches  = getStudentBatchesPWA($request["0"]);
         //liveQuizzes
                          $liveQuizzes = \App\Onlineclass::select(['quizzes.*', 'onlineclasses.live_quiz_popstatus'])
                          ->join('quizzes', 'quizzes.id', 'onlineclasses.live_quiz_id')
                          ->whereNotNull('class_time')->whereNotNull('valid_from')->whereNotNull('valid_to')
                          ->whereIn('batch_id', $batches)
                          ->whereRaw("'" . date('Y-m-d') . "' BETWEEN DATE(valid_from) AND DATE(valid_to)")
                          ->where('live_quiz_popstatus', 'yes')
                          ;
         // echo getEloquentSqlWithBindings( $onlineclasses );
     $live_quizzes = $liveQuizzes->orderBy('class_time')->get();

    foreach($live_quizzes as $quizzes){
     $quiz = Quiz::where('id',$quizzes["id"])->first();
     $quizsubject = $quiz->subjects()->first();

     if($quizsubject["subject_title"]!=null)
     $quizzes->subjectTitle = $quizsubject->subject_title;
      $quizzes->color_code   = $quizsubject["color_code"];
    }


     // Past Quizzes
    $pastQuizzes = Quiz::join('quizresults', 'quizzes.id', '=', 'quizresults.quiz_id')
            ->join('users','user_id','=','users.id')
            ->select(['title','is_paid' , 'marks_obtained','quizresults.quiz_id', 'exam_status','quizresults.created_at', 'quizzes.total_marks','quizzes.slug', 'quizresults.slug as resultsslug','users.name'])
            ->where('user_id', '=', $userID)
            ->orderBy('quizresults.updated_at', 'desc')
            ->get();

            foreach($pastQuizzes as $quizzes){
     $quiz = Quiz::where('id',$quizzes["quiz_id"])->first();
     $quizsubject = $quiz->subjects()->first();

     // dd($quiz);
     if($quizsubject["subject_title"]!=null)
      $quizzes->subjectTitle = $quizsubject["subject_title"];
      $quizzes->color_code   = $quizsubject["color_code"];
    }

    $response = ['liveQuizzes' => $live_quizzes,
                 'pastQuizzes'=> $pastQuizzes ];

         return $response;



    }


    public function getAnalysis(Request $request){

      $userID = $request["0"]["user_id"];

       $user = User::where('id',$userID)->first();

       $userid = $user->id;
      //ANALYSISBYEXAM
       $analysisByExam = array();
       $analysisByExam = Quiz::join('quizresults', 'quizzes.id', '=', 'quizresults.quiz_id')
            ->select(['title','is_paid' ,'dueration', 'quizzes.total_marks',  \DB::raw('count(quizresults.user_id) as attempts, quizzes.slug, user_id') ])
            ->where('user_id', '=', $user->id)
            ->groupBy('quizresults.quiz_id')
            ->get();

    $pieChartExam = array();
    foreach($analysisByExam as $Exam){
        $obj = new \stdClass();
        $obj->y = $Exam["attempts"];
        // $obj->label = $Exam["title"];
        array_push($pieChartExam , $obj);
        }


      //ANALYSISBYSUBJECT

       $analysisBySubject = array();
       $analysisBySubject = ( new QuizResult())->getOverallSubjectsReport($user);
       $color_correct = getColor('background',rand(00,9999));
       $color_wrong = getColor('background', rand(00,9999));
       $color_not_attempted = getColor('background', rand(00,9999));
       $finalanalysisBySubject = array();
       foreach($analysisBySubject as $subject){
           array_push($finalanalysisBySubject, $subject);
       }

       $marksPerSubject = array();
       foreach($finalanalysisBySubject as $perSubject){
        $markSubject = array();
        array_push($markSubject , $perSubject["subject_name"]);
        array_push($markSubject , $perSubject["correct_answers"]);
        array_push($markSubject , $perSubject["wrong_answers"]);
        array_push($markSubject , $perSubject["not_answered"]);
        array_push($marksPerSubject , $markSubject);
       }

       $marksPerTime = array();
       foreach($finalanalysisBySubject as $perSubject){
        $markTime = array();
        array_push($markTime , $perSubject["subject_name"]);
        array_push($markTime , $perSubject["time_spent"]);
        array_push($markTime , $perSubject["time_spent_on_correct_answers"]);
        array_push($markTime , $perSubject["time_spent_on_wrong_answers"]);
        array_push($marksPerTime , $markTime);
       }


    //HistoryAnalysis
    $historyAnalysis = Quiz::join('quizresults', 'quizzes.id', '=', 'quizresults.quiz_id')
            ->select(['title','is_paid' , 'marks_obtained','quiz_id', 'exam_status','quizresults.created_at', 'quizzes.total_marks','quizzes.slug', 'quizresults.slug as resultsslug','user_id' ])
            ->where('user_id', '=', $user->id)
            ->orderBy('quizresults.updated_at', 'desc')
            ->get();
    $lineChartHistory = array();
    foreach($historyAnalysis as $history){
        $obj = new \stdClass();
        $obj->y = $history["marks_obtained"];
        $obj->label = $history["title"];
        array_push($lineChartHistory , $obj);
        }



    $response = ['analysisByExam' => $analysisByExam,
                 'marksPerSubject'=> $marksPerSubject,
                 'marksPerTime'=> $marksPerTime,
                 'color_correct' => $color_correct,
                 'color_wrong' => $color_wrong,
                 'color_not_attempted' => $color_not_attempted,
                 'historyAnalysis' =>  $historyAnalysis,
                 'lineChartHistory' => $lineChartHistory,
                 'pieChartExam' => $pieChartExam];

         return $response;


    }

    /**
 * @param Request $request
 */
    public function updateUserInfo(Request $request)

    {

        $user = User::where('id',$request->id)->first();

        if($request->file('image')){
        $user = User::where('id',$request->id)->first();
        $image = $request->file('image');
        $rand_name = time() . str_random(10);
        $filename = $rand_name . '.' . $image->getClientOriginalExtension();

        Image::make($image)
            // ->resize(384, 384)
            ->save(base_path('public/uploads/users/' . $filename));

        $user->image = $filename;


        $user->save();

        }

        if($request->password){
          $user = User::where('id',$request->id)->first();

          $user->password  = \Hash::make($request->password);
           $user->save();

        }


        $response = [
            'success' => 'profileUpdated',
            'data' => [
                'id' => $user->id,
                'auth_token' => $user->auth_token,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image' => $user->image,
                'success' => 'profileUpdated',
            ]
        ];

        return response()->json($response);
    }


        /**
     * This method displays the payment transactions made by the user
     * The user info is accessed by the provided slug
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function subscriptionDetails(Request $request)
    {

      $user = User::where('id', $request["0"])->first();

      $payment = new Payment();
      $records = $payment->updateTransactionRecords($user->id);

      foreach($records as $record)
      {
        $rec = Payment::where('id',$record->id)->first();
        $this->isExpired($rec);
      }

    $paymentRecords = Payment::select(['item_name','item_id', 'plan_type', 'start_date', 'end_date', 'payment_gateway', 'updated_at','payment_status','id','cost', 'after_discount', 'paid_amount'])
         ->where('user_id', '=', $user->id)->orderBy('updated_at', 'desc')->get();
  $paymentRecordsID  = $paymentRecords->pluck('item_id')->toArray();

         //PAIDCONTENT

      $student_courses = getStudentClassesPWA('courses' , $user->id);
      // print_r($student_courses);
      $paidcontents = \App\StudentPaidContent::select(['student_paid_contents.*'])->join('student_paid_contents_data as spcd', 'spcd.student_paid_contents_id', '=', 'student_paid_contents.id')
      ->join('student_paid_contents_courses as spcc', 'spcc.student_paid_contents_id', '=', 'student_paid_contents.id')
      //->where('student_paid_contents.display_type', $display_type)
      ->whereIn('spcc.course_id', $student_courses)
      ->where('student_paid_contents.total_items', '>', 0)
      ->where('student_paid_contents.status', 'active')
      ->groupBy('spcd.student_paid_contents_id')
      ;
      $data['paid_contents']         = $paidcontents->paginate(getRecordsPerPage());

    $originalPaidContents = [];
      foreach( $data['paid_contents'] as $paid_content){
        if(!in_array($paid_content->id ,$paymentRecordsID)){
        $paidcontent = array();
        $subjects = \App\Subject::join('courses_subjects', 'subjects.id', '=', 'courses_subjects.subject_id')
     ->join('student_paid_contents_courses', 'student_paid_contents_courses.course_id', '=', 'courses_subjects.course_id')
     ->whereIn('courses_subjects.course_id', $student_courses)
     ->where('student_paid_contents_id', $paid_content->id)->get();
     array_push($paidcontent, $paid_content);
     array_push($paidcontent, $subjects);
     array_push($originalPaidContents, $paidcontent);
 }
      }

          $response = ['paymentRecords' => $paymentRecords,
                        'paidContents' => $originalPaidContents ];

          return $response;



    }

    public function subjectTests(Request $request){

       $user = User::where('id', $request["0"])->first();
        $student_courses = getStudentClassesPWA('courses' , $user->id);
              $date = date('Y-m-d');

                $type = "subject-exams";
                      $records = \App\StudentPaidContent::select(['q.title', 'q.dueration', 'q.total_questions', 'q.start_date', 'q.end_date', 'q.is_paid', 'q.total_marks','q.slug', 'q.validity','q.cost','q.start_time', 'q.id'])

      ->join('student_paid_contents_data as spcd', 'spcd.student_paid_contents_id', '=', 'student_paid_contents.id')
      ->join('student_paid_contents_courses as spcc', 'spcc.student_paid_contents_id', '=', 'student_paid_contents.id')
      ->join('quizzes as q', 'q.id', '=', 'spcd.item_id')
      ->join('quizzes_subjects','quizzes_subjects.quiz_id','=','q.id')
      ->join('payments as p', 'p.item_id', '=', 'spcd.student_paid_contents_id')

      ->where('spcd.item_type', $type)
      ->whereIn('spcc.course_id', $student_courses)
      ->where('student_paid_contents.total_items', '>', 0)
      ->where('student_paid_contents.status', 'active')
      ->groupBy('spcd.item_id')
      ->where('p.end_date','>=',$date)
      ->where('p.user_id','=',$user->id)
      ->where('p.plan_type','=','paidcontent');
      // echo getEloquentSqlWithBindings( $records );
      $records = $records->get();
   $subjectTests = [];
   $subjects = [];
   foreach($records as $record){
    $subjecttests = array();
    $subjects_list = \App\Subject::join('quizzes_subjects', 'subjects.id', 'quizzes_subjects.subject_id')->where('quiz_id', $record->id)->first();
    $subjects_list->pluck('subject_title')->toArray();
    array_push($subjecttests , $record);
    array_push($subjecttests, $subjects_list);
    array_push($subjectTests , $subjecttests);
    array_push($subjects , $subjects_list);
   }
        $response = ['subjectTests' => $subjectTests ,
                     'subjects' => $subjects ];

          return $response;
    }

    public function grandTests(Request $request){


         $user = User::where('id', $request["0"])->first();
        $student_courses = getStudentClassesPWA('courses' , $user->id);
              $date = date('Y-m-d');


        $type = "grand-exams";
                      $records = \App\StudentPaidContent::select(['q.title', 'q.dueration', 'q.total_questions', 'q.start_date', 'q.end_date', 'q.is_paid', 'q.total_marks','q.slug', 'q.validity','q.cost','q.start_time', 'q.id','q.year'])

      ->join('student_paid_contents_data as spcd', 'spcd.student_paid_contents_id', '=', 'student_paid_contents.id')
      ->join('student_paid_contents_courses as spcc', 'spcc.student_paid_contents_id', '=', 'student_paid_contents.id')
      ->join('quizzes as q', 'q.id', '=', 'spcd.item_id')
      ->join('quizzes_subjects','quizzes_subjects.quiz_id','=','q.id')
      ->join('payments as p', 'p.item_id', '=', 'spcd.student_paid_contents_id')

      ->where('spcd.item_type', $type)
      ->whereIn('spcc.course_id', $student_courses)
      ->where('student_paid_contents.total_items', '>', 0)
      ->where('student_paid_contents.status', 'active')
      ->groupBy('spcd.item_id')
      ->where('p.end_date','>=',$date)
      ->where('p.user_id','=',$user->id)
      ->where('p.plan_type','=','paidcontent');
      // echo getEloquentSqlWithBindings( $records );
      $grandTests = $records->get();
      $response = ['grandTests' => $grandTests
                     ];

          return $response;
    }

    public function previousYearsTests(Request $request){

         $user = User::where('id', $request["0"])->first();
        $student_courses = getStudentClassesPWA('courses' , $user->id);
              $date = date('Y-m-d');


        $type = "previousyear-exams";
                      $records = \App\StudentPaidContent::select(['q.title', 'q.dueration', 'q.total_questions', 'q.start_date', 'q.end_date', 'q.is_paid', 'q.total_marks','q.slug', 'q.validity','q.cost','q.start_time', 'q.id','q.year'])

      ->join('student_paid_contents_data as spcd', 'spcd.student_paid_contents_id', '=', 'student_paid_contents.id')
      ->join('student_paid_contents_courses as spcc', 'spcc.student_paid_contents_id', '=', 'student_paid_contents.id')
      ->join('quizzes as q', 'q.id', '=', 'spcd.item_id')
      ->join('quizzes_subjects','quizzes_subjects.quiz_id','=','q.id')
      ->join('payments as p', 'p.item_id', '=', 'spcd.student_paid_contents_id')

      ->where('spcd.item_type', $type)
      ->whereIn('spcc.course_id', $student_courses)
      ->where('student_paid_contents.total_items', '>', 0)
      ->where('student_paid_contents.status', 'active')
      ->groupBy('spcd.item_id')
      ->where('p.end_date','>=',$date)
      ->where('p.user_id','=',$user->id)
      ->where('p.plan_type','=','paidcontent');
      // echo getEloquentSqlWithBindings( $records );
      $records = $records->get();
       $previousTests = [];
   $years = [];
   foreach($records as $record){

    $previoustests = array();
    $year = array();
    array_push($year , $record->year);
    array_push($year , $record->slug);
    array_push($years ,$year);
    array_push($previoustests , $year);
    array_push($previoustests, $record);
    array_push($previousTests , $previoustests);

   }
        $response = ['previousTests' => $previousTests,
                     'years' => $years];

          return $response;
    }


    public function packageDetails(Request $request){
     $packageId = $request["0"]["id"];
     $details = \App\StudentPaidContent::where('id',$packageId)->first();
      $response = ['packageDetails' => $details ];

          return $response;
    }

        public function packageStatus(Request $request){
        $userID = $request["1"]["userID"];
        $packageID = $request["0"]["packageID"];


        if(Payment::isItemPurchasedNotTrail($packageID, 'paidcontent', $userID))
        {
          //User already purchased this item and it is valid
          //Return the user to back by the message

          $date = date('Y-m-d');
          $days = 0;
          $payments = \App\Payment::where('user_id', $userID)->where('payment_status', 'success')->get();


          foreach($payments as $record) {
            $days_diff = dateDiffInDays( $date, $record->end_date);

            if ( $days < $days_diff ) {
              $days = $days_diff;
            }
          }

          if ( $days > 5 ) {

                  $response = ['packageStatus' => 'false',
                                'message' => 'Alredy Purchased' ];

          return $response;
          }
        }else{

                  $response = ['packageStatus' => 'true' ];

          return $response;
        }
    }


    public function razorpaySuccess(Request $request){

      $paymentID = $request["0"]["paymentid"];
      $user_id = $request["1"]["user_id"];
      $packageId = $request["2"]["packageID"];

      $user    = User::where('id',$user_id)->first();
        //get API Configuration
        $api = new Api(env('RAZORPAY_APIKEY'), env('RAZORPAY_SECRET'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($paymentID);


                // $response = $api->payment->fetch($paymentID)->capture(array('amount'=>$payment['amount']));

                 $item = \App\StudentPaidContent::where('id',$packageId)->first();

                 // $type = $request->type;

                $payment_record                  = new Payment();
                $payment_record->transaction_id  = $paymentID;
                $payment_record->item_id         = $item->id;
                $payment_record->item_name       = $item->title;
                $payment_record->plan_type       = 'paidcontent';
                $payment_record->payment_gateway = 'Razorpay';
                $payment_record->slug            = $payment_record::makeSlug(getHashCode());
                $payment_record->cost            = $item->cost;
                $payment_record->user_id         = $user->id;
                $payment_record->payment_status  = PAYMENT_STATUS_SUCCESS;
                // $payment_record->coupon_applied  = $request->is_coupon_applied;
                // $payment_record->coupon_id       = $request->coupon_id;
                // $payment_record->actual_cost     = $request->actual_cost;
                // $payment_record->discount_amount = $request->discount_availed;
                // $payment_record->after_discount  = $request->after_discount;
                $payment_record->paid_by         = $payment->email;
                // $payment_record->paid_amount     = $request->after_discount;
                // $payment_record->paid_by_parent  = $request->parent_user;
                if ( ! empty( $request->subscribe_onlineclasses ) ) {
                  $payment_record->subscribe_onlineclasses = $request->subscribe_onlineclasses;
                }
                if ( ! empty( $request->online_classes_price ) ) {
                  $payment_record->online_classes_price = $request->online_classes_price;
                }

                $daysToAdd = '+'.$item->validity.'days';
                $payment_record->start_date = date('Y-m-d');

                  $duration = $item->duration;
                  $duration_type = $item->duration_type;

                  switch ($duration_type) {
                    case 'Day':
                        $payment_record->end_date = now()->addDays($duration);
                        break;
                    case 'Week':
                        $payment_record->end_date = now()->addWeeks($duration);
                        break;
                    case 'Month':
                        $payment_record->end_date = now()->addMonths($duration);
                        break;
                    case 'Year':
                        $payment_record->end_date = now()->addYears($duration);
                        break;
                    }

                // dd($payment_record);
                $payment_record->save();

                                                // Let us close free trail notifications for the same package if any!
                $free_trails = Payment::where('user_id', $user->id)->where('item_id', $item->id)->where('id', '!=', $payment_record->id)->update(['notification_closed' => 1]);

                // Let us close free trail notifications for the same package if any!
                // $free_trails = Payment::where('user_id', $user_id)->where('item_id', $item->id)->where('id', '!=', $payment_record->id)->update(['notification_closed' => 1]);


        $response = ['paymentStatus' => 'success' ,];

          return $response;




    }


    public function notificationData(Request $request){
      $user_id = $request["0"]["user_id"];


     if( isOnlinestudentPWA($user_id) )
                     {
                        $payments = \App\Payment::where('user_id', $user_id)->where('notification_closed', '0')->get();
                        $under_trail = '';
                        if( $payments->count() > 0 ) {
                                    $OriginalUnderTrail = [];
                            foreach( $payments as $payment ) {
                                $days = dateDiffInDays(date('Y-m-d'), $payment->end_date);
                                //$days = 0;

                                if ( $days < 30 ) {
                                    $package = \App\StudentPaidContent::find( $payment->item_id );

                                    if( $payment->notes == 'Trail period' ) {
                                        if ( $days > 0 ) {
                                            $under_trail = '<h3 style="color:red;">Your trail period for <i>'.$package->title.'</i> ends in ' . $days . ' Day(s).';

                                            if ( $days < 5 ) {
                                                $under_trail .= ' <a href="'.route('payments.checkout', ['type' => 'paidcontent', 'slug' => $package->slug]).'">Click</a> here to buy it.';
                                            }
                                            $under_trail .= '</h3>';
                                        } else {
                                            $under_trail = '<h3 style="color:red;">Your trail expired for <i>'.$package->title.'</i>.';

                                            $under_trail .= ' <a href="'.route('payments.checkout', ['type' => 'paidcontent', 'slug' => $package->slug]).'">Click</a> here to buy it.';
                                            $under_trail .= '</h3>';
                                        }

                                    } else {
                                        if ( $days > 0 ) {
                                           $undertrail = array();
                                            $under_trail = '<h3 style="color:red;">Your package validity <i>'.$package->title.'</i> ends in ' . $days . ' Day(s).';

                                            array_push($undertrail, $under_trail);

                                                $button = '0';
                                            array_push($undertrail, $button);

                                            array_push($undertrail, $package);
                                            array_push($OriginalUnderTrail, $undertrail);

                                        } else {
                                            $undertrail = array();
                                            $under_trail = '<h3 style="color:red;">Your package <i>'.$package->title.'</i> has been expired.';
                                            $button='1';
                                            array_push($undertrail, $under_trail);
                                            array_push($undertrail, $button);
                                            array_push($undertrail, $package);
                                            array_push($OriginalUnderTrail, $undertrail);

                                        }
                                    }

                                }
                            }

                        }
                          $response = ['notifications' => $OriginalUnderTrail ];
                            return $response;
                   }
    }

        public function notification(Request $request){


        $user_id = $request["0"]["user_id"];
    $data['notifications'] = Notification::where(function($query) use ($user_id) {
        $batches = getStudentBatchesPWA($user_id);
        if ( count( $batches ) ) {
          foreach ($batches as $batch_id) {
            $query->orWhere('batch_id', $batch_id);
          }
        }
        $query->orWhere('notification_for', 'allstudents' );
        $classes = getStudentClassesPWA('classes',$user_id);
        if ( count( $classes ) ) {
          foreach ($classes as $class_id) {
            $query->orWhere('student_class_id', $class_id);
          }
        }
               })->get();

              return  $response =  $data;
                            return $response;
    }

    public function examList(Request $request){
         $user_id = $request["0"];
         $records = array();
        $batches  = getStudentBatchesPWA($user_id);

        $records  = Quiz::join('batch_quizzes','batch_quizzes.quiz_id','=','quizzes.id')
        ->select(['title', 'dueration', 'quizzes.id','total_questions', 'batch_id', 'start_date', 'end_date', 'is_paid', 'total_marks','quizzes.slug', 'quizzes.validity','quizzes.cost','start_time'])
        ->where('is_multisubject', 'yes');
        $records->where('total_marks', '!=', 0)
        ->groupBy('batch_quizzes.quiz_id')
        ->whereIn('batch_quizzes.batch_id',$batches);

        //print_r($records->getBindings());
        //echo $records->toSql();
        $records = $records->get();

        $response = ['exams' => $records ];
                            return $response;
    }

    public function userNumber(Request $request){
     $response = ['number' => '8106354035' ];
                            return $response;
    }



    public function boards(){
         $boards = \App\Board::where('status', 'active')->get();
         $courses = \App\Course::where('institute_id', OWNER_INSTITUTE_ID)->get();
         $classes = \App\StudentClass::where('institute_id', OWNER_INSTITUTE_ID)->get();
         $response = ['boards' => $boards,
                      'courses' => $courses,
                      'classes' => $classes ];
                      return $response;

    }


    public function getClassCourses(Request $request){
        $classID = $request['0']['class_id'];
       $courses     = \DB::table('student_classes_courses')
            ->select('courses.*')
            ->join('courses', 'courses.id', 'student_classes_courses.course_id')
            ->where('student_classes_courses.student_class_id', $classID)->get();

        $response = ['courseList' => $courses];
        return $response;
    }


    public function registration(Request $request ){

          $validatedData = Validator::make($request->all(),[
            'username' => 'required|unique:users,username',
            'email'    => 'required|unique:users,email',
            'phone'    => 'required|unique:users,phone',

            'password' => 'required|min:5',
            'confirmPassword'=>'required|min:5|same:password']);
if(!$validatedData ->passes()) {
            return response()->json(['error'=>$validatedData->errors()->all()]);
}else{

        return $this->addUser( $request );


}

    }

    public function addUser( $request ) {

        $role_id = STUDENT_ROLE_ID;

        $user           = new User();
        $name           = $request->Fullname;

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
        $user->login_enabled  = 1;
        $user->board_id  = $request->board;
        if ( $request->class ) {
            $user->student_class_id  = $request->class;
        }
        $user->course_id  = $request->course;
        $user->assigned = 'no';

        /**
         * CR: Remove email verification if the student is registered from the panel
         * Date: May 27, 2020
         * Author: Adi
        */
        $user->is_verified = 1;
        $user->activation_code = str_random(30);
        $link = URL_USERS_CONFIRM.$user->activation_code;


        $users_phone = \App\UserPhone::where('mobile_number',$request->phone)->where('country_code', '91')->first();
        $otp  = $users_phone->otp;
            $users_phone = $request->phone;
            $user->is_mobile_verified  = 1;
            $user->otp  = $otp;

        $user->save();


            $user->institute_id = OWNER_INSTITUTE_ID;
            $user->login_enabled = 1;
            $user->save();


            $available_batch = \App\Batch::where('institute_id', OWNER_INSTITUTE_ID)->where('student_class_id', $request->class)->where('course_id', $request->course)->first();
            if ( $available_batch ) {
                \DB::table('batch_students')->insert([
                    'user_id' => $user->id,
                    'batch_id' => $available_batch->id,
                    'institute_id' => OWNER_INSTITUTE_ID
                ]);
            }

        $user->roles()->attach($user->role_id);

            // Default Pacjage.
            $default_package = \App\StudentPaidContent::join('student_paid_contents_courses as spcc', 'student_paid_contents.id', '=', 'spcc.student_paid_contents_id')->where('spcc.course_id', $request->course)->first();
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



                 $user->notify(new \App\Notifications\NewUserRegistration($user,$user->email,$password, $link));


            $phone = $request->phone;
            $data = [
                'institute_name' => $request->institute_name,
                'phone' => $phone,
                'address' => $request->institute_address,
                'email' => $request->email,
                'to_email' => 'info@learneazy.org',
             ];
             sendEmail('registration-owner', $data);


       $response = ['user' => $user,
                    'success' => 'true' ];
                      return $response;
    }


    public function otpVerify(Request $request){
       $otp = $request["0"]["otp"];
       $phone = $request["1"]["phone"];
       $phone_code ='91';


                $users_phone = \App\UserPhone::where('mobile_number', $phone)->where('country_code', $phone_code)->first();
                if ( $users_phone ) {
                    $users_phone->status = 'Mobile verified';
                    $users_phone->save();
                    $response = [ 'success' => 'true',
                                  'id'      =>  $users_phone->id,
                                  'message2' => 'successfully verified your mobile number.'];
                        return $response;

                } else {
                     $response = [ 'success' => 'false',
                                   'message2' => 'OTP not matched with the mobile number.'];
                        return $response;
                }


    }

    public function noteDest(Request $request){

                $id = $request["0"]["id"];
    $data['notifications'] = Notification::where('id',$id)->first();

         $response =  $data;
                            return $response;

    }


    public function enrollPhone(Request $request){
       $phone = $request["phone"];
       $phone_code = '91';
       $validations = $messsages = [];

            $validatedData = Validator::make($request->all(),[
            'phone'    => 'required|unique:users,phone']);
if(!$validatedData ->passes()) {
            return response()->json(['error'=>$validatedData->errors()->all()]);
}else{
    $users_phone = \App\UserPhone::where('mobile_number', $phone)->where('country_code', $phone_code)->first();
                if ( $users_phone ) {
                    if ( $users_phone->otp_used >= OTP_MAX_USAGE) {
                        $response = [   'attempts' => '0',
                                        'message' => 'used maximum attempts to send OTP.'];
                        return $response;
                    }else{
                        $otp = mt_rand(1000, 9999);
                        $users_phone->otp = $otp;
                        $users_phone->otp_used = $users_phone->otp_used + 1;
                        $users_phone->save();
                        $site_title = getSetting('site_title', 'site_settings');
                        $smsmessage = "$otp is your OTP to log into $site_title";

                        $phone = $phone_code . $phone;
                        $smsagent = \App\SMSAgent::sendUserOTPSMS( $phone, $smsmessage, $otp);
                        $response = [ 'attempts' => '1',
                                      'message' => 'OTP Sent to your mobile number.'];
                        return $response;
                    }
                    }else{
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
                    $response = [   'attempts' => '1',
                                   'message' => 'OTP Sent to your mobile number.'];
                        return $response;
                    }
}


    }

    public function getuserPhone(Request $request){
       $phnID = $request["0"];
       $users_phone = \App\UserPhone::where('id', $phnID)->select('mobile_number')->first();
       $response = [$users_phone];
       return $response;
    }

    public function classAttendence(Request $request){
       $userID = $request["0"];
       $classID = $request["1"];

$userAttendence = \App\OnlineclassAttendance::where('class_id',$classID)->where('student_id' , $userID)->first();
  if(!$userAttendence){

$rr = \App\OnlineclassAttendance::create([
          'class_id' => $classID,
          'student_id' => $userID,
        ]);

       $response = [$rr];
       return $response;
   }else{
       $response = [$userAttendence ];
       return $response;
   }
    }


    public function freeEnroll(Request $request){
       $userId = $request["0"];
       $packID = $request["1"];
       $paidcontent = \App\StudentPaidContent::where( 'id', $packID )->first();

      if($isValid = $this->isValidRecord($paidcontent)){

           $response = 'INVALID CONTENT';
        return $response;
      }

      if ( \App\Payment::isTrailUsedPWA($paidcontent->id, 'paidcontent' , $userId) ) {
        $response = 'You have already used trail for this package';
        return $response;
      }

      $user = User::where('id',$userId)->first();
      $free_trail_days = $paidcontent->free_trail_days;
      if ( empty( $free_trail_days ) ) {
        $free_trail_days = DEFAULT_VALIDITY_DAYS;
      }
      $user->package_id = $paidcontent->id;
      $user->trial_until = now()->addDays($free_trail_days);
      $user->save();

      $payment_record                  = new \App\Payment();
      $payment_record->transaction_id  = mt_rand();
      $payment_record->item_id         = $paidcontent->id;
      $payment_record->item_name       = $paidcontent->title;
      $payment_record->plan_type       = 'paidcontent';
      $payment_record->payment_gateway = 'Offline';
      $payment_record->slug            = $payment_record::makeSlug(getHashCode());
      $payment_record->cost            = 0;
      $payment_record->user_id         = $user->id;
      $payment_record->payment_status  = PAYMENT_STATUS_SUCCESS;
      $payment_record->actual_cost     = $paidcontent->cost;
      $payment_record->discount_amount = $paidcontent->cost;
      $payment_record->after_discount  = 0;
      $payment_record->paid_by         = $user->email;
      $payment_record->paid_amount     = 0;
      $payment_record->notes     = 'Trail period';

      $daysToAdd = '+'.$free_trail_days.'days';
      $payment_record->start_date = date('Y-m-d');
      $payment_record->end_date = now()->addDays($free_trail_days);
      $payment_record->save();

       $response = [   'success' => 'true',
                       'message' => 'Trail enabled for this package.'];
        return $response;


    }

    public function useractiveStatus(Request $request){
     $user = User::where('id',$request["0"])->first();
     if($user->login_enabled == 0){
              $response = [   'login_enabled' => '0'];
        return $response;
     }else{
              $response = [   'login_enabled' => '1'];
        return $response;
     }

    }


    }




