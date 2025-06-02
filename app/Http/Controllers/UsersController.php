<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use \App;
use App\Http\Requests;
use App\User;
use App\GeneralSettings as Settings;
use Image;
use ImageSettings;
use Yajra\Datatables\Datatables;
use DB;
use Illuminate\Support\Facades\Hash;
use Excel;
use Input;
use File;
use Auth;
use App\OneSignalApp;
use Exception;
use Illuminate\Support\Str;
class UsersController extends Controller
{

  public $excel_data = array();
    public function __construct()
    {
         $currentUser = \Auth::user();

         $this->middleware('auth');

    }

    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
     public function index($type = '', $type_id = '')
     {
  
      if( ! canDo('user_management_access') ) {
          prepareBlockUserMessage();
          return back();
        }

        $data['records']      = FALSE;

        $data['layout']       = getLayout();
        $data['active_class'] = 'users';
        $data['heading']      = getPhrase('users');
        $data['title']        = getPhrase('users');

        if ( empty( $type ) ) {
          $type = 'all';
        }
        if ( empty( $type_id ) ) {
          $type_id = '0';
        }
        $data['type']        = $type;
        $data['type_id']        = $type_id;
        // dd($data);
        $view_name = getTheme().'::users.list-users';
        return view($view_name, $data);
     }


    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */

    public function getDatatable($type = 'all', $type_id = 0)
    {
        $records = array();

        $institute_id   = adminInstituteId();

        if($type=='batch')
        {
          $records = User::join('roles', 'users.role_id', '=', 'roles.id')
          ->join('batch_students', 'batch_students.user_id', '=', 'users.id')
            ->select(['image','users.name','users.institute_id','email', 'roles.display_name','login_enabled','role_id','slug', 'users.updated_at','branch_id'])
            ->where('batch_students.batch_id', $type_id);
        } elseif($type=='all')
        {
            $records = User::join('roles', 'users.role_id', '=', 'roles.id')
             ->where('institute_id',$institute_id)
             ->where('users.id','!=', Auth::user()->id)
             ->where('role_id','!=',7)
             ->select(['image','users.name', 'institute_id','email','roles.display_name','login_enabled','role_id',
              'slug', 'users.id', 'users.updated_at','branch_id'])
             ->orderBy('users.updated_at', 'desc');

        } else {
            if ( 'class' === $type ) {
              $type_row = App\StudentClass::where('slug', $type_id)->first();
            } else {
              $type_row = App\Role::where('name', $type_id)->first();
            }

            $records = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->select(['image','users.name','institute_id','email', 'roles.display_name','login_enabled','role_id','slug', 'users.updated_at','branch_id']);
            //if(! checkRole(['owner'])){
              $records->where('institute_id',$institute_id);
            //}
            if ( 'class' === $type ) {
              $records->where('users.student_class_id',$type_row->id);
            } else {
              $records->where('roles.id',$type_row->id);
            }
            $records->orderBy('users.updated_at', 'desc');

        }

        return Datatables::of($records)
        ->addColumn('action', function ($records) {

          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                           <li><a href="'.URL_USERS_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
                          if(getRoleData($records->role_id)=='faculty')
                          {
                            $link_data .= ' <li><a href="'.route('users.set_permissions', ['user_slug' => $records->slug]).'"><i class="fa fa-spinner"></i>'.getPhrase("update_permissions").'</a></li>';

                             // $link_data .= ' <li><a href="'.URL_USERS_UPDATE_PARENT_DETAILS.$records->slug.'"><i class="fa fa-user"></i>'.getPhrase("update_permissions").'</a></li>';

                          }
                         $temp='';

                        //Show delete option to only the owner user
                        if(checkRole(getUserGrade(9)) && $records->id!=\Auth::user()->id)   {
                        $temp = '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
                         }

                        $temp .='</ul> </div>';
                        $link_data .= $temp;
            return $link_data;
            })

         ->editColumn('name', function($records) {
          if(getRoleData($records->role_id)=='student')
            return '<a href="'.URL_USER_DETAILS.$records->slug.'">'.ucfirst($records->name).'</a>';

          return ucfirst($records->name) . ' ('.$records->id.')';
        })
         ->editColumn('image', function($records){
            return '<img src="'.getProfilePath($records->image).'"  />';
        })


         ->editColumn('institute_id', function($records){

            $institute  = getInstitute($records->institute_id);
            if($institute)
            return '<a href = "'.URL_INSTITUTE_DETAILS.$institute->id.'">'.ucwords($institute->institute_name).'</a>';

             return '-';
        })

        ->removeColumn('login_enabled')
        ->removeColumn('role_id')
        ->removeColumn('branch_id')
        ->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('updated_at')
      ->removeColumn('validity_days_left')
      ->removeColumn('quiz_count_week')
      ->rawColumns(['image', 'name','action','institute_id'])
        // ->addAction('action',['printable' => false])

        ->make();
    }



