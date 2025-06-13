<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\Package;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
// use Image;
use ImageSettings;
use File;
use Input;
use Intervention\Image\Laravel\Facades\Image;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Carbon\Carbon;

class PackagesController extends Controller
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
      if(!canDo('package_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $data['active_class']       = 'packages';
        $data['title']              = getPhrase('packages');
        $data['layout']   =  getLayout();
      // return view('packages.list', $data);
         $view_name = getTheme().'::package.list';
        return view($view_name, $data);
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
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable($slug = '')
    {

      if(!canDo('package_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $records = array();

          $institute_id   = adminInstituteId();

         $records =  Package::select(['institute_id','title', 'cost', 'number_of_logins', 'package_for','trail_available','trail_period_days','is_default','duration_type','duration', 'image', 'id'])
                    ->where('institute_id',$institute_id);

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

            $temp.= '<li><a href="'.URL_PACKAGES_EDIT.$records->id.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
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
        ->editColumn('package_for', function($records)
        {
            return $records->package_for ;
        })

        ->editColumn('is_default', function($records)
        {
            return $records->is_default ;
        })

        ->editColumn('duration_type', function($records)
        {
            return $records->duration_type ;
        })

        ->editColumn('duration', function($records)
        {
            return $records->duration ;
        })

        ->editColumn('trail_available', function($records)
        {
            return $records->trail_available ;
        })

        ->editColumn('trail_period_days', function($records)
        {
            return $records->trail_period_days ;
        })

        ->editColumn('title', function($records) {
            return $records->title ;
        })
        ->editColumn('number_of_logins', function($records) {
            return $records->number_of_logins ;
        })

        ->editColumn('institute_id', function($records){

            $institute  = getInstitute($records->institute_id);
            if($institute)
            return '<a href = "'.URL_INSTITUTE_DETAILS.$institute->id.'">'.ucwords($institute->institute_name).'</a>';

             return '-';
        })
        ->editColumn('image', function($records){

            $settings = $this->getExamSettings();
            $path = $settings->courseImagepath;
            $image = $path.$settings->defaultCategoryImage;
            if($records->image)
                $image = $path.$records->image;
            return '<img src="'.PREFIX.$image.'" height="50" width="50" />';
        })

        ->removeColumn('id')
        ->removeColumn('institute_id')
        ->rawColumns(['image','action'])

        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
      if(!canDo('package_create'))
      {
        prepareBlockUserMessage();
        return back();
      }
      $data['record']           = FALSE;
      $data['active_class']       = 'packages';
      $data['title']              = getPhrase('create_package');
        $data['layout']   =  getLayout();
      // return view('packages.add-edit', $data);
         $view_name = getTheme().'::package.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
      if(!canDo('package_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

      $record = Package::getRecordWithSlug($slug);
      if($isValid = $this->isValidRecord($record))
        return redirect($isValid);

      $data['record']           = $record;
      $data['active_class']     = 'packages';
      $data['settings']         = FALSE;
        $data['title']            = getPhrase('edit_package');
        $data['layout']   =  getLayout();
      // return view('packages.add-edit', $data);
         $view_name = getTheme().'::package.add-edit';
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
      if(!canDo('package_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

      $record = Package::getRecordWithSlug($slug);
     $rules = [
         'title'            => 'required|max:60' ,
         'number_of_logins'   => 'required|numeric' ,
         'duration'   => 'required|numeric' ,
         'cost'   => 'required|numeric' ,
         'packimage'                => 'bail|mimes:png,jpg,jpeg|max:2048'
        ];
         /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */
       $name = $request->title;

       //Validate the overall request
       $this->validate($request, $rules);

        $record->title            = $name;
        if($name != $record->title)
            $record->slug = $record->makeSlug($name);
        $record->package_for        = $request->package_for;
        $record->number_of_logins   = $request->number_of_logins;
        $record->trail_available     = $request->trail_available;
        $record->trail_period_days       = $request->trail_period_days;
        $record->is_default       = $request->is_default;

        $record->duration_type       = $request->duration_type;
        $record->duration       = $request->duration;
        $record->cost       = $request->cost;
        $record->status       = $request->status;
        $record->save();

        $file_name = 'packimage';
        if ($request->hasFile($file_name))
        {

             $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
              $this->validate($request, $rules);

              $record->image      = $this->processUpload($request, $record, $file_name);
              $record->save();
        }


        $record->permissions()->sync(array_filter((array)$request->input('permissions')));

        flash('success','record_updated_successfully', 'success');
      return redirect(URL_PACKAGES);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    protected function deleteFile($filename, $path)
{
    $fullPath = public_path($path . '/' . $filename);

    if ($filename && file_exists($fullPath)) {
        @unlink($fullPath);
    }
}

    public function store(Request $request)
    {
      if(!canDo('package_create'))
      {
        prepareBlockUserMessage();
        return back();
      }
       // dd($request->all());
      $rules = [
         'title'           => 'bail|required|max:60' ,
         'number_of_logins'   => 'bail|required|numeric' ,
         'duration'   => 'required|numeric' ,
         'cost'   => 'required|numeric' ,
         'packimage'                => 'bail|mimes:png,jpg,jpeg|max:2048'
            ];
        $this->validate($request, $rules);
        $record = new Package();
        $name             =  $request->title;
        $record->title        = $name;
        $record->slug = $record->makeSlug($name);
        $record->package_for        = $request->package_for;
        $record->number_of_logins   = $request->number_of_logins;
        $record->trail_available     = $request->trail_available;
        $record->trail_period_days       = $request->trail_period_days;
        $record->is_default       = $request->is_default;
        $record->duration_type       = $request->duration_type;
        $record->duration       = $request->duration;
        $record->cost       = $request->cost;
        $record->status       = $request->status;
        $record->institute_id   = adminInstituteId();
        $record->save();


       $file_name = 'packimage';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            $this->validate($request, $rules);
            $this->setExamSettings();
            $examSettings = $this->getExamSettings();
          $path = $examSettings->courseImagepath;
          $this->deleteFile($record->image, $path);

              $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }

        if ( $request->is_default == 'yes' ) {
          \DB::table('packages')->where('id', '!=', $record->id)->update([
            'is_default' => 'no',
          ]);
        }

        $record->permissions()->sync(array_filter((array)$request->input('permissions')));

        flash('success','record_added_successfully', 'success');
      return redirect(URL_PACKAGES);
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

          $destinationPath      = $examSettings->courseImagepath;

          $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();

          $request->file($file_name)->move($destinationPath, $fileName);

         //Save Normal Image with 300x300
          // Image::make($destinationPath.$fileName)->fit($examSettings->imageSize)->save($destinationPath.$fileName);
          Image::read($destinationPath.$fileName)   // v3 uses read(), not make()
            ->cover($examSettings->imageSize, $examSettings->imageSize)
            ->save($destinationPath.$fileName);
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
      if(!canDo('package_delete'))
      {
        prepareBlockUserMessage();
        return back();
      }
      /**
       * Delete the questions associated with this quiz first
       * Delete the quiz
       * @var [type]
       */
        $record = Package::where('id', $slug)->first();
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
      return URL_COUPONS;
    }

    public function renewPackage( $slug = '' ) {

      $data['selected_package'] = null;
      if ( ! empty( $slug ) ) {
        $record = Package::getRecordWithSlug($slug);
        if($isValid = $this->isValidRecord($record))
          return redirect($isValid);

        $data['selected_package'] = $record;
      }
      $data['user']           = \Auth::user();
      $data['active_class']       = 'packages';
      $data['title']              = getPhrase('renew_package');
      $data['layout']   =  getLayout();
      $view_name = getTheme().'::package.renew';
      return view($view_name, $data);
    }

    public function storeRenewRequests(Request $request) {

        $thread = Thread::create(
            [
                'subject' => 'Renewal request from: ' . \Auth::user()->institute->institute_name,
            ]
        );
        // Message
        Message::create(
            [
                'thread_id' => $thread->id,
                'user_id'   => Auth::user()->id,
                'body'      => $request->message,
            ]
        );
        // Sender
        Participant::create(
            [
                'thread_id' => $thread->id,
                'user_id'   => Auth::user()->id,
                'last_read' => new Carbon,
            ]
        );
        // Recipients
        $thread->addParticipant([OWNER_USER_ID]);

      $data = [
        'message' => $request->message,
        'institute_id' => \Auth::user()->institute_id,
        'created_by_id' => \Auth::id(),
        'created_at' => date('Y-m-d H:i:s'),
        // 'updated_at' => date('Y-m-d H:i:s'),
        'message_id' => $thread->id,
      ];
      \DB::table('renewal_requests')->insert( $data );

      /**
       * Info@learneazy.org should get notification about the renewal request.
       */
      $institute = \Auth::user()->institute;
      $data = [
          'institute_name' => $institute->institute_name,
          'phone' => $institute->phone,
          'address' => $institute->institute_address,
          'email' => $institute->email,
          'to_email' => 'info@learneazy.org',
       ];
       sendEmail('renewal_requests', $data);

      flash('success','we have received your request. our team will get back to you soon.', 'overlay');

      return redirect()->route('packages.renew');
    }

    public function renewalRequests() {
      
      $data['active_class']       = 'packages';
      $data['title']              = getPhrase('renewal_requests');
      $data['layout']   =  getLayout();
      $view_name = getTheme().'::package.renewal_requests';
      return view($view_name, $data);
    }

    public function getRenewalRequests() {
      if(!canDo('package_access'))
      {
        prepareBlockUserMessage();
        return back();
      }



    $records =  \App\RenewalRequest::select(['created_by_id','institute_id', 'message', 'created_at', 'updated_at', 'replied', 'message_id', 'id'])->orderBy('created_at', 'desc');

        return Datatables::of($records)
        ->editColumn('created_by_id', function($records)
        {
            return $records->institute->institute_name . ' <p>Name' . $records->createdby->name . '</p><p>Address:'.$records->institute->institute_address.'</p><p>Phone:'.$records->institute->phone.'</p>';
        })
        ->editColumn('created_at', function($records)
        {
            return date( 'd/m/Y h:i A', strtotime($records->created_at));
        })
        ->editColumn('replied', function($records)
        {
            $str = ucfirst($records->replied);
            if ( 'yes' == $records->replied ) {
              $str .= '<br>' . date( 'd/m/Y h:i A', strtotime($records->updated_at));
            }
            return $str;
        })
         ->editColumn('institute_id', function($records){
            $institute  = getInstitute($records->institute_id);
            if($institute)
            return '<a href = "'.URL_INSTITUTE_DETAILS.$institute->id.'">'.ucwords($institute->institute_name).'</a>';

             return '-';
        })
        ->editColumn('message_id', function($records)
        {
            return '<a href="'.url('messages/' . $records->message_id . '/' . $records->id).'">Details</a>';
        })
        ->removeColumn('id')
        // ->removeColumn('institute_id')
        ->removeColumn('updated_at')
        ->rawColumns(['institute_id','message_id','replied'])
        ->make();
    }

}
