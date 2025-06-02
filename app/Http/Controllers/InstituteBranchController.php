<?php

namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Institute;
use Yajra\Datatables\Datatables;
use DB;
use Exception;
use File;
use Auth;

class InstituteBranchController extends Controller
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
      if(!checkRole(getUserGrade(9), 'institute_batch_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $data['active_class']       = 'branches';
        $data['title']              = getPhrase('branches');
        $data['layout']             = getLayout();


          $view_name = getTheme().'::institutes.branches.list';
        return view($view_name, $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {
      if(!checkRole(getUserGrade(9), 'institute_batch_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $institute_id   = adminInstituteId();

        if(checkRole(getUserGrade(3))){

          $records = Institute::select([ 'id','user_id','logo','institute_name','institute_address','phone','fax']);
        }
        else{

           $records = Institute::select([ 'id','user_id','logo','institute_name','institute_address','phone','fax'])
                                ->where('parent_id',$institute_id);

        }

        $records->orderBy('updated_at','desc');



        return Datatables::of($records)
        ->addColumn('action', function ($records) {

           $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="'.URL_INSTITUTE_BRANCH_EDIT.$records->id.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
                            $temp = '';
                            if(checkRole(getUserGrade(8))) {
                                $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->id.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
                            }

                            $temp .='</ul> </div>';
                            $link_data .= $temp;
                    return $link_data;
            })

        ->editColumn('logo', function($records)
        {
          $image_path = IMAGE_PATH_UPLOAD_BRANCH_DEFAULT;
          if($records->logo)
            $image_path = IMAGE_PATH_UPLOAD_BRANCH.$records->logo;
            return '<img src="'.$image_path.'" height="50" width="50" />';
        })

        ->removeColumn('id')
        ->removeColumn('user_id')
        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
      if(!checkRole(getUserGrade(8), 'institute_batch_create'))
      {
        prepareBlockUserMessage();
        return back();
      }
    	$data['record']         	= FALSE;
    	$data['active_class']     = 'branches';
    	$data['title']            = getPhrase('add_branche');
      $data['layout']           = getLayout();

         $view_name = getTheme().'::institutes.branches.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($id)
    {
      if(!checkRole(getUserGrade(9), 'institute_batch_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$record               = Institute::where('id', $id)->first();
      $data['record']       = $record;
    	$data['active_class'] = 'branches';
      $data['title']        = getPhrase('edit_branch');
      $data['layout']       = getLayout();

      $view_name = getTheme().'::institutes.branches.add-edit';
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
      if(!checkRole(getUserGrade(9), 'institute_batch_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

           $record                 = Institute::where('id', $id)->first();

         $this->validate($request, [

           'institute_name'    => 'bail|required',
           'institute_address' => 'bail|required',
           'phone'             => 'bail|required',

            ]);

        $user   = Auth::user();
        $record->user_id         = $user->id;
        $record->parent_id       = adminInstituteId();
        $record->institute_name  = $request->institute_name;
        $record->institute_address  = $request->institute_address;
        $record->status         = 1;
        $record->updated_by     = $user->id;
        $record->phone          = $request->phone;
        $record->fax            = $request->fax;
        $record->web_site       = $request->web_site;
        $record->save();

         $file_name = 'logo';
        if ($request->hasFile($file_name))
        {

          $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
          $this->validate($request, $rules);
          $examSettings = getExamSettings();
          $path = $examSettings->branchImagepath;
          $this->deleteFile($record->logo, $path);
          $record->logo    = $this->processUpload($request, $record,$file_name);
          $record->save();
        }

    	  flash('success','record_updated_successfully', 'success');
        return redirect(URL_INSTITUTE_BRANCH);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
      if(!checkRole(getUserGrade(9), 'institute_batch_create'))
      {
        prepareBlockUserMessage();
        return back();
      }

       $this->validate($request, [

           'institute_name'    => 'bail|required',
           'institute_address' => 'bail|required',
           'phone'             => 'bail|required',

            ]);

        $user                    = Auth::user();
    	  $record                  = new Institute();
        $record->user_id         = $user->id;
        $record->parent_id       = adminInstituteId();
        $record->institute_name  = $request->institute_name;
        $record->institute_address  = $request->institute_address;
        $record->status         = 1;
        $record->updated_by     = $user->id;
        $record->phone          = $request->phone;
        $record->fax            = $request->fax;
        $record->web_site       = $request->web_site;
        $record->save();

         $file_name = 'logo';
        if ($request->hasFile($file_name))
        {

          $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
          $this->validate($request, $rules);
          $examSettings = getExamSettings();
          $path = $examSettings->branchImagepath;
          $this->deleteFile($record->logo, $path);
          $record->logo    = $this->processUpload($request, $record,$file_name);
          $record->save();
        }

        flash('success','branch_is_added_successfully', 'success');
        return redirect(URL_INSTITUTE_BRANCH);
    }


         public function delete($id)
        {
             if(!checkRole(getUserGrade(8), 'institute_batch_delete'))
            {
              prepareBlockUserMessage();
              return back();
            }

            $record = Institute::where('id', $id)->first();
            try{

            if(!env('DEMO_MODE')) {

                $examSettings = getExamSettings();
                $path = $examSettings->branchImagepath;
                $this->deleteFile($record->image, $path);
                $record->delete();
            }
            $response['status'] = 1;
            $response['message'] = getPhrase('branch_deleted_successfully');

       } catch ( Exception $e) {

                 $response['status'] = 0;
           if(getSetting('show_foreign_key_constraint','module'))
            $response['message'] =  $e->errorInfo;
           else
            $response['message'] =  getPhrase('this_record_is_in_use_in_other_modules');
       }
       return json_encode($response);

    }

     public function processUpload(Request $request, $record, $file_name)
     {

         if ($request->hasFile($file_name)) {

            $examSettings     = getExamSettings();

            $destinationPath  = $examSettings->branchImagepath;

            $fileName         = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();

            $request->file($file_name)->move($destinationPath, $fileName);

            return $fileName;

        }
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




}
