<?php

namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Topic;
use App\Subject;
use Yajra\Datatables\Datatables;
use DB;
use Input;
use Excel;
use Exception;

class TopicsController extends Controller
{
    public $excel_data = array();
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
      if(!canDo('topic_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('topics_list');
        $data['layout']   =  getLayout();

    	// return view('mastersettings.topics.list', $data);

         $view_name = getTheme().'::mastersettings.topics.list';
        return view($view_name, $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {
       if(!canDo('topic_access'))
      {
        prepareBlockUserMessage();
        return back();
      }


        $institute_id   = adminInstituteId();
        if ( ! empty( request('institute') ) ) {
          $institute_id = request('institute');
        }


      if(shareData('share_topics')){
               $records = Topic::join('subjects', 'topics.subject_id', '=' ,'subjects.id')
         ->select(['topics.institute_id','subject_title','parent_id', 'topic_name','topics.slug', 'topics.id', 'topics.updated_at', 'chapter_id', 'subject_id']);
         if ( ! empty( request('institute') ) ) {
            $records->where('topics.institute_id', request('institute'));
          } else {
            $records->whereIn('topics.institute_id', [$institute_id, OWNER_INSTITUTE_ID]);
          }
        }
        else{

              $records = Topic::join('subjects', 'topics.subject_id', '=' ,'subjects.id')
         ->select(['subject_title','parent_id', 'topic_name','topics.slug', 'topics.id', 'topics.updated_at', 'chapter_id', 'subject_id'])
         ->where('topics.institute_id',$institute_id);

         }

      $subject = request('subject');
      $chapter = request('chapter');
      $topic = request('topic');
      $sub_topic = request('sub_topic');
      if ( ! empty( $subject ) ) {
        $records->where('topics.subject_id', $subject);
      }
      if ( ! empty( $chapter ) ) {
        $records->where('topics.chapter_id', $chapter);
      }
      if ( ! empty( $topic ) && ! empty( $sub_topic ) ) {
        $records->where('topics.parent_id', $topic);
        $records->where('topics.id', $sub_topic);
      } elseif ( ! empty( $topic ) ) {
        $records->where('topics.id', $topic);
      }

      $records->orderBy('updated_at','desc');

        return Datatables::of($records)
        ->addColumn('action', function ($records) {
           $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">';


                   $temp = '';

        if(checkRole(getUserGrade(2))) {

          $institute_id   = adminInstituteId();


          if($records->institute_id  == $institute_id || checkRole(getUserGrade(2))){

            $temp.= '<li><a href="'.URL_TOPICS_EDIT.'/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
          }

          elseif(!shareData('share_topics') || checkRole(getUserGrade(2))){

            $link_data  = '-';
            return $link_data;

          }


      }

        if(checkRole(getUserGrade(9))) {

          $institute_id   = adminInstituteId();


          if($records->institute_id  == $institute_id || checkRole(getUserGrade(1))){

          $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';

          }

          elseif(!shareData('share_topics') || checkRole(getUserGrade(1))){

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
        ->editColumn('topic_name', function($records)
        {
          $topic_name = $records->topic_name.' ('.$records->id.')';
          if ( $records->parent_id > 0 ) {
            $topic = Topic::find( $records->parent_id );
            if ( $topic ) {
              $topic_name = $topic->topic_name . ' ('.$topic->id.') / ' . $topic_name;
            }
          }
          return $topic_name;
        })
        ->editColumn('subject_title', function($records){
             return $records->subject_title . ' ('.$records->subject_id.')';
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
        ->removeColumn('subject_id')
        ->editColumn('parent_id', function($record){
        	if ( $record->chapter_id ) {
          $chapter = \App\Chapter::find( $record->chapter_id );
          return ($chapter) ? $chapter->chapter_name . ' ('.$chapter->id.')' : '';
          } else {
            return '';
          }
        })
        ->removeColumn('chapter_id')
        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
      if(!canDo('topic_create'))
      {
        prepareBlockUserMessage();
        return back();
      }


    	$data['record']        = FALSE;
    	$data['active_class']  = 'exams';
    	$data['parent_topics'] = array();
      $institute_id   = adminInstituteId();
      $list                  = \App\Subject::where('institute_id',$institute_id)->get();

      if(checkRole('institute')){
        if(shareData('share_subjects')){
         $list 						     = App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
        }
      }

    	$subjects			         = array_pluck($list, 'subject_title', 'id');
      $data['subjects']      = array(''=>getPhrase('select')) + $subjects;

      $data['parent_topics'][0]   = getPhrase('select');
      //$data['chapters'] = \App\Chapter::where('status', 'Active')->get()->pluck('chapter_name', 'id')->prepend(getPhrase('select'), '');
      $data['chapters'][0] = getPhrase('select');
      $data['title']              = getPhrase('add_topic');
      $data['layout']   =  getLayout();


       $view_name = getTheme().'::mastersettings.topics.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
      if(!canDo('topic_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }
    	$record = Topic::where('slug', $slug)->get()->first();

        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);

    	$data['record']       		= $record;
      $institute_id   = adminInstituteId();
    	$list                  = App\Subject::where('institute_id',$institute_id)->get();
      if(checkRole('institute')){
        if(shareData('share_subjects')){
         $list                 = App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
        }
      }
    	$data['subjects']			= collect($list)->pluck('subject_title', 'id')->toArray();

    	$data['parent_topics']		= collect(Topic::getTopics($record->subject_id, 0))->pluck('topic_name', 'id')->toArray();
	   	$data['parent_topics'][0] = 'Main Topic';
      $data['chapters'][0] = getPhrase('select');
      if ( ! empty( $record->subject_id ) ) {
        $data['chapters'] = \App\Chapter::where('status', 'Active')->where('subject_id', $record->subject_id)->get()->pluck('chapter_name', 'id')->prepend(getPhrase('select'), '');
      }
    	$data['active_class']       = 'exams';
        $data['title']              = getPhrase('edit_topic');
        $data['layout']   =  getLayout();

    	// return view('mastersettings.topics.add-edit', $data);

       $view_name = getTheme().'::mastersettings.topics.add-edit';
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

      if(!canDo('topic_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $record                 = Topic::where('slug', $slug)->get()->first();

        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);

          $this->validate($request, [
       	  'subject_id'        => 'bail|required|integer',
          'chapter_id'            => 'bail|required|integer',
          'parent_id'         => 'bail|required|integer',
          'topic_name'        => 'bail|required',
          ]);

        $name 					        = $request->topic_name;

       /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */
        if($name != $record->topic_name)
            $record->slug = $record->makeSlug($name);

        $record->topic_name 			= $name;
        $record->slug 			        = $record->makeSlug($name);
        $record->chapter_id				= $request->chapter_id;
        $record->parent_id        = $request->parent_id;
        $record->subject_id				= $request->subject_id;
        $record->description      = '';
        if(isset($request->description))
        $record->description 			= $request->description;
        $record->save();

    	flash('success','record_updated_successfully', 'success');
    	return redirect(URL_TOPICS);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
    	if(!canDo('topic_create'))
      {
        prepareBlockUserMessage();
        return back();
      }

       $this->validate($request, [
         'subject_id'          	 => 'bail|required|integer',
         'chapter_id'            => 'bail|required|integer',
         'parent_id'             => 'bail|required|integer',
         'topic_name'          	 => 'bail|required',
         ]);
    	$record = new Topic();
        $name 					        = $request->topic_name;
        $record->topic_name 			= $name;
        $record->slug 			        = $record->makeSlug($name);
        $record->chapter_id       = $request->chapter_id;
        $record->parent_id				= $request->parent_id;
        $record->subject_id				= $request->subject_id;
        $record->description 			= $request->description;
        $record->institute_id     = adminInstituteId();
        $record->save();

        flash('success','record_added_successfully', 'success');
    	return redirect(URL_TOPICS);
    }

    //Topics Directory
     public function topicsDirectory(Request $request) {

      $subject_id = $request->subject_id;

      $data['active_class']  = 'exams';
      $data['chapters']      = App\Chapter::get();
      if ( isInstitute() ) {
        $data['chapters']      = App\Chapter::where('institute_id', adminInstituteId())->get();
      }
      if ( IsFaculty() ) {
        $faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
        $data['chapters']      = App\Chapter::where('institute_id', adminInstituteId())
        ->whereIn('subject_id', $faculty_subjects)
        ->get();
      }
      $data['layout']        = getLayout();
      $data['title']         = getPhrase('directory');

      if ( ! empty( $subject_id ) ) {
        $data['chapters']      = App\Chapter::where('subject_id',$subject_id)->get();
       }

      $view_name = getTheme().'::exams.topics-directory';
      return view($view_name, $data);
    }
    //End Topics Directory



    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
      if(!canDo('topic_delete'))
      {
        prepareBlockUserMessage();
        return back();
      }
        $record = Topic::where('slug', $slug)->first();
        /**
         * Check if any questions are related to this specific topic.
         * If no questions exists, delete this topic else give appropriate message
         */
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

    /**
     * Returns the parent topics based on selected subject
     * @param  [type] $subject_id [description]
     * @return [type]             [description]
     */
    public function getInstituteSubjects($institute_id)
    {

      //$institute_id = adminInstituteId();

      $list = \App\Subject::where('institute_id', $institute_id)->where('status', 'Active')->get();

      $parents =  array();
      array_push($parents, array('id'=>0, 'text' => 'Please select'));
      foreach ($list as $key => $value) {
        $r = array('id'=>$value->id, 'text' => $value->subject_title);
        array_push($parents, $r);
      }
      return json_encode($parents);
    }

    /**
     * Returns the parent topics based on selected subject
     * @param  [type] $subject_id [description]
     * @return [type]             [description]
     */
    public function getSubjectChapters($subject_id)
    {

    	$institute_id = adminInstituteId();

      $list = \App\Chapter::where('subject_id', $subject_id)->where('institute_id', $institute_id)->where('status', 'Active')->get();
      if ( shareData('share_chapters') ) {
        $list = \App\Chapter::where('subject_id', $subject_id)->where('status', 'Active')->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
      }

      $parents =  array();
      array_push($parents, array('id'=>0, 'text' => 'Please select'));
      foreach ($list as $key => $value) {
        $r = array('id'=>$value->id, 'text' => $value->chapter_name);
        array_push($parents, $r);
      }
      return json_encode($parents);
    }

    /**
     * Returns the parent topics based on selected subject
     * @param  [type] $subject_id [description]
     * @return [type]             [description]
     */
    public function getChapterTopics($subject_id, $chapter_id)
    {

      $institute_id = adminInstituteId();
      $list = Topic::where('subject_id', $subject_id)->where('chapter_id', $chapter_id)->where('institute_id', $institute_id)->orderBy('sort_order')->get();
      if ( shareData('share_topics') ) {
        $list = Topic::where('subject_id', $subject_id)->where('chapter_id', $chapter_id)->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->orderBy('sort_order')->get();
      }

      $parents =  array();
      array_push($parents, array('id'=>0, 'text' => 'Parent'));

      foreach ($list as $key => $value) {
        $topic_name = $value->topic_name;
        if ( $value->parent_id > 0 ) {
          $parent_topic = Topic::find( $value->parent_id );
          if ( $parent_topic ) {
            $topic_name = $parent_topic->topic_name . ' => ' . $topic_name;
          }
        }
        $r = array('id'=>$value->id, 'text' => $topic_name);
        array_push($parents, $r);
      }
      return json_encode($parents);
    }

    /**
     * Returns the parent topics based on selected subject
     * @param  [type] $subject_id [description]
     * @return [type]             [description]
     */
    public function getChapterParentTopics($subject_id, $chapter_id)
    {
      $institute_id = adminInstituteId();

      $list = Topic::where('subject_id', $subject_id)->where('chapter_id', $chapter_id)->where('parent_id', '0')->where('institute_id', $institute_id)->orderBy('sort_order')->get();
      if ( shareData('share_topics') ) {
        $list = Topic::where('subject_id', $subject_id)->where('chapter_id', $chapter_id)->where('parent_id', '0')->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->orderBy('sort_order')->get();
      }

      $parents =  array();
      array_push($parents, array('id'=>'', 'text' => 'Select'));

      foreach ($list as $key => $value) {
        $topic_name = $value->topic_name;
        $r = array('id'=>$value->id, 'text' => $topic_name);
        array_push($parents, $r);
      }
      return json_encode($parents);
    }

    /**
     * Returns the parent topics based on selected subject
     * @param  [type] $subject_id [description]
     * @return [type]             [description]
     */
    public function getChapterSubTopics($subject_id, $chapter_id, $topic_id)
    {
      $institute_id = adminInstituteId();

      $list = Topic::where('subject_id', $subject_id)->where('chapter_id', $chapter_id)->where('parent_id', $topic_id)->where('institute_id', $institute_id)->orderBy('sort_order')->get();
      if ( shareData('share_topics') ) {
        $list = Topic::where('subject_id', $subject_id)->where('chapter_id', $chapter_id)->where('parent_id', $topic_id)->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->orderBy('sort_order')->get();
      }

      $parents =  array();
      array_push($parents, array('id'=>'', 'text' => 'Select'));

      foreach ($list as $key => $value) {
        $topic_name = $value->topic_name;
        $r = array('id'=>$value->id, 'text' => $topic_name);
        array_push($parents, $r);
      }
      return json_encode($parents);
    }

    /**
     * Returns the parent topics based on selected subject
     * @param  [type] $subject_id [description]
     * @return [type]             [description]
     */
    public function getChapterTopicsExam($subject_id, $chapter_id)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

      $institute_id = adminInstituteId();
      $list = Topic::where('subject_id', $subject_id)->where('chapter_id', $chapter_id)->where('institute_id', $institute_id)->orderBy('sort_order')->get();
      if ( shareData('share_topics') ) {
        $list = Topic::where('subject_id', $subject_id)->where('chapter_id', $chapter_id)->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->orderBy('sort_order')->get();
      }

      $parents =  [];
      array_push( $parents, array( 'id' => '', 'text' => 'Please select') );
      foreach ($list as $key => $value) {
        $topic_name = $value->topic_name;
        if ( $value->parent_id > 0 ) {
          $parent_topic = Topic::find( $value->parent_id );
          if ( $parent_topic ) {
            $topic_name = $parent_topic->topic_name . ' => ' . $topic_name;
          }
        }
        $r = array('id'=>$value->id, 'text' => $topic_name);
        array_push($parents, $r);
      }
      return json_encode($parents);
    }

    /**
     * Returns the parent topics based on selected subject
     * @param  [type] $subject_id [description]
     * @return [type]             [description]
     */
    public function getParentTopics($subject_id)
    {
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }


      $list = Topic::getTopics($subject_id, 0);

      $parents =  array();
      array_push($parents, array('id'=>0, 'text' => 'Parent'));

      foreach ($list as $key => $value) {
        $r = array('id'=>$value->id, 'text' => $value->topic_name);
            array_push($parents, $r);
      }
      return json_encode($parents);
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
      return URL_TOPICS_LIST;
    }

    /**
    * Display a Import Users page
    *
    * @return Response
    */
     public function import()
     {
        if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['records']      = FALSE;
        $data['active_class'] = 'exams';
        $data['heading']      = getPhrase('topics');
        $data['title']        = getPhrase('import_topics');
        $data['layout']        = getLayout();
        // return view('mastersettings.topics.import.import', $data);

         $view_name = getTheme().'::mastersettings.topics.import.import';
        return view($view_name, $data);
     }

     public function isValidChapter($subject_id, $chapter_id)
     {
      $subject_id = (int) $subject_id;
      $chapter_id = (int) $chapter_id;
       return \App\Chapter::where('subject_id','=',$subject_id)->where('id', $chapter_id)->get()->count();
     }

     public function isValidSubject($subject_id)
     {
      $subject_id = (int) $subject_id;
       return Subject::where('id','=',$subject_id)->get()->count();
     }
     /**
      * This method returns the list of childs and parents by filtering on condition
      * It also inserts the parent records to db and collects the ids of the parent
      * To place those for child records
      * @param  string $value [description]
      * @return [type]        [description]
      */
     public function getParentAndChilds($records)
     {

       $parent_records  = [];
       $child_records   = [];
       $failed_list     = [];
       $success_list    = [];

       foreach($records as $record)
       {
            if(!$this->isValidSubject($record->subject_id))
            {
               $temp['record'] = $record;
               $temp['type']  = getPhrase('invalid_subject_id');
              $failed_list[count($failed_list)]  = (object)$temp;
              continue;
            }

            if(!$this->isValidChapter($record->subject_id, $record->chapter_id))
            {
               $temp['record'] = $record;
               $temp['type']  = getPhrase('invalid_chapter_id_or_chapter_not_in_subject');
              $failed_list[count($failed_list)]  = (object)$temp;
              continue;
            }

          if(!$record->parent_id) {
             if(!$parent_records[(int)$record->id] = $this->pushToDb($record)) {
                $temp['record'] = $record;
                $temp['type']  = getPhrase('unknown_error_occurred');
                $failed_list[count($failed_list)]  = (object)$temp;
                continue;
             }

             $success_list[] = $record;
          }
          else
            $child_records[] = $record;
       }

       return array(
                    'parent_records'  => $parent_records,
                    'child_records'   => $child_records,
                    'failed_records'  => $failed_list,
                    'success_records' => $success_list
                    );
     }

     public function readExcel(Request $request)
     {
         $rules = [
         'excel'               => 'bail|required' ,
            ];
        $this->validate($request, $rules);

       if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }
       $success_list = [];
       $failed_list = [];
       try{
        if(Input::hasFile('excel')){
          $path = Input::file('excel')->getRealPath();
          $data = Excel::load($path, function($reader) {
          })->get();

          $all_records  = array();
          $excel_record = array();
          $final_records =array();
          $isHavingDuplicate = 0;
          if(!empty($data) && $data->count()){
            foreach ($data as $key => $value) {
              foreach($value as $record)
              {
                $all_records[] = $record;
              }

            }

            /**
             * 1) Validate the excel data by verifing the subject id validity and filter only valid recods
             * 2) First Insert the parent topic records and capture the id of the parent
             * 3) Get the list of child records, before insert the child record check the parent
             *   id in the captured array or it exists in db
             * 4) Insert only valid parent id records.
             * 5) Maintain Failed and Success List with error messages.
             *
             * @var [type]
             */
            $processed_records = (object) $this->getParentAndChilds($all_records);

              $parent_records = $processed_records->parent_records;
              $child_records  = $processed_records->child_records;
              $failed_list    = $processed_records->failed_records;
              $success_list   = $processed_records->success_records;

              foreach($child_records as $record)
              {
                $record = $record;

                $temp_parent_id = (int)$record->parent_id;

                if( !array_key_exists($temp_parent_id, $parent_records) &&
                    !$this->isRecordExists('id',$temp_parent_id))
                {
                  $temp['record'] = $record;
                  $temp['type']   = getPhrase('Invalid Subject Id');
                  $failed_list[count($failed_list)] = (object)$temp;
                  continue;
                }
                $record['parent_id'] =  $parent_records[$record->parent_id];
                if ( ! empty( $parent_records[$record->chapter_id] ) ) {
                  $record['chapter_id'] =  $parent_records[$record->chapter_id];
                }

              if( $this->pushToDb($record))
                $success_list[] = $record;
              }

          }
        }

       $data['failed_list']   =   $failed_list;
       $data['success_list']  =    $success_list;
       $this->excel_data['failed'] = $failed_list;
       $this->excel_data['success'] = $success_list;
         flash('success','record_added_successfully', 'success');
       $this->downloadExcel();
        }
     catch( \Illuminate\Database\QueryException $e)
     {
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash('oops...!',$e->errorInfo, 'error');
       }
       else {
          flash('oops...!','improper_sheet_uploaded', 'error');
       }

       return back();
     }
        // URL_USERS_IMPORT_REPORT
         $data['failed_list']   =   $failed_list;
       $data['success_list']  =    $success_list;
       $data['records']      = FALSE;
       $data['layout']       = getLayout();
       $data['active_class'] = 'settings';
       $data['heading']      = getPhrase('users');
       $data['title']        = getPhrase('report');
       // return view('mastersettings.topics.import.import-result', $data);

       $view_name = getTheme().'::mastersettings.topics.import.import-result';
        return view($view_name, $data);

     }

     public function getFailedData()
{
  return $this->excel_data;
}

public function downloadExcel()
{
    Excel::create('topics_report', function($excel) {
      $excel->sheet('Failed', function($sheet) {
      $sheet->row(1, array('Reason','topic_name', 'parent_id','subject_id','description'));
      $data = $this->getFailedData();
      $cnt = 2;
      foreach ($data['failed'] as $data_item) {
        $item = $data_item->record;
        $sheet->appendRow($cnt++, array($data_item->type, $item->topic_name, $item->parent_id, $item->subject_id, $item->description));
      }
    });

    $excel->sheet('Success', function($sheet) {
      $sheet->row(1, array('topic_name', 'parent_id','subject_id','description'));
      $data = $this->getFailedData();
      $cnt = 2;
      foreach ($data['success'] as $data_item) {
        $item = $data_item;
       $sheet->appendRow($cnt++, array($item->topic_name, $item->parent_id, $item->subject_id, $item->description));
      }

    });

    })->download('xlsx');
}


     /**
      * This method verifies if the record exists with the title or code
      * If Exists it returns true else it returns false
      * @param  [type]  $value [description]
      * @param  string  $type  [description]
      * @return boolean        [description]
      */
     public function isRecordExists($record_value, $type='id')
     {
        return Topic::where($type,'=',$record_value)->get()->count();
     }

     /**
      * This method inserts the array of records to db
      * @param  [type] $records [description]
      * @return [type]          [description]
      */
     public function pushToDb($request)
     {

        $topic                = new Topic();
        $name                 = $request->topic_name;
        $topic->topic_name    = $name;
        $topic->parent_id     = $request->parent_id;
        $topic->slug          = $topic->makeSlug(getHashCode());
        $topic->subject_id    = $request->subject_id;
        $topic->chapter_id     = $request->chapter_id;
        $topic->institute_id  = adminInstituteId();
        $topic->description   = '';
        if($request->description)
        $topic->description   = $request->description;
       $topic->save();
        return $topic->id;
     }

}
