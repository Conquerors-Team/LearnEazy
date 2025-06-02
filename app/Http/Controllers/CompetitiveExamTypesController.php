<?php
namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\CompetitiveExamTypes;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;

class CompetitiveExamTypesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');


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
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        if(!canDo('competitive_exam_type_access'))
        {
          prepareBlockUserMessage();
          return back();
        }


        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('competitive_exam_types');
        $data['layout']     = getLayout();

         $view_name = getTheme().'::exams.competitive_exam_types.list';
        return view($view_name, $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {


        if(!canDo('competitive_exam_type_access'))
        {
          prepareBlockUserMessage();
          return back();
        }

         $institute_id   = adminInstituteId();

        if(checkRole(getUserGrade(3))) {

            $records = CompetitiveExamTypes::select([ 'title', 'description', 'status', 'id']);

        }else{

            if(shareData()){

                      $records = CompetitiveExamTypes::select([ 'title', 'description', 'status', 'id']);

            } else {
              $records = CompetitiveExamTypes::select(['title', 'description', 'status', 'id'])
                                     ->where('institute_id', $institute_id);
            }


        }

         $records->orderBy('updated_at', 'desc');

        $this->setExamSettings();
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

            $temp.= '<li><a href="'.URL_COMPETITIVE_EXAM_TYPES_EDIT.'/'.$records->id.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
          }

          elseif(!shareData() || checkRole(getUserGrade(2))){

            $link_data  = '-';
            return $link_data;

          }


      }

        if(checkRole(getUserGrade(9))) {

          $institute_id   = adminInstituteId();


          if($records->institute_id  == $institute_id || checkRole(getUserGrade(1))){

          $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->id.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';

          }

          elseif(!shareData() || checkRole(getUserGrade(1))){

            $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->id.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
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

        ->editColumn('title', function($row) {
            return $row->title ;
        })
        ->editColumn('description', function($row) {
            return $row->description ;
        })
        ->editColumn('status', function($row) {
            return $row->status ;
        })
        // ->removeColumn('status')
        ->removeColumn('id')

        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
        if(!canDo('competitive_exam_type_create'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['record']             = FALSE;
        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('create');
        $data['layout']     = getLayout();

           $view_name = getTheme().'::exams.competitive_exam_types.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($id)
    {
        if(!canDo('competitive_exam_type_edit'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = CompetitiveExamTypes::getRecordWithSlug($id);
        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);

        $data['record']             = $record;
        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('edit');
        $data['layout']     = getLayout();


          $view_name = getTheme().'::exams.competitive_exam_types.add-edit';
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
        if(!canDo('competitive_exam_type_edit'))
        {
          prepareBlockUserMessage();
          return back();
        }


        $record = CompetitiveExamTypes::getRecordWithSlug($id);
        $rules = [
         'title'          => 'bail|required|max:60',
          ];
         /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */
       $name = $request->title;
        // if($name != $record->category)
        //     $record->slug = $record->makeSlug($name);

       //Validate the overall request
       $this->validate($request, $rules);
        $record->title           = $name;
        $record->description        = $request->description;
        $record->status  = $request->status;
        $record->save();

        flash('success','record_updated_successfully', 'success');
        return redirect(URL_COMPETITIVE_EXAM_TYPES);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
        if(!canDo('competitive_exam_type_create'))
        {
          prepareBlockUserMessage();
          return back();
        }


        $rules = [
          'title'          => 'bail|required|max:60',
            ];
        $this->validate($request, $rules);
        $record = new CompetitiveExamTypes();
        $name                       =  $request->title;
        $record->title           = $name;
        $record->description        = $request->description;
        $record->status  = $request->status;
        $record->save();


        flash('success','record_added_successfully', 'success');
        return redirect(URL_COMPETITIVE_EXAM_TYPES);
    }

    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($id)
    {
        if(!canDo('competitive_exam_type_delete'))
        {
          prepareBlockUserMessage();
          return back();
        }


        $record = CompetitiveExamTypes::where('id', $id)->first();
            try{
            if(!env('DEMO_MODE')) {
                $this->setExamSettings();
                $examSettings = $this->getExamSettings();
                $path = $examSettings->categoryImagepath;
                $record->delete();
            }
            $response['status'] = 1;
            $response['message'] = getPhrase('category_deleted_successfully');

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
        return URL_COMPETITIVE_EXAM_TYPES;
    }


}
