<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Http\Requests;
use App\User;
use App\Institute;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Exception;

class InstituteController extends Controller
{

    public function __construct()
    {
      $this->middleware('auth');
    }


    /**
     * Get The all institutes with status
     * @return [type] [description]
     */
    public function viewInstitutes( $assigned_type = '')
    {

      if(!canDo('institute_access'))
      {
        prepareBlockUserMessage();
        return back();
      }
        $data['active_class']       = 'institutes';
        $data['title']              = getPhrase('institutes');
        $data['type']               = $assigned_type;

        $view_name = getTheme().'::institutes.list';
        return view($view_name, $data);
    }


    public function getDatatable(Request $request)
    {

       if(!canDo('institute_access'))
      {
        prepareBlockUserMessage();
        return back();
      }


      if( !empty( request('type') ) )
      {
        $assigned_type = request('type');

        $records = Institute::select([ 'institutes.id','institutes.user_id','institutes.institute_name','institutes.institute_address','institutes.status'])
                                 ->join('users','users.id','institutes.user_id')
                                 ->where('users.assigned',$assigned_type)
                                 ->where('institutes.parent_id',0)
                                 ->orderBy('institutes.updated_at','desc');
                             }

      else{

        $records = Institute::select([ 'institutes.id','institutes.user_id','institutes.institute_name','institutes.institute_address','institutes.status'])
                                 ->join('users','users.id','institutes.user_id')
                                 // ->where('users.assigned',$assigned_type)
                                 ->where('institutes.parent_id',0)
                                 ->orderBy('institutes.updated_at','desc');
                             }

         // $records = Institute::select([ 'id','user_id','institute_name','institute_address','status'])
                                 // ->where('parent_id',0)
                                 // ->orderBy('updated_at','desc');

            // dd( $records->get() );

        return Datatables::of($records)
        ->addColumn('action', function ($records) {


            $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="'.URL_INSTITUTE_DETAILS.$records->id.'"><i class="fa fa-eye"></i>'.getPhrase("view").'</a></li>
                            <li><a href="'.URL_INSTITUTE_SET_PERMISSION.$records->id.'"><i class="fa fa-eye"></i>'.getPhrase("set permissions").'</a></li>
                            <li><a href="'.URL_EDIT_INSTITUTE_DETAILS.$records->id.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
                            $temp = '';
                            // if(checkRole(getUserGrade(1)) && $records->status != 1 ) {

                            //     $temp .= ' <li><a href="javascript:void(0);" onclick="approveInstitute(\''.$records->id.'\');"><i class="fa fa-check"></i>'.getPhrase("approve").'</a></li>';
                            // }
                            // if(checkRole(getUserGrade(1)) && $records->status == 1 ) {

                            //     $temp .= ' <li><a href="'.URL_VIEW_ASSOSIATES_LIST.$records->id.'"><i class="fa fa-exchange"></i>'.getPhrase("make_associate").'</a></li>';
                            // }

                            $temp .='</ul> </div>';
                            $link_data .= $temp;
                    return $link_data;
            })

        ->editColumn('user_id',function($records){
          // dd($records->user_id);
            $user  = getUserRecord($records->user_id);
            // echo $user->name;
            return ucwords($user->name);
        })

        ->editColumn('institute_name',function($records){

            $record  = getUserRecord($records->user_id);
            $str = '<a href = "'.URL_INSTITUTE_DETAILS.$records->id.'">'.$record->instituteDetails('name').'</a><p>Login: '.$record->email.'</p>';

            $users = \App\User::where('institute_id', $records->id)->count();
            $students = \App\User::where('institute_id', $records->id)->where('role_id', STUDENT_ROLE_ID)->count();
            $staff = \App\User::where('institute_id', $records->id)->where('role_id', FACULTY_ROLE_ID)->count();
            $str .= '<p style="color:green;">Students: '.$students.'</p>';
            $str .= '<p style="color:green;">Staff: '.$staff.'</p>';

            $emails_sent = \DB::table('email_usage_history')->where('institute_id', $records->id)->count();
            $sms_sent = \DB::table('sms_usage_history')->where('institute_id', $records->id)->count();
            $str .= '<p style="color:green;">Emails Used: '.$emails_sent.'</p>';
            $str .= '<p style="color:green;">SMS Used: '.$sms_sent.'</p>';

            return $str;


        })

        ->editColumn('institute_address',function($records){

            $record  = getUserRecord($records->user_id);
            return '<p>'.$record->instituteDetails('address').'</p>';

        })
         ->editColumn('status',function($records){

           if($records->status  == 0){

              return getPhrase('blocked');
           }
           elseif($records->status == 1){
              return getPhrase('approved');
           }
           elseif ($records->status  == APPROVE) {

               return getPhrase('approved');
           }

           elseif( $records->status  == REJECT ) {

               return getPhrase('rejected');
           }

           elseif ( $records->status  == BLOCK ) {

               return getPhrase('blocked');
           }

        })

        ->removeColumn('id')
        ->rawColumns(['institute_name', 'institute_address','action'])
        ->make();

    }

