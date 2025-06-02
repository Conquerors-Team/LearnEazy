<?php
use \Illuminate\Http\JsonResponse;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
/**
 * Custom  error message display.
 *
 * @param array $messages
 * @return \Illuminate\Validation\ValidationException
 */
function setAlert($messages) {
  $response = new JsonResponse([
              'message' => 'The given data is invalid',
              'errors' => $messages
           ], 422);
  throw new \Illuminate\Validation\ValidationException(Validator(), $response);
}
/**
 * Flash Helper
 * @param  string|null  $title
 * @param  string|null  $text
 * @return void
 */


function flash($title = null, $text = null, $type='info')
{
    $flash = app('App\Http\Flash');

    if (func_num_args() == 0) {
        return $flash;
    }
    return $flash->$type($title, $text);
}

function canDo( $permission, $user_id = '' ) {
  $user = '';
  if ( \Auth::check() ) {
    $user = Auth::user();
  }
  if ( ! empty( $user_id ) ) {
    $user_details = \App\User::find( $user_id );
    if ( $user_details ) {
      $user = $user_details;
    }
  }

  if ( $user ) {
    if ( in_array($user->role_id, [OWNER_ROLE_ID, ADMIN_ROLE_ID]) ) {
      return true;
    } elseif( in_array($user->role_id, [INSTITUTE_ROLE_ID, FACULTY_ROLE_ID, STUDENT_ROLE_ID]) ) {
      $permissions = \App\Institute::select(['permissions.*'])->join('permission_institute as pi', 'pi.institute_id', '=', 'institutes.id')
      ->join('permissions', 'permissions.id', '=', 'pi.permission_id')
      ->where( 'institutes.id', $user->institute_id )->get()->pluck('title')->toArray();
      //echo $permission;
      //dd($permissions);

      if ( in_array($permission, $permissions) ) {
        // $user = Auth::user();
        if ( $user->role_id != 1 ) {
          if ( $user->institute_id == MASTER_INSTITUTE_ID ) {
            return true;
          }
          $validity_days_left = getValidityDaysLeftAttribute( $user->id );
          if ( $validity_days_left <= 0 ) {
             return false;
          }
        }
        return true;
      } else {
        return false;
      }
    } else { // Student
      $permissions = \App\User::select(['permissions.*'])->join('permission_users as pu', 'pu.user_id', '=', 'users.id')
      ->join('permissions', 'permissions.id', '=', 'pu.permission_id')
      ->where( 'users.id', $user->id )->get()->pluck('title')->toArray();
      if ( in_array($permission, $permissions) ) {
        return true;
      } else {
        return false;
      }
    }
  }
}
/**
 * Language Helper
 * @param  string|null  $phrase
 * @return string
 */
function getPhrase($key = null)
{
    $phrase = app('App\Language');

    if (func_num_args() == 0) {
        return '';
    }

    return  $phrase::getPhrase($key);
}

/**
 * This method fetches the specified key in the type of setting
 * @param  [type] $key          [description]
 * @param  [type] $setting_type [description]
 * @return [type]               [description]
 */
function getSetting($key, $setting_type)
{
    return App\Settings::getSetting($key, $setting_type);
}

/**
 * This method fetches the specified key in the type of setting
 * @param  [type] $key          [description]
 * @param  [type] $setting_type [description]
 * @return [type]               [description]
 */
function getThemeSetting($key, $setting_type)
{
    return App\SiteTheme::getSetting($key, $setting_type);
}

/**
 * Language Helper
 * @param  string|null  $phrase
 * @return string
 */
function isActive($active_class = '', $value = '')
{
    $value = isset($active_class) ? ($active_class == $value) ? 'active' : '' : '';
    if($value)
        return "class = ".$value;
    return $value;
}

/**
 * This method returns the path of the user image based on the type
 * It verifies wether the image is exists or not,
 * if not available it returns the default image based on type
 * @param  string $image [Image name present in DB]
 * @param  string $type  [Type of the image, the type may be thumb or profile,
 *                       by default it is thumb]
 * @return [string]      [returns the full qualified path of the image]
 */
function getProfilePath($image = '', $type = 'thumb')
{
    $obj = app('App\ImageSettings');
    $path = '';

    if($image=='') {
        if($type=='profile')
            return PREFIX.$obj->getDefaultProfilePicPath();
        return PREFIX.$obj->getDefaultprofilePicsThumbnailpath();
    }


    if($type == 'profile')
        $path = $obj->getProfilePicsPath();
    else
        $path = $obj->getProfilePicsThumbnailpath();
    $imageFile = $path.$image;

    if (File::exists($imageFile)) {
        return PREFIX.$imageFile;
    }

    if($type=='profile')
        return PREFIX.$obj->getDefaultProfilePicPath();
    return PREFIX.$obj->getDefaultprofilePicsThumbnailpath();

}

