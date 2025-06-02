<?php
namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\QuizCategory;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;

class QuizCategoryController extends Controller
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
        if(!canDo('exams_category_access'))
        {
          prepareBlockUserMessage();
          return back();
        }

        // $ins_associates  = associates();
        // dd($ins_associates);

        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('exam_categories');
        $data['layout']     = getLayout();

         $view_name = getTheme().'::exams.quizcategories.list';
        return view($view_name, $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {

        if(!canDo('exams_category_access'))
        {
          prepareBlockUserMessage();
          return back();
        }

         $institute_id   = adminInstituteId();

        $records = QuizCategory::select(['category', 'image', 'description', 'id','slug'])
                                     ->where('institute_id', $institute_id);

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

            $temp.= '<li><a href="'.URL_QUIZ_CATEGORY_EDIT.'/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
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

        ->removeColumn('id')
        ->removeColumn('slug')

        ->editColumn('image', function($records){

            $settings = $this->getExamSettings();
            $path = $settings->categoryImagepath;
            $image = $path.$settings->defaultCategoryImage;
            if($records->image)
                $image = $path.$records->image;
            return '<img src="'.PREFIX.$image.'" height="50" width="50" />';
        })
        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
        if(!canDo('exams_category_create'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['record']             = FALSE;
        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('create_category');
        $data['layout']     = getLayout();

           $view_name = getTheme().'::exams.quizcategories.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
         if(!canDo('exams_category_edit'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = QuizCategory::getRecordWithSlug($slug);
        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);

        $data['record']             = $record;
        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('edit_category');
        $data['layout']     = getLayout();


          $view_name = getTheme().'::exams.quizcategories.add-edit';
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
         if(!canDo('exams_category_edit'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = QuizCategory::getRecordWithSlug($slug);
        $rules = [
         'category'          => 'bail|required|max:60',
          'catimage'         => 'bail|mimes:png,jpg,jpeg|max:2048'
          ];
         /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */
       $name = $request->category;
        if($name != $record->category)
            $record->slug = $record->makeSlug($name);

       //Validate the overall request
       $this->validate($request, $rules);
        $record->category           = $name;
        $record->description        = $request->description;
        $record->record_updated_by  = Auth::user()->id;
        $record->save();
         $file_name = 'catimage';
        if ($request->hasFile($file_name))
        {

             $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
              $this->validate($request, $rules);

              $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }

        flash('success','record_updated_successfully', 'success');
        return redirect(URL_QUIZ_CATEGORIES);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
        if(!canDo('exams_category_create'))
        {
          prepareBlockUserMessage();
          return back();
        }
// catimage
        $rules = [
         'category'                => 'bail|required|max:60' ,
         'catimage'                => 'bail|mimes:png,jpg,jpeg|max:2048'
            ];
        $this->validate($request, $rules);
        $record = new QuizCategory();
        $name                       =  $request->category;
        $record->category           = $name;
        $record->slug               = $record->makeSlug($name);
        $record->description        = $request->description;
        $record->record_updated_by  = Auth::user()->id;

        $record->institute_id   = adminInstituteId();

        $record->save();
         $file_name = 'catimage';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            $this->validate($request, $rules);
            $this->setExamSettings();
            $examSettings = $this->getExamSettings();
            $path = $examSettings->categoryImagepath;
            $this->deleteFile($record->image, $path);

              $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }

        flash('success','record_added_successfully', 'success');
        return redirect(URL_QUIZ_CATEGORIES);
    }

    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
         if(!canDo('exams_category_delete'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = QuizCategory::where('slug', $slug)->first();
            try{
            if(!env('DEMO_MODE')) {
                $this->setExamSettings();
                $examSettings = $this->getExamSettings();
                $path = $examSettings->categoryImagepath;
                $this->deleteFile($record->image, $path);
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
            // return $this->getRedirectUrl();
        }

        return FALSE;
    }

    public function getReturnUrl()
    {
        return URL_QUIZ_CATEGORIES;
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
     public function processUpload(Request $request, $record, $file_name)
     {
         if(env('DEMO_MODE')) {
        return ;
       }

         if ($request->hasFile($file_name)) {
          $examSettings = getExamSettings();

          $destinationPath      = $examSettings->categoryImagepath;

          $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();

          $request->file($file_name)->move($destinationPath, $fileName);

         //Save Normal Image with 300x300
          Image::make($destinationPath.$fileName)->fit($examSettings->imageSize)->save($destinationPath.$fileName);
         return $fileName;
        }
     }
}
