<?php
namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Board;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;

class BoardsController extends Controller
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
        if(!canDo('board_access'))
        {
          prepareBlockUserMessage();
          return back();
        }

        // $ins_associates  = associates();
        // dd($ins_associates);

        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('boards');
        $data['layout']     = getLayout();

         $view_name = getTheme().'::exams.boards.list';
        return view($view_name, $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {
        if(!canDo('board_access'))
        {
          prepareBlockUserMessage();
          return back();
        }

         $institute_id   = adminInstituteId();

        if(checkRole(getUserGrade(3))) {

            $records = Board::select([ 'title', 'status', 'id']);

        }else{

            if(shareData()){

                      $records = Board::select([ 'title','status', 'id']);

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

            $temp.= '<li><a href="'.URL_BOARD_EDIT.'/'.$records->id.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
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
        if(!canDo('board_create'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['record']             = FALSE;
        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('create_board');
        $data['layout']     = getLayout();

           $view_name = getTheme().'::exams.boards.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($id)
    {
        if(!canDo('board_edit'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = Board::getRecordWithSlug($id);
        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);

        $data['record']             = $record;
        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('edit_category');
        $data['layout']     = getLayout();


          $view_name = getTheme().'::exams.boards.add-edit';
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
        if(!canDo('board_edit'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = Board::getRecordWithSlug($id);
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
        // $record->description = $request->description;
        $record->status  = $request->status;
        $record->save();

        $record->classes()->sync(array_filter((array)$request->input('classes')));

        flash('success','record_updated_successfully', 'success');
        return redirect(URL_BOARDS);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
        if(!canDo('board_create'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $rules = [
          'title'          => 'bail|required|max:60',
            ];
        $this->validate($request, $rules);
        $record = new Board();
        $name                       =  $request->title;
        $record->title           = $name;
        $record->status  = $request->status;
        // $record->description = $request->description;
        $record->save();

        $record->classes()->sync(array_filter((array)$request->input('classes')));

        flash('success','record_added_successfully', 'success');
        return redirect(URL_BOARDS);
    }

    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($id)
    {
        if(!canDo('board_delete'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = Board::where('id', $id)->first();
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
        return URL_QUESTION_BANK_TYPES;
    }


}