/**
 * This method returns the standard date format set by admin
 * @return [string] [description]
 */
function getDateFormat()
{
    $obj = app('App\GeneralSettings');
    return $obj->getDateFormat();
}


function getBloodGroups()
{
    return array(
            'A +ve'    => 'A +ve',
            'A -ve'    => 'A -ve',
            'B +ve'    => 'B +ve',
            'B -ve'    => 'B -ve',
            'O +ve'    => 'O +ve',
            'O -ve'    => 'O -ve',
            'AB +ve'   => 'AB +ve',
            'AB -ve'   => 'AB -ve',
        );
}

function getAge($date)
{


    // return Carbon::createFromDate(1984, 7, 17)->diff(Carbon::now())->format('%y years, %m months and %d days');
}

function getLibrarySettings()
{
    return json_decode((new App\LibrarySettings())->getSettings());
}

function getExamSettings()
{
    return json_decode((new App\ExamSettings())->getSettings());
}

/**
 * This method is used to generate the formatted number based
 * on requirement with the follwoing formatting options
 * @param  [type]  $sno    [description]
 * @param  integer $length [description]
 * @param  string  $token  [description]
 * @param  string  $type   [description]
 * @return [type]          [description]
 */
function makeNumber($sno, $length=2, $token = '0',$type='left')
{
    if($type=='right')
        return str_pad($sno, $length, $token, STR_PAD_RIGHT);

    return str_pad($sno, $length, $token, STR_PAD_LEFT);

}

/**
 * This method returns the settings for the selected key
 * @param  string $type [description]
 * @return [type]       [description]
 */
function getSettings($type='')
{
    if($type=='lms')
        return json_decode((new App\LmsSettings())->getSettings());

    if($type=='paidcontent')
        return json_decode((new App\PaidcontentSettings())->getSettings());

    if($type=='lms')
        return json_decode((new App\LmsSettings())->getSettings());

    if($type=='subscription')
        return json_decode((new App\SubscriptionSettings())->getSettings());

    if($type=='general')
        return json_decode((new App\GeneralSettings())->getSettings());

    if($type=='email'){
        $dta = json_decode((new App\EmailSettings())->getSettings());
        return $dta;
      }

   if($type=='attendance')
        return json_decode((new App\AttendanceSettings())->getSettings());

}

/**
 * This method returns the role of the currently logged in user
 * @return [type] [description]
 */
 function getRole($user_id = 0)
 {
     if($user_id)
        return getUserRecord($user_id)->roles()->first()->name;
     return Auth::user()->roles()->first()->name;
 }

/**
 * This is a common method to send emails based on the requirement
 * The template is the key for template which is available in db
 * The data part contains the key=>value pairs
 * That would be replaced in the extracted content from db
 * @param  [type] $template [description]
 * @param  [type] $data     [description]
 * @return [type]           [description]
 */
 function sendEmail($template, $data)
 {
    return (new App\EmailTemplate())->sendEmail($template, $data);
 }

/**
 * This method returns the formatted by appending the 0's
 * @param  [type] $number [description]
 * @return [type]         [description]
 */
 function formatPercentage($number)
 {
     return sprintf('%.2f',$number).' %';
 }


/**
 * This method returns the user based on the sent userId,
 * If no userId is passed returns the current logged in user
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
 function getUserRecord($user_id = 0)
 {
    if($user_id)
     return (new App\User())->where('id','=',$user_id)->first();
    return Auth::user();
 }

/**
 * Returns the user record with the matching slug.
 * If slug is empty, it will return the currently logged in user
 * @param  string $slug [description]
 * @return [type]       [description]
 */
function getUserWithSlug($slug='')
{
    if($slug)
     return App\User::where('slug', $slug)->get()->first();
    return Auth::user();
}

/**
 * This method identifies if the url contains the specific string
 * @param  [type] $str [description]
 * @return [type]      [description]
 */
 function urlHasString($str)
 {
    $url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
     if (strpos($url, $str))
        return TRUE;
    return FALSE;

 }

//  function checkRole($roles, $permission = '')
//  {
//      if(Entrust::hasRole($roles)) {
//         if ( ! empty( $permission ) ) {
//           $user = Auth::user();

