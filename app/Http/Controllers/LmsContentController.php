<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\Lmscategory;
use App\LmsContent;
use App\LmsSeries;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;
use Exception;
class LmsContentController extends Controller
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
    public function index()
    {
       if(!canDo('lms_content_access'))
      {
        prepareBlockUserMessage();
        return back();
      }
        $data['active_class']       = 'lms';
        $data['title']              = 'LMS'.' '.getPhrase('content');
        $data['layout']              = getLayout();
    	// return view('lms.lmscontents.list', $data);

          $view_name = getTheme().'::lms.lmscontents.list';
        return view($view_name, $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable(Request $request)
    {
      if(!canDo('lms_content_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

     $records  = array();

      $institute_id   = adminInstituteId();


        if(checkRole(getUserGrade(3))){

               $records = LmsContent::join('subjects', 'lmscontents.subject_id', '=', 'subjects.id')
            ->select(['lmscontents.institute_id','lmscontents.title','lmscontents.image','lmscontents.content_type', 'subjects.subject_title','lmscontents.slug', 'lmscontents.id','lmscontents.updated_at', 'chapter_id', 'topic_id', 'sub_topic_id' ]);
         }
         else{

            if(shareData('share_lms_contents')){

                  $records = LmsContent::join('subjects', 'lmscontents.subject_id', '=', 'subjects.id')
            ->select(['lmscontents.institute_id','lmscontents.title','lmscontents.image','lmscontents.content_type', 'subjects.subject_title','lmscontents.slug', 'lmscontents.id','lmscontents.updated_at', 'chapter_id', 'topic_id', 'sub_topic_id' ])
            ->whereIn('lmscontents.institute_id', [$institute_id, OWNER_INSTITUTE_ID]);
            }
            else{

                 $records = LmsContent::join('subjects', 'lmscontents.subject_id', '=', 'subjects.id')
            ->select(['lmscontents.title','lmscontents.image','lmscontents.content_type', 'subjects.subject_title','lmscontents.slug', 'lmscontents.id','lmscontents.updated_at', 'chapter_id', 'topic_id', 'sub_topic_id' ])
               ->where('lmscontents.institute_id',$institute_id);

            }

         }

    $records->orderBy('updated_at','desc');
    $content_type = request('content_type');
    $subject_id = request('subject');
    $chapter_id = request('chapter');
    $topic_id = request('topic');
    $sub_topic_id = request('sub_topic');

    if ( ! empty( $content_type ) ) {
      $records->where('lmscontents.content_type', $content_type);
    }
    if ( ! empty( $subject_id ) ) {
      $records->where('lmscontents.subject_id', $subject_id);
    }
    if ( ! empty( $chapter_id ) ) {
      $records->where('lmscontents.chapter_id', $chapter_id);
    }
    if ( ! empty( $topic_id ) ) {
      $records->where('lmscontents.topic_id', $topic_id);
    }
    if ( ! empty( $sub_topic_id ) ) {
      $records->where('lmscontents.sub_topic_id', $sub_topic_id);
    }

    if(checkRole(getUserGrade(10))) { // Faculty
      $subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
      $records->whereIn('lmscontents.subject_id', $subjects);
    }

        $this->setSettings();
        return Datatables::of($records)
        ->addColumn('action', function ($records) {
            $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">';
                           $temp = '';

        // $temp = '<li><a href="'.URL_LMS_CONTENT_VIEW.$records->slug.'" target="_blank"><i class="fa fa-eye"></i>'.getPhrase("preview").'</a></li>

        // ';

        if(checkRole(getUserGrade(2))) {

          $institute_id   = adminInstituteId();


          if($records->institute_id  == $institute_id || checkRole(getUserGrade(2))){

            $temp.= '<li><a href="'.URL_LMS_CONTENT_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
          }

          elseif(!shareData('share_lms_contents') || checkRole(getUserGrade(2))){

            $link_data  = '-';
            return $link_data;

          }


      }

        if(checkRole(getUserGrade(9))) {

          $institute_id   = adminInstituteId();


          if($records->institute_id  == $institute_id || checkRole(getUserGrade(1))){

          $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';

          }

          elseif(!shareData('share_lms_contents') || checkRole(getUserGrade(1))){

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
        ->removeColumn('updated_at')
        ->removeColumn('slug')

        ->removeColumn('chapter_id')
        ->removeColumn('topic_id')
        ->removeColumn('sub_topic_id')

        ->editColumn('institute_id', function($records){

            $institute  = getInstitute($records->institute_id);
            if($institute)
            return '<a href = "'.URL_INSTITUTE_DETAILS.$institute->id.'">'.ucwords($institute->institute_name).'</a>';

             return '-';
        })
        ->editColumn('content_type', function($row)
        {
          $str = 'Type: ' . ucfirst($row->content_type);
          if ( $row->subject ) {
            $str .= '<br>Subject: ' . $row->subject->subject_title;
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
        ->editColumn('image', function($records){
            $image_path = IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings');

            if($records->image)
            $image_path = IMAGE_PATH_UPLOAD_LMS_CONTENTS.$records->image;

            return '<img src="'.$image_path.'" height="30" width="50" />';
        })
        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
       if(!canDo('lms_content_create'))
      {
        prepareBlockUserMessage();
        return back();
      }
    	$data['record']         	= FALSE;
    	$data['active_class']       = 'lms';
        $data['chapters']   = [];
      $data['topics'] = [];
        $institute_id   = adminInstituteId();
        /*
        if(shareData() || checkRole(getUserGrade(3))){

        $data['subjects']           = array_pluck(App\Subject::all(), 'subject_title', 'id');
        }
        else{



    	$data['subjects']       	= array_pluck(App\Subject::where('institute_id',$institute_id)->get(), 'subject_title', 'id');

        }
        */

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

        $data['title']              = getPhrase('add_content');
    	$data['layout']              = getLayout();

    	// return view('lms.lmscontents.add-edit', $data);
         $view_name = getTheme().'::lms.lmscontents.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
      if(!canDo('lms_content_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }
    	$record = LmsContent::getRecordWithSlug($slug);
    	if($isValid = $this->isValidRecord($record))
    		return redirect($isValid);

    	$data['record']         	= $record;
    	$data['title']       		= getPhrase('edit').' '.$record->title;
    	$data['active_class']       = 'lms';
      $institute_id   = adminInstituteId();
    	 if(shareData('share_lms_contents') || checkRole(getUserGrade(3))){
            // $data['subjects']           = array_pluck(App\Subject::all(), 'subject_title', 'id');

            $subjects = \App\Subject::query();
            if(shareData('share_topics')){
              $subjects->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
            } else {
              $subjects->where('institute_id', $institute_id)->get();
            }
            $data['subjects'] = $subjects->pluck('subject_title', 'id')->prepend('Please select', '')->toArray();

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


        //$data['subjects']           = array_pluck(App\Subject::where('institute_id',$institute_id)->get(), 'subject_title', 'id');
        $subjects = \App\Subject::query();
        if(shareData('share_topics')){
          $subjects->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
        } else {
          $subjects->where('institute_id', $institute_id)->get();
        }
        $data['subjects'] = $subjects->pluck('subject_title', 'id')->prepend('Please select', '')->toArray();

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
    	$data['settings']           = json_encode($record);
        $data['layout']              = getLayout();
    	// return view('lms.lmscontents.add-edit', $data);
          $view_name = getTheme().'::lms.lmscontents.add-edit';
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
      if(!canDo('lms_content_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$record = LmsContent::getRecordWithSlug($slug);
		  $rules = [
         'subject_id'                   => 'bail|required|integer' ,
         'chapter_id'                   => 'bail|required|integer' ,
         'topic_id'                   => 'bail|required|integer' ,
         'title'                        => 'bail|required' ,
         'content_type'                 => 'bail|required',
         //'code'                         => 'bail|required|unique:lmscontents,code,'.$record->id,
        ];
        $file_path = $record->file_path;
        switch ($request->content_type) {
            case 'url':
            case 'video_url':
            case 'audio_url':
            case 'iframe':
                    if($request->file_path)
                        $file_path = $request->file_path;
                break;
            case 'file' :
                   if($request->file_path)
                    $file_path = $request->lms_file;
                break;
            case 'video' :
                    if($request->file_path)
                    $file_path = $request->lms_file;
                break;
            case 'audio' :
                    if($request->file_path)
                    $file_path = $request->lms_file;
                break;
        }


        $this->validate($request, $rules);
         DB::beginTransaction();
       try{
       $name = $request->title;
        if($name != $record->title)
            $record->slug = $record->makeSlug($name, TRUE);

    	$name  						=  $request->title;
		$record->title 				= $name;
        $record->title              = $name;

        $record->subject_id         = $request->subject_id;

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

        $record->code               = $request->code;
        $record->content_type       = $request->content_type;

        $record->file_path          = $file_path;
        $record->description        = $request->description;
        $record->record_updated_by  = Auth::user()->id;

        $record->save();
         $file_name = 'image';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            $this->validate($request, $rules);
            $this->setSettings();
            $examSettings = $this->getSettings();
            $path = $examSettings->contentImagepath;
            $this->deleteFile($record->image, $path);

              $record->image      = $this->processUpload($request, $record,$file_name);

              $record->save();
        }

         $file_name = 'lms_file';
        if ($request->hasFile($file_name))
        {

            $this->setSettings();
            $examSettings = $this->getSettings();
            $path = $examSettings->contentImagepath;
            $this->deleteFile($record->file_path, $path);

              $record->file_path      = $this->processUpload($request, $record,$file_name, FALSE);

              $record->save();
        }
        DB::commit();
        flash('success','record_updated_successfully', 'success');

    }  catch(Exception $e)
     {
        DB::rollBack();
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash('oops...!',$e->errorInfo, 'error');
       }
       else {
          //dd( $e->getMessage() );
          flash('oops...!','improper_data_file_submitted', 'error');
       }
     }
    	return redirect(URL_LMS_CONTENT);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {

       if(!canDo('lms_content_create'))
      {
        prepareBlockUserMessage();
        return back();
      }

	    $rules = [
         'subject_id'          	        => 'bail|required|integer' ,
         'chapter_id'                   => 'bail|required|integer' ,
         'topic_id'                   => 'bail|required|integer' ,
         'title'          	   			=> 'bail|required' ,
         'content_type'                 => 'bail|required',
         //'code'                         => 'bail|required|unique:lmscontents',

        ];
        $file_path = '';
        switch ($request->content_type) {
            case 'url':
            case 'video_url':
            case 'audio_url':
            case 'iframe':
                    $rules['file_path'] = 'bail|required';
                    $file_path = $request->file_path;
                break;
            case 'file' :
                     $rules['lms_file'] = 'bail|required';
                    $file_path = $request->lms_file;
                break;
            case 'video' :

                      $rules['lms_file'] = 'bail|required';
                    $file_path = $request->lms_file;
                break;
            case 'audio' :
                    $rules['lms_file'] = 'bail|required';
                    $file_path = $request->lms_file;
                break;
            case 'iframe' :
                    $rules['file_path'] = 'bail|required';
                    $file_path = $request->file_path;
        }



        $this->validate($request, $rules);
     DB::beginTransaction();
       try{
        $record = new LmsContent();
      	$name  						=  $request->title;
		$record->title 				= $name;
       	$record->slug 				= $record->makeSlug($name, TRUE);
        $record->subject_id         = $request->subject_id;

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

        $record->code               = $request->code;
       	$record->content_type 		= $request->content_type;

       	$record->file_path 		   = $file_path;
        $record->description		= $request->description;
        $record->record_updated_by 	= Auth::user()->id;

         $record->institute_id   = adminInstituteId();

        $record->save();
 		 $file_name = 'image';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            $this->validate($request, $rules);
		    $this->setSettings();
            $examSettings = $this->getSettings();
	        $path = $examSettings->contentImagepath;
	        $this->deleteFile($record->image, $path);

              $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }

         $file_name = 'lms_file';
        if ($request->hasFile($file_name))
        {

            // $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            // $this->validate($request, $rules);
		    $this->setSettings();
            $examSettings = $this->getSettings();
	        $path = $examSettings->contentImagepath;
	        $this->deleteFile($record->file_path, $path);

              $record->file_path      = $this->processUpload($request, $record, $file_name, FALSE);
              $record->save();
        }

         DB::commit();
        flash('success','record_added_successfully', 'success');

    }
     catch( Exception $e)
     {
        DB::rollBack();
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash('oops...!',$e->errorInfo, 'error');
       }
       else {
          flash('oops...!','improper_data_file_submitted', 'error');
       }
     }

    	return redirect(URL_LMS_CONTENT);
    }

    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
      if(!canDo( 'lms_content_delete'))
      {
        prepareBlockUserMessage();
        return back();
      }
        $record = LmsContent::where('slug', $slug)->first();
        $this->setSettings();
        try{
            if(!env('DEMO_MODE')) {
                $examSettings = $this->getSettings();
                $path = $examSettings->contentImagepath;
                $this->deleteFile($record->image, $path);
                if($record->file_path!='')
                    $this->deleteFile($record->file_path, $path);
                $record->delete();
            }

            $response['status'] = 1;
            $response['message'] = getPhrase('content_deleted_successfully');
        }
        catch (\Illuminate\Database\QueryException $e) {
                 $response['status'] = 0;
           if(getSetting('show_foreign_key_constraint','module'))
            $response['message'] =  $e->errorInfo;
           else
            $response['message'] =  getPhrase('this_record_is_in_use_in_other_modules');
       }
       return json_encode($response);

    }

    public function viewItem($slug)
    {

        $record = LmsContent::getRecordWithSlug($slug);
        // dd($record);
        if($isValid = $this->isValidRecord($record))
          return redirect($isValid);

        $data['active_class']       = 'exams';
        $data['pay_by']             = '';
        $data['title']              = $record->title;
        $data['content_type']       = $record->content_type;
        $data['item']               = $record;
        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'student.lms.lms-series-list';
        $data['right_bar_data']     = array(
                                            'item' => $record,
                                            );
        $data['layout']              = getLayout();
       // return view('student.exams.exam-series-view-item', $data);

        $view_name = getTheme().'::student.lms.show-content';
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

    public function getReturnUrl()
    {
    	return URL_LMS_CONTENT;
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

     /**
     * This method process the image is being refferred
     * by getting the settings from ImageSettings Class
     * @param  Request $request   [Request object from user]
     * @param  [type]  $record    [The saved record which contains the ID]
     * @param  [type]  $file_name [The Name of the file which need to upload]
     * @return [type]             [description]
     */
     public function processUpload(Request $request, $record, $file_name, $is_image = TRUE)
     {

        if(env('DEMO_MODE')) {
            return 'demo';
        }


         if ($request->hasFile($file_name)) {
          $settings = $this->getSettings();
          $destinationPath      = $settings->contentImagepath;
          $path = $_FILES[$file_name]['name'];
          $ext = pathinfo($path, PATHINFO_EXTENSION);

          $fileName = $record->id.'-'.$file_name.'.'.$ext;

          $request->file($file_name)->move($destinationPath, $fileName);
         if($is_image){

         //Save Normal Image with 300x300
          Image::make($destinationPath.$fileName)->fit($settings->imageSize)->save($destinationPath.$fileName);
         }
         return $fileName;
        }

     }
}
