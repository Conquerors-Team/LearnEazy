<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \App;
use App\Subject;
use App\LmsNote;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;
use Input;
class LmsNotesController extends Controller
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
      if(!canDo('lms_notes_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $data['active_class']       = 'lms';
        $data['title']              = 'LMS'.' '.getPhrase('notes');
        $data['layout']   =  getLayout();

      // return view('lms.lmsnotes.list', $data);

        $view_name = getTheme().'::lms.lmsnotes.list';
        return view($view_name, $data);
    }


    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {

      if(!canDo('lms_notes_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $records = array();

        $institute_id   = adminInstituteId();

        if(checkRole(getUserGrade(3))){

              $records = LmsNote::select(['institute_id','title', 'content_type', 'file_path', 'slug', 'id', 'updated_at', 'subject_id', 'chapter_id', 'topic_id', 'sub_topic_id', 'record_updated_by']);
         }
         else{

            if(shareData('share_lms_notes')){

               $records = LmsNote::select(['institute_id','title', 'content_type', 'file_path', 'slug', 'id', 'updated_at', 'subject_id', 'chapter_id', 'topic_id', 'sub_topic_id', 'record_updated_by'])
                ->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID]);
            }
            else{

                  $records = LmsNote::select(['title', 'content_type', 'slug', 'file_path', 'id', 'updated_at', 'subject_id', 'chapter_id', 'topic_id', 'sub_topic_id', 'record_updated_by'])
                  ->where('institute_id',$institute_id);

            }

         }

          $institute = request('institute');
          $subject_id = request('subject');
          $chapter_id = request('chapter');
          $topic_id = request('topic');
          $sub_topic_id = request('sub_topic');

          if ( ! empty( $institute ) ) {
            $records->where('lms_notes.institute_id', $institute);
          }
          if ( ! empty( $subject_id ) ) {
            $records->where('lms_notes.subject_id', $subject_id);
          }
          if ( ! empty( $chapter_id ) ) {
            $records->where('lms_notes.chapter_id', $chapter_id);
          }
          if ( ! empty( $topic_id ) ) {
            $records->where('lms_notes.topic_id', $topic_id);
          }
          if ( ! empty( $sub_topic_id ) ) {
            $records->where('lms_notes.sub_topic_id', $sub_topic_id);
          }

         if ( isFaculty() ) {
          $faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
          $records->whereIn('lms_notes.subject_id', $faculty_subjects);
         }

        $records->orderBy('updated_at', 'desc');

        return Datatables::of($records)
        ->addColumn('action', function ($records) use( $institute_id ) {

          $callfrom = request('callfrom');

          if ( $callfrom == 'batch' ) {
            $batch_id = request('batch_id');
            $assigned = \DB::table('batch_lmsnotes')->where('lms_note_id', $records->id)->where('institute_id', $institute_id)->where('batch_id', $batch_id)->first();
            $selected = '';
            if ( $assigned ) {
              $selected = ' checked';
            }
            return '<input type="checkbox" name="lmsnotes['.$records->id.']" value="'.$records->id.'" style="display:block;"'.$selected.'>';
          } else {

          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">';

                        $temp = '<li><a href="'.route('lms.preview_notes', ['slug' => $records->slug]).'" target="_blank"><i class="fa fa-eye"></i>'.getPhrase("preview").'</a></li>';

        $institute_id   = adminInstituteId();
        /*
        if(checkRole(getUserGrade(2))) {




          if($records->institute_id  == $institute_id || checkRole(getUserGrade(2))){

            $temp.= '<li><a href="'.URL_LMS_NOTES_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';

            $temp.= '<li><a href="'.route('lms.preview_notes', ['slug' => $records->slug]).'" target="_blank"><i class="fa fa-eye"></i>'.getPhrase("preview").'</a></li>';
          }

          elseif(!shareData() || checkRole(getUserGrade(2))){

            $link_data  = '-';
            return $link_data;

          }


      }
      */

      if(checkRole(getUserGrade(2))) {
        if ( isFaculty() ) {
          if ( $records->record_updated_by  == \Auth::id() ) {
            $temp.= '<li><a href="'.URL_LMS_NOTES_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
            $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
          }
        } else{
          if($records->institute_id  == $institute_id ) {
          $temp.= '<li><a href="'.URL_LMS_NOTES_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
          $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
          }
        }
      }

/*
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
        */

        $temp .='</ul></div>';

        $link_data = $link_data.$temp;



        return $link_data;
      }
            })
        ->editColumn('title', function($records)
        {
          return $records->title;
        })
        ->editColumn('content_type', function($row)
        {
          /*
          $image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
          if($row->image)
          $image_path = IMAGE_PATH_UPLOAD_LMS_NOTES.$row->image;

          return '<img src="'.$image_path.'" height="50" width="50"  />';
          */
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

        ->editColumn('institute_id', function($records){

            $institute  = getInstitute($records->institute_id);
            if($institute)
            return '<a href = "'.URL_INSTITUTE_DETAILS.$institute->id.'">'.ucwords($institute->institute_name).'</a>';

             return '-';
        })

        ->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('updated_at')

        ->removeColumn('file_path')
        ->removeColumn('cost')
        ->removeColumn('validity')
        ->removeColumn('subject_id')
        ->removeColumn('chapter_id')
        ->removeColumn('topic_id')
        ->removeColumn('sub_topic_id')
        ->removeColumn('record_updated_by')
        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
      if(!canDo('lms_notes_create'))
      {
        prepareBlockUserMessage();
        return back();
      }
      $data['record']           = FALSE;
      $data['active_class']       = 'lms';
      $institute_id   = adminInstituteId();

      if(shareData('share_subjects')){
        $data['categories']         = array_pluck(App\LmsCategory::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get(),'category', 'id');

        $data['subjects'] = \App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get()->pluck('subject_title', 'id')->toArray();
        if ( isFaculty() ) {
          $faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
          $data['subjects'] = \App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->whereIn('id', $faculty_subjects)->get()->pluck('subject_title', 'id')->toArray();
        }
        $data['chapters']   = [];
        $data['topics'] = [];
      }
      else{

        $data['categories']         = array_pluck(App\LmsCategory::where('institute_id',$institute_id)->get(),'category', 'id');

        $data['subjects'] = \App\Subject::where('institute_id', $institute_id)->get()->pluck('subject_title', 'id')->toArray();
        if ( isFaculty() ) {
          $faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
          $data['subjects'] = \App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->whereIn('id', $faculty_subjects)->get()->pluck('subject_title', 'id')->toArray();
        }

        $data['chapters']   = [];
        $data['topics'] = [];
      }

        $data['title']              = getPhrase('add_series');
        $data['layout']   =  getLayout();

      // return view('lms.lmsnotes.add-edit', $data);
       $view_name = getTheme().'::lms.lmsnotes.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
      if(!canDo('lms_notes_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

      $record = LmsNote::getRecordWithSlug($slug);

      if($isValid = $this->isValidRecord($record))
        return redirect($isValid);
      $data['record']           = $record;
      $data['active_class']     = 'lms';
      $data['settings']         = FALSE;
      $institute_id   = adminInstituteId();

       if(shareData('share_subjects')){

        $data['categories']         = array_pluck(App\LmsCategory::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get(),'category', 'id');
        $data['subjects'] = \App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get()->pluck('subject_title', 'id')->toArray();
        if ( isFaculty() ) {
          $faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
          $data['subjects'] = \App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->whereIn('id', $faculty_subjects)->get()->pluck('subject_title', 'id')->toArray();
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
      }
      else{

        $data['categories']         = array_pluck(App\LmsCategory::where('institute_id',$institute_id),'category', 'id');
        $data['subjects'] = \App\Subject::where('institute_id', adminInstituteId())->get()->pluck('subject_title', 'id')->toArray();
        if ( isFaculty() ) {
          $faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
          $data['subjects'] = \App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->whereIn('id', $faculty_subjects)->get()->pluck('subject_title', 'id')->toArray();
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
      }
      $data['title']            = getPhrase('edit_notes');
      $data['layout']   =  getLayout();

      // return view('lms.lmsnotes.add-edit', $data);
         $view_name = getTheme().'::lms.lmsnotes.add-edit';
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
      if(!canDo('lms_notes_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

      $record = LmsNote::getRecordWithSlug($slug);
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


        $record->title        = $name;

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
        $record->file_path          = $file_path;
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


        $record->description    = $request->description;

        $record->record_updated_by  = Auth::user()->id;
        $record->save();
        $file_name = 'lms_file';
        if ($request->hasFile($file_name))
        {
          $examSettings = getSettings('lms');
          $path = $examSettings->notesImagepath;
          $this->deleteFile($record->image, $path);
          $record->file_path      = $this->processUpload($request, $record, $file_name, FALSE);
          $record->save();
        }

        $file_name = 'image';
        if ($request->hasFile($file_name))
        {
          $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
          $this->validate($request, $rules);
          $examSettings = getSettings('lms');
          $path = $examSettings->notesImagepath;
          $this->deleteFile($record->image, $path);
          $record->image      = $this->processUpload($request, $record,$file_name, TRUE);
          $record->save();
        }
        flash('success','record_updated_successfully', 'success');
      return redirect(URL_LMS_NOTES);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
      if(!canDo('lms_notes_create'))
      {
        prepareBlockUserMessage();
        return back();
      }

      $rules = [
         'title'               => 'bail|required' ,
         'subject_id' => 'required',
         'chapter_id' => 'required',
         'topic_id' => 'required',
         'content_type' => 'bail|required',
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

        $record = new LmsNote();


        $name                 =  $request->title;
        $record->title        = $name;
        $record->slug         = $record->makeSlug($name, TRUE);

        $record->subject_id  = $request->subject_id;
        $record->chapter_id  = $request->chapter_id;

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

        $record->content_type  = $request->content_type;
        $record->file_path  = $file_path;
        $record->description    = $request->description;
        $record->record_updated_by  = Auth::user()->id;
        $record->institute_id   = adminInstituteId();

        $record->save();

        $file_name = 'lms_file';
        if ($request->hasFile($file_name))
        {
          $this->setSettings('lms');
          $examSettings = $this->getSettings();
          $path = $examSettings->notesImagepath;
          $this->deleteFile($record->file_path, $path);

          $record->file_path      = $this->processUpload($request, $record, $file_name, FALSE);
          $record->save();
        }

        $file_name = 'image';
        if ($request->hasFile($file_name))
        {
          $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
          $this->validate($request, $rules);
          $examSettings = getSettings('lms');
          $path = $examSettings->notesImagepath;
          $this->deleteFile($record->image, $path);
          $record->image      = $this->processUpload($request, $record,$file_name, TRUE);
          $record->save();
        }

        flash('success','record_added_successfully', 'success');
      return redirect(URL_LMS_NOTES);
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
     public function processUpload(Request $request, $record, $file_name, $is_image = TRUE)
     {
      if(env('DEMO_MODE')) {
        return 'demo';
      }
         if ($request->hasFile($file_name)) {
          $examSettings = getSettings('lms');

            $imageObject = new ImageSettings();

          $destinationPath            = $examSettings->notesImagepath;
          $destinationPathThumb       = $examSettings->notesThumbImagepath;

          $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();

          $request->file($file_name)->move($destinationPath, $fileName);

         if($is_image){
         //Save Normal Image with 300x300
          Image::make($destinationPath.$fileName)->fit($imageObject->getThumbnailSize())->save($destinationPath.$fileName);
         }
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
      if(!canDo('lms_notes_delete'))
      {
        prepareBlockUserMessage();
        return back();
      }
      /**
       * Delete the questions associated with this quiz first
       * Delete the quiz
       * @var [type]
       */
        $record = LmsNote::where('slug', $slug)->first();
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
      return URL_LMS_NOTES;
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function previewNotes($slug)
    {

      $record = LmsNote::getRecordWithSlug($slug);
      // dd($record);
      if($isValid = $this->isValidRecord($record))
        return redirect($isValid);
      $data['content']           = $record;
      $data['active_class']     = 'lms';
      $data['settings']         = FALSE;
      $data['title']            = getPhrase('edit_notes');
      $data['layout']   =  getLayout();
  
      // return view('lms.lmsnotes.add-edit', $data);
         $view_name = getTheme().'::lms.lmsnotes.preview';
        return view($view_name, $data);
    }
}
