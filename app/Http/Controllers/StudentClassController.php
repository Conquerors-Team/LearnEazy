<?php
namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\StudentClass;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;

class StudentClassController extends Controller
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
        if(!canDo('classes_access'))
        {
          prepareBlockUserMessage();
          return back();
        }

        // $ins_associates  = associates();
        // dd($ins_associates);

        $data['active_class']       = 'classes';
        $data['title']              = getPhrase('classes');
        $data['layout']     = getLayout();


         $view_name = getTheme().'::institutes.classes.list';
        return view($view_name, $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {

        if(!canDo('classes_access'))
        {
          prepareBlockUserMessage();
          return back();
        }

         $institute_id   = adminInstituteId();
         /*
        if(shareData('share_classes')){
                      $records = StudentClass::select(['institute_id', 'name', 'description', 'id','slug'])
                       ->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID]);
            }else{

                 $records = StudentClass::select(['institute_id', 'name', 'description', 'id','slug'])
                                     ->where('institute_id', $institute_id);
            }
            */

        $records = StudentClass::select(['institute_id', 'name', 'id','slug'])
                                     ->where('institute_id', $institute_id);

         $records->orderBy('updated_at', 'desc');

// dd( $records->get() );
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
            $temp.= '<li><a href="'.URL_INSTITUTE_CLASS_EDIT.'/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
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

        ->editColumn('institute_id', function($records){
            $institute  = getInstitute($records->institute_id);
            if($institute)
            return '<a href = "'.URL_INSTITUTE_DETAILS.$institute->id.'">'.ucwords($institute->institute_name).'</a>';

             return '-';
        })
        ->editColumn('name', function($records){

             return $records->name . ' ('. $records->id.')';
        })
        ->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('name_institute')
        ->rawColumns(['institute_id','action'])
        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
        if(!canDo('classes_create'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['record']             = FALSE;
        $data['active_class']       = 'classes';
        $data['title']              = getPhrase('create_class');
        $data['layout']     = getLayout();

           $view_name = getTheme().'::institutes.classes.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
         if(!canDo('classes_edit'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = StudentClass::getRecordWithSlug($slug);
        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);

        $data['record']             = $record;
        $data['active_class']       = 'classes';
        $data['title']              = getPhrase('edit_class');
        $data['layout']     = getLayout();

        $view_name = getTheme().'::institutes.classes.add-edit';
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
         if(!canDo('classes_edit'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = StudentClass::getRecordWithSlug($slug);
        $rules = [
         'name'          => 'bail|required|max:60',
         //'courses'          => 'required',
          ];
         /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */
       $name = $request->name;
        if($name != $record->name)
            $record->slug = $record->makeSlug($name);

       //Validate the overall request
       $this->validate($request, $rules);
        $record->name           = $name;
        $record->description        = $request->description;
        $record->save();

        //$record->courses()->sync(array_filter((array)$request->input('courses')));

        flash('success','record_updated_successfully', 'success');
        return redirect(URL_INSTITUTE_CLASSES);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
        if(!canDo('classes_create'))
        {
          prepareBlockUserMessage();
          return back();
        }
// catimage
        $rules = [
         'name'                => 'bail|required|max:60',
         //'courses'          => 'required',
            ];
        $this->validate($request, $rules);
        $record = new StudentClass();
        $name                       =  $request->name;
        $record->name           = $name;
        $record->slug               = $record->makeSlug($name);
        $record->description        = $request->description;
        $record->institute_id   = adminInstituteId();

        $record->save();

        //$record->courses()->sync(array_filter((array)$request->input('courses')));
        flash('success','record_added_successfully', 'success');
        return redirect(URL_INSTITUTE_CLASSES);
    }

    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
         if(!canDo('classes_delete'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = StudentClass::where('slug', $slug)->first();
            try{
            if(!env('DEMO_MODE')) {
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
        return URL_INSTITUTE_CLASSES;
    }
}
