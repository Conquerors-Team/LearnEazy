<?php

namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Chapter;
use App\Subject;
use Yajra\Datatables\Datatables;
use DB;
use Input;
use Excel;
use Exception;

class ChaptersController extends Controller
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
      if(!canDo('chapter_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('chapters_list');
        $data['layout']   =  getLayout();

    	// return view('mastersettings.chapters.list', $data);

         $view_name = getTheme().'::mastersettings.chapters.list';
        return view($view_name, $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {
       if(!canDo('chapter_access'))
      {
        prepareBlockUserMessage();
        return back();
      }


       $institute_id   = adminInstituteId();
       if ( ! empty( request('institute') ) ) {
            $institute_id = request('institute');
          }



      if(shareData('share_chapters')){
               $records = Chapter::join('subjects', 'chapters.subject_id', '=' ,'subjects.id')
         ->select(['chapters.institute_id','subject_title','chapter_name','chapters.slug', 'chapters.id', 'chapters.updated_at', 'subject_id']);
         if ( ! empty( request('institute') ) ) {
            $records->where('chapters.institute_id', request('institute'));
          } else {
            $records->whereIn('chapters.institute_id', [$institute_id, OWNER_INSTITUTE_ID]);
          }
        }
        else{

              $records = Chapter::join('subjects', 'chapters.subject_id', '=' ,'subjects.id')
         ->select(['subject_title', 'chapter_name','chapters.slug', 'chapters.id', 'chapters.updated_at', 'subject_id'])
         ->where('chapters.institute_id',$institute_id);

         }

      $subject = request('subject');
      if ( ! empty( $subject ) ) {
        $records->where('chapters.subject_id', $subject);
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

            $temp.= '<li><a href="'.url('mastersettings/chapters/edit').'/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
          }

          elseif(!shareData('share_chapters') || checkRole(getUserGrade(2))){

            $link_data  = '-';
            return $link_data;

          }


      }

        if(checkRole(getUserGrade(9))) {

          $institute_id   = adminInstituteId();


          if($records->institute_id  == $institute_id || checkRole(getUserGrade(1))){

          $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';

          }

          elseif(!shareData('share_chapters') || checkRole(getUserGrade(1))){

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
        ->editColumn('chapter_name', function($records)
        {
          return $records->chapter_name.' ('.$records->id.')';
        })

        ->editColumn('subject_title', function($row) {
          return $row->subject_title.' ('.$row->subject_id.')';
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
        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
      if(!canDo('chapter_create'))
      {
        prepareBlockUserMessage();
        return back();
      }


    	$data['record']        = FALSE;
    	$data['active_class']  = 'exams';
    	$data['parent_chapters'] = array();
      $institute_id   = adminInstituteId();
      $list                  = App\Subject::where('institute_id',$institute_id)->get();

      if(checkRole('institute')){
        if(shareData('share_subjects')){
         $list 						     = App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
        }
      }

    	$subjects			         = array_pluck($list, 'subject_title', 'id');
      $data['subjects']      = array(''=>getPhrase('select')) + $subjects;

      $data['parent_chapters'][0]   = getPhrase('select');
      $data['title']              = getPhrase('add_chapter');
      $data['layout']   =  getLayout();


       $view_name = getTheme().'::mastersettings.chapters.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
      if(!canDo('chapter_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }
    	$record = Chapter::where('slug', $slug)->get()->first();

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

    	$data['subjects']			= array_pluck($list, 'subject_title', 'id');
    	$data['parent_chapters']		= array_pluck(Chapter::getChapters($record->subject_id,0),'chapter_name','id');
	   	$data['parent_chapters'][0] = 'Parent';
    	$data['active_class']       = 'exams';
        $data['title']              = getPhrase('edit_chapter');
        $data['layout']   =  getLayout();

    	// return view('mastersettings.chapters.add-edit', $data);

       $view_name = getTheme().'::mastersettings.chapters.add-edit';
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

      if(!canDo('chapter_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $record                 = Chapter::where('slug', $slug)->get()->first();

        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);

          $this->validate($request, [
       	  'subject_id'        => 'bail|required|integer',
          'chapter_name'        => 'bail|required|max:512',
          ]);

        $name 					        = $request->chapter_name;

       /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */
        if($name != $record->chapter_name)
            $record->slug = $record->makeSlug($name);

        $record->chapter_name 			= $name;
        $record->slug 			        = $record->makeSlug($name);

        $record->subject_id				= $request->subject_id;
        $record->description      = '';
        if(isset($request->description))
        $record->description 			= $request->description;
        $record->save();

    	flash('success','record_updated_successfully', 'success');
    	return redirect()->route('mastersettings.chapters_index');
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
    	if(!canDo('chapter_create'))
      {
        prepareBlockUserMessage();
        return back();
      }

       $this->validate($request, [
         'subject_id'          	 => 'bail|required|integer',

         'chapter_name'          	 => 'bail|required|max:512',
         ]);
    	$record = new Chapter();
        $name 					        = $request->chapter_name;
        $record->chapter_name 			= $name;
        $record->slug 			        = $record->makeSlug($name);

        $record->subject_id				= $request->subject_id;
        $record->description 			= $request->description;
        $record->institute_id     = adminInstituteId();
        $record->save();

        flash('success','record_added_successfully', 'success');
    	return redirect()->route('mastersettings.chapters_index');
    }



    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {

      if(!canDo('chapter_delete'))
      {
        prepareBlockUserMessage();
        return back();
      }
        $record = Chapter::where('slug', $slug)->first();
        /**
         * Check if any questions are related to this specific chapter.
         * If no questions exists, delete this chapter else give appropriate message
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
     * Returns the parent chapters based on selected subject
     * @param  [type] $subject_id [description]
     * @return [type]             [description]
     */
    public function getParentChapters($subject_id)
    {
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }


    	$list = Chapter::getChapters($subject_id, 0);

    	$parents =  array();
    	array_push($parents, array('id'=>0, 'text' => 'Parent'));

    	foreach ($list as $key => $value) {
    		$r = array('id'=>$value->id, 'text' => $value->chapter_name);
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
        $data['heading']      = getPhrase('chapters');
        $data['title']        = getPhrase('import_chapters');
        $data['layout']        = getLayout();
        // return view('mastersettings.chapters.import.import', $data);

         $view_name = getTheme().'::mastersettings.chapters.import.import';
        return view($view_name, $data);
     }

     public function isValidSubject($subject_id)
     {
      $subject_id = (int) $subject_id;
       return Subject::where('id','=',$subject_id)->get()->count();
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
       // try{

        if(Input::hasFile('excel')){
          $path = Input::file('excel')->getRealPath();
          $data = Excel::load($path, function($reader) {
          })->get();

          $excel_record = array();
          $final_records =array();
          $isHavingDuplicate = 0;
          if(!empty($data) && $data->count()){
            foreach ($data as $key => $value) {
              foreach($value as $record)
              {
                unset($excel_record);
                if(!$record->chapter_name || !$record->subject_id){
                    $temp = array();
                 $temp['record']    = $excel_record;
                 $temp['type']      = getPhrase('title_or_subject_id_cannot_be_null');
                 $failed_list[$failed_length] = (object)$temp;
                 continue;
                }

                 $excel_record['chapter_name'] = $record->chapter_name;
                 $excel_record['description'] = $record->description;
                 if($this->isValidSubject($record->subject_id))
                {
                  $excel_record['subject_id'] = $record->subject_id;
                } else {
                  $temp = array();
                  $temp['record']    = $excel_record;
                  $temp['type']      = getPhrase('subject_not exists');
                  $failed_list[$failed_length] = (object)$temp;
                  continue;
                }

                 $failed_length = count($failed_list);
                $excel_record = (object)$excel_record;



                if($this->isRecordExists($record->chapter_name, 'chapter_name'))
                {
                 $temp = array();
                 $temp['record']    = $excel_record;
                 $temp['type']      = getPhrase('record_already_exists_with_this_title');
                 $failed_list[$failed_length] = (object)$temp;
                  continue;
                }



                $final_records[] = $excel_record;


              }

            }

              if($this->pushToDb($final_records))
                  $success_list = $final_records;

          }
        }


       $data['failed_list']   =   $failed_list;
       $data['success_list']  =    $success_list;
         $this->excel_data['failed'] = $failed_list;
       $this->excel_data['success'] = $success_list;
        flash('success','record_added_successfully', 'success');

       $this->downloadExcel();

    /*   }
     catch( Exception $e)
     {
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash('oops...!',$e->getMessage(), 'error');
       }
       else {
          dd($e->getMessage());
          flash('oops...!','improper_sheet_uploaded', 'error');
       }
       return back();
     }*/
        // URL_USERS_IMPORT_REPORT
       $data['failed_list']   =   $failed_list;
       $data['success_list']  =    $success_list;
       $data['records']      = FALSE;
       $data['layout']       = getLayout();
       $data['active_class'] = 'exams';
       $data['heading']      = getPhrase('users');
       $data['title']        = getPhrase('report');
      // flash('success','record_added_successfully', 'success');
       // return view('mastersettings.subjects.import.import-result', $data);

         $view_name = getTheme().'::mastersettings.subjects.import.import-result';
        return view($view_name, $data);

     }

     public function getFailedData()
{
  return $this->excel_data;
}

public function downloadExcel()
{
    Excel::create('chapters_report', function($excel) {
      $excel->sheet('Failed', function($sheet) {
      $sheet->row(1, array('Reason','chapter_name', 'subject_id','description'));
      $data = $this->getFailedData();
      $cnt = 2;
      foreach ($data['failed'] as $data_item) {
        $item = $data_item->record;
        $sheet->appendRow($cnt++, array($data_item->type, $item->chapter_name, $item->subject_id, $item->description));
      }
    });

    $excel->sheet('Success', function($sheet) {
      $sheet->row(1, array('chapter_name', 'subject_id','description'));
      $data = $this->getFailedData();
      $cnt = 2;
      foreach ($data['success'] as $data_item) {
        $item = $data_item;
       $sheet->appendRow($cnt++, array($item->chapter_name, $item->subject_id, $item->description));
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
         $institute_id = adminInstituteId();
        return Chapter::where($type,'=',$record_value)->where('institute_id', $institute_id)->get()->count();
     }



     /**
      * This method inserts the array of records to db
      * @param  [type] $records [description]
      * @return [type]          [description]
      */
     public function pushToDb($records)
     {
      foreach($records as $request) {
        $chapter                = new Chapter();
        $name                   = $request->chapter_name;
        $chapter->chapter_name = $name;
        $chapter->slug          = $chapter->makeSlug($name, TRUE);
        $chapter->subject_id = $request->subject_id;
        $chapter->institute_id  = adminInstituteId();
        $chapter->save();
      }
       return TRUE;
     }

}
