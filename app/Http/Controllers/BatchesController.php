<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Http\Requests;
use App\Batch;
use App\User;
use App\BatchStudent;
use App\FeePayment;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use Exception;

class BatchesController extends Controller
{

    public function __construct()
    {
      $this->middleware('auth');
    }
    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {

      if(!canDo('institute_batch_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $data['active_class'] = 'batches';
        $data['layout']       = getLayout();
        $data['title']        = getPhrase('batches');

        $view_name = getTheme().'::batches.list';
        return view($view_name, $data);


    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {

      if(!canDo('institute_batch_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $institute_id   = adminInstituteId();

      $records = array();


       if ( isFaculty() ) {
        $batches = Auth::user()->faculty_batches()->get()->pluck('id')->toArray();
        $records = Batch::select(['name','start_date','end_date','capacity','updated_at','id', 'student_class_id', 'course_id', 'enable_sms_alerts'])
                            ->whereIn('id',$batches);
       } else {
       $records = Batch::select(['name','start_date','end_date','capacity','updated_at','id', 'student_class_id', 'course_id', 'enable_sms_alerts'])
                            ->where('institute_id',$institute_id);
                          }

            $records->orderBy('updated_at', 'desc');


        return Datatables::of($records)
        ->addColumn('booked_seats', function($records){

             return  BatchStudent::where('batch_id',$records->id)->get()->count();


        })
        ->addColumn('name', function($record){
             $class  =  \App\StudentClass::find( $record->student_class_id );
             $name = $record->name . ' ('.$record->id.')';
             if ( $class ) {
              $name .= '<br>(Class: '.$class->name.')';
             }

             $course  =  \App\Course::find( $record->course_id );
             if ( $course ) {
              $name .= '(Course: '.$course->title.')';
             }
             if ( canDo('sms_alerts') ) {
                $name .= '<br>SMS Alerts: '.ucfirst($record->enable_sms_alerts);
             }
             return $name;
        })
        ->addColumn('available_seats', function($records){

             $data  =  BatchStudent::where('batch_id',$records->id)->get()->count();
             return $records->capacity - $data;

        })
        ->addColumn('start_date', function($records){
             return '<a href="'.URL_BATCHS_ADD_LMS.$records->id.'" class="btn  btn-primary button"><i class="fa fa-graduation-cap"></i></a>';
        })
        ->addColumn('end_date', function($records){
             return '<a href="'.URL_BATCHS_ADD_LMSNOTES.$records->id.'" class="btn btn-primary button"><i class="fa fa-sticky-note-o"></i></a>';
        })
        ->addColumn('capacity', function($records){
             return '<a href="'.route('batches.report', ['batch_id' => $records->id]).'" class="btn btn-primary button"><i class="fa fa-bar-chart-o"></i></a>';
        })
        ->addColumn('action', function ($records) {

         $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">';
                        if(checkRole(getUserGrade(9))) {
                        $link_data .='
                           <li><a href="'.URL_BATCHS_EDIT.$records->id.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
                         }
                         /*
                         $link_data .= '
                         <li><a href="'.URL_BATCHS_ADD_LMS.$records->id.'"><i class="fa fa-graduation-cap"></i>Assign LMS</a></li>
                         <li><a href="'.URL_BATCHS_ADD_LMSNOTES.$records->id.'"><i class="fa fa-sticky-note-o"></i>Assign Notes</a></li>
                         <li><a href="'.route('batches.report', ['batch_id' => $records->id]).'"><i class="fa fa-bar-chart-o"></i>Exam Report</a></li>
                         ';
                         */

                   $temp = '';

                   $batch_students   = BatchStudent::where('batch_id',$records->id)->get()->count();
                   $batch_record     = Batch::find($records->id);

                   //if($batch_record->end_date >= date('Y-m-d')){

                         if(checkRole(getUserGrade(9))) {
                         $temp .= ' <li><a href="'.URL_BATCHS_ADD_STUDENTS.$records->id.'"><i class="fa fa-users"></i>'.getPhrase("add_students").'</a></li>';
                        }
                  // }



                    if($batch_students > 0 ) {

                        $temp .= ' <li><a href="'.URL_VIEW_BATCH_STUDENTS.$records->id.'" ><i class="fa fa-eye"></i>'. getPhrase("view_students").'</a></li>';

                    }

                    if(checkRole(getUserGrade(2))) {

                        if(checkRole(getUserGrade(9))) {
                        $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->id.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
                        }

                    }

                    $temp .='</ul></div>';


                    $link_data .=$temp;
            return $link_data;
            })


        ->editColumn('institute_id', function($records){

            $institute  = getInstitute($records->institute_id);
            if($institute)
            return '<a href = "'.URL_INSTITUTE_DETAILS.$institute->id.'">'.ucwords($institute->institute_name).'</a>';

             return '-';
        })
        ->removeColumn('id')
        ->removeColumn('updated_at')
        ->removeColumn('student_class_id')
        ->removeColumn('course_id')

        ->removeColumn('total_seats')
        ->removeColumn('booked_seats')
        ->removeColumn('available_seats')
        // ->removeColumn('capacity')
        ->removeColumn('enable_sms_alerts')

        ->make();
    }


    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
      if(!canDo('institute_batch_create'))
      {
        prepareBlockUserMessage();
        return back();
      }
      $data['record']           = FALSE;
      $data['layout']           = getLayout();
      $data['active_class']     = 'batches';
      $data['title']            = getPhrase('add_batch');
      $data['slots_times']    = makeTimeSlots();

      $view_name = getTheme().'::batches.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($id)
    {
      if(!canDo('institute_batch_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

      $record = Batch::where('id',$id)->first();
      if($isValid = $this->isValidRecord($record))
        return redirect($isValid);

      $data['record']       = $record;
      $data['active_class'] = 'batches';
      $data['layout']       = getLayout();
      $data['title']        = getPhrase('edit_batch');
      $data['slots_times']    = makeTimeSlots();

      $view_name = getTheme().'::batches.add-edit';
      return view($view_name, $data);
    }

    /**
     * Update record based on slug and reuqest
     * @param  Request $request [Request Object]
     * @param  [type]  $slug    [Unique Slug]
     * @return void
     */
    public function update(Request $request, $id)
    {
      if(!canDo('institute_batch_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

      $record = Batch::where('id',$id)->first();
      $rules = [
         'name'              => 'bail|required|max:40|unique:batches,name,'.$id.',id,institute_id,' . \Auth::user()->institute_id,
         'student_class_id'  => 'bail|required',
         'course_id'  => 'bail|required',
         'capacity'          => 'bail|nullable|numeric',
         'start_date'        => 'bail|required',
         'end_date'          => 'bail|required',
         //'start_time'        => 'bail|required',
         //'end_time'          => 'bail|required'
       ];

        $this->validate($request, $rules);

        $record->name           = $request->name;
        $record->student_class_id           = $request->student_class_id;
        $record->course_id           = $request->course_id;
        $record->start_date     = $request->start_date;
        $record->end_date       = $request->end_date;

        $record->capacity       = $request->capacity;
        $record->fee_perhead    = $request->fee_perhead;
        $record->start_time     = ($request->start_time) ? $request->start_time : '00:00:00';
        $record->end_time       = ($request->end_time) ? $request->end_time : '00:00:00';

        if ( ! empty( $request->enable_sms_alerts ) ) {
          $record->enable_sms_alerts = $request->enable_sms_alerts;
        }
        $record->save();

        flash('success','record_updated_successfully', 'success');
        return redirect(URL_BATCHS);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
      if(!canDo('institute_batch_create'))
      {
        prepareBlockUserMessage();
        return back();
      }

      $rules = [
         // 'name'              => 'bail|required|max:40|unique:batches',
         'name'              => 'bail|required|max:40|unique:batches,name,NULL,id,institute_id,' . \Auth::user()->institute_id,
         'student_class_id'  => 'bail|required',
         'course_id'  => 'bail|required',
         'capacity'          => 'bail|nullable|numeric',
         'start_date'        => 'bail|required',
         'end_date'          => 'bail|required',
         //'start_time'        => 'bail|required',
         //'end_time'          => 'bail|required'
       ];

        $this->validate($request, $rules);

        $record                 = new Batch();
        $record->user_id        = Auth::user()->id;
        $record->institute_id   = adminInstituteId();
        $record->name           = $request->name;
        $record->student_class_id           = $request->student_class_id;
        $record->course_id           = $request->course_id;
        $record->start_date     = $request->start_date;
        $record->end_date       = $request->end_date;
        $record->capacity       = $request->capacity;
        $record->fee_perhead    = $request->fee_perhead;
        $record->start_time     = ($request->start_time) ? $request->start_time : '00:00:00';
        $record->end_time       = ($request->end_time) ? $request->end_time : '00:00:00';
        if ( ! empty( $request->enable_sms_alerts ) ) {
          $record->enable_sms_alerts = $request->enable_sms_alerts;
        }
        $record->save();

        flash('success','new_batch_created_successfully', 'success');
        return redirect(URL_BATCHS);
    }

    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($id)
    {
      if(!canDo('institute_batch_delete'))
      {
        prepareBlockUserMessage();
        return back();
      }
      /**
       * Check if any quizzes are associated with this instructions page,
       * if not delete
       * @var [type]
       */
        $record = Batch::where('id', $id)->first();
        $response = [];
       try {
        if(!env('DEMO_MODE')) {
          $record->delete();
        }
          $response['status'] = 1;
          $response['message'] = getPhrase('record_deleted_successfully');
      }
       catch ( \Illuminate\Database\QueryException $e) {
                 $response['status'] = 0;
           if(getSetting('show_foreign_key_constraint','module'))
            $response['message'] =  $e->errorInfo;
           else
            $response['message'] =  getPhrase('this_record_is_in_use_in_other_modules');
       }
        return json_encode($response);
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
      return URL_BATCHS;
    }


    /**
     * Add Students To Batch
     * @param [type] $batch_id [description]
     */
    public function addStudents($batch_id)
    {

       if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }


        $record               = Batch::find($batch_id);
        $data['active_class'] = 'batches';
        $data['layout']       = getLayout();
        $data['title']        = getPhrase('add_students_to ').ucwords($record->name);
        $data['record']       = $record;
        $any_records          = BatchStudent::where('batch_id',$batch_id)->select(['user_id'])->get();
        $data['items']        = json_encode(array('target_items'=>$any_records));

        $view_name = getTheme().'::batches.students-list';
        return view($view_name, $data);

    }

    /**
     * Return Institute Students
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getStudents(Request $request)
    {
       // return $request;
      $assigned       = $request->assigned;
       $user_id       = $request->user_id;
       $institute_id  = $request->institute_id;
       $batch_id      = $request->batch_id;
       $batch_students   = BatchStudent::where('batch_id',$batch_id)->pluck('user_id')->toArray();

       if(count($batch_students) > 0){
         $records  = User::select('users.*', 'student_classes.name as classname')->where('users.institute_id',$institute_id)
                         ->join('student_classes', 'student_classes.id', '=', 'users.student_class_id')
                         ->where('users.id','!=',$user_id)
                         ->whereNotIn('users.id',$batch_students);
          if ( ! empty( $assigned ) ) {
            $records->where('users.assigned', $assigned);
          }
          // echo $records->toSql();
          $records = $records->get();
        }
        else{
            $records  = User::select('users.*', 'student_classes.name as classname')->where('users.institute_id',$institute_id)
                         ->join('student_classes', 'student_classes.id', '=', 'users.student_class_id')
                         ->where('users.id','!=',$user_id);
             if ( ! empty( $assigned ) ) {
            $records->where('users.assigned', $assigned);
          }
          $records = $records->get();
        }
        return $records;
    }


    /**
     * Get Batch Students
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getBatchStudents(Request $request)
    {

       $user_id        = $request->user_id;
       $institute_id   = $request->institute_id;
       $batch_id       = $request->batch_id;

       $records  = User::join('batch_students','batch_students.user_id','=','users.id')
       ->join('student_classes', 'student_classes.id', '=', 'users.student_class_id')
                         ->where('batch_id', $batch_id)
                         ->where('users.id', '!=', $user_id)
                         ->where('users.role_id', STUDENT_ROLE_ID)
                         ->select(['users.id','users.name','email','users.slug', 'student_classes.name as classname'])
                         ->get();

        return $records;

    }

    /**
     * Store students to batch
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function storeStudents(Request $request)
    {

       $batch           = Batch::find($request->batch_id);
       $batch_students  = BatchStudent::where('batch_id',$request->batch_id)->get()->count();
       $available_seats = $batch->capacity - $batch_students;
       $users           = $request->user_ids;

       if(!$users){

         flash('Oops...','select_the_students','overlay');
         return back();
       }

       /*
       if(count($users) > $available_seats){

         flash('Oops...','batch_strength_is_exceded','overlay');
         return back();
       }
       */


        DB::beginTransaction();

        try {

           foreach ($users as $key => $value) {

             $record                = new BatchStudent();
             $record->user_id       = $value;
             $record->batch_id      = $batch->id;
             $record->institute_id  = $batch->institute_id;
             $record->save();

             $user = \App\User::find( $value );
             if ( $user ) {
              $user->assigned = 'yes';
              $user->save();
             }

             $fee = $batch->fee_perhead;
             $course = \App\Course::find( $batch->course_id );
             if ( $course && ! empty( $course->fee_percourse )) {
              $fee = $course->fee_percourse;
             }
             //Add Fee record for each batch student
             FeePayment::addFeeRecord($record, $fee);

          }

         DB::commit();
         flash('success','students_are_successfully_added_to_batch','success');

      }catch(Exception $e) {

           DB::rollBack();
          flash('Oops...!','Error! Please Try again', 'error');
       }

      return redirect(URL_BATCHS);

    }

   /**
    * View available students in batch
    * @param  [type] $batch_id [description]
    * @return [type]           [description]
    */
    public function viewBatchStudents($batch_id)
    {

        if(!canDo('institute_batch_view'))
      {
        prepareBlockUserMessage();
        return back();
      }


        $record               = Batch::find($batch_id);
        $data['active_class'] = 'batches';
        $data['layout']       = getLayout();
        $data['title']        = getPhrase('view_students_of ').ucwords($record->name);
        $data['record']       = $record;
        $any_records          = BatchStudent::where('batch_id',$batch_id)->select(['user_id'])->get();

        $view_name = getTheme().'::batches.students-view-list';
        return view($view_name, $data);

    }

    /**
     * Delete the available students in batch
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateBatchStudents(Request $request)
    {

       if($request->has('user_ids')){

         $batch_id = $request->batch_id;
         $users    = $request->user_ids;

         BatchStudent::where('batch_id',$batch_id)
                      ->whereIn('user_id',$users)
                      ->delete();

          foreach ($users as $key => $value) {
            $user = \App\User::find( $value );
            if ( $user ) {
              $user->assigned = 'no';
              $user->save();
            }
          }
         flash('success','students_are_successfully_removed','success');

       }
       else{

            flash('Oops...!','please_select_the_students','overlay');
       }
         return redirect(URL_BATCHS);
    }

    /**
     * Add LMS To Batch
     * @param [type] $batch_id [description]
     */
    public function addLms(Request $request, $batch_id)
    {

       if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }


        $record               = Batch::find($batch_id);
        $institute_id   = adminInstituteId();

        if ( $request->isMethod('post') ) {
          $lmsseries = $request->lmsseries;
          $batch_id = $request->batch_id;

          if ( ! empty( $lmsseries ) ) {
            $lmsseries_temp = [];
            foreach ($lmsseries as $key => $value) {
              $lmsseries_temp[] = [
                'batch_id' => $batch_id,
                'institute_id' => $institute_id,
                'lms_series_id' => $key,
              ];
            }

            \App\BatchLms::where('batch_id', $batch_id)->where('institute_id', $institute_id)->delete();
            $record->lmsseries()->sync( $lmsseries_temp );
            flash('success','LMS_successfully_updated','success');
          } else {
            flash('Oops...!','please_select_the_LMS','overlay');
          }
          return redirect(URL_BATCHS);
        }
        $data['active_class'] = 'batches';
        $data['layout']       = getLayout();
        $data['title']        = getPhrase('add_LMS_to ').ucwords($record->name);
        $data['record']       = $record;
        $any_records          = BatchStudent::where('batch_id',$batch_id)->select(['user_id'])->get();
        $data['items']        = json_encode(array('target_items'=>$any_records));

        $view_name = getTheme().'::batches.lms-list';
        return view($view_name, $data);

    }


    /**
     * Add LMS To Batch
     * @param [type] $batch_id [description]
     */
    public function addLmsnotes(Request $request, $batch_id)
    {

       if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

        $record               = Batch::find($batch_id);
        $institute_id   = adminInstituteId();

        if ( $request->isMethod('post') ) {
          $lmsnotes = $request->lmsnotes;
          $batch_id = $request->batch_id;

          if ( ! empty( $lmsnotes ) ) {
            $lmsnotes_temp = [];
            foreach ($lmsnotes as $key => $value) {
              $lmsnotes_temp[] = [
                'batch_id' => $batch_id,
                'institute_id' => $institute_id,
                'lms_note_id' => $key,
              ];
            }
            \DB::table('batch_lmsnotes')->where('batch_id', $batch_id)->where('institute_id', $institute_id)->delete();
            $record->lmsnotes()->sync( $lmsnotes_temp );
            flash('success','LMS_successfully_updated','success');
          } else {
            flash('Oops...!','please_select_the_LMS','overlay');
          }
          return redirect(URL_BATCHS);
        }
        $data['active_class'] = 'batches';
        $data['layout']       = getLayout();
        $data['title']        = getPhrase('add_LMS_notes_to ').ucwords($record->name);
        $data['record']       = $record;
        $any_records          = BatchStudent::where('batch_id',$batch_id)->select(['user_id'])->get();
        $data['items']        = json_encode(array('target_items'=>$any_records));

        $view_name = getTheme().'::batches.lmsnotes-list';
        return view($view_name, $data);

    }

    public function batchReport( $batch_id, $exam_slug = '' ) {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

      $batch = Batch::find( $batch_id );
      if($isValid = $this->isValidRecord($batch))
        return redirect($isValid);

      if ( ! empty( $exam_slug ) ) {
        $exam = \App\Quiz::where('slug',$exam_slug)->first();
        if($isValid = $this->isValidRecord($exam))
          return redirect($isValid);
      }


      $data['active_class']       = 'batches';
      $data['title']              = getPhrase('batch_report');
      $data['batch'] = $batch;
      $data['batch_exams'] = $batch->quizzes()->get();

      $data['batch_id']       = $batch_id;
      $data['exam_slug']       = $exam_slug;
      if ( ! empty( $exam_slug ) ) {
        $data['chart_data']= (object)$this->getBatchStats($batch, $exam_slug);
      }


      $data['layout']             = getLayout();

      // dd( $data['chart_data'] );

      $view_name = getTheme().'::batches.batch-report';
      return view($view_name, $data);
    }

    /**
     * This method prepares the chart data for success and failed records
     * @param  [type] $payment_data [description]
     * @return [type]               [description]
     */
    public function getBatchStats($batch, $exam_slug)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

      // $students = $batch->quizzes()->
      //$payment_dataset = [$payment_data->success, $payment_data->cancelled, $payment_data->pending];
      //$payment_labels = [getPhrase('success'), getPhrase('cancelled'), getPhrase('pending')];
      //$payment_dataset_labels = [getPhrase('total')];

      //$payment_bgcolor = [getColor('',4),getColor('',9),getColor('',18)];
      //$payment_border_color = [getColor('background',4),getColor('background',9),getColor('background',18)];
      $exam = \App\Quiz::where('slug', $exam_slug)->first();
      $questions_res = $exam->getQuestions()->pluck('questionbank_id');

      $total_questions = $exam->total_questions;
      // dd($total_questions);
      $dataset = [];
      $questions = $result_set = $final_colors = [];

      $student_names = $batch->students()->get();//->pluck('name')->toArray();
      $index = 0;

      $user_responses = [];
      $question_answers = [];
      foreach ($questions_res as $question_id) {
        foreach ($student_names as $user) {
          $result = \App\QuizResult::where('quiz_id', $exam->id)->where('user_id', $user->id)->latest('created_at')->first();

          //$user->question_id = $question_id;
          if ( $result ) {
            $correct_answer_questions = (array)json_decode($result->correct_answer_questions);
            $wrong_answer_questions = (array)json_decode($result->wrong_answer_questions);
            $not_answered_questions = (array)json_decode($result->not_answered_questions);

            if ( in_array($question_id, $correct_answer_questions)) {
              $user_responses[] = [$user->id => '#45c490'];
              $question_answers[] = ['question_id' => $question_id,
              'user_id' => $user->id,
              'color' => '#45c490',];
            } elseif ( in_array($question_id, $wrong_answer_questions)) {
              $user_responses[] = [$user->id => '#ff0000'];
              $question_answers[] = ['question_id' => $question_id,
              'user_id' => $user->id,
              'color' => '#ff0000',];
            } else {
              $user_responses[] = [$user->id => '#D3D3D3'];
              $question_answers[] = ['question_id' => $question_id,
              'user_id' => $user->id,
              'color' => '#D3D3D3',];
            }
          } else {
            $user_responses[] = [$user->id => '#D3D3D3'];
            $question_answers[] = [
              'question_id' => $question_id,
              'user_id' => $user->id,
              'color' => '#D3D3D3',
               ];
          }
          // $user->question_answers = $question_answers;
        }
      }

      $quiz_attempted_students = [];
      // dd($student_names);

      foreach ($student_names as $record) {
        // Latest exam result.
        $result = \App\QuizResult::where('quiz_id', $exam->id)->where('user_id', $record->id)->latest('created_at')->first();

        // dd($result);

        if ( $result ) {
          $correct_answer_questions = (array)json_decode($result->correct_answer_questions);
          $wrong_answer_questions = (array)json_decode($result->wrong_answer_questions);
          $not_answered_questions = (array)json_decode($result->not_answered_questions);
          $question_bgcolor = [];

          foreach ($questions_res as $key => $q) {
            // echo $q;
            if ( !in_array($q, $correct_answer_questions) && !in_array($q, $wrong_answer_questions) && !in_array($q, $not_answered_questions))
            {
              array_push( $not_answered_questions, $q);
            }

          }
          // dd( $not_answered_questions );
          $record->correct_answer_questions = $correct_answer_questions;

          $record->wrong_answer_questions = $wrong_answer_questions;

          $record->not_answered_questions = $not_answered_questions;
// dd($record);
          array_push( $quiz_attempted_students,$record );

        }

        // dd($quiz_attempted_students);
      }

      $payments_stats['title'] = getPhrase('overall_statistics');
      $graph_data = [];

      $graph_data['quiz_attempted_students'] = $quiz_attempted_students;

      $graph_data['students_data'] = $student_names;

      $graph_data['total_questions'] = $total_questions;

      $graph_data['questions_res'] = $questions_res;

      $graph_data['user_responses'] = $user_responses;
      $graph_data['exam'] = $exam;

      $questions_users = [];
      foreach ($questions_res as $question_id) {
        $questions_users[ $question_id ] = $student_names->pluck('id')->toArray();
      }
      $graph_data['questions_users'] = $questions_users;

      // dd($graph_data);

      return $graph_data;
    }

    public function changePopQuizInfo( Request $request, $lms_series_id, $content_id ) {

      $batches = $request->batches;
      $institute_id   = adminInstituteId();

      if ( ! empty( $batches ) ) {
      $batches_temp = [];
      foreach ($batches as $key => $value) {
        $batches_temp[] = [
          'lmsseries_id' => $lms_series_id,
          'lmscontent_id' => $content_id,
          'batch_id' => $value,
          'pop_quiz' => 'yes',
        ];
      }
    }
    \DB::table('lmsseries_data_batch_popquiz')->where('lmsseries_id', $lms_series_id)->where('lmscontent_id', $content_id)->delete();
    \DB::table('lmsseries_data_batch_popquiz')->insert( $batches_temp );

      if ( ! empty( $batches ) ) {
        foreach ($batches as $key => $value) {
          // Let us add to batch quizzes.
          $lmsseries_data = \DB::table('lmsseries_data')->where('lmsseries_id', $lms_series_id)->where('lmscontent_id', $content_id)->whereNotNull('quiz_id')->first();

          if ( $lmsseries_data ) {
            $assigned_record = \DB::table('batch_quizzes')
            ->where('batch_id', $value)
            ->where('institute_id', $institute_id)
            ->where('quiz_id', $lmsseries_data->quiz_id)
            ->first();
            if ( ! $assigned_record ) {
            \DB::table('batch_quizzes')->insert( [
                'batch_id' => $value,
                'institute_id' => $institute_id,
                'quiz_id' => $lmsseries_data->quiz_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
              ] );
            } else {
              /*
              \DB::table('batch_quizzes')->where('id', $assigned_record->id)->update([
                'updated_at' => date('Y-m-d H:i:s'),
              ]);
              */
            }
          }
        }
      }

      return json_encode( ['status' => 'success', 'message' => 'Quiz updated'] );
    }

    public function emsilSmsAlerts(Request $request) {
      if(!canDo('email_alerts') && !canDo('sms_alerts'))
      {
        prepareBlockUserMessage();
        return back();
      }

      if ( $request->isMethod('post') ) {
        $institute_id   = adminInstituteId();

        $sync_data = [];
        $selected_alerts = $request->batches;
        if ( ! empty( $selected_alerts ) ) {
          foreach ($selected_alerts as $alert_id => $batches) {
            if ( ! empty( $batches ) ) {
              if ( is_array( $batches ) ) {
                foreach ($batches as $batch_id) {
                  $sync_data[] = [
                    'alert_id' => $alert_id,
                    'batch_id' => $batch_id,
                    'institute_id' => $institute_id,
                  ];
                }
              } else {
                $sync_data[] = [
                  'alert_id' => $alert_id,
                  'batch_id' => $batches,
                  'institute_id' => $institute_id,
                ];
              }
            }
          }
        }

        \DB::table('alerts_enabled')->where('institute_id', $institute_id)->delete();
        if ( ! empty( $sync_data ) ) {
          \DB::table('alerts_enabled')->insert( $sync_data );
      }

        flash('success','alert_updated_successfully','success');
        return back();
      }

      $data['record']           = FALSE;
      $data['layout']           = getLayout();
      $data['active_class']     = 'alerts';
      $data['title']            = getPhrase('email_alerts');
      if(canDo('email_alerts') && canDo('sms_alerts')) {
        $data['title']            = 'Email & SMS Alert Settings';
      } elseif(canDo('sms_alerts')) {
        $data['title']            = 'SMS Alert Settings';
      }

      $view_name = getTheme().'::batches.email-sms-alerts';
        return view($view_name, $data);
    }

    public function smsAlerts(Request $request) {
      if(!canDo('sms_alerts'))
      {
        prepareBlockUserMessage();
        return back();
      }

      if ( $request->isMethod('post') ) {
        $institute_id   = adminInstituteId();

        $batches = \App\Batch::where('status', 'active')->where('institute_id', $institute_id)->update(['enable_sms_alerts' => 'no']);

        if ( ! empty( $request->batches ) ) {
          \App\Batch::where('status', 'active')->where('institute_id', $institute_id)->whereIn('id', $request->batches)->update(['enable_sms_alerts' => 'yes']);
        }

        flash('success','alert_updated_successfully','success');
        return back();
      }
      $data['record']           = FALSE;
      $data['layout']           = getLayout();
      $data['active_class']     = 'alerts';
      $data['title']            = 'SMS alerts';

      $view_name = getTheme().'::batches.sms-alerts';
        return view($view_name, $data);
    }

    public function batchReportsList() {
      if(!canDo('institute_batch_access'))
      {
      prepareBlockUserMessage();
      return back();
      }

      $data['active_class'] = 'batch_reports';
      $data['layout']       = getLayout();
      $data['title']        = getPhrase('reports');

      $view_name = getTheme().'::batches.reports-list';
      return view($view_name, $data);
    }

    public function getBatchReportsList() {

      $institute_id   = adminInstituteId();

      $records = [];

      if ( isFaculty() ) {
        $batches = Auth::user()->faculty_batches()->get()->pluck('id')->toArray();
        $records = \App\Batch::join('batch_quizzes AS bq', 'bq.batch_id', '=', 'batches.id')
      ->join('quizzes AS q', 'q.id', '=', 'bq.quiz_id')
      ->join('quizresults AS qr', 'qr.quiz_id', '=', 'bq.quiz_id')

      ->join('batch_students AS bs', function($join) {
        $join->on('bs.user_id', '=', 'qr.user_id');
        $join->on('bs.batch_id','=', 'batches.id');
      })

      ->select(['qr.created_at', 'batches.name', 'q.title', 'batches.id', 'q.slug'])
      //->where('q.institute_id', $institute_id)
      // ->where('q.record_updated_by', \Auth::id())
      ->whereIn('batches.id', $batches)
      //->groupBy('qr.id')
      // ->groupBy('bq.quiz_id')
      ->groupBy(\DB::raw('date(qr.created_at)')) // Added by ADI
      ->groupBy('batches.id') // Added by ADI
      ->groupBy('q.id') // Added by ADI
      ->orderBy('qr.created_at', 'desc');

        // $records->where('q.record_updated_by', \Auth::id())
        // ->whereIn('batches.id', $batches);
      } else {
        $records = \App\Batch::join('batch_quizzes AS bq', 'bq.batch_id', '=', 'batches.id')
      ->join('quizzes AS q', 'q.id', '=', 'bq.quiz_id')
      ->join('quizresults AS qr', 'qr.quiz_id', '=', 'bq.quiz_id')
      ->select(['qr.created_at', 'batches.name', 'bq.user_id', 'q.title', 'batches.id', 'q.slug'])
      ->where('q.institute_id', $institute_id)
      // ->groupBy('qr.id')
      // ->groupBy('bq.quiz_id')
      ->groupBy(\DB::raw('date(qr.created_at)')) // Added by ADI
      ->groupBy('batches.id') // Added by ADI
      ->groupBy('q.id') // Added by ADI
      ->orderBy('qr.created_at', 'desc');

      }
      // echo getEloquentSqlWithBindings( $records );
      return Datatables::of($records)
        ->addColumn('created_at', function($row){
             return date('d-m-Y', strtotime($row->created_at));
        })
        ->addColumn('name', function($row){
             return '<a href="'.URL_VIEW_BATCH_STUDENTS.$row->id.'">'.$row->name.'</a>';
        })
        ->addColumn('user_id', function($row){
             if( isFaculty() ) {
             $link_data = '<a href="'.url('batches/report/' . $row->id . '/' . $row->slug).'" class="btn btn-lg btn-success button">View</a>';
              return $link_data;
            } else {
             $user = \App\User::find( $row->user_id );
             if ( $user ) {
              return $user->name;
             } else {
              return '-';
              }
            }
        })
        ->addColumn('action', function ($row) {
          $link_data = '<a href="'.url('batches/report/' . $row->id . '/' . $row->slug).'" class="btn btn-lg btn-success button">View</a>';
            return $link_data;
        })
        ->removeColumn('id')
        ->removeColumn('slug')

        ->make();
    }
}
