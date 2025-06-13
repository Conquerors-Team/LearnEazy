<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\LmsContent;
use App\LmsSeries;
use App\LmsNote;
use App\QuizCategory;
use App\StudentPaidContent;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
// use Image;
use Intervention\Image\ImageManager as Image;
use ImageSettings;
use File;
use Input;

class StudentPaidContentController extends Controller
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
      // if(!canDo('exam_series_access'))
      // {
      //   prepareBlockUserMessage();
      //   return back();
      // }

        $data['active_class']       = 'package';
        $data['title']              = getPhrase('student_paid_content');
        $data['layout']   =  getLayout();

    	// return view('exams.examseries.list', $data);
       $view_name = getTheme().'::package.studentpaidcontent.list';
        return view($view_name, $data);
    }

    protected  $examSettings;

    public function setExamSettings()
    {
        $this->examSettings = getExamSettings();
    }

    public function getExamSettings()
    {
        return $this->examSettings;
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {

      // if(!canDo('exam_series_access'))
      // {
      //   prepareBlockUserMessage();
      //   return back();
      // }

       $institute_id   = adminInstituteId();


        $records = array();

        if(checkRole(getUserGrade(3))){

            $records = StudentPaidContent::select(['institute_id','title', 'cost', 'duration', 'duration_type','slug', 'id', 'updated_at']);

        }else{

          if(shareData()){

               $records = StudentPaidContent::select(['institute_id','title', 'cost', 'duration', 'duration_type','slug', 'id', 'updated_at']);
          }
          else{

              $records = StudentPaidContent::select(['title', 'cost', 'duration', 'duration_type','slug', 'id', 'updated_at'])
              ->where('institute_id',$institute_id);
          }



        }

            $records->orderBy('updated_at', 'desc');


        return Datatables::of($records)
        ->addColumn('action', function ($records) {

          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">';





        $temp = '';

        $temp .= '
        <li><a href="'.route('studentpaidcontent.edit', $records->slug).'"><i class="fa fa-pencil"></i>Edit</a></li>
        ';

        $temp .= '
        <li><a href="'.route('studentpaidcontent.courses', $records->slug).'"><i class="fa fa-graduation-cap"></i>Update Course</a></li>
        ';

        $temp .= '
        <li><a href="'.route('studentpaidcontent.series', $records->id).'"><i class="fa fa-fw fa-list-ol"></i>Update LmsSeries</a></li>
        ';

        $temp .= '
        <li><a href="'.route('studentpaidcontent.notes', $records->id).'"><i class="fa fa-sticky-note"></i>Update LmsNotes</a></li>
        ';


        $temp .= '
        <li><a href="'.route('studentpaidcontent.exams', [$records->id, 'chapter']).'"><i class="fa fa-fw fa-desktop"></i>Update Chapter Tests</a></li>
        ';

        $temp .= '
        <li><a href="'.route('studentpaidcontent.exams', [$records->id,'subject']).'"><i class="fa fa-fw fa-desktop"></i>Update Subject Tests</a></li>
        ';

        $temp .= '
        <li><a href="'.route('studentpaidcontent.exams', [$records->id,'previousyear']).'"><i class="fa fa-fw fa-desktop"></i>Update Previousyear Tests</a></li>
        ';

        $temp .= '
        <li><a href="'.route('studentpaidcontent.exams', [$records->id,'grand']).'"><i class="fa fa-fw fa-desktop"></i>Update Grand Tests</a></li>
        ';

        $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';

        $temp .='</ul></div>';

        //$temp .='</ul></div>';

        $link_data = $link_data.$temp;



        return $link_data;
            })
        // ->editColumn('title', function($records)
        // {
        //  $institute_id   = adminInstituteId();
        //  if($records->institute_id  == $institute_id || checkRole(getUserGrade(3)))
        //   return '<a href="'.URL_EXAM_SERIES_UPDATE_SERIES.$records->slug.'">'.$records->title.'</a>';

        // 	return $records->title;
        // })
        ->editColumn('cost', function($records)
        {
          return $records->cost;
        })

        ->editColumn('duration', function($records)
        {
          return $records->duration;
        })

        ->editColumn('duration_type', function($records)
        {
          return $records->duration_type;
        })



        ->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('institute_id')
        ->removeColumn('updated_at')
        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
      // if(!canDo('exam_series_create'))
      // {
      //   prepareBlockUserMessage();
      //   return back();
      // }

    	$data['record']         	= FALSE;
      $institute_id   = adminInstituteId();

      $data['lms_contents']       = array_pluck(LmsContent::where('institute_id',$institute_id)->get(), 'title', 'id');
      $data['lms_series']       = array_pluck(LmsSeries::where('institute_id',$institute_id)->get(), 'title', 'id');
      $data['lms_notes']       = array_pluck(LmsNote::where('institute_id',$institute_id)->get(), 'title', 'id');
    	$data['active_class']     = 'package';
      $data['title']          = getPhrase('add_student_package_content');
      $data['layout']   =  getLayout();

    	// return view('exams.examseries.add-edit', $data);
        $view_name = getTheme().'::package.studentpaidcontent.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
      // if(!canDo('exam_series_edit'))
      // {
      //   prepareBlockUserMessage();
      //   return back();
      // }

    	$record = StudentPaidContent::getRecordWithSlug($slug);

    	if($isValid = $this->isValidRecord($record))
    		return redirect($isValid);
    	// dd($record);
    	$data['record']       	  = $record;
    	$data['active_class']     = 'package';
    	$data['settings']         = FALSE;
      $institute_id   = adminInstituteId();
      $data['lms_contents']       = array_pluck(LmsContent::where('institute_id',$institute_id)->get(), 'title', 'id');
      $data['lms_series']       = array_pluck(LmsSeries::where('institute_id',$institute_id)->get(), 'title', 'id');
      $data['lms_notes']       = array_pluck(LmsSeries::where('institute_id',$institute_id)->get(), 'title', 'id');
    	$data['title']            = getPhrase('edit_student_package_content');
      $data['layout']   =  getLayout();

    	// return view('exams.examseries.add-edit', $data);
       $view_name = getTheme().'::package.studentpaidcontent.add-edit';
        return view($view_name, $data);
    }

    /**
     * Update record based on slug and reuqest
     * @param  Request $request [Request Object]
     * @param  [type]  $slug    [Unique Slug]
     * @return void
     */
    public function update(Request $request, $slug)
    {
      // if(!canDo('exam_series_edit'))
      // {
      //   prepareBlockUserMessage();
      //   return back();
      // }

    	$record = StudentPaidContent::getRecordWithSlug($slug);
		 $rules = [
         'title'          	   => 'bail|required|max:40',
         'image'         => 'mimes:png,jpg,jpeg|max:2048'

            ];
         /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */
       $name = $request->title;
        if($name != $record->title)
            $record->slug = $record->makeSlug($name);

       //Validate the overall request
       $this->validate($request, $rules);
    	  $record->title 				= $name;
       	$record->slug 				= $record->makeSlug($name);
        $record->cost      = $request->cost;
        $record->duration		= $request->duration;
        $record->duration_type	= $request->duration_type;
        $record->status  = $request->status;
        //$record->display_type      = $request->display_type;
        $record->free_trail_days      = $request->free_trail_days;
        $record->is_paid      = $request->is_paid;
        $record->short_description      = $request->short_description;
        $record->save();

        $file_name = 'image';
         if ($request->hasFile($file_name))
        {

             $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
              $this->validate($request, $rules);

              $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }

        flash('success','record_updated_successfully', 'success');
    	return redirect(URL_PAID_CONTENT);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
      if(!canDo('exam_series_create'))
      {
        prepareBlockUserMessage();
        return back();
      }

	    $rules = [
         'title'          	   => 'bail|required|max:40' ,
         'image'                => 'bail|mimes:png,jpg,jpeg|max:2048'
          ];

        $this->validate($request, $rules);
        $record = new StudentPaidContent();
      	$name  						=  $request->title;
    		$record->title 				= $name;
       	$record->slug 				= $record->makeSlug($name);
        $record->cost      = $request->cost;
        $record->duration   = $request->duration;
        $record->duration_type  = $request->duration_type;
        $record->status  = $request->status;
        $record->institute_id   = adminInstituteId();

        // $record->display_type      = $request->display_type;
        $record->free_trail_days      = $request->free_trail_days;
        $record->is_paid      = $request->is_paid;
        $record->short_description      = $request->short_description;

        $record->save();


       $file_name = 'image';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            $this->validate($request, $rules);
        $this->setExamSettings();
            $examSettings = $this->getExamSettings();
          $path = $examSettings->courseImagepath;
          $this->deleteFile($record->image, $path);

              $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }

        flash('success','record_added_successfully', 'success');
    	return redirect(URL_PAID_CONTENT);
    }

    /**
     * This method process the image is being refferred
     * by getting the settings from ImageSettings Class
     * @param  Request $request   [Request object from user]
     * @param  [type]  $record    [The saved record which contains the ID]
     * @param  [type]  $file_name [The Name of the file which need to upload]
     * @return [type]             [description]
     */
    public function processUpload(Request $request, $record, $file_name)
     {
         if(env('DEMO_MODE')) {
        return ;
       }

         if ($request->hasFile($file_name)) {
          $examSettings = getExamSettings();

          $destinationPath      = $examSettings->courseImagepath;

          $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();

          $request->file($file_name)->move($destinationPath, $fileName);

         //Save Normal Image with 300x300
          // Image::make($destinationPath.$fileName)->fit($examSettings->imageSize)->save($destinationPath.$fileName);
          $manager = new Image(new \Intervention\Image\Drivers\Gd\Driver());

          $image = $manager->read($destinationPath . $fileName);
          $image->resize(height: $examSettings->imageSize, width: $examSettings->imageSize)->save($destinationPath . $fileName);
          return $fileName;
        }
     }

     public function deleteFile($record, $path, $is_array = FALSE)
    {
      if(env('DEMO_MODE')) {
        return;
      }
        $files = array();
        $files[] = $path.$record;
        File::delete($files);
    }


    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
      // if(!canDo('exam_series_delete'))
      // {
      //   prepareBlockUserMessage();
      //   return back();
      // }
      /**
       * Delete the questions associated with this quiz first
       * Delete the quiz
       * @var [type]
       */
        $record = StudentPaidContent::where('slug', $slug)->first();

      try{
        if(!env('DEMO_MODE')) {
          $record->delete();
        }
        $response['status'] = 1;
        $response['message'] = getPhrase('record_deleted_successfully');
      } catch ( \Illuminate\Database\QueryException $e) {
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
    	return URL_PAID_CONTENT;
    }


    /**
     * Returns the list of subjects based on the requested subject
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getExams(Request $request)
    {


    	$category_id = $request->category_id;
      $is_paid     = $request->series_type;

    	$exams = Quiz::where('category_id','=',$category_id)
            				->where('total_marks','!=','0')
                    ->where('is_paid',$is_paid)
            				->get();

    	return json_encode(array('exams'=>$exams));
    }

    /**
     * Updates the questions in a selected quiz
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function updateSeries($slug)
    {

       if(!checkRole(getUserGrade(2)))
       {
            prepareBlockUserMessage();
            return back();
        }

    	/**
    	 * Get the Quiz Id with the slug
    	 * Get the available questions from questionbank_quizzes table
    	 * Load view with this data
    	 */
		$record = StudentPaidContent::getRecordWithSlug($slug);

    	$data['record']         	= $record;
    	$data['active_class']       = 'exams';
        //$data['right_bar']          = FALSE;
        //$data['right_bar_path']     = 'exams.examseries.right-bar-update-questions';

        $data['settings']           = FALSE;
        $previous_records = array();
        if($record->total_exams > 0)
        {
            $quizzes = DB::table('examseries_data')
                            ->where('examseries_id', '=', $record->id)
                            ->get();

            foreach($quizzes as $quiz)
            {
                $temp = array();
                $temp['id'] = $quiz->quiz_id;
                $quiz_details = Quiz::where('id', '=', $quiz->quiz_id)->first();

                $temp['dueration'] = $quiz_details->dueration;
                $temp['total_marks'] = $quiz_details->total_marks;
                $temp['total_questions'] = $quiz_details->total_questions;
                $temp['title'] = $quiz_details->title;

                array_push($previous_records, $temp);
            }
            $settings['exams'] = $previous_records;
            $settings['total_questions'] = $record->total_questions;
        $data['settings']           = json_encode($settings);
        }


    	$data['exam_categories']       	= array_pluck(App\QuizCategory::all(),
    									'category', 'id');

    	$data['title']              = getPhrase('update_series_for').' '.$record->title;
      $data['layout']   =  getLayout();

    	// return view('exams.examseries.update-questions', $data);
       $view_name = getTheme().'::exams.examseries.update-questions';
        return view($view_name, $data);

    }

    public function storeSeries(Request $request, $slug)
    {

        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }

        $exam_series = ExamSeries::getRecordWithSlug($slug);