//           if ( $user->role_id != 1) {
//             if ( isFaculty() ) {
//               $permissions = Auth::user()->permissions->pluck('title')->toArray();
//             } else {
//               $permissions = Auth::user()->institute->permissions->pluck('title')->toArray();
//             }
//             if ( empty( $permissions ) ) {
//                 return FALSE;
//             } elseif ( in_array($permission, $permissions ) ) {
//                 if ( $user->validity_days_left <= 0 ) {
//                   return FALSE;
//                 }
//                 return TRUE;
//             } else {
//                 return FALSE;
//             }
//           }
//         }

//         return TRUE;
//      }

//     return FALSE;
//  }


function checkRole($roles, $permission = '')
{
    $user = Auth::user();

    // Check if user has one of the roles
    if ($user->hasAnyRole((array)$roles)) {

        // If a specific permission is also required
        if (!empty($permission)) {

            // Skip permission check if user is super admin (role_id == 1)
            if ($user->role_id != 1) {

                // Retrieve permissions based on user type
                if (isFaculty()) {
                    $permissions = $user->permissions->pluck('name')->toArray();
                } else {
                    $permissions = $user->institute?->permissions?->pluck('name')->toArray() ?? [];
                }

                if (empty($permissions)) {
                    return false;
                }

                // Check if permission exists and user has valid subscription
                if (in_array($permission, $permissions)) {
                    if ($user->validity_days_left <= 0) {
                        return false;
                    }
                    return true;
                } else {
                    return false;
                }
            }
        }

        // If only role check was needed
        return true;
    }

    return false;
}

 function getUserGrade($grade = 5)
 {
     switch ($grade) {
         case 1:
             return ['owner'];
             break;
        case 2:
             return ['owner', 'admin','institute', 'faculty'];
             break;
        case 3:
             return ['owner', 'admin'];
             break;
        case 4:
             return ['owner', 'admin', 'parent','institute','institute_admin'];
             break;
        case 5:
             return ['student'];
             break;
        case 6:
             return ['admin'];
             break;
        case 7:
             return ['parent'];
             break;
        case 8:
             return ['institute'];
             break;
        case 9:
             return ['institute','institute_admin','owner'];
             break;
        case 10:
             return ['faculty'];
             break;
     }
 }
 /**
  * Returns the appropriate layout based on the user logged in
  * @return [type] [description]
  */
 function getLayout()
 {
    //dd((checkRole(getUserGrade(8))));
    $layout = 'layouts.defaultlayout';
    if(checkRole(getUserGrade(2)))
      $layout             = 'layouts.admin.adminlayout';
    if(checkRole(['institute']))
      $layout             = 'layouts.institute.institutelayout';
    if(checkRole(['parent']))
      $layout             = 'layouts.parent.parentlayout';
    if(checkRole(['faculty']))
      $layout             = 'layouts.faculty.facultylayout';
    if(checkRole(['student']))
      $layout             = 'layouts.student.studentlayout';

    /*
    $user = Auth::user();
    if ( $user->role_id != 1 ) {
      if ( $user->validity_days_left <= 0 ) {
         $layout = 'layouts.defaultlayout';
      }
    }
    */

    return $layout;
 }

 function validateUser($slug)
 {
    if($slug == Auth::user()->slug)
        return TRUE;
    return FALSE;
 }

 /**
  * Common method to send user restriction message for invalid attempt
  * @return [type] [description]
  */
 function prepareBlockUserMessage()
 {
    $user = Auth::user();
    if ( $user->validity_days_left <= 0 ) {
      flash('Ooops..!', 'Your validity expires. Please contact administrator.', 'info');
    } else {
      flash('Ooops..!', 'you_have_no_permission_to_access', 'error');
    }
    return '';
 }

 /**
  * Common method to send user restriction message for invalid attempt
  * @return [type] [description]
  */
 function pageNotFound()
 {
    flash('Ooops..!', 'page_not_found', 'error');
     return '';
 }


 function isEligible($slug)
 {
     if(!checkRole(getUserGrade(2)))
     {
        if(!validateUser($slug))
        {
            if(!checkRole(['parent']) || !isActualParent($slug))
            {
               prepareBlockUserMessage();
               return FALSE;
            }
        }
     }
     return TRUE;
 }

 /**
  * This method checks wether the student belongs to the currently loggedin parent or not
  * And returns the boolean value
  * @param  [type]  $slug [description]
  * @return boolean       [description]
  */
 function isActualParent($slug)
 {
     return (new App\User())
              ->isChildBelongsToThisParent(
                                    getUserWithSlug($slug)->id,
                                    Auth::user()->id
                                    );

 }

