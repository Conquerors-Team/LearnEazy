<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \App;

use App\Notification;
use Yajra\Datatables\Datatables;
use DB;
use Auth;

class NotificationsController extends Controller
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
      if(!canDo('internal_notification_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $data['active_class']       = 'notifications';
        $data['title']              = getPhrase('notifications');
        $data['layout']              = getLayout();
    	// return view('notifications.list', $data);

          $view_name = getTheme().'::notifications.list';
        return view($view_name, $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable($slug = '')
    {

      if(!canDo('internal_notification_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $records = array();

        $institute_id   = adminInstituteId();

         if(checkRole(getUserGrade(3))){
               $records = Notification::select(['title', 'valid_from', 'valid_to', 'url', 'id','slug' ]);
         }
         else{

          if(checkRole(getUserGrade(3))){ // Student.
            $batches  = getStudentBatches();
            $records = Notification::select(['title', 'valid_from', 'valid_to', 'url', 'id','slug' ])
              ->whereIn('batch_id', $batches);
          } elseif(checkRole(getUserGrade(10))){ // Faculty.
            $records = Notification::select(['title', 'valid_from', 'valid_to', 'url', 'id','slug' ])
            ->where('created_by_id', Auth::id());
          }
          else{
            $records = Notification::select(['title', 'valid_from', 'valid_to', 'url', 'id','slug' ])
            ->where('institute_id',$institute_id);
          }

         }



            $records->orderBy('updated_at', 'desc');


        return Datatables::of($records)
        ->addColumn('action', function ($records) {

          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">';

                       $temp = '';



        $temp.= '<li><a href="'.URL_ADMIN_NOTIFICATIONS_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
        $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';

        $temp .='</ul></div>';

        $link_data = $link_data.$temp;



        return $link_data;
            })
        ->editColumn('status', function($records)
        {
            return ($records->status == 'Active') ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
        })

            ->editColumn('institute_id', function($records){

            $institute  = getInstitute($records->institute_id);
            if($institute)
            return '<a href = "'.URL_INSTITUTE_DETAILS.$institute->id.'">'.ucwords($institute->institute_name).'</a>';

             return '-';
        })

        ->removeColumn('id')
        ->removeColumn('slug')
        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
      if(!canDo('internal_notification_create'))
      {
        prepareBlockUserMessage();
        return back();
      }
    	$data['record']         	= FALSE;
    	$data['active_class']       = 'notifications';
     	$data['title']              = getPhrase('add_notification');
     	$data['layout']              = getLayout();
    	// return view('notifications.add-edit', $data);

         $view_name = getTheme().'::notifications.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
      if(!canDo('internal_notification_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$record = Notification::getRecordWithSlug($slug);
    	if($isValid = $this->isValidRecord($record))
    		return redirect($isValid);

      $record->valid_from_time = '';
      $record->valid_to_time = '';

      /*
      if ( ! empty( $record->valid_from ) ) {
        $date_from = date('Y/m/d', strtotime($record->valid_from));
        $record->valid_from_time = date('H:i', strtotime($record->valid_from));
      }
      if ( ! empty( $record->valid_to ) ) {
        $date_to = date('Y/m/d', strtotime($record->valid_to));
        $record->valid_to_time = date('H:i', strtotime($record->valid_to));
      }
      */
      //dd( $record );
      $data['record']       		= $record;
      $data['active_class']     	= 'notifications';
      $data['settings']       	= FALSE;
      $data['title']            	= getPhrase('edit_notification');
      $data['layout']             = getLayout();
    	// return view('notifications.add-edit', $data);

           $view_name = getTheme().'::notifications.add-edit';
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
      if(!canDo('internal_notification_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$record = Notification::getRecordWithSlug($slug);
		 $rules = [
        'title'          	=> 'bail|required|max:65535' ,

         'valid_from'      	=> 'bail|required',
         'valid_to'      	=> 'bail|required',
         'notification_for'       => 'bail|required',
            ];

         if ( $request->notification_for == 'batch' ) {
          $rules['batch_id'] = 'required';
        }
        if ( $request->notification_for == 'class' ) {
          $rules['student_class_id'] = 'required';
        }

         /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */
       $name = $request->title;
        if($name != $record->title)
            $record->slug = $record->makeSlug($name);

       //Validate the overall request
       $this->validate($request, $rules);

        $record->title          	= $name;
        //$record->valid_from			= $request->valid_from;
        //$record->valid_to			= $request->valid_to;

        $valid_from = $request->valid_from;
        if ( ! empty( $request->valid_from_time ) ) {
          $valid_from .= ' ' . $request->valid_from_time;
        }
        $record->valid_from     = $valid_from;

        $valid_to = $request->valid_to;
        if ( ! empty( $request->valid_to_time ) ) {
          $valid_to .= ' ' . $request->valid_to_time;
        }
        $record->valid_to     = $valid_to;

        $record->url				= $request->url;
        $record->short_description		= $request->short_description;
        $record->description		= $request->description;
        // $record->batch_id    = $request->batch_id;

        if ( $request->notification_for == 'batch' ) {
          $record->batch_id    = $request->batch_id;
          $record->student_class_id    = null;
        }
        if ( $request->notification_for == 'class' ) {
          $record->batch_id    = null;
          $record->student_class_id    = $request->student_class_id;
        }
        if ( in_array($request->notification_for, ['allinstitutes', 'allstudents'])) {
            $record->batch_id    = null;
            $record->student_class_id    = null;
        }
        $record->notification_for     = $request->notification_for;

       	$record->record_updated_by 	= Auth::user()->id;
        $record->save();
        flash('success','record_updated_successfully', 'success');
    	return redirect(URL_ADMIN_NOTIFICATIONS);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
      if(!canDo('internal_notification_create'))
      {
        prepareBlockUserMessage();
        return back();
      }

	       $rules = [
         'title'          	=> 'bail|required|max:65535',
         'valid_from'      	=> 'bail|required' ,
         'valid_to'      	=> 'bail|required' ,
         'notification_for'       => 'bail|required',
          ];
        if ( $request->notification_for == 'batch' ) {
          $rules['batch_id'] = 'required';
        }
        if ( $request->notification_for == 'class' ) {
          $rules['student_class_id'] = 'required';
        }
        $this->validate($request, $rules);
        $record = new Notification();
      	$name  						=  $request->title;
		    $record->title 				= $name;
       	$record->slug 				= $record->makeSlug($name);

        $valid_from = $request->valid_from;
        if ( ! empty( $request->valid_from_time ) ) {
          $valid_from .= ' ' . $request->valid_from_time;
        }
        $record->valid_from			= $valid_from;

        $valid_to = $request->valid_to;
        if ( ! empty( $request->valid_to_time ) ) {
          $valid_to .= ' ' . $request->valid_to_time;
        }
        $record->valid_to			= $valid_to;
        $record->url				= $request->url;
        $record->short_description	= $request->short_description;
        $record->description		= $request->description;

        if ( $request->notification_for == 'batch' ) {
          $record->batch_id    = $request->batch_id;
          $record->student_class_id    = null;
        }
        if ( $request->notification_for == 'class' ) {
          $record->student_class_id    = $request->student_class_id;
          $record->batch_id    = null;
        }
        if ( in_array($request->notification_for, ['allinstitutes', 'allstudents'])) {
            $record->batch_id    = null;
            $record->student_class_id    = null;
        }
        $record->notification_for     = $request->notification_for;

       	$record->record_updated_by 	= Auth::user()->id;
        $record->created_by_id  = Auth::id();
        $record->institute_id   = adminInstituteId();


        $record->save();
        flash('success','record_added_successfully', 'success');
    	return redirect(URL_ADMIN_NOTIFICATIONS);
    }

    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
      if(!canDo('internal_notification_delete'))
      {
        prepareBlockUserMessage();
        return back();
      }
      /**
       * Delete the questions associated with this quiz first
       * Delete the quiz
       * @var [type]
       */
        $record = Notification::where('slug', $slug)->first();
        if(!env('DEMO_MODE')) {
            $record->delete();
        }

        $response['status'] = 1;
        $response['message'] = getPhrase('record_deleted_successfully');
        return json_encode($response);
    }

    public function isValidRecord($record)
    {
    	if ($record === null) {

    		flash('Ooops...!', getPhrase("page_not_found"), 'error');
   			return $this->getReturnUrl();
		}

		return FALSE;
    }

    public function getReturnUrl()
    {
    	return URL_ADMIN_NOTIFICATIONS;
    }

    public function usersList()
    {   
        if(!canDo('internal_notification_access'))
        {
          prepareBlockUserMessage();
          return back();
        }

        $institute_id   = Auth::user()->institute_id;

        $data['active_class']       = 'notifications';
        $data['title']              = getPhrase('notifications');
        $data['layout']             = getLayout();
        $date = date('Y-m-d');
        // $data['notifications']  	= Notification::where('institute_id',$institute_id)->paginate(getRecordsPerPage());

        if(checkRole(getUserGrade(10))) {
          $data['notifications'] = Notification::where('notification_for', 'faculty' )->where('institute_id', $institute_id)->paginate(getRecordsPerPage());
        } else {
        $data['notifications'] = Notification::where(function($query) {
        $batches = getStudentBatches();
        if ( count( $batches ) ) {
          foreach ($batches as $batch_id) {
            $query->orWhere('batch_id', $batch_id);
          }
        }
        $query->orWhere('notification_for', 'allstudents' );
        $classes = getStudentClasses();
        if ( count( $classes ) ) {
          foreach ($classes as $class_id) {
            $query->orWhere('student_class_id', $class_id);
          }
        }
               })
        ->paginate(getRecordsPerPage());
      }


           $view_name = getTheme().'::notifications.users-list';
        return view($view_name, $data);
    }

    public function display($slug)
    {
        $record = Notification::getRecordWithSlug($slug);
        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);

        $data['active_class']       = 'notifications';
        $data['title']              = $record->title;
        $data['layout']             = getLayout();
        $data['notification']       = $record;

        // return view('notifications.details', $data);

           $view_name = getTheme().'::notifications.details';
        return view($view_name, $data);
    }
}