//dd( $request->all() );
        $series_id  = $exam_series->id;
        $institute_id   = adminInstituteId();
        //$quizzes  	= json_decode($request->saved_series);
        $quizzes    = $request->saved_series;
        $questions 	= 0;
        $exams 		= 0;
        $quizzes_to_update = array();
        foreach ($quizzes as $quiz_id => $value) {
            $temp = array();
            $record = \App\Quiz::find( $quiz_id );
            if ( $record ) {
              $temp['quiz_id'] = $record->id;
              $temp['examseries_id'] = $series_id;
              $temp['institute_id'] = $institute_id;
              array_push($quizzes_to_update, $temp);
              $questions += $record->total_questions;
            }
        }
        $exam_series->total_questions = $questions;
        $exam_series->total_exams = count($quizzes);
        if(!env('DEMO_MODE')) {
          //Clear all previous questions
          DB::table('examseries_data')->where('examseries_id', '=', $series_id)->where('institute_id', '=', $institute_id)->delete();
          //Insert New Questions
          DB::table('examseries_data')->insert($quizzes_to_update);
          $exam_series->save();
        }
        flash('success','record_updated_successfully', 'success');
        return redirect(URL_EXAM_SERIES);
    }

    /**
     * This method lists all the available exam series for students
     *
     * @return [type] [description]
     */
    public function listSeries()
    {
      if(checkRole(getUserGrade(2)))
      {
        return back();
      }

       $batches  = getStudentBatches();

      if(count($batches) == 0){

         flash('Ooops...!','you_are_not_assigned_to_any_batch_contact_your_admin','overlay');
         return back();
      }
        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('exam_series');
        $data['series']             = [];
        $user = Auth::user();

        $courses = getStudentClasses('courses');
        if(count($courses) == 0){
         flash('Ooops...!','courses_not_assigned_contact_your_admin','overlay');
         return back();
       }
        $data['series']             = ExamSeries::select('examseries.*')
                                      ->join('examseries_courses', 'examseries.id', '=', 'examseries_courses.exam_series_id')
                                      ->whereIn('course_id', $courses)
                                        ->paginate(getRecordsPerPage());

        $data['layout']             = getLayout();
          $data['user']             = $user;
       // return view('student.exams.exam-series-list', $data);

        $view_name = getTheme().'::student.exams.exam-series-list';
        return view($view_name, $data);


    }

    /**
     * This method displays all the details of selected exam series
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function viewItem($slug)
    {

        $record = ExamSeries::getRecordWithSlug($slug);

        if($isValid = $this->isValidRecord($record))
          return redirect($isValid);

        $data['active_class']       = 'exams';
        $data['pay_by']             = '';
        $data['content_record']     = FALSE;
        $data['title']              = $record->title;
        $data['item']               = $record;
         // $data['right_bar']          = TRUE;
          $data['right_bar_path']     = 'student.exams.exam-series-item-view-right-bar';
        $data['right_bar_data']     = array(
                                            'item' => $record,
                                            );
        $data['layout']              = getLayout();
       // return view('student.exams.series.series-view-item', $data);

         $view_name = getTheme().'::student.exams.series.series-view-item';
        return view($view_name, $data);
    }

    public function updateSeriesCourses( Request $request, $slug, $class = '') {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

      /**
      * Get the Quiz Id with the slug
      * Get the available questions from questionbank_quizzes table
      * Load view with this data
      */
      $record = ExamSeries::where('slug', $slug)->first();

      $data['active_class']       = 'lms';

      $institute_id   = adminInstituteId();

      if ( $request->isMethod('post') ) {
        $rules = [
          'class'               => 'bail|required' ,
          'courses' => 'required',
        ];
        $this->validate($request, $rules);
        $courses = $request->courses;

        $series = ExamSeries::where('slug', $slug)->first();

        $sync_data = [];
        foreach ($courses as $course_id) {
          $sync_data[] = [
            'course_id' => $course_id,
            'class_id' => $request->class,
          ];
        }
        $series->courses()->sync( $sync_data );

        flash('success','record_updated_successfully', 'success');
        return redirect(URL_EXAM_SERIES);
      }


      if(checkRole(getUserGrade(3))){
        $data['classes'] = \App\StudentClass::get()->pluck('name', 'id')->prepend('Please select', '');
      } elseif( shareData('share_classes') ) {
        $data['classes'] = \App\StudentClass::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get()->pluck('name', 'id')->prepend('Please select', '');
      } else {
        $data['classes'] = \App\StudentClass::where('institute_id', $institute_id)->get()->pluck('name', 'id')->prepend('Please select', '');
      }

      $data['courses'] = null;
      $data['selected_class'] = null;
      if ( ! empty( $class ) ) {
        $selected_class = \App\StudentClass::find( $class );
        $record->class = $class;
        if(checkRole(getUserGrade(3))){
          $data['courses'] = $selected_class->courses()->pluck('title', 'id');
        } elseif( shareData('share_courses') ) {
          $data['courses'] = $selected_class->courses()->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->pluck('title', 'id');
        } else {
          $data['courses'] = $selected_class->courses()->where('institute_id', $institute_id)->pluck('title', 'id');
        }
      }

      $data['title']              = getPhrase('update_series_for').' '.$record->title;
      $data['layout']   =  getLayout();

      $data['record']           = $record;

      $view_name = getTheme().'::exams.examseries.update-list-courses';
      return view($view_name, $data);
    }

    public function updateLmsSeries( Request $request, $slug ) {

      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }


      $record = StudentPaidContent::where('id', $slug)->first();

      $data['active_class']       = 'package';

      $institute_id   = adminInstituteId();

      if ( $request->isMethod('post') ) {

        $items   = json_decode($request->saved_series);

        $selected_series = [];
        foreach( $items as $item ) {
          $series = \App\LmsSeries::find($item->id);
          $series_data = [
            'student_paid_contents_id' => $record->id,
            'item_id' => $item->id,
            'item_type' => 'lmsseries',
          ];
          if ( $series ) {
            $series_data['subject_id'] = $series->subject_id;
            $series_data['chapter_id'] = $series->chapter_id;
            $series_data['topic_id'] = $series->topic_id;
          }
          $selected_series[] = $series_data;
        }

        // Let us delete lmsseries type only.
        \App\StudentPaidContentData::where('student_paid_contents_id', $record->id)->where('item_type', 'lmsseries')->delete();
        $record->lmsseries()->syncWithoutDetaching( $selected_series );

        $record->total_items = \App\StudentPaidContentData::where('student_paid_contents_id', $record->id)->count();
        $record->save();

        flash('success','record_updated_successfully', 'success');
        return redirect(URL_PAID_CONTENT);
      }


      // $subjects = $record->courses()->join('courses_subjects as cs', 'cs.course_id', '=', 'courses.id')->get()->pluck('subject_id')->toArray();
      // $lmsseries = \App\LmsSeries::where('institute_id', $institute_id);
      // if ( ! empty( $subjects ) ) {
      // 	//$lmsseries->whereIn('subject_id', $subjects);
      // }
      $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'package.studentpaidcontent.update-lmsseries.right-bar-update-lmslist';
        $institute_id   = adminInstituteId();
        $list                  = App\Subject::where('institute_id',$institute_id)->get();

           if(checkRole('institute')){
        if(shareData('share_subjects')){
          $list                 = App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
        }

      }

        $data['subjects']         = array_pluck($list,'subject_title', 'id');

        $data['settings']           = FALSE;
        $previous_records = array();
        if($record->total_items > 0)
        {
            $series = \App\StudentPaidContentData::
            select(['lmsseries.id', 'lmsseries.title', 'subjects.subject_title', 'chapters.chapter_name', 'topics.topic_name'])
            ->join('lmsseries', 'lmsseries.id', '=', 'student_paid_contents_data.item_id')
            ->join('subjects', 'subjects.id', '=', 'lmsseries.subject_id')
            ->join('chapters', 'chapters.id', '=', 'lmsseries.chapter_id')
            ->join('topics', 'topics.id', '=', 'lmsseries.topic_id')
            ->where('student_paid_contents_id', '=', $record->id)
            ->where('student_paid_contents_data.item_type', 'lmsseries')
            ->groupBy('student_paid_contents_data.item_id')
            ->get();
                            // dd($series);
            foreach($series as $r)
            {
                $temp = array();
                $temp['id']   = $r->id;
                $temp['title']     = $r->title;
                $temp['subject_title'] = $r->subject_title;
                $temp['chapter_name']      = $r->chapter_name;
                $temp['topic_name']      = $r->topic_name;

                array_push($previous_records, $temp);
            }
            $settings['contents'] = $previous_records;
            // dd($previous_records);
        $data['settings']           = json_encode($settings);
        }


      $data['exam_categories']        = array_pluck(App\QuizCategory::where('institute_id',$institute_id)->get(),
                      'category', 'id');
      // $datsa['lmsseries'] = $lmsseries->get()->pluck('title', 'id');

      $data['title']              = getPhrase('update_series_for').' '.$record->title;
      $data['layout']   =  getLayout();

      $data['record']           = $record;

      $view_name = getTheme().'::package.studentpaidcontent.update-lmsseries.update-list-lms-series';
      return view($view_name, $data);
    }

    public function updateLmsNotes( Request $request, $slug ) {

      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

      /**
      * Get the Quiz Id with the slug
      * Get the available questions from questionbank_quizzes table
      * Load view with this data
      */
      $record = StudentPaidContent::where('id', $slug)->first();

      $data['active_class']       = 'package';

      $institute_id   = adminInstituteId();

      if ( $request->isMethod('post') ) {
// dd($request->all());
        $items   = json_decode($request->saved_series);
        $selected_series = [];
        foreach( $items as $notes_id ) {
        	$series = \App\LmsNote::find($notes_id->id);
          $series_data = [
            'student_paid_contents_id' => $record->id,
            'item_id' => $notes_id->id,
            'item_type' => 'lmsnotes',
          ];
          if ( $series ) {
            $series_data['subject_id'] = $series->subject_id;
            $series_data['chapter_id'] = $series->chapter_id;
            $series_data['topic_id'] = $series->topic_id;
          }

          $selected_series[] = $series_data;
        }
        // Let us delete lmsnotes type only.
        \App\StudentPaidContentData::where('student_paid_contents_id', $record->id)->where('item_type', 'lmsnotes')->delete();
        $record->lmsnotes()->syncWithoutDetaching( $selected_series );

        $record->total_items = \App\StudentPaidContentData::where('student_paid_contents_id', $record->id)->count();
        $record->save();

        flash('success','record_updated_successfully', 'success');
        return redirect(URL_PAID_CONTENT);
      }
      $data['settings']         = FALSE;
      $institute_id   = adminInstituteId();

       if(shareData('share_subjects')){

        $data['categories']         = array_pluck(App\LmsCategory::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get(),'category', 'id');
        $data['subjects'] = \App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get()->pluck('subject_title', 'id')->toArray();

        $data['chapters']   = \App\Chapter::where('status', 'Active')->where('subject_id', $record->subject_id)->get()->pluck('chapter_name', 'id')->prepend(getPhrase('select'), '');
        $data['topics'] = \App\Topic::where('subject_id', $record->subject_id)->get()->map(function ($entry, $group) {
              $topic_name = $entry->topic_name;
              if ( $entry->parent_id > 0 ) {
                $parenttopic = \App\Topic::find( $entry->parent_id );
                if ( $parenttopic ) {
                  $topic_name = $parenttopic->topic_name . ' => ' . $topic_name;
                }
              }
              $item = ['id' => $entry->id, 'topic_name' => $topic_name];
              return $item;
          })->pluck('topic_name', 'id')->prepend(getPhrase('select'), '');
      }
      else{

        $data['categories']         = array_pluck(App\LmsCategory::where('institute_id',$institute_id),'category', 'id');
        $data['subjects'] = \App\Subject::where('institute_id', adminInstituteId())->get()->pluck('subject_title', 'id')->toArray();

        $data['chapters']   = \App\Chapter::where('status', 'Active')->where('subject_id', $record->subject_id)->get()->pluck('chapter_name', 'id')->prepend(getPhrase('select'), '');
        $data['topics'] = \App\Topic::where('subject_id', $record->subject_id)->get()->map(function ($entry, $group) {
              $topic_name = $entry->topic_name;
              if ( $entry->parent_id > 0 ) {
                $parenttopic = \App\Topic::find( $entry->parent_id );
                if ( $parenttopic ) {
                  $topic_name = $parenttopic->topic_name . ' => ' . $topic_name;
                }
              }
              $item = ['id' => $entry->id, 'topic_name' => $topic_name];
              return $item;
          })->pluck('topic_name', 'id')->prepend(getPhrase('select'), '');
      }
      $data['title']            = getPhrase('edit_notes');

      $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'package.studentpaidcontent.update-notes.right-bar-update-notes';

      $subjects = $record->courses()->join('courses_subjects as cs', 'cs.course_id', '=', 'courses.id')->get()->pluck('subject_id')->toArray();
      $lmsnotes = \App\LmsNote::where('institute_id', $institute_id);
      if ( ! empty( $subjects ) ) {
      	// $lmsnotes->whereIn('subject_id', $subjects);
      }
      $previous_records = array();
        if($record->total_items > 0)
        {
            $series = \App\StudentPaidContentData::
            select(['lms_notes.id', 'lms_notes.title', 'subjects.subject_title', 'chapters.chapter_name', 'topics.topic_name','lms_notes.content_type'])
            ->join('lms_notes', 'lms_notes.id', '=', 'student_paid_contents_data.item_id')
            ->join('subjects', 'subjects.id', '=', 'lms_notes.subject_id')
            ->join('chapters', 'chapters.id', '=', 'lms_notes.chapter_id')
            ->join('topics', 'topics.id', '=', 'lms_notes.topic_id')
            ->where('student_paid_contents_id', '=', $record->id)
            ->where('student_paid_contents_data.item_type', 'lmsnotes')
            ->groupBy('student_paid_contents_data.item_id')
            ->get();
                            // dd($series);
            foreach($series as $r)
            {
                $temp = array();
                $temp['id']   = $r->id;
                $temp['title']     = $r->title;
                $temp['subject_title'] = $r->subject_title;
                $temp['chapter_name']      = $r->chapter_name;
                $temp['topic_name']      = $r->topic_name;

                array_push($previous_records, $temp);
            }
            $settings['contents'] = $previous_records;
            // dd($previous_records);
        $data['settings']           = json_encode($settings);
        }
      $data['lmsnotes'] = $lmsnotes->get()->pluck('title', 'id')->prepend('Please select', '');

      $data['title']              = getPhrase('update_notes_for').' '.$record->title;
      $data['layout']   =  getLayout();

      $data['record']           = $record;

      $view_name = getTheme().'::package.studentpaidcontent.update-notes.update-list-lms-notes';
      return view($view_name, $data);
    }

    public function updateCourses( Request $request, $slug ) {

      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
      /**
      * Get the Quiz Id with the slug
      * Get the available questions from questionbank_quizzes table
      * Load view with this data
      */
      $record = StudentPaidContent::where('slug', $slug)->first();

      $data['active_class']       = 'package';

      $institute_id   = adminInstituteId();

      if ( $request->isMethod('post') ) {
        $record->courses()->sync( array_filter((array)$request->input('courses')) );
        flash('success','record_updated_successfully', 'success');
        return redirect(URL_PAID_CONTENT);
      }

      $courses = \App\Course::where('institute_id', $institute_id);

      $data['courses'] = $courses->get()->pluck('title', 'id');

      $data['title']              = getPhrase('update_courses_for').' '.$record->title;
      $data['layout']   =  getLayout();

      $data['record']           = $record;

      $view_name = getTheme().'::package.studentpaidcontent.update-paidcontent-courses';
      return view($view_name, $data);
    }

    public function updateExams( Request $request, $slug, $type = '' ) {

      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
      $record = StudentPaidContent::where('id', $slug)->first();

      $data['active_class']       = 'package';

      $institute_id   = adminInstituteId();

      if ( $request->isMethod('post') ) {
        // dd($request->all());
        $items   = json_decode($request->saved_series);
        $selected_exams = [];
        $item_type = 'exams';
        foreach( $items as $exam_id ) {

          if( $type == 'chapter') {
              $selected_exams[] = [
                'student_paid_contents_id' => $record->id,
                'item_id' => $exam_id->id,
                'item_type' => 'chapter-exams',
              ];
              $item_type = 'chapter-exams';
            }
            if( $type == 'subject') {
              $selected_exams[] = [
                'student_paid_contents_id' => $record->id,
                'item_id' => $exam_id->id,
                'item_type' => 'subject-exams',
              ];
              $item_type = 'subject-exams';
            }
            if( $type == 'previousyear') {
                $selected_exams[] = [
                  'student_paid_contents_id' => $record->id,
                  'item_id' => $exam_id->id,
                  'item_type' => 'previousyear-exams',
                ];
                $item_type = 'previousyear-exams';
            }
            if( $type == 'grand') {
              $selected_exams[] = [
                'student_paid_contents_id' => $record->id,
                'item_id' => $exam_id->id,
                'item_type' => 'grand-exams',
              ];
              $item_type = 'grand-exams';
            }
        }
        // Let us delete lmsnotes type only.
        \App\StudentPaidContentData::where('student_paid_contents_id', $record->id)->where('item_type', $item_type)->delete();
        $record->exams()->syncWithoutDetaching( $selected_exams );

        $record->total_items = \App\StudentPaidContentData::where('student_paid_contents_id', $record->id)->count();
        $record->save();

        flash('success','record_updated_successfully', 'success');
        return redirect(URL_PAID_CONTENT);
      }

        $data['settings']           = FALSE;
        $previous_records = array();
        if($record->total_items > 0)
        {
            $series = \App\StudentPaidContentData::
            select(['quizzes.id', 'quizzes.title', 'subjects.subject_title', 'chapters.chapter_name'])
             ->join('quizzes', 'quizzes.id', '=', 'student_paid_contents_data.item_id')
            ->join('subjects', 'subjects.id', '=', 'quizzes.subject_id')
            ->join('chapters', 'chapters.id', '=', 'quizzes.chapter_id')
            ->where('student_paid_contents_id', '=', $record->id);

            if( $type == 'chapter')
            {
              $series = $series->where('item_type', 'chapter-exams');
            }
            if( $type == 'subject')
            {
              $series = $series->where('item_type', 'subject-exams');
            }
            if( $type == 'previousyear')
            {
              $series = \App\StudentPaidContentData::
            select(['quizzes.id', 'quizzes.title'])
             ->join('quizzes', 'quizzes.id', '=', 'student_paid_contents_data.item_id')
            ->where('student_paid_contents_id', '=', $record->id);
              $series = $series->where('item_type', 'previousyear-exams');
            }
            if( $type == 'grand')
            {
              $series = \App\StudentPaidContentData::
            select(['quizzes.id', 'quizzes.title'])
             ->join('quizzes', 'quizzes.id', '=', 'student_paid_contents_data.item_id')
            ->where('student_paid_contents_id', '=', $record->id);
              $series = $series->where('item_type', 'grand-exams');
            }
                            // dd($series);
            foreach($series->get() as $r)
            {
                $temp = array();
                $temp['id']   = $r->id;
                $temp['title']     = $r->title;
                $temp['subject_title'] = $r->subject_title;
                $temp['chapter_name']      = $r->chapter_name;
                $temp['topic_name']      = $r->topic_name;

                array_push($previous_records, $temp);
            }
            $settings['contents'] = $previous_records;
            // dd($previous_records);
        $data['settings']           = json_encode($settings);
        }

      $list = App\Subject::where('institute_id',$institute_id)->get();

           if(checkRole('institute')){
        if(shareData('share_subjects')){
          $list = App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
        }

      }

        $data['subjects'] = array_pluck($list,'subject_title', 'id');

      // $data['exams'] = $exams->get()->pluck('title', 'id')->prepend('Please select', '');

      $data['title']              = getPhrase('update_exams_for').' '.$record->title;
      $data['layout']   =  getLayout();

      $data['record']           = $record;

      if ( $type == 'chapter') {

        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'package.studentpaidcontent.update-chapter-tests.right-bar-update-chapter-tests';

      $view_name = getTheme().'::package.studentpaidcontent.update-chapter-tests.update-paidcontent-exams';
      return view($view_name, $data);
    }

    if ( $type == 'subject') {

        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'package.studentpaidcontent.update-subject-tests.right-bar-update-subject-tests';

      $view_name = getTheme().'::package.studentpaidcontent.update-subject-tests.update-paidcontent-exams';
      return view($view_name, $data);
    }
    if ( $type == 'previousyear') {

        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'package.studentpaidcontent.update-previousyear-tests.right-bar-update-lmslist';

      $view_name = getTheme().'::package.studentpaidcontent.update-previousyear-tests.update-paidcontent-exams';
      return view($view_name, $data);
    }
    if ( $type == 'grand') {

        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'package.studentpaidcontent.update-grand-tests.right-bar-update-lmslist';

      $view_name = getTheme().'::package.studentpaidcontent.update-grand-tests.update-paidcontent-exams';
      return view($view_name, $data);
    }

    }

    /**
     * Returns the list of subjects based on the requested subject
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getLmsSeries(Request $request)
    {


      $subject_id   = $request->subject_id;
      $chapter_id   = $request->chapter_id;
      $topic_id   = $request->topic_id;
      $sub_topic_id   = $request->sub_topic_id;

      $items      = App\LmsSeries::select(['lmsseries.id', 'lmsseries.title', 'subjects.subject_title', 'chapters.chapter_name', 'topics.topic_name'])
      ->join('subjects', 'subjects.id', '=', 'lmsseries.subject_id')
      ->join('chapters', 'chapters.id', '=', 'lmsseries.chapter_id')
      ->join('topics', 'topics.id', '=', 'lmsseries.topic_id');

      if ( ! empty( $subject_id ) ) {
        $items->where('lmsseries.subject_id', $subject_id);
      }
      if ( ! empty( $chapter_id ) ) {
        $items->where('lmsseries.chapter_id', $chapter_id);
      }
      if ( ! empty( $topic_id ) ) {
        $items->where('lmsseries.topic_id', $topic_id);
      }
      if ( ! empty( $sub_topic_id ) ) {
        $items->where('lmsseries.sub_topic_id', $sub_topic_id);
      }
      return json_encode(array('items' => $items->get()));
    }

    public function getLmsNotes(Request $request)
    {
      // dd("fghjk");

      $subject_id   = $request->subject_id;
      $chapter_id   = $request->chapter_id;
      $topic_id   = $request->topic_id;
      $sub_topic_id   = $request->sub_topic_id;

      $items      = App\LmsNote::select(['lms_notes.id', 'lms_notes.title', 'subjects.subject_title', 'chapters.chapter_name', 'topics.topic_name','lms_notes.content_type'])
      ->join('subjects', 'subjects.id', '=', 'lms_notes.subject_id')
      ->join('chapters', 'chapters.id', '=', 'lms_notes.chapter_id')
      ->join('topics', 'topics.id', '=', 'lms_notes.topic_id');

      if ( ! empty( $subject_id ) ) {
        $items->where('lms_notes.subject_id', $subject_id);
      }
      if ( ! empty( $chapter_id ) ) {
        $items->where('lms_notes.chapter_id', $chapter_id);
      }
      if ( ! empty( $topic_id ) ) {
        $items->where('lms_notes.topic_id', $topic_id);
      }
      if ( ! empty( $sub_topic_id ) ) {
        $items->where('lms_notes.sub_topic_id', $sub_topic_id);
      }
      return json_encode(array('items' => $items->get()));
    }

    public function getChapterTests(Request $request)
    {
      // dd("fghjk");

      $subject_id   = $request->subject_id;
      $chapter_id   = $request->chapter_id;
      // $topic_id   = $request->topic_id;
      // $sub_topic_id   = $request->sub_topic_id;
      // dd(App\Quiz::where())
      $items      = App\Quiz::select(['quizzes.id', 'quizzes.title', 'subjects.subject_title', 'chapters.chapter_name','quizzes.exam_type'])
      ->join('subjects', 'subjects.id', '=', 'quizzes.subject_id')
      ->join('chapters', 'chapters.id', '=', 'quizzes.chapter_id')
      ->where('quizzes.display_type','chapter');

      if ( ! empty( $subject_id ) ) {
        $items->where('quizzes.subject_id', $subject_id);
      }
      if ( ! empty( $chapter_id ) ) {
        $items->where('quizzes.chapter_id', $chapter_id);
      }
      // dd($items->get());
      // echo getEloquentSqlWithBindings( $items );
      // $items = $items->where('display_type','chapter');
      return json_encode(array('items' => $items->get()));
    }

    public function getSubjectTests(Request $request)
    {
      // dd("fghjk");

      $subject_id   = $request->subject_id;
      $chapter_id   = $request->chapter_id;
      $topic_id   = $request->topic_id;
      $sub_topic_id   = $request->sub_topic_id;

      $items      = App\Quiz::select(['quizzes.id', 'quizzes.title', 'subjects.subject_title', 'chapters.chapter_name','quizzes.exam_type'])
      ->join('subjects', 'subjects.id', '=', 'quizzes.subject_id')
      ->join('chapters', 'chapters.id', '=', 'quizzes.chapter_id')
      ->where('quizzes.display_type','subject');

      if ( ! empty( $subject_id ) ) {
        $items->where('quizzes.subject_id', $subject_id);
      }
      if ( ! empty( $chapter_id ) ) {
        $items->where('quizzes.chapter_id', $chapter_id);
      }
      return json_encode(array('items' => $items->get()));
    }

    public function getPreviousYearTests(Request $request)
    {
      // dd($request->subject_id);

      $year   = $request->subject_id;
      $comptetitive_type   = $request->chapter_id;
      $topic_id   = $request->topic_id;
      $sub_topic_id   = $request->sub_topic_id;

      $items = App\Quiz::select(['quizzes.id', 'quizzes.title'])
      ->where('quizzes.display_type','previousyear');

      if ( ! empty( $year ) ) {
        $items->where('quizzes.year', $year);
      }
      if ( ! empty( $comptetitive_type ) ) {
        $items->where('quizzes.competitive_exam_type_id', $comptetitive_type);
      }
      return json_encode(array('items' => $items->get()));
    }

    public function getGrandTests(Request $request)
    {
      // dd("fghjk");

      $subject_id   = $request->subject_id;
      $comptetitive_type   = $request->chapter_id;
      $topic_id   = $request->topic_id;
      $sub_topic_id   = $request->sub_topic_id;

      $items = App\Quiz::select(['quizzes.id', 'quizzes.title'])
      ->where('quizzes.display_type','previousyear');

      // if ( ! empty( $subject_id ) ) {
      //   $items->where('quizzes.year', $subject_id);
      // }
      if ( ! empty( $comptetitive_type ) ) {
        $items->where('quizzes.competitive_exam_type_id', $comptetitive_type);
      }
      return json_encode(array('items' => $items->get()));
    }
}