/**
 * This method returns the role name or role ID based on the type of parameter passed
 * It returns ID if role name is supplied
 * It returns Name if ID is passed
 * @param  [type] $type [description]
 * @return [type]       [description]
 */
 function getRoleData($type)
 {

     if(is_numeric($type))
     {
        /**
         * Return the Role Name as the type is numeric
         */
        return App\Role::where('id','=',$type)->first()->name;

     }

     //Return Role Id as the type is role name
     return App\Role::where('name','=',$type)->first()->id;

 }

 /**
  * Checks the subscription details and returns the boolean value
  * @param  string  $type [this is the of package]
  * @return boolean       [description]
  */
 function isSubscribed($type = 'main',$user_slug='')
 {
    $user = getUserWithSlug();
    if($user_slug)
        $user = getUserWithSlug($user_slug);

    if($user->subscribed($type))
      return TRUE;
    return FALSE;
 }

 /**
  * Checks the subscription details and returns the boolean value
  * @param  string  $type [this is the of package]
  * @return boolean       [description]
  */
 function isFaculty()
 {
    if ( Auth::user()->role_id == FACULTY_ROLE_ID ) {
      return TRUE;
    }
    return FALSE;
 }

 function isInstitute()
 {
    if ( Auth::user()->role_id == INSTITUTE_ROLE_ID ) {
      return TRUE;
    }
    return FALSE;
 }

 function isStudent()
 {
    if ( Auth::user()->role_id == STUDENT_ROLE_ID ) {
      return TRUE;
    }
    return FALSE;
 }

 function isAdmin()
 {
    if ( in_array( \Auth::user()->role_id, [OWNER_ROLE_ID, ADMIN_ROLE_ID] ) ) {
      return TRUE;
    }
    return FALSE;
 }

/**
 * This method will send the random color to use in graph
 * The random color generation is based on the number parameter
 * As the border and bgcolor need to be same,
 * We are maintainig number parameter to send the same value for bgcolor and background color
 * @param  string  $type   [description]
 * @param  integer $number [description]
 * @return [type]          [description]
 */
 function getColor($type = 'background',$number = 777) {

    $hash = md5('color'.$number); // modify 'color' to get a different palette
    $color = array(
        hexdec(substr($hash, 0, 2)), // r
        hexdec(substr($hash, 2, 2)), // g
        hexdec(substr($hash, 4, 2))); //b
    if($type=='border')
    return 'rgba('.$color[0].','.$color[1].','.$color[2].',1)';
    return 'rgba('.$color[0].','.$color[1].','.$color[2].',0.2)';
}


// function pushNotification($channels = ['owner','admin'], $event = 'newUser',  $options)
// {

//      $pusher = \Illuminate\Support\Facades\App::make('pusher');

//          $pusher->trigger( $channels,
//                       $event,
//                       $options
//                      );



// }

function pushNotification($channels = ['owner','admin'], $event = 'newUser', $options = [])
{
    $pusher = app('pusher');

    $pusher->trigger(
        $channels,
        $event,
        $options
    );
}


/**
 * This method is used to return the default validation messages
 * @param  string $key [description]
 * @return [type]      [description]
 */
function getValidationMessage($key='required')
{
    $message = '<p ng-message="required">'.getPhrase('this_field_is_required').'</p>';

    if($key === 'required')
        return $message;

        switch($key)
        {
          case 'minlength' : $message = '<p ng-message="minlength">'
                                        .getPhrase('the_text_is_too_short')
                                        .'</p>';
                                        break;
          case 'maxlength' : $message = '<p ng-message="maxlength">'
                                        .getPhrase('the_text_is_too_long')
                                        .'</p>';
                                        break;
          case 'pattern' : $message   = '<p ng-message="pattern">'
                                        .getPhrase('invalid_input')
                                        .'</p>';
                                        break;
            case 'image' : $message   = '<p ng-message="validImage">'
                                        .getPhrase('please_upload_valid_image_type')
                                        .'</p>';
                                        break;
          case 'email' : $message   = '<p ng-message="email">'
                                        .getPhrase('please_enter_valid_email')
                                        .'</p>';
                                        break;

          case 'number' : $message   = '<p ng-message="number">'
                                        .getPhrase('please_enter_valid_number')
                                        .'</p>';
                                        break;

          case 'confirmPassword' : $message   = '<p ng-message="compareTo">'
                                        .getPhrase('password_and_confirm_password_does_not_match')
                                        .'</p>';
                                        break;
           case 'password' : $message   = '<p ng-message="minlength">'
                                        .getPhrase('the_password_is_too_short')
                                        .'</p>';
                                        break;
           case 'phone' : $message   = '<p ng-message="minlength">'
                                        .getPhrase('please_enter_valid_phone_number')
                                        .'</p>';
                                        break;
        }
    return $message;
}

/**
 * Returns the predefined Regular Expressions for validation purpose
 * @param  string $key [description]
 * @return [type]      [description]
 */
