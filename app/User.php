<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Laravel\Cashier\Billable;
use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Notifications\Notifiable;
use Auth;
use Carbon\Carbon;

class User extends Authenticatable
{
    use EntrustUserTrait;
    use Billable;
    use Messagable;
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

   protected $dates = ['trial_ends_at', 'subscription_ends_at', 'valid_until', 'trial_until'];

   protected $appends = ['validity_days_left', 'quiz_count_week'];

   public static function boot()
    {
        parent::boot();
        User::observe(new \App\Observers\UserActionsObserver);
    }

    public function staff()
    {
        return $this->hasOne('App\Staff');
    }

     /**
     * The roles that belong to the user.
     */
    public function roles()
    {
         return $this->belongsToMany('App\Role', 'role_user');

    }

    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * The roles that belong to the user.
     */
    public function subjects()
    {
         return $this->belongsToMany(Subject::class, 'users_subjects');

    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_users');
    }

    public function institute_permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_institute');
    }


    /**
     * Returns the student record from students table based on the relationship
     * @return [type]        [Student Record]
     */
    public function student()
    {
        return $this->hasOne('App\Student');
    }


    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    public function student_class()
    {
        return $this->belongsTo(StudentClass::class, 'student_class_id')->withDefault();
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_students', 'user_id', 'batch_id');
    }

    public function faculty_batches() {
        return $this->belongsToMany(Batch::class, 'batch_faculty', 'user_id', 'batch_id');
    }

    public static function getRecordWithSlug($slug)
    {
        return User::where('slug', '=', $slug)->first();
    }

    public function isChildBelongsToThisParent($child_id, $parent_id)
    {
        return User::where('id', '=', $child_id)
              ->where('parent_id','=',$parent_id)
              ->get()
              ->count();
    }

    public function getLatestUsers($limit = 5)
    {
        if(checkRole('institute')){

           $institute_id   = adminInstituteId();

           return User::where('role_id','=',getRoleData('student'))
                     ->where('institute_id',$institute_id)
                     ->orderBy('id','desc')
                     ->limit($limit)
                     ->get();


        }
        return User::orderBy('id','desc')
                     ->limit($limit)
                     ->get();
    }


     /**
      * This method accepts the user object from social login methods
      * Registers the user with the db
      * Sends Email with credentials list
      * @param  User   $user [description]
      * @return [type]       [description]
      */
     public function registerWithSocialLogin($receivedData = '')
     {
        $user        = new User();
        $password         = str_random(8);
        $user->password   = bcrypt($password);
        $slug             = $user->makeSlug($receivedData->name);
        $user->username   = $slug;
        $user->slug       = $slug;

        $role_id        = getRoleData('student');

        $user->name  = $receivedData->name;
        $user->email = $receivedData->email;
        $user->role_id = $role_id;
        $user->login_enabled  = 1;
         if(!env('DEMO_MODE')) {
        $user->save();
        $user->roles()->attach($user->role_id);
        try{
            $user->notify(new \App\Notifications\NewUserRegistration($user,$user->email,$password));
        }
        catch(Exception $ex)
        {
            return $user;
        }

        }
       return $user;
     }

     /**
     * This method will return the user title
     * @return [type] [description]
     */
    public function getUserTitle()
    {
        return ucfirst($this->name);
    }



    public function instituteDetails($type='name')
    {

               $record  = Institute::where('user_id',$this->id)->first();

             if($type == 'name'){

               return ucwords($record->institute_name);
             }
             elseif($type == 'address'){

                 return ucwords($record->institute_address);
             }


    }

     public function studentInstitute($type='name')
    {

            $record  = Institute::where('id',$this->institute_id)->first();

            if ( $record ) {

             if($type == 'name'){
               return ucwords($record->institute_name);
             }
             elseif($type == 'address'){
                 return ucwords($record->institute_address);
             }
         } else {
            return '';
         }

    }


    public static function getUserSeleted($type='')
    {
        $user         = Auth::user();
        $preferences  = (array)json_decode($user->settings);
        // dd($preferences);
        $cats  = array();
        $lmscats  = array();
        if(isset($preferences['user_preferences'])){

        $cats         = $preferences['user_preferences']->quiz_categories;
        $lmscats      = $preferences['user_preferences']->lms_categories;

       }

        if($type == 'categories')
        return $cats;

        if($type == 'lms_categories')
          return count($lmscats);

      if( in_array($type, ['lms_subjects', 'exam_subjects'])) {
            $subjects = \DB::table('batches')
            ->select('courses_subjects.subject_id')
            ->join('batch_students', 'batches.id', '=', 'batch_students.batch_id')
            ->join('courses', 'courses.id', '=', 'batches.course_id')
            ->join('courses_subjects', 'courses_subjects.course_id', '=', 'courses.id')
            ->where('batch_students.user_id', $user->id)->get()->pluck('subject_id')->toArray();
            return $subjects;
        }

       if($type == 'quizzes')

            $batches  = getStudentBatches();


           return Quiz::join('batch_quizzes','batch_quizzes.quiz_id','=','quizzes.id')
                        ->whereIn('batch_quizzes.batch_id',$batches)
                        ->whereIn('quizzes.category_id',$cats)
                        ->where('total_questions','>',0)
                        ->get()
                        ->count();

       return 0;

     }


     public static function getUserSeletedPWA($type='' , $id='')
    {
        $user         = User::where('id',$id)->first();
        $preferences  = (array)json_decode($user->settings);
        // dd($preferences);
        $cats  = array();
        $lmscats  = array();
        if(isset($preferences['user_preferences'])){

        $cats         = $preferences['user_preferences']->quiz_categories;
        $lmscats      = $preferences['user_preferences']->lms_categories;

       }

        if($type == 'categories')
        return $cats;

        if($type == 'lms_categories')
          return count($lmscats);

      if( in_array($type, ['lms_subjects', 'exam_subjects'])) {
            $subjects = \DB::table('batches')
            ->select('courses_subjects.subject_id')
            ->join('batch_students', 'batches.id', '=', 'batch_students.batch_id')
            ->join('courses', 'courses.id', '=', 'batches.course_id')
            ->join('courses_subjects', 'courses_subjects.course_id', '=', 'courses.id')
            ->where('batch_students.user_id', $user->id)->get()->pluck('subject_id')->toArray();
            return $subjects;
        }

       if($type == 'quizzes')

            $batches  = getStudentBatches();


           return Quiz::join('batch_quizzes','batch_quizzes.quiz_id','=','quizzes.id')
                        ->whereIn('batch_quizzes.batch_id',$batches)
                        ->whereIn('quizzes.category_id',$cats)
                        ->where('total_questions','>',0)
                        ->get()
                        ->count();

       return 0;

     }

    public function getQuizCountWeekAttribute() {
        $from_date = Carbon::now();
        $to_date = now()->addDays(7, false);
        return QuizResult::where('user_id', Auth::id())->whereBetween(\DB::raw('DATE(created_at)'), array($from_date, $to_date))->count();
    }
    public function getValidityDaysLeftAttribute()
    {
        // // Future field that will be implemented after payments
        $loggedin_user = \Auth::user();
        if($loggedin_user){
            
        $institute_id = $loggedin_user->institute_id;
        $user = User::where('institute_id', $institute_id)->where('role_id', INSTITUTE_ROLE_ID)->first();
        if ( ! $user ) {
            $user = $loggedin_user;
        }
        // $institute = \App\Institute::find( $user->institute_id ); // Owners record, if the other users of same owner logged in we need to check owners validity!

        // $plan_until_owner = Carbon::now();
        $plan_until_owner = '';
        if ( $user->valid_until ) {
            $plan_until_owner = $user->valid_until;
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
    }
}
