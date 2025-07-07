<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \App;
use App\Subject;
use App\LmsSeries;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
// use Image;
use Intervention\Image\ImageManager as Image;
use ImageSettings;
use File;
use Input;
class LmsSeriesController extends Controller
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
      if(!canDo('lms_series_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $data['active_class']       = 'lms';
        $data['title']              = 'LMS'.' '.getPhrase('series');
        $data['layout']   =  getLayout();

    	// return view('lms.lmsseries.list', $data);
// dd($data);
        $view_name = getTheme().'::lms.lmsseries.list';
        return view($view_name, $data);
    }


    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {

      if(!canDo('lms_series_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $records = array();

        $institute_id   = adminInstituteId();

        if(checkRole(getUserGrade(3))){
              $records = LmsSeries::select(['lmsseries.institute_id','lmsseries.title', 'lmsseries.image', 'lmsseries.is_paid', 'lmsseries.cost', 'lmsseries.validity',  'lmsseries.total_items','lmsseries.show_in_front','lmsseries.slug', 'lmsseries.id', 'lmsseries.updated_at', 'lmsseries.subject_id', 'lmsseries.chapter_id', 'lmsseries.topic_id', 'lmsseries.sub_topic_id']);
              if ( ! empty( request('institute') ) ) {
              $records->where('lmsseries.institute_id', request('institute'));
            }
         }
         else{

            if(shareData('share_lms_series')){

               $records = LmsSeries::select(['lmsseries.institute_id','lmsseries.title', 'lmsseries.image', 'lmsseries.is_paid', 'lmsseries.cost', 'lmsseries.validity',  'lmsseries.total_items','lmsseries.show_in_front','lmsseries.slug', 'lmsseries.id', 'lmsseries.updated_at', 'lmsseries.subject_id', 'lmsseries.chapter_id', 'lmsseries.topic_id', 'lmsseries.sub_topic_id']);
                // ->whereIn('lmsseries.institute_id', [$institute_id, OWNER_INSTITUTE_ID]);
               if ( ! empty( request('institute') ) ) {
                $records->where('lmsseries.institute_id', request('institute'));
              } else {
                $records->whereIn('lmsseries.institute_id', [$institute_id, OWNER_INSTITUTE_ID]);
              }
            }
            else{
                  $records = LmsSeries::select(['lmsseries.title', 'lmsseries.image', 'lmsseries.is_paid', 'lmsseries.cost', 'lmsseries.validity',  'lmsseries.total_items','lmsseries.show_in_front','lmsseries.slug', 'lmsseries.id', 'lmsseries.updated_at', 'lmsseries.subject_id', 'lmsseries.chapter_id', 'lmsseries.topic_id', 'lmsseries.sub_topic_id'])
                  ->where('lmsseries.institute_id',$institute_id);

            }

         }



        $records->orderBy('updated_at', 'desc');

        //$content_type = request('content_type');
        $subject_id = request('subject');
        $chapter_id = request('chapter');
        $topic_id = request('topic');
        $sub_topic_id = request('sub_topic');
        $callfrom = request('callfrom');

        if ( ! empty( $content_type ) ) {
          //$records->where('lmscontents.content_type', $content_type);
        }
        if ( ! empty( $subject_id ) ) {
          $records->where('lmsseries.subject_id', $subject_id);
        }
        if ( ! empty( $chapter_id ) ) {
          $records->where('lmsseries.chapter_id', $chapter_id);
        }
        if ( ! empty( $topic_id ) ) {
          $records->where('lmsseries.topic_id', $topic_id);
        }
        if ( ! empty( $sub_topic_id ) ) {
          $records->where('lmsseries.sub_topic_id', $sub_topic_id);
        }

        if(checkRole(getUserGrade(10))) { // Faculty
          $faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
          $records->whereIn('lmsseries.subject_id', $faculty_subjects);
        }
// dd(getEloquentSqlWithBindings( $records ));
        $final_table = Datatables::of($records)
        ->addColumn('action', function ($records) use( $callfrom ) {

          $institute_id   = adminInstituteId();
          if ( $callfrom == 'batch') {
            $batch_id = request('batch_id');
            $assigned = \App\BatchLms::where('lms_series_id', $records->id)->where('institute_id', $institute_id)->where('batch_id', $batch_id)->first();
            $selected = '';
            if ( $assigned ) {
              $selected = ' checked';
            }
            return '<input type="checkbox" name="lmsseries['.$records->id.']" value="'.$records->id.'" style="display:block;"'.$selected.'>';
          } else {
          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">';

        $temp = '<li><a href="'.URL_STUDENT_LMS_SERIES_VIEW.$records->slug.'" target="_blank"><i class="fa fa-eye"></i>'.getPhrase("preview").'</a></li>

        ';

        if ( isFaculty() ) {
          if($records->record_updated_by  == \Auth::id() ) {
            $temp.= '<li><a href="'.URL_LMS_SERIES_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
          } else {
            $temp .='';
          }
        } else {
          if($records->institute_id  == $institute_id){
            $temp.= '<li><a href="'.URL_LMS_SERIES_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
          } else {
            $temp .='';
          }
        }


       if(checkRole(getUserGrade(2))) {
          $institute_id   = adminInstituteId();
          if($records->institute_id  == $institute_id ){
            $temp.= '<li><a href="'.URL_LMS_SERIES_UPDATE_SERIES.$records->slug.'"><i class="fa fa-spinner"></i>'.getPhrase("update").'</a></li>';
            $temp.= '<li><a href="'.URL_LMS_SERIES_UPDATE_SERIES_EXAMS.$records->slug.'"><i class="fa fa-file-o"></i>Update Exam/Order</a></li>';
            $temp.= '<li><a href="'.URL_LMS_SERIES_UPDATE_SERIES_COURSES.$records->slug.'"><i class="fa fa-file-o"></i>Update Course</a></li>';
          }
          elseif(!shareData('share_lms_series') || checkRole(getUserGrade(2))){
            // $link_data  = '-';
            return $link_data . $temp;
          }
      }

      if(checkRole(getUserGrade(2))) {
        $institute_id   = adminInstituteId();
        if ( isFaculty() ) {
          if ( $records->record_updated_by  == \Auth::id() ) {
            $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
          }
        } else {
          if($records->institute_id  == $institute_id ){
          $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
          }
        }
      }

        $temp .='</ul></div>';

        $link_data = $link_data.$temp;



        return $link_data;
        }
        });


        $final_table = $final_table->editColumn('title', function($records)
        {
           $institute_id   = adminInstituteId();
         if($records->institute_id  == $institute_id || checkRole(getUserGrade(3)))
          return '<a href="'.URL_LMS_SERIES_UPDATE_SERIES.$records->slug.'">'.$records->title.'</a>';

          return $records->title;
        })
        ->editColumn('image', function($records)
        {
          $image_path = IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings');
          if($records->image)
            $image_path = IMAGE_PATH_UPLOAD_LMS_SERIES.$records->image;

            return '<img src="'.$image_path.'" height="30" width="50"  />';
        })
        ->editColumn('cost', function($records)
        {
           return ($records->is_paid) ? $records->cost : '-';
        })
        ->editColumn('validity', function($records)
        {
           return ($records->is_paid) ? $records->validity : '-';
        })
        ->editColumn('is_paid', function($records)
        {
            return ($records->is_paid) ? '<span class="label label-primary">'.getPhrase('paid') .'</span>' : '<span class="label label-success">'.getPhrase('free').'</span>';
        })

         ->editColumn('show_in_front', function($row)
        {
            $str = '';
            if ( $row->subject ) {
              $str .= 'Subject: ' . $row->subject->subject_title;
            }
            if ( $row->chapter ) {
              $str .= '<br>Chapter: ' . $row->chapter->chapter_name;
            }
            if ( $row->topic ) {
              $str .= '<br>Topic: ' . $row->topic->topic_name;
            }
            if ( $row->sub_topic ) {
              $str .= '<br>Sub topic: ' . $row->sub_topic->topic_name;
            }
            return $str;
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

        // ->removeColumn('is_paid')
        // ->removeColumn('cost')
        // ->removeColumn('validity')
        ->removeColumn('subject_id')

        ->removeColumn('chapter_id')
        ->removeColumn('topic_id')
        ->removeColumn('sub_topic_id')
        ->rawColumns(['institute_id','title','image','is_paid','action','show_in_front'])

        ->make();

        // dd($records->all());

        return $final_table;
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
      if(!canDo('lms_series_create'))
      {
        prepareBlockUserMessage();
        return back();
      }
    	$data['record']         	= FALSE;
    	$data['active_class']       = 'lms';
      $data['chapters']   = [];
      $data['topics'] = [];
      if(shareData() || checkRole(getUserGrade(3))) {
        $data['categories'] = App\LmsCategory::all()->pluck('category', 'id')->toArray();
      } else {
        $institute_id = adminInstituteId();
        $data['categories'] = App\LmsCategory::where('institute_id', $institute_id)->get()->pluck('category', 'id')->toArray();
      }


      	$data['title']              = getPhrase('add_series');
        $data['layout']   =  getLayout();

    	// return view('lms.lmsseries.add-edit', $data);
       $view_name = getTheme().'::lms.lmsseries.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
      if(!canDo( 'lms_series_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$record = LmsSeries::getRecordWithSlug($slug);

    	if($isValid = $this->isValidRecord($record))
    		return redirect($isValid);
    	$data['record']       	  = $record;
    	$data['active_class']     = 'lms';
    	$data['settings']         = FALSE;
    	 if (shareData() || checkRole(getUserGrade(3))) {
    $data['categories'] = App\LmsCategory::all()->pluck('category', 'id')->toArray();
} else {
    $institute_id = adminInstituteId();
    $data['categories'] = App\LmsCategory::where('institute_id', $institute_id)
        ->pluck('category', 'id')
        ->toArray();
}

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

    	$data['title']            = getPhrase('edit_series');
      $data['layout']   =  getLayout();

    	// return view('lms.lmsseries.add-edit', $data);
         $view_name = getTheme().'::lms.lmsseries.add-edit';
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
      if(!canDo('lms_series_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }



    	$record = LmsSeries::getRecordWithSlug($slug);
		 $rules = [
        'title'      => 'bail|required',
        'subject_id' => 'required',
         'chapter_id' => 'required',
         'topic_id' => 'required',
      ];
         /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */
       $name = $request->title;
        if($name != $record->title)
            $record->slug = $record->makeSlug($name, TRUE);

       //Validate the overall request
       $this->validate($request, $rules);

        if($request->has('show_in_front')){

          if($request->is_paid == 1 && $request->show_in_front == 1){

            flash('Ooops...!','home_page_series_must_be_free_type_series','overlay');
            return back();
          }
          else{

            $record->show_in_front   = $request->show_in_front;
          }

        }

    	$record->title 				= $name;

        $record->is_paid			= $request->is_paid;
        // $record->lms_category_id			= $request->lms_category_id;
        $record->subject_id      = $request->subject_id;

        $record->chapter_id      = $request->chapter_id;
        $topic_id = null;
        if ( ! empty( $request->topic_id ) ) {
          $topic_id = $request->topic_id;
        }
        $sub_topic_id = null;
        if ( ! empty( $topic_id ) ) {
          $topic = \App\Topic::find( $topic_id );
          if ( $topic && $topic->parent_id > 0 ) {
            $sub_topic_id = $topic->id;
            $topic_id = $topic->parent_id;
          }
        }
        $record->topic_id  = $topic_id;
        $record->sub_topic_id  = $sub_topic_id;

        if ( ! empty( $request->quiz_id ) ) {
          $record->quiz_id      = $request->quiz_id;
        }

        $record->validity			= -1;
        $record->cost				= 0;
        if($request->is_paid) {
        	$record->validity		= $request->validity;
        	$record->cost			= $request->cost;
    	}

        $record->total_items		= $request->total_items;


        // $record->short_description	= $request->short_description;
        // $record->description		= $request->description;
        $record->start_date   = $request->start_date;
        $record->end_date   = $request->end_date;
        $record->record_updated_by 	= Auth::user()->id;
        $record->save();
        $file_name = 'image';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            $this->validate($request, $rules);
		    $examSettings = getSettings('lms');
	        $path = $examSettings->seriesImagepath;
	        $this->deleteFile($record->image, $path);
            $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }
        flash('success','record_updated_successfully', 'success');
    	return redirect(URL_LMS_SERIES);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
      // dd($request->all());
      if(!canDo('lms_series_create'))
      {
        prepareBlockUserMessage();
        return back();
      }

	    $rules = [
         'title'          	   => 'bail|required' ,
         'subject_id' => 'required',
         'chapter_id' => 'required',
         'topic_id' => 'required',
          ];
          // dd($request);

        $this->validate($request, $rules);

        $record = new LmsSeries();

         if($request->has('show_in_front')){

          if($request->is_paid == 1 && $request->show_in_front == 1){

            flash('Ooops...!','home_page_series_must_be_free_type_series','overlay');
            return back();
          }
          else{

            $record->show_in_front   = $request->show_in_front;
          }

        }
      	$name  						    =  $request->title;
		    $record->title 				= $name;
       	$record->slug 				= $record->makeSlug($name, TRUE);
        $record->is_paid			= $request->is_paid;
        $record->validity			= -1;
        // $record->lms_category_id	= $request->lms_category_id;
        $record->subject_id  = $request->subject_id;

        $record->chapter_id      = $request->chapter_id;
        $topic_id = null;
        if ( ! empty( $request->topic_id ) ) {
          $topic_id = $request->topic_id;
        }
        $sub_topic_id = null;
        if ( ! empty( $topic_id ) ) {
          $topic = \App\Topic::find( $topic_id );
          if ( $topic && $topic->parent_id > 0 ) {
            $sub_topic_id = $topic->id;
            $topic_id = $topic->parent_id;
          }
        }
        $record->topic_id  = $topic_id;
        $record->sub_topic_id  = $sub_topic_id;

        if ( ! empty( $request->quiz_id ) ) {
          $record->quiz_id      = $request->quiz_id;
        }
        $record->cost				= 0;
        if($request->is_paid) {
        	$record->validity		= $request->validity;
        	$record->cost			= $request->cost;
    	}
        // $record->total_items		= $request->total_items;
        // $record->short_description	= $request->short_description;
        // $record->description		= $request->description;
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
		    $examSettings = getSettings('lms');
	        $path = $examSettings->seriesImagepath;
	        $this->deleteFile($record->image, $path);
            $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }
        flash('success','record_added_successfully', 'success');
    	return redirect(URL_LMS_SERIES);
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
          $examSettings = getSettings('lms');

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
      if(!canDo('lms_series_delete'))
      {
        prepareBlockUserMessage();
        return back();
      }
      /**
       * Delete the questions associated with this quiz first
       * Delete the quiz
       * @var [type]
       */
        $record = LmsSeries::where('slug', $slug)->first();
        if(!$record)
        {
          $response['status'] = 0;
          $response['message'] = getPhrase('invalid_record');
           return json_encode($response);
        }

        try{
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
    	return URL_LMS_SERIES;
    }


    /**
     * Returns the list of subjects based on the requested subject
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getSeries(Request $request)
    {

    	$subject_id 	= $request->subject_id;
      $chapter_id   = $request->chapter_id;
      $topic_id   = $request->topic_id;
      $question_bank_type_id   = $request->question_bank_type_id;
      $questionbank_category_id   = $request->questionbank_category_id;

    	$items 			= App\LmsContent::where('subject_id','=',$subject_id);
      if ( ! empty( $chapter_id ) ) {
        $items->where('chapter_id', $chapter_id);
      }
      if ( ! empty( $topic_id ) ) {
        $items->where('topic_id', $topic_id);
      }
      if ( ! empty( $question_bank_type_id ) ) {
        $items->where('question_bank_type_id', $question_bank_type_id);
      }
      if ( ! empty( $questionbank_category_id ) ) {
        $items->where('questionbank_category_id', $questionbank_category_id);
      }
      //$items->get();

    	return json_encode(array('items'=>$items->get()));
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
		$record = LmsSeries::getRecordWithSlug($slug);
    	$data['record']         	= $record;
    	$data['active_class']       = 'lms';
        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'lms.lmsseries.right-bar-update-lmslist';
        $institute_id   = adminInstituteId();
        $subjects = \App\Subject::query();
        if(shareData('share_subjects')){
          $subjects->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID]);
        } else {
          $subjects->where('institute_id', $institute_id);
        }

        if(checkRole(getUserGrade(10))) { // Faculty
          $faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
          $subjects->whereIn('id', $faculty_subjects);
        }

        $subjects = $subjects->get();
        $data['subjects'] = $subjects->pluck('subject_title', 'id')->prepend('Please select', '')->toArray();

        $data['settings']           = FALSE;
        $previous_records = array();
        if($record->total_items > 0)
        {
            $series = DB::table('lmsseries_data')
                            ->where('lmsseries_id', '=', $record->id)
                            ->get();

            foreach($series as $r)
            {
                $temp = array();
                $temp['id'] 	= $r->lmscontent_id;
                $series_details = App\LmsContent::where('id', '=', $r->lmscontent_id)->first();
              // dd($series_details);
                $temp['content_type'] = $series_details->content_type;
                $temp['code'] 		 = $series_details->code;
                $temp['title'] 		 = $series_details->title;

                array_push($previous_records, $temp);
            }
            $settings['contents'] = $previous_records;

        $data['settings']           = json_encode($settings);
        }


    	$data['exam_categories']       	= array_pluck(App\QuizCategory::where('institute_id',$institute_id)->get(),
    									'category', 'id');

    	$data['title']              = getPhrase('update_series_for').' '.$record->title;
      $data['layout']   =  getLayout();
$data['categories'] = $data['subjects'];

         $view_name = getTheme().'::lms.lmsseries.update-list';
        return view($view_name, $data);

    }

    public function storeSeries(Request $request, $slug)
    {

        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }

        //dd( $request->all() );

        $lms_series = LmsSeries::getRecordWithSlug($slug);

        $lmsseries_id  = $lms_series->id;
        $contents  	= json_decode($request->saved_series);

        $contents_to_update = array();
        foreach ($contents as $record) {
            $temp = array();
            $temp['lmscontent_id'] = $record->id;
            $temp['lmsseries_id'] = $lmsseries_id;
            array_push($contents_to_update, $temp);

        }
        $lms_series->total_items = count($contents);
        if(!env('DEMO_MODE')) {
        //Clear all previous questions
        DB::table('lmsseries_data')->where('lmsseries_id', '=', $lmsseries_id)->delete();
        //Insert New Questions
        DB::table('lmsseries_data')->insert($contents_to_update);
          $lms_series->save();
        }
        flash('success','record_updated_successfully', 'success');
        return redirect(URL_LMS_SERIES);
    }

    /**
     * Updates the questions in a selected quiz
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function updateSeriesExams($slug)
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
    $record = LmsSeries::getRecordWithSlug($slug);
      $data['record']           = $record;
      $data['active_class']       = 'lms';
        //$data['right_bar']          = TRUE;
        //$data['right_bar_path']     = 'lms.lmsseries.right-bar-update-lmslist';
        $list                  = App\Subject::all();

           if(checkRole('institute')){

        $institute_id   = adminInstituteId();
        if(shareData()){

         $list                 = App\Subject::all();
        }else{

        $list           = App\Subject::where('institute_id',$institute_id)->get();
        }

      }

        $data['categories']         =  $list->pluck('subject_title', 'id')->toArray();

        $data['settings']           = FALSE;
        $previous_records = array();
        if($record->total_items > 0)
        {
            /*
            $series = DB::table('lmsseries_data')
                            ->where('lmsseries_id', '=', $record->id)
                            ->get();
                            */

                $series = \App\LmsSeriesData::join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
               ->where('lmsseries_id', $record->id)
               ->whereNotNull('content_type')
               ->orderBy('display_order')
               ->get();

            foreach($series as $r)
            {
                $temp = array();
                $temp['id']   = $r->lmscontent_id;
                $series_details = App\LmsContent::where('id', '=', $r->lmscontent_id)->first();
              // dd($series_details);
                $temp['content_type'] = $series_details->content_type;
                $temp['code']      = $series_details->code;
                $temp['title']     = $series_details->title;

                array_push($previous_records, $temp);
            }
            $settings['contents'] = $previous_records;

        $data['settings']           = json_encode($settings);
        }


      $data['series'] = DB::table('lmsseries_data')
                            ->join('lmscontents', 'lmscontents.id', '=', 'lmsseries_data.lmscontent_id')
                            ->where('lmsseries_id', '=', $record->id)
                            ->whereNotNull('content_type')
                            ->orderBy('display_order')
                            ->get();

      $data['exam_categories']        = App\QuizCategory::all()->pluck('category', 'id')->toArray();

      $data['title']              = getPhrase('update_series_for').' '.$record->title;
      $data['layout']   =  getLayout();


         $view_name = getTheme().'::lms.lmsseries.update-list-exams';
        return view($view_name, $data);

    }

    public function storeSeriesExams(Request $request, $slug)
    {

        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }
        $lms_series = LmsSeries::getRecordWithSlug($slug);

        $lmsseries_id  = $lms_series->id;

        $contents   = $request->quizzes;

        if ( ! empty( $contents ) ) {
          foreach ($contents as $content_id => $quiz_id) {
              if ( ! empty( $quiz_id ) ) {
                DB::table('lmsseries_data')->where('lmsseries_id', '=', $lmsseries_id)->where('lmscontent_id', $content_id)->update(['quiz_id' => $quiz_id]);
              }
          }
        }

        $sort_order   = $request->sort_order;
        if ( ! empty( $sort_order ) ) {
            foreach ($sort_order as $order => $content_id) {
              DB::table('lmsseries_data')->where('lmsseries_id', '=', $lmsseries_id)->where('lmscontent_id', $content_id)->update(['display_order' => $order]);
            }
        }
        flash('success','record_updated_successfully', 'success');
        return redirect(URL_LMS_SERIES);
    }

    /**
     * This method lists all the available exam series for students
     *
     * @return [type] [description]
     */
    public function listSeries()
    {
        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('exam_series');
        $data['series']         = LmsSeries::paginate((new App\GeneralSettings())->getPageLength());
        $data['layout']              = getLayout();
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

        $record = LmsSeries::getRecordWithSlug($slug);

        if($isValid = $this->isValidRecord($record))
          return redirect($isValid);

        $data['active_class']       = 'exams';
        $data['pay_by']             = '';
        $data['title']              = $record->title;
        $data['item']               = $record;
         $data['right_bar']          = TRUE;
          $data['right_bar_path']     = 'student.exams.exam-series-item-view-right-bar';
        $data['right_bar_data']     = array(
                                            'item' => $record,
                                            );
        $data['layout']              = getLayout();
       // return view('student.exams.exam-series-view-item', $data);

        $view_name = getTheme().'::student.exams.exam-series-view-item';
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
      $record = LmsSeries::where('slug', $slug)->first();

      $data['active_class']       = 'lms';

      $institute_id   = adminInstituteId();

      if ( $request->isMethod('post') ) {
        $rules = [
          'class'               => 'bail|required' ,
          'courses' => 'required',
        ];
        $this->validate($request, $rules);
        $courses = $request->courses;

        $series = LmsSeries::where('slug', $slug)->first();

        $sync_data = [];
        foreach ($courses as $course_id) {
          $sync_data[] = [
            'course_id' => $course_id,
            'class_id' => $request->class,
          ];
        }
        $series->courses()->sync( $sync_data );

        flash('success','record_updated_successfully', 'success');
        return redirect(URL_LMS_SERIES);
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
      // dd( $data );
      $data['title']              = getPhrase('update_series_for').' '.$record->title;
      $data['layout']   =  getLayout();

      $data['record']           = $record;

      $view_name = getTheme().'::lms.lmsseries.update-list-courses';
      return view($view_name, $data);
    }

    //Series Directory
     public function lmsSeriesDirectory(Request $request) {

      $subject_id = $request->subject_id;
      $institute_id = adminInstituteId();

      $data['active_class']  = 'lms';
      $data['chapters']      = App\Chapter::where('institute_id', $institute_id)->get();
      if ( isInstitute() ) {
        if(shareData('share_lms_series')){
          $data['chapters']      = App\Chapter::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
        } else {
          $data['chapters']      = App\Chapter::where('institute_id', $institute_id)->get();
        }
      }
      if ( IsFaculty() ) {
        $faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
        if(shareData('share_lms_series')){
          $data['chapters']      = App\Chapter::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])
        ->whereIn('subject_id', $faculty_subjects)
        ->get();
        } else {
          $data['chapters']      = App\Chapter::where('institute_id', $institute_id)
        ->whereIn('subject_id', $faculty_subjects)
        ->get();
        }
      }
      //dd( $data );

      $data['layout']        = getLayout();
      $data['title']         = getPhrase('directory');

      if ( ! empty( $subject_id ) ) {
        $data['chapters']      = App\Chapter::where('subject_id',$subject_id)->get();
       }

      $view_name = getTheme().'::lms.lmsseries.lmsseries-directory';
      return view($view_name, $data);
    }
    //End Series Directory

}