function getRegexPattern($key='name')
{
    $phone_regx = getSetting('phone_number_expression', 'site_settings');
    $pattern = array(
                    'name' => '/(^[A-Za-z0-9@~`!@#$%^&*()_=+\';:\"?>.<,-. ]+$)+/',
                    'email' => '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',
                    'phone'=>$phone_regx
                    );
    return $pattern[$key];
}

function getPhoneNumberLength()
{
  return getSetting('site_favicon', 'site_settings');
}


function getArrayFromJson($jsonData)
{
    $result = array();
    if($jsonData)
    {
        foreach(json_decode($jsonData) as $key=>$value)
            $result[$key] = $value;
    }
    return $result;
}


function prepareArrayFromString($string='', $delimeter = '|')
{

    return explode($delimeter, $string);
}

/**
 * Returns the random hash unique code
 * @return [type] [description]
 */
function getHashCode()
{
  return bin2hex(openssl_random_pseudo_bytes(20));
}

/**
 * Sends the default Currency set for the project
 * @return [type] [description]
 */
function getCurrencyCode()
{
  //return getSetting('currency_code','site_settings') ;
  return 'Rs.';
}

/**
 * Returns the max records per page
 * @return [type] [description]
 */
function getRecordsPerPage()
{
  return RECORDS_PER_PAGE;
}

/**
 * Checks wether the user is eligible to use the current item
 * @param  [type]  $item_id   [description]
 * @param  [type]  $item_type [description]
 * @return boolean            [description]
 */
function isItemPurchased($item_id, $item_type, $user_id = '')
{
  return App\Payment::isItemPurchased($item_id, $item_type, $user_id);
}

function isOnlineclassSubscribed($user_id = '')
{
  return App\Payment::isOnlineclassSubscribed($user_id);
}

function humanizeDate($target_date)
{
   $created = new \Carbon\Carbon($target_date);
   $now = \Carbon\Carbon::now();
   $difference = ($created->diff($now)->days < 1) ? getPhrase('today')
                                : $created->diffForHumans($now);
    return $difference;
}


function getTimeFromSeconds($seconds)
{
    return gmdate("H:i:s",$seconds);
}

function getRazorKey()
{
return env('RAZORPAY_APIKEY', 'rzp_test_A7YYdxPOae6Dpn');
}

function getRazorSecret()
{
return env('RAZORPAY_SECRET','j1ikm980d6Lxs4ZNceOv44Sz');
}

function getTheme()
{

  $theme_name  = 'default';

  $current_theme  = App\SiteTheme::where('is_active',1)->first();

  if($current_theme){
    $theme_name = $current_theme->theme_title_key;
  }

  Theme::set($theme_name);
  return Theme::current();
}

function getDefaultTheme()
{
    $current_theme  = App\SiteTheme::where('is_active',1)->first();

    if($current_theme){

       $theme_name = $current_theme->theme_title_key;
       return $theme_name;
    }
    return FALSE;
}


function getThemeColor(){

  $current_theme  = App\SiteTheme::where('is_active',1)->first();

  return $current_theme->theme_color;

}

function getLangugesOptions(){

  $languages_data               = array();
  $languages_data['Afrikanns']  = 'Afrikanns';
  $languages_data['Albanian']   = 'Albanian';
  $languages_data['Arabic']     = 'Arabic';
  $languages_data['Armenian']   = 'Armenian';
  $languages_data['Basque']     = 'Basque';
  $languages_data['Bengali']    = 'Bengali';
  $languages_data['Bulgarian']  = 'Bulgarian';
  $languages_data['Catalan']    = 'Catalan';
  $languages_data['Cambodian']  = 'Cambodian';
  $languages_data['Chinese']    = 'Chinese';
  $languages_data['Croation']   = 'Croation';
  $languages_data['Czech']      = 'Czech';
  $languages_data['Danish']     = 'Danish';
  $languages_data['Dutch']      = 'Dutch';
  $languages_data['Estonian']   = 'Estonian';
  $languages_data['French']     = 'French';
  $languages_data['German']     = 'German';
  $languages_data['Greek']      = 'Greek';
  $languages_data['Gujarati']   = 'Gujarati';
  $languages_data['Hebrew']     = 'Hebrew';
  $languages_data['Hindi']      = 'Hindi';
  $languages_data['Hungarian']  = 'Hungarian';
  $languages_data['Italian']    = 'Italian';
  $languages_data['Japanese']   = 'Japanese';
  $languages_data['Malayalam']  = 'Malayalam';
  $languages_data['Marathi']    = 'Marathi';
  $languages_data['Nepali']     = 'Nepali';
  $languages_data['Romanian']   = 'Romanian';
  $languages_data['Russian']    = 'Russian';
  $languages_data['Spanish']    = 'Spanish';
  $languages_data['Tamil']      = 'Tamil';
  $languages_data['Telugu']     = 'Telugu';
  $languages_data['Turkish']    = 'Turkish';
  $languages_data['Urdu']       = 'Urdu';
  $languages_data['Vietnamese'] = 'Vietnamese';

    return $languages_data;
}


 function adminInstituteId($user_id = 0)
 {

      if($user_id){
        //  $user  =   App\User()->where('id','=',$user_id)->first();
        $user = App\User::where('id', $user_id)->first();


      }else{

         $user =  Auth::user();
      }

       /*
       $record  = App\User::join('institutes','institutes.user_id','=','users.id')
                            ->where('institutes.user_id',$user->id)
                            ->where('institutes.parent_id',0)
                            ->select(['institutes.id as inst_id'])
                            ->first();

         return $record->inst_id;
         */
         return $user->institute_id;


 }


 function getInstitute($id){

     return App\Institute::find($id);
 }

 function branches($institute_id = 0)
 {
    $records  = App\Institute::where('parent_id',$institute_id)
                               ->pluck('institute_name','id')
                               ->toArray();
    return $records;
 }