     /**
      * Show the form for creating a new resource.
      *
      * @return Response
      */
     public function create()
     {
        if(!checkRole(getUserGrade(4), 'user_create'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['record']       = FALSE;
        $data['active_class'] = 'users';

        $roles                = \App\Role::select('display_name', 'id','name')->get();
        $final_roles = [];
        foreach($roles as $role)
        {
           if(!checkRole(getUserGrade(1))) {
             if(checkRole(getUserGrade(8))){
               if( in_array(strtolower($role->name), ['student', 'faculty'])) {
                $final_roles[$role->id] = $role->display_name;
              }

             }else{

                if(!(strtolower($role->name) == 'admin' || strtolower($role->name) =='owner' || strtolower($role->name) == 'parent'))
                $final_roles[$role->id] = $role->display_name;
             }


          }
          else{

            if(!(strtolower($role->name) == 'institute' || strtolower($role->name) == 'parent')){

             $final_roles[$role->id] = $role->display_name;
            }
          }

        }
        $data['roles']        = $final_roles;
        $data['title']        = getPhrase('add_user');
        if(checkRole(['parent']))
        $data['active_class'] = 'children';
        $data['layout']       = getLayout();

        // if(checkRole(['institute'])){

        //   $institute          = adminInstituteId();
        //   $branches           = branches($institute);
        //   $data['branches']   = $branches;
        //   $data['institute']  = getInstitute($institute);

        //    $view_name = getTheme().'::users.institute-add-edit-user';
        // return view($view_name, $data);

        // }


        $view_name = getTheme().'::users.add-edit-user';
        return view($view_name, $data);
     }

     /**
      * This method returns the roles based on the user type logged in
      * @param  [type] $roles [description]
      * @return [type]        [description]
      */
     public function getUserRoles()
     {
        $roles                = \App\Role::pluck('display_name', 'id');

        return array_where($roles, function ($key, $value) {
          if(!checkRole(getUserGrade(1))) {
            if(!($value == 'Admin' || $value =='Owner'))
              return $value;
          }
          else
            return $value;
        });
     }

     /**
      * Store a newly created resource in storage.
      *
      * @return Response
      */
     public function store(Request $request )
     {
      //  dd($request->all());
        $columns = array(
        'name'  => 'bail|required',
        'username' => 'bail|required|unique:users,username',
        'email' => 'bail|required|unique:users,email',
        'image' => 'bail|mimes:png,jpg,jpeg|max:2048',
        'password'=> 'bail|required|min:5',
        'password_confirmation'=>'bail|required|min:5|same:password',
        );

        if(checkRole(getUserGrade(2)))
          $columns['role_id']  = 'bail|required';



        $this->validate($request,$columns);

        $role_id = getRoleData('student');

        if($request->role_id)
          $role_id = $request->role_id;

        $user           = new User();
        $name           = $request->name;
        $user->name     = $name;
        $user->email    = $request->email;
        $password       = $request->password;
        $user->password = bcrypt($password);


        if(checkRole(['parent']))
          $user->parent_id = getUserWithSlug()->id;

        $user->role_id        = $role_id;
        $user->login_enabled  = 0;
        $slug                 = $user::makeSlug($name);
        $user->username       = $request->username;
        $user->slug           = $slug;

        $phone = $request->phone;
        $user->phone          = $phone;
        $user->online_url = $request->online_url;
        $user->white_board_code = $request->white_board_code;

        if ( ! empty( $request->student_class_id ) ) {
          $user->student_class_id = $request->student_class_id;
        }
        $user->address        = $request->address;
        $user->institute_id   = adminInstituteId();
        $user->added_by       = Auth::user()->id;

        $user->activation_code = str::random(30);
        $link = URL_USERS_CONFIRM.$user->activation_code;
        $user->save();

        if ( $user->role_id == 8 && checkRole(getUserGrade(2))) { // Faculty.
          $user->subjects()->sync(array_filter((array)$request->input('subjects')));

          $user->faculty_batches()->sync(array_filter((array)$request->input('faculty_batches')));
        }



        if(!env('DEMO_MODE')) {
           $user->roles()->attach($user->role_id);
          $this->processUpload($request, $user);
        }
        $message = getPhrase('record_added_successfully_with_password ').' '.$password;
        $exception = 0;

        if ( $request->login_enabled == 1 ) {
            $user->login_enabled  = 1;
            $user->activation_code = null;
            $user->is_verified  = 1;
            $user->save();
        }  else {
        $user->login_enabled  = 0;
        //$user->is_verified  = 0;
        $user->save();
      }


      //  $sb_users = [
      //   'first_name' => $request->name,
      //   'last_name' =>  '#' . rand(0, 99999),
      //   'password' => bcrypt($request->password),
      //   'email' =>  $request->email,
      //   'profile_image' => 'default',
      //   'user_type' => 'lead',
      //   'creation_time' => date('Y-m-d H:i:s'),
      //   'token' => $user::makeSlug($name),
      //   'last_activity' => date('Y-m-d H:i:s'),
      //   'typing' =>'-1',
      //   'department' => 'NULL',
      // ];
      // \DB::table('sb_users')->insert( $sb_users );

      if ( canSend('user_creation', $user->id, 'Email') ) {
        try{
         if(!env('DEMO_MODE')) {
            $user->notify(new \App\Notifications\NewUserRegistration($user,$user->email,$password, $link));

            emailHistory( $user->email, $user->email.','.$password.','.$link );
          }
       }
       catch(Exception $ex)
       {
          $message = getPhrase('record_added_successfully_with_password ').' '.$password;
          $message .= getPhrase('\ncannot_send_email_to_user, please_check_your_server_settings');
          $exception = 1;
       }
    }

   if ( canSend('user_creation', $user->id, 'SMS') && ! empty( $phone ) ) {
        $site_title = getSetting('site_title', 'site_settings');
        $smsmessage = "Your account created at $site_title. Please login with following details.\r\nUsername: $request->username\r\nPassword: $password";
        $phone = '91' . $phone;
        $smsagent = \App\SMSAgent::sendUserSMS( $phone, $smsmessage);
    }

// die('gggggggggggg');
      $flash = app('App\Http\Flash');
      $flash->create('Success...!', $message, 'success', 'flash_overlay',FALSE);


       if(checkRole(['parent']))
        return redirect('dashboard');

       return redirect(URL_USERS);
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

     /**
      * This method sends the message to admin via One Signal
      * @param  string $message [description]
      * @return [type]          [description]
      */
     public function sendOneSignalMessage($new_message='')
     {
        $gcpm = new OneSignalApp();

      $message = array(
             "en" => $new_message,
             "title" => 'New Registration',
             "icon" => "myicon",
             "sound" => "default"
            );
          $data = array(
            "body" => $new_message,
             "title" => "New Registration",
          );

          $gcpm->setDevices(env('ONE_SIGNAL_USER_ID'));
          $response = $gcpm->sendToAll($message,$data);
     }




     protected function processUpload(Request $request, User $user)
     {

       if(env('DEMO_MODE')) {
        return 'demo';
       }

         if ($request->hasFile('image')) {

          $imageObject = new ImageSettings();

          $destinationPath      = $imageObject->getProfilePicsPath();
          $destinationPathThumb = $imageObject->getProfilePicsThumbnailpath();

          $fileName = $user->id.'.'.$request->image->guessClientExtension();
          ;
          $request->file('image')->move($destinationPath, $fileName);
          $user->image = $fileName;

          Image::make($destinationPath.$fileName)->fit($imageObject->getProfilePicSize())->save($destinationPath.$fileName);

          Image::make($destinationPath.$fileName)->fit($imageObject->getThumbnailSize())->save($destinationPathThumb.$fileName);
          $user->save();
        }
     }

    public function isValidRecord($record)
    {
      if ($record === null) {
        flash('Ooops...!', getPhrase("page_not_found"), 'error');
        return $this->getRedirectUrl();
    }

    return FALSE;
    }

    public function getReturnUrl()
    {
      return URL_USERS;
    }

     /**
      * Display the specified resource.
      *
      *@param  unique string  $slug
      * @return Response
      */
     public function show($slug)
     {
        //
     }



     /**
      * Show the form for editing the specified resource.
      *
      * @param  unique string  $slug
      * @return Response
      */
     public function edit($slug)
     {

        if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = User::with(['subjects'])->where('slug', $slug)->get()->first();

        if($isValid = $this->isValidRecord($record))
         return redirect($isValid);
       /**
        * Validate the non-admin user wether is trying to access other user profile
        * If so return the user back to previous page with message
        */

        if(!isEligible($slug))
          return back();


        /**
         * Make sure the Admin or staff cannot edit the Admin/Owner accounts
         * Only Owner can edit the Admin/Owner profiles
         * Admin can edit his own account, in that case send role type admin on condition
         */

     $UserOwnAccount = FALSE;
     if(\Auth::user()->id == $record->id)
      $UserOwnAccount = TRUE;

      if(!$UserOwnAccount)  {
        $current_user_role = getRoleData($record->role_id);

        if((($current_user_role=='admin' || $current_user_role == 'owner') ))
        {
          if(!checkRole(getUserGrade(1))) {
            prepareBlockUserMessage();
            return back();
          }
        }
      }

        $data['record']             = $record;
        // dd('hrere');
        // $data['roles']              = $this->getUserRoles();

         $roles                = \App\Role::select('display_name', 'id','name')->get();
        $final_roles = [];
        foreach($roles as $role)
        {

          if(!checkRole(getUserGrade(1))) {

             if(checkRole(getUserGrade(8))){

                if( in_array(strtolower($role->name), ['student', 'faculty']) ) {
                  $final_roles[$role->id] = $role->display_name;
                }

             }else{

                if(!(strtolower($role->name) == 'admin' || strtolower($role->name) =='owner' || strtolower($role->name) == 'parent'))
                $final_roles[$role->id] = $role->display_name;
             }


          }
          else{

            if(!(strtolower($role->name) == 'institute' || strtolower($role->name) == 'parent')){

             $final_roles[$role->id] = $role->display_name;
            }
          }
        }
        $data['roles']        = $final_roles;


        if($UserOwnAccount && checkRole(['admin']))
          $data['roles'][getRoleData('admin')] = 'Admin';

        $data['active_class']       = 'users';
        $data['title']              = getPhrase('edit_user');
        $data['layout']             = getLayout();
        //dd($data);
        // return view('users.add-edit-user', $data);

            $view_name = getTheme().'::users.add-edit-user';
        return view($view_name, $data);
     }



     /**
      * Update the specified resource in storage.
      *
      * @param  int  $id
      * @return Response
      */
     public function update(Request $request, $slug)
     {
        $record     = User::where('slug', $slug)->first();

        $validation = [
        'name'      => 'bail|required',
        //'email'     => 'bail|required|unique:users,email,'.$record->id,
        'image'     => 'bail|mimes:png,jpg,jpeg|max:2048',
        ];
        if(checkRole(getUserGrade(4))) {
          $validation['email'] = 'bail|required|unique:users,email,'.$record->id;
        }

        if ( ! empty( $request->password ) ) {
          $validation['password'] = 'bail|required|min:5';
          $validation['password_confirmation'] = 'bail|required|min:5|same:password';
        }

        //if(!isEligible($slug))
         // return back();



        $this->validate($request, $validation);

        $name = $request->name;
        $previous_role_id = $record->role_id;
         if($name != $record->name)
            $record->slug = $record::makeSlug($name);

        $record->name = $name;
        if(checkRole(getUserGrade(4))) {
          $record->email = $request->email;
        }

        $phone = $request->phone;
      $record->phone = $phone;
     $record->online_url = $request->online_url;
     $record->white_board_code = $request->white_board_code;


        if ( ! empty( $request->student_class_id ) ) {
          $record->student_class_id = $request->student_class_id;
        }
       $record->address = $request->address;
       if($request->password) {
        $password       = $request->password;
        $record->password = bcrypt($password);
      }

      if ( $request->login_enabled == 1 ) {
          $record->login_enabled  = 1;
          $record->activation_code = null;
          $record->is_verified  = 1;
      } else {
        $record->login_enabled  = 0;
        $record->is_verified  = 1;
      }
      $record->save();


      if ( $record->role_id == 8 && checkRole(getUserGrade(2))) { // Faculty.
          $record->subjects()->sync(array_filter((array)$request->input('subjects')));
          $record->faculty_batches()->sync(array_filter((array)$request->input('faculty_batches')));
        }

        /*
      $site_title = getSetting('site_title', 'site_settings');
      $smsmessage = "Your account created at $site_title. Please login with following details.\r\nUsername: $request->username\r\nPassword: $request->password";
      $phone = '919866211858';
      $smsagent = \App\SMSAgent::sendUserSMS( $phone, $smsmessage);
      */


        if(!env('DEMO_MODE')) {
          $this->processUpload($request, $record);
        }
        flash('success','record_updated_successfully', 'success');

        // dd( $record );
        // return redirect('users/edit/'.$record->slug);
        if(checkRole(getUserGrade(3)))
        return redirect(URL_USERS);
       return redirect(URL_USERS_EDIT.$record->slug);
      }



     /**
      * Remove the specified resource from storage.
      *
      * @param  unique string  $slug
      * @return Response
      */
    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
        if(!checkRole(getUserGrade(9),'user_delete'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = User::where('slug', $slug)->first();

        /**
         * Check if any exams exists with this category,
         * If exists we cannot delete the record
         */
           if(!env('DEMO_MODE')) {
           $imageObject = new ImageSettings();

          $destinationPath      = $imageObject->getProfilePicsPath();
          $destinationPathThumb = $imageObject->getProfilePicsThumbnailpath();

            $this->deleteFile($record->image, $destinationPath);
            $this->deleteFile($record->image, $destinationPathThumb);
            $record->delete();
          }
            $response['status'] = 1;
            $response['message'] = getPhrase('record_deleted_successfully');
            return json_encode($response);

    }

    public function deleteFile($record, $path, $is_array = FALSE)
    {
       if(env('DEMO_MODE')) {
        return ;
       }

        $files = array();
        $files[] = $path.$record;
        File::delete($files);
    }



    public function listUsers($role_name)
    {
      $role = App\Role::getRoleId($role_name);

      $users = User::where('role_id', '=', $role->id)->get();

      $users_list =  array();

      foreach ($users as $key => $value) {
        $r = array('id'=>$value->id, 'text' => $value->name, 'image' => $value->image);
            array_push($users_list, $r);
      }
      return json_encode($users_list);
    }

    public function details($slug)
    {
        $record     = User::where('slug', $slug)->get()->first();

        if($isValid = $this->isValidRecord($record))
         return redirect($isValid);

       /**
        * Validate the non-admin user wether is trying to access other user profile
        * If so return the user back to previous page with message
        */

        if(!isEligible($slug))
          return back();

        $data['record']      = $record;



         $user = $record;
            //Overall performance Report
            $resultObject = new App\QuizResult();
            $records = $resultObject->getOverallSubjectsReport($user);
            $color_correct          = getColor('background', rand(0,999));
            $color_wrong            = getColor('background', rand(0,999));
            $color_not_attempted    = getColor('background', rand(0,999));
            $correct_answers        = 0;
            $wrong_answers          = 0;
            $not_answered           = 0;

            foreach($records as $record) {
                $record = (object)$record;
                $correct_answers    += $record->correct_answers;
                $wrong_answers      += $record->wrong_answers;
                $not_answered       += $record->not_answered;

           }

            $labels = [getPhrase('correct'), getPhrase('wrong'), getPhrase('not_answered')];
            $dataset = [$correct_answers, $wrong_answers, $not_answered];
            $dataset_label[] = 'lbl';
            $bgcolor  = [$color_correct,$color_wrong,$color_not_attempted];
            $border_color = [$color_correct,$color_wrong,$color_not_attempted];
            $chart_data['type'] = 'pie';
            //horizontalBar, bar, polarArea, line, doughnut, pie
            $chart_data['title'] = getphrase('overall_performance');

            $chart_data['data']   = (object) array(
                    'labels'            => $labels,
                    'dataset'           => $dataset,
                    'dataset_label'     => $dataset_label,
                    'bgcolor'           => $bgcolor,
                    'border_color'      => $border_color
                    );

            $data['chart_data'][] = (object)$chart_data;

            //Best scores in each quizzes
            $records = $resultObject->getOverallQuizPerformance($user);
            $labels = [];
            $dataset = [];
            $bgcolor = [];
            $bordercolor = [];

            foreach($records as $record) {
                $color_number = rand(0,999);
                $record = (object)$record;
                $labels[] = $record->title;
                $dataset[] = $record->percentage;
                $bgcolor[] = getColor('background',$color_number);
                $bordercolor[] = getColor('border', $color_number);
           }

            $labels = $labels;
            $dataset = $dataset;
            $dataset_label = getPhrase('performance');
            $bgcolor  = $bgcolor;
            $border_color = $bordercolor;
            $chart_data['type'] = 'bar';
            //horizontalBar, bar, polarArea, line, doughnut, pie
            $chart_data['title'] = getPhrase('best_performance_in_all_quizzes');

            $chart_data['data']   = (object) array(
                    'labels'            => $labels,
                    'dataset'           => $dataset,
                    'dataset_label'     => $dataset_label,
                    'bgcolor'           => $bgcolor,
                    'border_color'      => $border_color
                    );

            $data['chart_data'][] = (object)$chart_data;

        $data['ids'] = array('myChart0', 'myChart1');
        $data['title']        = getPhrase('user_details');
        $data['layout']        = getLayout();
         $data['active_class'] = 'users';
        if(checkRole(['parent']))
          $data['active_class'] = 'children';
      //   $data['right_bar']          = TRUE;

      // $data['right_bar_path']     = 'student.exams.right-bar-performance-chart';
      // $data['right_bar_data']     = array('chart_data' => $data['chart_data']);

        // return view('users.user-details', $data);

            $view_name = getTheme().'::users.user-details';
        return view($view_name, $data);

    }

    /**
     * This method will show the page for change password for user
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function changePassword($slug)
    {

       $record = User::where('slug', $slug)->get()->first();

        if($isValid = $this->isValidRecord($record))
         return redirect($isValid);
       /**
        * Validate the non-admin user wether is trying to access other user profile
        * If so return the user back to previous page with message
        */

        if(!isEligible($slug))
          return back();

        $data['record']             = $record;
        $data['active_class']       = 'profile';
        $data['title']              = getPhrase('change_password');
        $data['layout']             = getLayout();
        // return view('users.change-password.change-view', $data);

               $view_name = getTheme().'::users.change-password.change-view';
        return view($view_name, $data);
    }

    /**
     * This method updates the password submitted by the user
     * @param  Request $request [description]
     * @return [type]           [description]
     */
     public function updatePassword(Request $request)
    {


        $this->validate($request, [
            'old_password' => 'required',
            'password'     => 'required|confirmed',
        ]);

        $credentials = $request->only(
            'old_password', 'password', 'password_confirmation'
        );
        $user = \Auth::user();


        if (Hash::check($credentials['old_password'], $user->password)){
            $password = $credentials['password'];
            $user->password = bcrypt($password);
            $user->save();
            flash('success','password_updated_successfully', 'success');
            return redirect(URL_USERS_CHANGE_PASSWORD.$user->slug);

        }else {

            flash('Oops..!','old_and_new_passwords are not same', 'error');
            return redirect()->back();
       }
  }

  /**
    * Display a Import Users page
    *
    * @return Response
    */
     public function importUsers($role = 'student')
     {
        if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['records']      = FALSE;
        $data['active_class'] = 'users';
        $data['heading']      = getPhrase('users');
        $data['title']        = getPhrase('import_users');
        $data['layout']        = getLayout();
        // return view('users.import.import', $data);

           $view_name = getTheme().'::users.import.import';
        return view($view_name, $data);
     }

     public function readExcel(Request $request)
     {

        $columns = array(
        'excel'  => 'bail|required',
        );

        $this->validate($request,$columns);

       if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }
       $success_list = [];
       $failed_list = [];

       try{
        if(Input::hasFile('excel')){
          $path = Input::file('excel')->getRealPath();
          $data = Excel::load($path, function($reader) {
          })->get();

          $user_record = array();
          $users =array();
          $isHavingDuplicate = 0;
          if(!empty($data) && $data->count()){

            foreach ($data as $key => $value) {

              foreach($value as $record)
              {
                unset($user_record);

                $user_record['username'] = $record->username;
                $user_record['name'] = $record->name;
                $user_record['email'] = $record->email;
                $username = $record->username;
                if ( empty( $username ) ) {
                  $username = strtolower( preg_replace("/[^a-zA-Z]+/", "", $record->name) );
                }
                $user_record['username'] = $username;

                $email = $record->email;
                if ( empty( $email ) ) {
                  $email = strtolower( preg_replace("/[^a-zA-Z]+/", "", $record->name) );
                  $email = $email . '@' . $email . '.com';
                  $email = strtolower( $email );
                }
                $user_record['email'] = $email;

                if ( ! empty( $record->student_class ) ) {
                  $student_class = \App\StudentClass::find( (int)$record->student_class );
                  if ( $student_class ) {
                    $user_record['student_class_id'] = $student_class->id;
                  }
                }

                $user_record['password'] = $record->password;
                $user_record['phone'] = $record->phone;
                $user_record['address'] = $record->address;
                $user_record['role_id'] = STUDENT_ROLE_ID;

                $user_record = (object)$user_record;
                $failed_length = count($failed_list);
                if($this->isRecordExists($record->username, 'username'))
                {

                  $isHavingDuplicate = 1;
                  $temp = array();
                 $temp['record'] = $user_record;
                 $temp['type'] ='Record already exists with this name';
                 $failed_list[$failed_length] = (object)$temp;
                  continue;
                }

                if($this->isRecordExists($record->email, 'email'))
                {
                  $isHavingDuplicate = 1;
                  $temp = array();
                 $temp['record'] = $user_record;
                 $temp['type'] ='Record already exists with this email';
                 $failed_list[$failed_length] = (object)$temp;
                  continue;
                }

                $users[] = $user_record;

              }

            }
              if($this->addUser($users))
                  $success_list = $users;
          }
        }



       $this->excel_data['failed'] = $failed_list;
       $this->excel_data['success'] = $success_list;

       flash('success','record_added_successfully', 'success');
       $this->downloadExcel();

     }

     catch( Exception $e)
     {
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash('oops...!',$e->errorInfo, 'error');
       }
       else {
          flash('oops...!','improper_sheet_uploaded', 'error');
       }

       return back();
     }

        // URL_USERS_IMPORT_REPORT
       $data['failed_list']   =   $failed_list;
       $data['success_list']  =    $success_list;
       $data['records']      = FALSE;
       $data['layout']      = getLayout();
       $data['active_class'] = 'users';
       $data['heading']      = getPhrase('users');
       $data['title']        = getPhrase('report');

       // return view('users.import.import-result', $data);

         $view_name = getTheme().'::users.import.import-result';
        return view($view_name, $data);

     }

public function getFailedData()
{
  return $this->excel_data;
}

public function downloadExcel()
{
    Excel::create('users_report', function($excel) {
      $excel->sheet('Failed', function($sheet) {
      $sheet->row(1, array('Reason','Name', 'Username','Email','Password','Phone','Address', 'student_class'));
      $data = $this->getFailedData();
      $cnt = 2;
      // dd($data['failed']);
      foreach ($data['failed'] as $data_item) {
        $item = $data_item->record;
        $sheet->appendRow($cnt++, array($data_item->type, $item->name, $item->username, $item->email, $item->password, $item->phone, $item->address, $item->student_class_id));
      }
    });

    $excel->sheet('Success', function($sheet) {
      $sheet->row(1, array('Name', 'Username','Email','Password','Phone','Address', 'student_class'));
      $data = $this->getFailedData();
      // dd( $data );
      $cnt = 2;
      foreach ($data['success'] as $data_item) {
        $item = $data_item;
        $sheet->appendRow($cnt++, array($item->name, $item->username, $item->email, $item->password, $item->phone, $item->address, $item->student_class_id));
      }

    });

    })->download('xlsx');

    return TRUE;
}
     /**
      * This method verifies if the record exists with the email or user name
      * If Exists it returns true else it returns false
      * @param  [type]  $value [description]
      * @param  string  $type  [description]
      * @return boolean        [description]
      */
     public function isRecordExists($record_value, $type='email')
     {
        return User::where($type,'=',$record_value)->get()->count();
     }

     public function addUser($users)
     {

      foreach($users as $request) {
        $user           = new User();
        $name           = $request->name;
        $user->name     = $name;
        $user->email    = $request->email;
        $user->username    = $request->username;
        $password = $request->password;
        $user->password = bcrypt($password);

        $user->role_id        = $request->role_id;
        $user->login_enabled  = 1;
        $user->is_verified  = 1;
        $user->slug           = $user::makeSlug($name);

        $phone = $request->phone;
        $user->phone          = $phone;
        $user->address        = $request->address;
        $user->institute_id   = adminInstituteId();
        if ( ! empty( $request->student_class_id ) ) {
          $user->student_class_id        = $request->student_class_id;
        }
        $user->save();

        $user->roles()->attach($user->role_id);

        if ( canSend('user_import', $user->id, 'SMS') && ! empty( $phone ) ) {
            $site_title = getSetting('site_title', 'site_settings');
            $smsmessage = "Your account created at $site_title. Please login with following details.\r\nUsername: $request->username\r\nPassword: $request->password";
            $phone = '91' . $phone;
            $smsagent = \App\SMSAgent::sendUserSMS( $phone, $smsmessage);
        }

        if ( canSend('user_import', $user->id, 'Email') ) {
            $link = '';
            try{
             if(!env('DEMO_MODE')) {
                $user->notify(new \App\Notifications\NewUserRegistration($user,$user->email,$password, $link));
              }
           }
           catch(Exception $ex)
           {

           }
        }
      }
       return true;
     }

  /**
   * This method shows the user preferences based on provided user slug and settings available in table.
   * @param  [type] $slug [description]
   * @return [type]       [description]
   */
  public function settings($slug)
  {

       $record = User::where('slug', $slug)->first();

        if($isValid = $this->isValidRecord($record))
         return redirect($isValid);
       /**
        * Validate the non-admin user wether is trying to access other user profile
        * If so return the user back to previous page with message
        */

        if(!isEligible($slug))
          return back();


        /**
         * Make sure the Admin or staff cannot edit the Admin/Owner accounts
         * Only Owner can edit the Admin/Owner profiles
         * Admin can edit his own account, in that case send role type admin on condition
         */

     $UserOwnAccount = FALSE;
     if(\Auth::user()->id == $record->id)
      $UserOwnAccount = TRUE;

      if(!$UserOwnAccount)  {
        $current_user_role = getRoleData($record->role_id);

        if((($current_user_role=='admin' || $current_user_role == 'owner') ))
        {
          if(!checkRole(getUserGrade(1))) {
            prepareBlockUserMessage();
            return back();
          }
        }
      }

       $data['record']            = $record;


        if(checkRole('student')){

        $institute_id   = $record->institute_id;
        }else{

        $institute_id   = adminInstituteId();
        }

        if(shareData()){

         $data['quiz_categories']   = App\QuizCategory::get();
         $data['lms_category']      = App\LmsCategory::get();

        }else{

         $data['quiz_categories']   = App\QuizCategory::where('institute_id',$institute_id)->get();
         $data['lms_category']      = App\LmsCategory::where('institute_id',$institute_id)->get();


        }


       // $data['quiz_categories']   = App\QuizCategory::get();
       // $data['lms_category']      = App\LmsCategory::get();

       // dd($data);
       $data['layout']       = getLayout();
       $data['active_class'] = 'users';
       $data['heading']      = getPhrase('account_settings');
       $data['title']        = getPhrase('account_settings');


        $view_name = getTheme().'::users.account-settings';
        return view($view_name, $data);

}

  /**
   * This method updates the user preferences based on the provided categories
   * All these settings will be stored under Users table settings field as json format
   * @param  Request $request [description]
   * @param  [type]  $slug    [description]
   * @return [type]           [description]
   */
  public function updateSettings(Request $request, $slug)
  {
        $record = User::where('slug', $slug)->first();

        if($isValid = $this->isValidRecord($record))
         return redirect($isValid);
       /**
        * Validate the non-admin user wether is trying to access other user profile
        * If so return the user back to previous page with message
        */

        if(!isEligible($slug))
          return back();


        /**
         * Make sure the Admin or staff cannot edit the Admin/Owner accounts
         * Only Owner can edit the Admin/Owner profiles
         * Admin can edit his own account, in that case send role type admin on condition
         */

     $UserOwnAccount = FALSE;
     if(\Auth::user()->id == $record->id)
      $UserOwnAccount = TRUE;

      if(!$UserOwnAccount)  {
        $current_user_role = getRoleData($record->role_id);

        if((($current_user_role=='admin' || $current_user_role == 'owner') ))
        {
          if(!checkRole(getUserGrade(1))) {
            prepareBlockUserMessage();
            return back();
          }
        }
      }

    $options = [];
    if($record->settings)
    {
      $options =(array) json_decode($record->settings)->user_preferences;

    }

    $options['quiz_categories'] = [];
    $options['lms_categories']  = [];
    if($request->has('quiz_categories')) {
    foreach($request->quiz_categories as $key => $value)
      $options['quiz_categories'][] = $key;
    }
    if($request->has('lms_categories')) {
      foreach($request->lms_categories as $key => $value)
        $options['lms_categories'][] = $key;
    }

    $record->settings = json_encode(array('user_preferences'=>$options));
    $record->save();

    flash('success','record_updated_successfully', 'success');
     return back();
  }


  public function viewParentDetails($slug)
  {
     if(!checkRole(getUserGrade(4)))
        {
          prepareBlockUserMessage();
          return back();
        }

       $record = User::where('slug', '=', $slug)->first();

       if($isValid = $this->isValidRecord($record))
         return redirect($isValid);

       $data['layout']       = getLayout();
       $data['active_class'] = 'users';
       $data['record']       = $record;

       $data['heading']      = getPhrase('parent_details');
       $data['title']        = getPhrase('parent_details');
       // return view('users.parent-details', $data);

         $view_name = getTheme().'::users.parent-details';
        return view($view_name, $data);
  }

  public function updateParentDetails(Request $request, $slug)
  {

     if(!checkRole(getUserGrade(4)))
        {
          prepareBlockUserMessage();
          return back();
        }


    $user                   = User::where('slug', '=', $slug)->first();
        $role_id = getRoleData('parent');
        $message = '';
        $hasError = 0;

        DB::beginTransaction();
        if($request->account == 0)
        {
            //User is not having an account, create it and send email
            //Update the newly created user ID to the current user parent record
            $parent_user = new User();
            $parent_user->name = $request->parent_name;
            $parent_user->username = $request->parent_user_name;
            $parent_user->role_id = $role_id;
            $parent_user->slug = $parent_user::makeSlug($request->parent_user_name);
            $parent_user->email = $request->parent_email;
            $parent_user->password = bcrypt('password');

        try{
            $parent_user->save();
            $parent_user->roles()->attach($role_id);
            $user->parent_id = $parent_user->id;
            $user->save();

            sendEmail('registration', array('user_name'=>$user->name, 'username'=>$user->username, 'to_email' => $user->email, 'password'=>$parent_user->password));

            DB::commit();
            $message = 'record_updated_successfully';
        }
        catch(Exception $ex){
            DB::rollBack();
            $hasError = 1;
            $message = $ex->getMessage();
        }
    }
        if($request->account == 1)
        {
            try{
             $user->parent_id =  $request->parent_user_id;
             $user->save();
             DB::commit();
            }
            catch(Exception $ex)
            {
                $hasError = 1;
                DB::rollBack();
                $message = $ex->getMessage();
            }
        }
        if(!$hasError)
            flash('success',$message, 'success');
        else
            flash('Ooops',$message, 'error');
        return back();
  }


  public function getParentsOnSearch(Request $request)
  {
        $term = $request->search_text;
        $role_id = getRoleData('parent');
        $records = App\User::
            where('name','LIKE', '%'.$term.'%')
            ->orWhere('username', 'LIKE', '%'.$term.'%')
            ->orWhere('phone', 'LIKE', '%'.$term.'%')
            ->groupBy('id')
            ->havingRaw('role_id='.$role_id)
            ->select(['id','role_id','name', 'username', 'email', 'phone'])
            ->get();
            return json_encode($records);
  }




   /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function SubscribedUsers()
    {
        if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['active_class']       = 'users';
        $data['title']              = getPhrase('subscribed_users');
      // return view('exams.quizcategories.list', $data);

         $view_name = getTheme().'::users.subscribeduser';
        return view($view_name, $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function SubscribersData()
    {

      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

        $records = array();


        $records = App\UserSubscription::select(['email', 'created_at'])
                                     ->orderBy('updated_at', 'desc');


        return Datatables::of($records)
        ->make();

   }

   public function systemReset( Request $request )
    {
        if ( $request->isMethod('post') ) {

            $preserved_tables = [
                'examtypes',
                'languages',
                'migrations',
                'permissions',
                'permission_institute',
                'roles',
                'role_user',
                'settings',
                'themes',
                'users',
                'emailtemplates',
            ];

            DB::statement("SET foreign_key_checks=0");
            $databaseName = DB::getDatabaseName();
            $tables = DB::select("SELECT * FROM information_schema.tables WHERE table_schema = '$databaseName'");

            foreach ($tables as $table) {
                $name = $table->TABLE_NAME;
                //if you don't want to truncate few tables
                if ( ! in_array( $name, $preserved_tables ) ) {
                    DB::table( $name )->truncate();
                }
            }
            DB::statement("SET foreign_key_checks=1");

            $loggedin_user_id = Auth::id();
            $institute_id = Auth::user()->institute_id;

            DB::table('users')->where('id', '!=', $loggedin_user_id)->delete();
            DB::table('role_user')->where('user_id', '!=', $loggedin_user_id)->delete();
            DB::table('permission_institute')->where('institute_id', '!=', $institute_id)->delete();

            flash('success','system_reset_successfully', 'success');
            return redirect()->route('user.dashboard');
        }

        $data['records']      = FALSE;
        $data['layout']       = getLayout();
        $data['active_class'] = 'users';
        $data['heading']      = getPhrase('users');
        $data['title']        = getPhrase('users');

        $view_name = getTheme().'::users.system-reset';
        return view($view_name, $data);
        //return view('users.system-reset');
    }

    public function setPermissions(Request $request, $slug)
    {
        if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record                = User::where('slug', '=', $slug)->first();

        if ( $request->isMethod('post') ) {
          $record->permissions()->sync(array_filter((array)$request->input('permissions')));

          flash('success','permissions_updated_successfully', 'overlay');
          return redirect(URL_USERS);
        }
        $data['record']        = $record;
        $data['active_class']  = 'users';
        $data['title']              = getPhrase('set_permissions');
        $data['layout']     = getLayout();

        $view_name = getTheme().'::users.set-permissions';
        return view($view_name, $data);
    }

    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
     public function loginHistory()
     {
        if( ! canDo('user_management_access') ) {
          prepareBlockUserMessage();
          return back();
        }

        $data['records']      = FALSE;
        $data['layout']       = getLayout();
        $data['active_class'] = 'login_history';
        $data['heading']      = getPhrase('login_history');
        $data['title']        = getPhrase('login_history');
        $view_name = getTheme().'::users.login-history';
        return view($view_name, $data);
     }


    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */

    public function getHistory()
    {
        $records = array();

        $institute_id   = adminInstituteId();

        $records = \App\UserLoginHistory::select(['username', 'ipaddress', 'device_name', 'device_type', 'platform', 'platform_version', 'browser', 'browser_version', 'created_at', 'login_status'])->whereNotNull('ipaddress')->orderBy('id', 'desc');

        return Datatables::of($records)
        ->editColumn('device_name', function($row){
            return $row->device_name . '<p>Type: '.$row->device_type.'</p>';
        })
        ->editColumn('platform', function($row){
            return $row->platform . '<p>Version: '.$row->platform_version.'</p>';
        })
        ->editColumn('browser', function($row){
            return $row->browser . '<p>Version: '.$row->browser_version.'</p>';
        })
        ->editColumn('created_at', function($row){
            return date('d/m/Y h:i:s A', strtotime($row->created_at));
        })
        ->removeColumn('updated_at')
        ->removeColumn('id')
        ->removeColumn('password')
        ->removeColumn('device_type')
        ->removeColumn('platform_version')
        ->removeColumn('browser_version')

        ->removeColumn('user_id')
        ->removeColumn('robot')
        ->removeColumn('languages')
        ->removeColumn('request_headers')
        ->make();
    }

    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
     public function actionsHistory()
     {
        if( ! canDo('user_management_access') ) {
          prepareBlockUserMessage();
          return back();
        }

        $data['records']      = FALSE;
        $data['layout']       = getLayout();
        $data['active_class'] = 'user_actions';
        $data['heading']      = getPhrase('user_actions');
        $data['title']        = getPhrase('user_actions');
        $view_name = getTheme().'::users.user-actions';
        return view($view_name, $data);
     }


    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */

    public function getActionsHistory()
    {
        $records = array();

        $institute_id   = adminInstituteId();

        $records = \App\UserAction::select(['action', 'action_model', 'action_id', 'created_at', 'user_id', 'record_original', 'record_update'])->orderBy('id', 'desc');

        return Datatables::of($records)
        ->editColumn('created_at', function($row){
            return date('d/m/Y h:i:s A', strtotime($row->created_at));
        })
        ->editColumn('user_id', function($row){
            if ( ! $row->user_id ) {
              return '';
            }
            return $row->user->name;
        })
        ->removeColumn('updated_at')
        ->removeColumn('id')
        //->removeColumn('user_id')
        ->removeColumn('record_original')
        ->removeColumn('record_update')
        ->make();
    }

}
