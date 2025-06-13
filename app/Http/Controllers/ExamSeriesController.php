<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\Quiz;
use App\Subject;
use App\QuestionBank;
use App\QuizCategory;
use App\ExamSeries;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
// use Image;
use Intervention\Image\ImageManager as Image;
use ImageSettings;
use File;
use Input;

class ExamSeriesController extends Controller
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
      if(!canDo('exam_series_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('exam_series');
        $data['layout']   =  getLayout();

    	// return view('exams.examseries.list', $data);
       $view_name = getTheme().'::exams.examseries.list';
        return view($view_name, $data);
    }


    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {

      if(!canDo('exam_series_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

       $institute_id   = adminInstituteId();


        $records = array();

        if(checkRole(getUserGrade(3))){

            $records = ExamSeries::select(['institute_id','title', 'image', 'is_paid', 'cost', 'validity',  'total_exams','total_questions','slug', 'id', 'updated_at']);

        }else{

          if(shareData()){

               $records = ExamSeries::select(['institute_id','title', 'image', 'is_paid', 'cost', 'validity',  'total_exams','total_questions','slug', 'id', 'updated_at']);
          }
          else{

              $records = ExamSeries::select(['title', 'image', 'is_paid', 'cost', 'validity',  'total_exams','total_questions','slug', 'id', 'updated_at'])
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

                        $temp = '
                        <li><a href="'.route('examseries.courses', $records->slug).'"><i class="fa fa-file-o"></i>Update Course</a></li>
                        ';

        if(checkRole(getUserGrade(2))) {

          $institute_id   = adminInstituteId();


          if($records->institute_id  == $institute_id || checkRole(getUserGrade(2))){

            $temp.= '<li><a href="'.URL_EXAM_SERIES_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
          }

          elseif(!shareData() || checkRole(getUserGrade(2))){

            $link_data  = '-';
            return $link_data;

          }


      }

       if(checkRole(getUserGrade(2))) {

          $institute_id   = adminInstituteId();


          if($records->institute_id  == $institute_id || checkRole(getUserGrade(2))){
            $temp.= '<li><a href="'.URL_EXAM_SERIES_UPDATE_SERIES.$records->slug.'"><i class="fa fa-spinner"></i>'.getPhrase("add_quizzes").'</a></li>';
          }

          elseif(!shareData() || checkRole(getUserGrade(2))){

            $link_data  = '-';
            return $link_data;

          }


      }

        if(checkRole(getUserGrade(9))) {

          $institute_id   = adminInstituteId();


          if($records->institute_id  == $institute_id || checkRole(getUserGrade(1))){

          $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';

          }

          elseif(!shareData() || checkRole(getUserGrade(1))){

            $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
          }

           else{

            $link_data  = '-';
            return $link_data;
          }

        }

        $temp .='</ul></div>';

        $link_data = $link_data.$temp;



        return $link_data;
            })
        ->editColumn('title', function($records)
        {
         $institute_id   = adminInstituteId();
         if($records->institute_id  == $institute_id || checkRole(getUserGrade(3)))
          return '<a href="'.URL_EXAM_SERIES_UPDATE_SERIES.$records->slug.'">'.$records->title.'</a>';

        	return $records->title;
        })
        ->editColumn('cost', function($records)
        {
          return ($records->is_paid) ? $records->cost : '-';
        })

        ->editColumn('validity', function($records)
        {
          return ($records->is_paid) ? $records->validity : '-';
        })

        ->editColumn('image', function($records)
        {
          $image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
          if($records->image)
            $image_path = IMAGE_PATH_UPLOAD_SERIES.$records->image;
            return '<img src="'.$image_path.'" height="60" width="60"  />';
        })
        ->editColumn('is_paid', function($records)
        {
            return ($records->is_paid) ? '<span class="label label-primary">'.getPhrase('paid') .'</span>' : '<span class="label label-success">'.getPhrase('free').'</span>';
        })

          ->editColumn('institute_id', function($records){

            $institute  = getInstitute($records->institute_id);
            if($institute)
            return '<a href = "'.URL_INSTITUTE_DETAILS.$institute->id.'">'.ucwords($institute->institute_name).'</a>';

             return '-';
        })

        ->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('updated_at')
        ->rawColumns(['institute_id','title','image','is_paid','action'])
        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
      if(!canDo('exam_series_create'))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$data['record']         	= FALSE;
      $institute_id   = adminInstituteId();

      $data['categories']       = array_pluck(QuizCategory::where('institute_id',$institute_id)->get(), 'category', 'id');

    	$data['active_class']     = 'exams';
      $data['title']          = getPhrase('add_exam_series');
      $data['layout']   =  getLayout();

    	// return view('exams.examseries.add-edit', $data);
        $view_name = getTheme().'::exams.examseries.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
      if(!canDo('exam_series_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$record = ExamSeries::getRecordWithSlug($slug);

    	if($isValid = $this->isValidRecord($record))
    		return redirect($isValid);
    	// dd($record);
    	$data['record']       	  = $record;
    	$data['active_class']     = 'exams';
    	$data['settings']         = FALSE;
      $institute_id   = adminInstituteId();
      $data['categories']       = array_pluck(QuizCategory::where('institute_id',$institute_id)->get(), 'category', 'id');
    	$data['title']            = getPhrase('edit_series');
      $data['layout']   =  getLayout();

    	// return view('exams.examseries.add-edit', $data);
       $view_name = getTheme().'::exams.examseries.add-edit';
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
      if(!canDo('exam_series_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$record = ExamSeries::getRecordWithSlug($slug);
		 $rules = [
         'title'          	   => 'bail|required|max:40' ,

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
        $record->is_paid      = $request->is_paid;
        //$record->category_id			= $request->category_id;
        $record->validity			= -1;
        $record->cost				= 0;
        if($request->is_paid) {
        	$record->validity		= $request->validity;
        	$record->cost			= $request->cost;
    	}
        $record->total_exams		= $request->total_exams;
        $record->total_questions	= $request->total_questions;

        $record->short_description	= $request->short_description;
        $record->description    = $request->description;
        $record->start_date   = $request->start_date;
        $record->end_date		= $request->end_date;
        $record->record_updated_by 	= Auth::user()->id;
        $record->save();
        $file_name = 'image';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            $this->validate($request, $rules);
		    $examSettings = getExamSettings();
	        $path = $examSettings->seriesImagepath;
	        $this->deleteFile($record->image, $path);
            $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }
        flash('success','record_updated_successfully', 'success');
    	return redirect(URL_EXAM_SERIES);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
      // dd($request->all());
      if(!canDo('exam_series_create'))
      {
        prepareBlockUserMessage();
        return back();
      }

	    $rules = [
         'title'          	   => 'bail|required|max:40' ,
          ];

        $this->validate($request, $rules);
        $record = new ExamSeries();
      	$name  						=  $request->title;
    		$record->title 				= $name;
       	$record->slug 				= $record->makeSlug($name);
        $record->is_paid			= $request->is_paid;
        $record->validity			= -1;
        $record->cost				= 0;
        if($request->is_paid) {
        	$record->validity		= $request->validity;
        	$record->cost			= $request->cost;
    	}

        // $record->total_exams		= $request->total_exams;
        // $record->total_questions	= $request->total_questions;
        // $record->category_id      = $request->category_id;

        $record->short_description	= $request->short_description;
        $record->description		= $request->description;
        $record->start_date   = $request->start_date;
        $record->end_date   = $request->end_date;
        $record->record_updated_by 	= Auth::user()->id;
         $record->institute_id   = adminInstituteId();

        $record->save();
        $file_name = 'image';
        if ($request->hasFile($file_name))
        {

          $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
          $this->validate($request, $rules);
		      $examSettings = getExamSettings();
	        $path = $examSettings->seriesImagepath;
	        $this->deleteFile($record->image, $path);
          $record->image      = $this->processUpload($request, $record,$file_name);
          $record->save();
        }
        flash('success','record_added_successfully', 'success');
    	return redirect(URL_EXAM_SERIES);
    }

    public function deleteFile($record, $path, $is_array = FALSE)
    {
      if(env('DEMO_MODE')) {
        return '';
      }
        $files = array();
        $files[] = $path.$record;
        File::delete($files);
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
        return 'demo';
      }
         if ($request->hasFile($file_name)) {
          $examSettings = getExamSettings();

            $imageObject = new ImageSettings();

          $destinationPath            = $examSettings->seriesImagepath;
          $destinationPathThumb       = $examSettings->seriesThumbImagepath;

          $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();

          $request->file($file_name)->move($destinationPath, $fileName);

         //Save Normal Image with 300x300
          // Image::make($destinationPath.$fileName)->fit($examSettings->imageSize)->save($destinationPath.$fileName);
          $manager = new Image(new \Intervention\Image\Drivers\Gd\Driver());

          // Resize and save main image
          $image = $manager->read($destinationPath . $fileName);
          $image->cover($examSettings->imageSize, $examSettings->imageSize)
          ->save($destinationPath . $fileName);

          // Resize and save thumbnail
          $thumbnail = $manager->read($destinationPath . $fileName);
          $thumbnail->cover($imageObject->getThumbnailSize(), $imageObject->getThumbnailSize())
            ->save($destinationPathThumb . $fileName);
            //  Image::make($destinationPath.$fileName)->fit($imageObject->getThumbnailSize())->save($destinationPathThumb.$fileName);
        return $fileName;

        }
     }

    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
      if(!canDo('exam_series_delete'))
      {
        prepareBlockUserMessage();
        return back();
      }
      /**
       * Delete the questions associated with this quiz first
       * Delete the quiz
       * @var [type]
       */
        $record = ExamSeries::where('slug', $slug)->first();

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
    	return URL_EXAM_SERIES;
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
		$record = ExamSeries::getRecordWithSlug($slug);

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
}