function shareData( $key = '' ) {
    $data  = getSetting($key, 'category_settings');
    $permissions = Auth::user()->institute->permissions->pluck('title')->toArray();

    if ( empty( $permissions ) ) {
      return  FALSE;
    }
    // elseif(isFaculty()) {
    //    return  FALSE;
    // }
    else {
      $share_permissions = ['share_questions', 'share_lms_contents', 'share_lms_series', 'share_lms_notes', 'share_classes', 'share_subjects', 'share_chapters', 'share_topics', 'share_exams', 'share_exam_series'];
      if ( ! empty( $key ) ) {
        if ( in_array($key, $permissions ) ) {
          return TRUE;
        } else {
          return FALSE;
        }
      } else {
        $total = count( $share_permissions );
        $given_permissions = 0;
        for( $i = 0; $i < count($share_permissions); $i++ ) {
            if ( in_array($share_permissions[ $i ], $permissions)) {
              $given_permissions++;
            }
        }
        if ( $total == $given_permissions ) {
          return TRUE;
        } else {
          return FALSE;
        }
      }
    }
    return FALSE;
}

function getStudentBatches( $type = ''){

     $current_student   = Auth::user();
     if ( 'subjects' === $type ) {
      $userSubjects = \App\User::getUserSeleted('lms_subjects');

      if ( ! empty( $userSubjects ) ) {
        $student_batches   = App\BatchStudent::select('batch_id')->join('batches', 'batches.id', '=', 'batch_students.batch_id')
        ->join('courses', 'courses.id', '=', 'batches.course_id')
        ->join('courses_subjects', 'courses_subjects.course_id', '=', 'courses.id')
        ->whereIn('courses_subjects.subject_id', $userSubjects)
        ->pluck('batch_id')
        ->toArray();

      } else {
        $student_batches = [];
      }
     } else {
      $student_batches   = App\BatchStudent::where('user_id',$current_student->id)
      ->pluck('batch_id')
      ->toArray();

    }

     return $student_batches;
}

function getStudentBatchesPWA($id){


      $current_student =  \App\User::where('id' , $id)->first();

 $student_batches   = App\BatchStudent::where('user_id',$current_student->id)
      ->pluck('batch_id')
      ->toArray();


     return $student_batches;
}

function getFacultyBatches(){
  return \App\User::find( \Auth::id() )->faculty_batches()->pluck('batch_id')->toArray();
}

function getStudentClasses( $type = ''){
     $current_student   = Auth::user();
      $student_classes   = \App\Batch::select('batches.*')->join('batch_students', 'batches.id', '=', 'batch_students.batch_id')
      ->where('batch_students.user_id', $current_student->id)->get();

      if ( $type == 'courses') {
        $student_classes = $student_classes->pluck('course_id')
      ->toArray();
      } else {
        $student_classes = $student_classes->pluck('student_class_id')
      ->toArray();
      }

     return $student_classes;
}

// function getStudentClassesPWA( $type = '' ,$userId){
function getStudentClassesPWA($userId, $type = '') {
     $current_student   = $userId;
      $student_classes   = \App\Batch::select('batches.*')->join('batch_students', 'batches.id', '=', 'batch_students.batch_id')
      ->where('batch_students.user_id', $current_student)->get();

      if ( $type == 'courses') {
        $student_classes = $student_classes->pluck('course_id')
      ->toArray();
      } else {
        $student_classes = $student_classes->pluck('student_class_id')
      ->toArray();
      }

     return $student_classes;
}


