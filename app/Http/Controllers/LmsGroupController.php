<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\LmsGroup;
use App\LmsSettings;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;

class LmsGroupController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    protected  $examSettings;

    public function setSettings()
    {
        $this->examSettings = getSettings('lms');
    }

    public function getSettings()
    {
        return $this->examSettings;
    }

    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index( $slug = '')
    {

        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
      if( $slug != null || $slug != "")
      {
        $data['slug'] = $slug;
      }
        $data['active_class']       = 'lms';
        $data['title']              = 'Course Contents';
        $data['layout']   =  getLayout();

    	// return view('lms.lmscategories.list', $data);

           $view_name = getTheme().'::lms.lmsgroups.list';
        return view($view_name, $data);

    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

      $records   = array();
      $institute_id   = adminInstituteId();

      if ( isFaculty() ) {
        $faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
          $records = \App\LmsGroup::select(['lmsgroups.institute_id', 'lmsgroups.title', 'lmsgroups.description', 'lmsgroups.id','lmsgroups.slug'])->join('lmsseries_lmsgroups', 'lmsseries_lmsgroups.lmsgroups_id', '=', 'lmsgroups.id')->join('lmsseries', 'lmsseries.id', '=', 'lmsseries_lmsgroups.lmsseries_id')->whereIn('lmsseries.subject_id', $faculty_subjects)
          ->where('lmsgroups.institute_id', $institute_id)
          ->groupBy('lmsgroups_id');
      } else {
       $records = LmsGroup::select(['institute_id', 'title', 'description', 'id','slug'])
                                    ->where('institute_id',$institute_id);
      }
       $records->orderby('lmsgroups.updated_at','desc');



        $this->setSettings();
        return Datatables::of($records)
        ->addColumn('action', function ($records) {


            $link_data =  '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">';
                        $temp = '';

        if(checkRole(getUserGrade(2))) {

          $institute_id   = adminInstituteId();


          if($records->institute_id  == $institute_id || checkRole(getUserGrade(2))){

            if ( ! isFaculty() ) {
            $temp.= '<li><a href="'.URL_LMS_GROUPS_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';

            $temp.= '<li><a href="'.route('lmsgroups.updateseries', ['slug' => $records->slug]).'"><i class="fa fa-pencil"></i>'.getPhrase("update_series").'</a></li>';
            }

            $temp.= '<li><a href="'.URL_LMS_GROUPS_VIEW.$records->slug.'"><i class="fa fa-eye"></i>'.getPhrase("view").'</a></li>';
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
        ->removeColumn('id')
        ->removeColumn('slug')

        ->editColumn('institute_id', function($records){

            $institute  = getInstitute($records->institute_id);
            if($institute) {
              if ( checkRole(getUserGrade(1)) ) {
                return '<a href = "'.URL_INSTITUTE_DETAILS.$institute->id.'">'.ucwords($institute->institute_name).'</a>';
              } else {
                return ucwords($institute->institute_name);
              }

            }

             return '-';
        })
        ->rawColumns(['institute_id','action'])
        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
    	$data['record']         	= FALSE;
    	$data['active_class']       = 'lms';
    	$data['title']              = getPhrase('create_category');
        $data['layout']   =  getLayout();

    	// return view('lms.lmscategories.add-edit', $data);

         $view_name = getTheme().'::lms.lmsgroups.add-edit';
        return view($view_name, $data);
    }

    public function show( $slug, $subject_slug = '' )
    {
        // dd($slug);
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
    }

        $record = LmsGroup::select(['lmsseries.*', 'subjects.subject_title'])->where('lmsgroups.slug',$slug)
                  ->join('lmsseries_lmsgroups','lmsseries_lmsgroups.lmsgroups_id','lmsgroups.id')
                  ->join('lmsseries','lmsseries.id','lmsseries_lmsgroups.lmsseries_id')
                  ->join('subjects','lmsseries.subject_id','subjects.id')
                  ->get()
                  ->groupBy(function( $entry ) {
                        return $entry->subject_title;
                    })
                  ;
        if ( ! empty( $subject_slug ) ) {
            $record = LmsGroup::select(['lmsseries.*', 'subjects.subject_title'])
                    ->where('lmsgroups.slug',$slug)->where('subjects.slug', $subject_slug)
                  ->join('lmsseries_lmsgroups','lmsseries_lmsgroups.lmsgroups_id','lmsgroups.id')
                  ->join('lmsseries','lmsseries.id','lmsseries_lmsgroups.lmsseries_id')
                  ->join('subjects','lmsseries.subject_id','subjects.id')
                  ->get()
                  ->groupBy(function( $entry ) {
                        return $entry->subject_title;
                    })
                  ;
        }
        /*
        echo printSql(LmsGroup::select(['lmsseries.*', 'subjects.subject_title'])->where('lmsgroups.slug',$slug)
                  ->join('lmsseries_lmsgroups','lmsseries_lmsgroups.lmsgroups_id','lmsgroups.id')
                  ->join('lmsseries','lmsseries.id','lmsseries_lmsgroups.lmsseries_id')
                  ->join('subjects','lmsseries.subject_id','subjects.id')
                  );
        die();
        */
                  // dd($record);
        $data['record'] = $record;
        $data['group'] = LmsGroup::where('slug',$slug)->first();
        $data['title'] = getPhrase('lms_group_details');
        $data['layout'] = getLayout();
        $data['active_class']       = 'View';
        $data['subject_slug']       = $subject_slug;
        $data['subject'] = '';
        if ( ! empty($subject_slug) ) {
          $data['subject']       = \App\Subject::where('slug', $subject_slug)->first();
        }
        // dd($data);
        if ( isFaculty() ) {
          $view_name = getTheme().'::lms.lmsgroups.view-faculty';
        } else {
          $view_name = getTheme().'::lms.lmsgroups.view';
        }

        return view($view_name, $data);
    }
    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
      // dd($slug);
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
    	$record = LmsGroup::getRecordWithSlug($slug);
    	if($isValid = $this->isValidRecord($record))
    		return redirect($isValid);

    	$data['record']       		= $record;
    	$data['active_class']       = 'lms';
    	$data['title']              = getPhrase('edit_category');
        $data['layout']   =  getLayout();

    	// return view('lms.lmscategories.add-edit', $data);
          $view_name = getTheme().'::lms.lmsgroups.add-edit';
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
      // dd($request->all());
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
    	$record = LmsGroup::getRecordWithSlug($slug);


		$rules = [
         'title'          	   => 'bail|required|max:60' ,
          ];
         /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */
       $name = $request->title;
        if($name != $record->title)
            $record->slug = $record->makeSlug($name,TRUE);

       //Validate the overall request
       $this->validate($request, $rules);
    	$record->category 			= $name;
        $record->description		= $request->description;
        $record->record_updated_by 	= Auth::user()->id;
        $record->save();

        flash('success','record_updated_successfully', 'success');
    	return redirect(URL_LMS_GROUPS);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

	    $rules = [
         'title'          	   => 'bail|required|max:60' ,
            ];
        $this->validate($request, $rules);
        $record = new LmsGroup();
      	$name  						=  $request->title;
		$record->title 			= $name;
       	$record->slug 				= $record->makeSlug($name,TRUE);
        $record->description		= $request->description;
        $record->record_updated_by 	= Auth::user()->id;
         $record->institute_id   = adminInstituteId();

        $record->save();


        flash('success','record_added_successfully', 'success');
    	return redirect(URL_LMS_GROUPS);
    }

    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $record = LmsGroup::where('slug', $slug)->first();

        try{
        $this->setSettings();

        $examSettings = $this->getSettings();
        $path = IMAGE_PATH_UPLOAD_LMS_CATEGORIES;
        $r =  $record;
        if(!env('DEMO_MODE')) {
            $record->delete();
            $this->deleteFile($r->image, $path);
        }

            $response['status'] = 1;
            $response['message'] = getPhrase('category_deleted_successfully');
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
    	 return URL_LMS_GROUPS;
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
          $settings = json_decode((new LmsSettings())->getSettings());


          $destinationPath      = $settings->categoryImagepath;
          $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();

          $request->file($file_name)->move($destinationPath, $fileName);

         //Save Normal Image with 300x300
          Image::make($destinationPath.$fileName)->fit($settings->imageSize)->save($destinationPath.$fileName);
         return $fileName;
        }
     }



     /**
     * Updates the questions in a selected quiz
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function updateSeries( Request $request, $slug )
    {
        // dd($request->all());

       if(!checkRole(getUserGrade(2)))
       {
            prepareBlockUserMessage();
            return back();
        }

        $record = LmsGroup::where('slug', $slug)->first();
        // dd($record);

        if ( $request->isMethod('post') ) {

        $items   = json_decode($request->saved_series);

        $selected_series = [];
        foreach( $items as $item ) {
            $selected_series[] = [
                'lmsseries_id' => $item->id,
                'lmsgroups_id' => $record->id,
            ];
        }
// dd($selected_series);
        // Let us delete lmsnotes type only.
        // \App\StudentPaidContentData::where('student_paid_contents_id', $record->id)->where('item_type', 'lmsseries')->delete();
        $record->lmsseries()->sync( $selected_series );

        // $record->total_items = \App\StudentPaidContentData::where('student_paid_contents_id', $record->id)->count();
        $record->save();

        flash('success','record_updated_successfully', 'success');
        return redirect(URL_LMS_GROUPS);
      }
        /**
         * Get the Quiz Id with the slug
         * Get the available questions from questionbank_quizzes table
         * Load view with this data
         */
        $record = LmsGroup::getRecordWithSlug($slug);
        $data['record']             = $record;
        $data['active_class']       = 'lms';
        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'lms.lmsgroups.right-bar-update-lmslist';
        $institute_id   = adminInstituteId();
        $list                  = App\Subject::where('institute_id',$institute_id)->get();

           if(checkRole('institute')){
        if(shareData('share_subjects')){
          $list                 = App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
        }

      }
// dd($list);
        $data['subjects']           = array_pluck($list,'subject_title', 'id');

        $data['settings']           = FALSE;
        $previous_records = array();

            $series = DB::table('lmsseries_lmsgroups')
                            ->where('lmsgroups_id', '=', $record->id)
                            ->get();
        if(count($series) > 0)
        {
            foreach($series as $r)
            {
                $temp = array();
                $temp['id']     = $r->lmsseries_id;
                $series_details = App\LmsSeries::where('id', '=', $r->lmsseries_id)->first();
              // dd($series_details);
                $temp['content_type'] = $series_details->content_type;
                $temp['code']        = $series_details->code;
                $temp['title']       = $series_details->title;

                array_push($previous_records, $temp);
            }
            $settings['contents'] = $previous_records;

        $data['settings']           = json_encode($settings);
        }


        $data['exam_categories']        = array_pluck(App\QuizCategory::where('institute_id',$institute_id)->get(),
                                        'category', 'id');

        $data['title']              = getPhrase('update_series_for').' '.$record->title;
      $data['layout']   =  getLayout();


         $view_name = getTheme().'::lms.lmsgroups.update-list';
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

        $lms_series = LmsGroup::getRecordWithSlug($slug);

        $lmsseries_id  = $lms_series->id;
        $contents   = json_decode($request->saved_series);

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
        DB::table('lmsseries_lmsgroups')->where('lmsseries_id', '=', $lmsseries_id)->delete();
        //Insert New Questions
        DB::table('lmsseries_lmsgroups')->insert($contents_to_update);
          $lms_series->save();
        }
        flash('success','record_updated_successfully', 'success');
        return redirect(URL_LMS_GROUPS);
    }

    public function getLmsGroups(Request $request)
    {
      // dd("fghjk");

      $subject_id   = $request->subject_id;
      $chapter_id   = $request->chapter_id;
      $topic_id   = $request->topic_id;
      $sub_topic_id   = $request->sub_topic_id;

      $items      = App\LmsSeries::select(['lms_notes.id', 'lms_notes.title', 'subjects.subject_title', 'chapters.chapter_name', 'topics.topic_name','lms_notes.content_type'])
      ->join('subjects', 'subjects.id', '=', 'lms_notes.subject_id')
      ->join('chapters', 'chapters.id', '=', 'lms_notes.chapter_id')
      ->join('topics', 'topics.id', '=', 'lms_notes.topic_id');

      if ( ! empty( $subject_id ) ) {
        $items->where('lms.subject_id', $subject_id);
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
}