    /**
     * View the institute details
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function details($id)
    {
       if(!canDo('institute_view'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $record                     = Institute::find($id);
        $user                       = getUserRecord($record->user_id);
        $institute_name             = $user->instituteDetails('name');
        $data['active_class']       = 'institutes';
        $data['title']              = $institute_name;
        $data['record']             = $record;
        $data['user']               = $user;
        $is_superadmin              = FALSE;
        $data['layout']             = getLayout();

        if(checkRole(getUserGrade(1))){

          if(Auth::user()->institute_id == $record->id)

            $is_superadmin = TRUE;
        }
        $data['is_superadmin']  = $is_superadmin;

        $view_name = getTheme().'::institutes.details';
        return view($view_name, $data);

    }


    public function updateInstitute(Request $request)
    {
      // dd("in updateInstitute method");

        if(!canDo('institute_edit'))
        {
          prepareBlockUserMessage();
          return back();
        }
// dd($request->all());
        $record             = Institute::find($request->institute_id);
        $user               = getUserRecord($record->user_id);

        $status             = $request->status;
        $record->comments   = $request->comments;
        $record->updated_by = Auth::user()->id;
        $user->package_id  = $request->package_id;
        $user->is_verified  = 1;

        if ( $request->valid_until ) {
          $user->valid_until = date('Y-m-d', strtotime($request->valid_until));
          $record->valid_until = date('Y-m-d', strtotime($request->valid_until));
        }

         $status_message  = '';

        if($status == 'approve'){
          $user->login_enabled = 1;
          $record->status  = APPROVE;
        }
        elseif($status == 'reject'){
          $record->status       = REJECT;
          $user->login_enabled  = 0;
        }
        elseif($status == 'block'){
          $record->status       = BLOCK;
          $user->login_enabled  = 0;
           $status_message  = 'Blocked';
        }

        elseif($status == 'unblock'){
          $record->status      = APPROVE;
          $user->login_enabled = 1;
          $status_message  = 'Unblocked';
        }

        $user->save();
        $record->save();

        if ( checkRole(getUserGrade(1)) && $request->package_id) { // Student.
          $permissions = Package::join('packages_permissions as pp', 'packages.id', '=', 'pp.packages_permissions')->where('packages.id', $request->package_id)->get()->pluck('permission_id')->toArray();
          $record->permissions()->sync($permissions);
        }

        try
        {
            $user->notify( new \App\Notifications\InstituteStatus($user, $status_message, $request->institute_name ,$request->comments));
        }
        catch(Exception $ex)
        {
          // dd($ex->getMessage());
        }

        flash('success','institute_is '.getPhrase($status).' successfully','success');
        return redirect( URL_VIEW_INSTITUES );



    }


     public function instituteRegistration()
    {

         if(!checkRole(getUserGrade(3)))
          {
            prepareBlockUserMessage();
            return back();
          }

        $data['active_class']       = 'institutes';
        $data['title']              = getPhrase('institute_register');
        $data['record']             =  FALSE;
        $data['ins_name']           = '';
        $data['ins_address']           = '';
/*
        $user = Auth::user();
        $password = '123';
        $user->notify( new \App\Notifications\InstituteRegistration($user,'adiyya@conquerorstech.net',$password,'Adi' ));
dd( $user );
*/
        $view_name = getTheme().'::institutes.add-edit-registration';
        return view($view_name, $data);
    }


      /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function addInstitute(Request $request)
     {
        $columns = array(
                    'name'              => 'required|max:20|',
                    'institute_name'    => 'required|max:200|',
                    'institute_address' => 'required',
                    'phone'             => 'required',
                    'address'           => 'required',
                    'username'          => 'required|unique:users,username',
                    'email'             => 'required|unique:users,email',
                    'password'          => 'required|min:5',
                    'password_confirmation'=>'required|min:5|same:password',
                    // 'valid_until'       => 'required',
                    'package_id'       => 'required',
                    );

          $this->validate($request,$columns);

          DB::beginTransaction();

          try {

            $user                 = new User();
            $name                 = $request->name;
            $user->name           = $name;
            $user->username       = $request->username;
            $user->email          = $request->email;
            $password             = $request->password;
            $user->password       = bcrypt($password);
            $user->role_id        = 7;
            $slug                 = $user::makeSlug($name);
            $user->slug           = $slug;
            $user->login_enabled  = 1;
            $user->is_verified  = 1;
            $user->phone          = $request->phone;
            $user->address        = $request->address;
            $user->package_id  = $request->package_id;

            if ( $request->valid_until ) {
              $user->valid_until    = date('Y-m-d', strtotime($request->valid_until));
            } else {
              $package = \App\Package::find( $request->package_id );
              if ( $package ) {
                $duration = $package->duration;
                $duration_type = $package->duration_type;
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
              }
            }



            $user->save();

            $user->roles()->attach($user->role_id);

            $valid_until = $request->valid_until;

            Institute::addRecord($request->institute_name, $request->institute_address,$user, $valid_until);

            try
            {

                 $user->notify( new \App\Notifications\InstituteRegistration($user,$user->email,$password,$request->institute_name ));

            }
            catch(Exception $ex)
            {

            }


           DB::commit();


            flash('success','institute_added_successfully', 'overlay');


        }

         catch (Exception $e ) {

          DB::rollBack();
            //dd($e->getMessage());
          flash('Oops...!', $e->getMessage(), 'error');
        }

           return redirect(URL_VIEW_INSTITUES);

     }


     public function edit($id)
     {

          if(!canDo('institute_edit'))
          {
            prepareBlockUserMessage();
            return back();
          }

        $institute = Institute::find($id);
        if($isValid = $this->isValidRecord($institute))
          return redirect($isValid);

        $record                = User::find($institute->user_id);
        $record->valid_until = $institute->valid_until;
        $data['record']        = $record;
        // dd($record);
        $data['active_class']  = 'institutes';
        $data['title']         = getPhrase('edit_institute');
        $data['ins_name']      = $institute->institute_name;
        $data['ins_address']   = $institute->institute_address;
        $data['valid_until']   = $institute->valid_until;

        $view_name = getTheme().'::institutes.add-edit-registration';
        return view($view_name, $data);

     }



    public function update(Request $request, $id)
    {
         // dd("in update method");
        if(!canDo('institute_edit'))
        {
          prepareBlockUserMessage();
          return back();
        }


          $institute            = Institute::find($id);
          // dd($institute);
          $user                 = User::find($institute->user_id);

             $columns = array(

                    'name'              => 'bail|required|max:20|',
                    'institute_name'    => 'bail|required|max:200|',
                    'institute_address' => 'bail|required',
                    'phone'             => 'bail|required',
                    'address'           => 'bail|required',
                    'username'          => 'bail|required|unique:users,username, '.$user->id,
                    'email'             => 'bail|required|unique:users,email,'.$user->id,
                    'valid_until'       => 'required',
                    );

          $this->validate($request,$columns);

          DB::beginTransaction();

          try {
                $name                 = $request->name;
                $use_email            = $request->email;

                if($name != $user->name){

                $slug                 = $user::makeSlug($name);
                $user->slug           = $slug;
                $user->name           = $name;

                }

                if($use_email != $user->email){

                  $password             = "password";
                  $user->password       = bcrypt($password);
                }

                $user->username       = $request->username;
                $user->phone          = $request->phone;
                $user->address        = $request->address;
                $user->email          = $request->email;
                $user->is_verified        = 1;
                $user->login_enabled  = 1;
                if( ! empty( $request->valid_until ) ) {
                  $user->valid_until    = date('Y-m-d', strtotime($request->valid_until));
                }
                if( $user->assigned == 'registered')
                {

                  // dd($user);
                $user->notify( new \App\Notifications\InstituteAccountActive($user,  'Approved', $request->institute_name, '' ));
              }

              $user->assigned = 'yes';
                $user->save();



                $institute->institute_name     = $request->institute_name;
                $institute->institute_address  = $request->institute_address;
                $institute->phone              = $request->phone;
                if( ! empty( $request->valid_until ) ) {
                  $institute->valid_until    = date('Y-m-d', strtotime($request->valid_until));
                }

                $status             = $request->status;
                $status_message  = '';
                $comments = '';
                if($status == '1'){
                  $institute->status  = 1;
                  $user->login_enabled  = 1;
                  $user->is_verified  = 1;
                  $status_message = 'APPROVED';
                } else {
                  $institute->status       = 0;
                  $user->login_enabled  = 0;
                  $status_message = 'BLOCKED';
                }
                $institute->save();

            if( !empty( $use_email ) )
            {

                 try
            {
                $user->notify( new \App\Notifications\InstituteStatus($user, $status_message, $request->institute_name ,$comments));
            }
            catch(Exception $ex)
            {
              // dd($ex->getMessage());
            }
          }


           DB::commit();

            flash('success','institute_updated_successfully', 'overlay');


        }

         catch (Exception $e ) {

          DB::rollBack();
            // dd( $e->getMessage() );
          flash('Oops...!','Error! Please Try again', 'error');
        }
           return redirect(URL_VIEW_INSTITUES);
    }

    public function setPermissions(Request $request, $id)
    {
        if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record                = Institute::where('id', '=', $id)->first();

        if ( $request->isMethod('PATCH') ) {
          $record->permissions()->sync(array_filter((array)$request->input('permissions')));

          flash('success','institute_updated_successfully', 'overlay');
          return redirect(URL_VIEW_INSTITUES);
        }
        $data['record']        = $record;
        $data['active_class']  = 'institutes';
        $data['ins_name']      = $record->institute_name;
        $data['ins_address']   = $record->institute_address;
        $data['active_class']       = 'institutes';
        $data['title']              = getPhrase('set_permissions');
        $data['layout']     = getLayout();

        $view_name = getTheme().'::institutes.set-permissions';
        return view($view_name, $data);
    }


      public function isValidRecord($record)
    {

       if ($record === null) {

          flash('Ooops...!', getPhrase("page_not_found"), 'error');
          return $this->getRedirectUrl();
       }

       return FALSE;
    }
    




}