function getBatchName($id){

  $record  = App\Batch::find($id);
  if ( $record ) {
    return ucwords($record->name);
  } else {
    return '';
  }

}

function makeTimeSlots(){

$time_values = array();

for($time=6; $time<=23; $time++){

    for($interval=0; $interval<60; $interval +=10){

         $index_num = makeNumber($time).':'.makeNumber($interval).':00';

         $time_values[$index_num] = makeNumber($time).':'.makeNumber($interval);

     }

  }
  return $time_values;
}

function getController( $key = '' ) {
  $action = app('request')->route();
  if ( $action ) {
    $action = $action->getAction();
  }

  $controller = class_basename($action['controller']);

  $parts = explode('@', $controller);

  $controller = $parts[0];
  if ( ! empty( $parts[1] ) ) {
    $action = $parts[1];
  }


  $result = array(
    'controller' => $controller,
    'action' => $action,
  );
  if ( ! empty( $key ) ) {
    if ( ! empty( $result[ $key ] ) ) {
      $result = $result[ $key ];
    }
  }
  return $result;
}

function isOnlinestudent() {
  // Online student is a one who belong to owner institute.
  if (Auth::user()->institute_id == OWNER_INSTITUTE_ID ) {
    return TRUE;
  } else {
    return FALSE;
  }
}

function isOnlinestudentPWA($user_id) {
  // Online student is a one who belong to owner institute.
    $user = \App\User::where('id' , $user_id)->first();

  if ($user->institute_id == OWNER_INSTITUTE_ID ) {
    return TRUE;
  } else {
    return FALSE;
  }
}

/**
 * Combines SQL and its bindings
 *
 * @param \Eloquent $query
 * @return string
 */
function getEloquentSqlWithBindings($query)
{
    return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
        return is_numeric($binding) ? $binding : "'{$binding}'";
    })->toArray());
}

/**
 * Combines SQL and its bindings
 *
 * @param \Eloquent $query
 * @return string
 */
function printSql($query)
{
    return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
        return is_numeric($binding) ? $binding : "'{$binding}'";
    })->toArray());
}

/**
* Get IP address of the current user system.
*
* @return string
*/
function GetIP()
{
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
    {
        if (array_key_exists($key, $_SERVER) === true)
        {
            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip)
            {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
                {
                    return $ip;
                }
            }
        }
    }
}

function canSendEmail() {
  $id = \Auth::id();

  if ( ! canDo('email_alerts') ) {
    return false;
  }

  $student_batches   = App\BatchStudent::where('user_id', $id)
  ->join('batches', 'batches.id', '=', 'batch_students.batch_id')
  ->where('enable_email_alerts', 'yes')->get()->pluck('batch_id')
      ->toArray();
  if ( empty( $student_batches ) ) {
    return false;
  } else {
    return true;
  }
}

function canSendSMS() {
  $id = \Auth::id();

  if ( ! canDo('sms_alerts') ) {
    return false;
  }

  $student_batches   = App\BatchStudent::where('user_id', $id)
  ->join('batches', 'batches.id', '=', 'batch_students.batch_id')
  ->where('enable_sms_alerts', 'yes')->get()->pluck('batch_id')
      ->toArray();
  if ( empty( $student_batches ) ) {
    return false;
  } else {
    return true;
  }
}

function canSend( $permission, $user_id = '', $type = 'Email' ) {

  if ( \Auth::check() ) {
    if ( isAdmin() ) {
      return false;
    }
    if ( isFaculty() ) {
      return false;
    }
  }

  if ( $type == 'Email' && ! canDo('email_alerts') ) {
    return false;
  } elseif ( $type == 'SMS' && ! canDo('sms_alerts') ) {
    return false;
  }

  $institute_id   = adminInstituteId();
  if ( ! empty( $user_id ) ) {
    $institute_id = \App\User::find( $user_id )->institute_id;
  }

  if ( in_array($permission, ['user_creation', 'user_import', 'onlineclass_absent']) ) {
      $permission_check = \App\Alert::join('alerts_enabled', 'alerts_enabled.alert_id', '=', 'alerts.id')->where('name', $permission)->where('type', $type)->where('institute_id', $institute_id)->first();

      if ( $permission_check && $permission_check->batch_id == 'yes' ) {
        return true;
      } else {
        return false;
      }
  }

  $student_batches   = \App\BatchStudent::where('batches.user_id', $user_id)
  ->join('batches', 'batches.id', '=', 'batch_students.batch_id')->get()->pluck('batch_id')
      ->toArray();
  if ( empty( $student_batches ) ) { // Which means student not in any batch!
    return false;
  } else {
    $permission_check = \App\Alert::join('alerts_enabled', 'alerts_enabled.alert_id', '=', 'alerts.id')->where('name', $permission)->where('type', $type)->whereIn('batch_id', $student_batches)->first();
    if ( $permission_check ) {
      return true;
    } else {
      return false;
    }
  }
}

function emailHistory( $email, $message, $institute_id = '' ) {
  $data = [
    'message' => $message,
    'controller_details' => json_encode(getController()),
    'email' => $email,
    'created_at' => date('Y-m-d H:i:s'),
  ];
  if ( \Auth::check() ) {
    $data['institute_id'] = adminInstituteId();
  } elseif( ! empty( $institute_id )) {
    $data['institute_id'] = $institute_id;
  } else {
    $data['institute_id'] = OWNER_INSTITUTE_ID;
  }
  \DB::table('email_usage_history')->insert($data);
}

function smsHistory( $phone, $message, $type, $institute_id = '' ) {
  $data = [
    'message' => $message,
    'controller_details' => json_encode(getController()),
    'phone' => $phone,
    'sms_type' => $type,
    'created_at' => date('Y-m-d H:i:s'),
  ];
  if ( \Auth::check() ) {
    $data['institute_id'] = adminInstituteId();
  } elseif( ! empty( $institute_id )) {
    $data['institute_id'] = $institute_id;
  } else {
    $data['institute_id'] = OWNER_INSTITUTE_ID;
  }
  \DB::table('sms_usage_history')->insert($data);
}

function getValidityDaysLeftAttribute( $user_id )
{
    //dd($user_id);
    // Future field that will be implemented after payments
    $loggedin_user = \App\User::find( $user_id );
    $institute_id = $loggedin_user->institute_id;
    $user = \App\User::where('institute_id', $institute_id)->where('role_id', INSTITUTE_ROLE_ID)->first();
    if ( ! $user ) {
        $user = $loggedin_user;
    }
    // $institute = \App\Institute::find( $user->institute_id ); // Owners record, if the other users of same owner logged in we need to check owners validity!

    // $plan_until_owner = Carbon::now();
    $plan_until_owner = '';
    if ( $user->valid_until ) {
        $plan_until_owner = $user->valid_until;
    }
    if ( isOnlinestudent() ) {
      $payments = \App\Payment::where('user_id', \Auth::id())->where('notification_closed', '0')->get();
      // dd($payments);
      if ( $payments->count() > 0 ) {
        $days_left = 0;
        foreach( $payments as $payment ) {
            $days = dateDiffInDays(date('Y-m-d'), $payment->end_date);
            if ( $days > $days_left ) {
              $days_left = $days;
            }
        }
        return $days_left;
      } else {
        $user = \Auth::user();
        $plan_until_owner = $user->valid_until;
        if( empty($plan_until_owner)) {
          $plan_until_owner = $user->trial_until;
        }
      }
    }
    if ($plan_until_owner) {
        $days = now()->diffInDays($plan_until_owner, false);
        if ( $days < 0 ) {
            return 0;
        } else {
            return $days;
        }
    } elseif( ! empty( $user->trial_until )) {
        return now()->diffInDays($user->trial_until, false);
    }

    // return EXPIRE_NOTIFICATION_BEFORE;
    return 0;
}

function dateDiffInDays($date1, $date2)
{
    // Calculating the difference in timestamps
    $diff = strtotime($date2) - strtotime($date1);

    // 1 day = 24 hours
    // 24 * 60 * 60 = 86400 seconds
    return round($diff / 86400);
}

function validPackages( $user_id = '' ) {
  if ( empty( $user_id ) ) {
    $user_id = \Auth::id();
  }

  $payments = \App\Payment::where('user_id', $user_id)->get();

  $packages = [];
  if( $payments->count() > 0 ) {
    foreach( $payments as $payment ) {
      $days = dateDiffInDays(date('Y-m-d'), $payment->end_date);
      if ( $days > 0 ) {
        $packages[] = $payment->item_id;
      }
    }
  }
  return $packages;
}

function getPackageContents() {
  $packages = validPackages();
  dd( $packages );
  if ( ! empty( $packages ) ) {
    $contents = \DB::select('SELECT lmsseries.* from student_paid_contents  spc inner join student_paid_contents_data spcd on spcd.student_paid_contents_id = spc.id inner join lmsseries on lmsseries.id = spcd.item_id where spcd.item_type = "lmsseries" and spc.id in('.implode(',', $packages).')')->get();
  }
}