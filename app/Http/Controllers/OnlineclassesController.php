<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \App;

use App\Onlineclass;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Excel;
use Input;
use File;

class OnlineclassesController extends Controller
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
    if (!canDo('onlineclasses_access')) {
      prepareBlockUserMessage();
      return back();
    }

    $data['active_class']       = 'onlineclasses';
    $data['title']              = getPhrase('onlineclasses');
    $data['layout']              = getLayout();
    // return view('onlineclasses.list', $data);

    $view_name = getTheme() . '::onlineclasses.list';
    return view($view_name, $data);
  }

  public function attendenceIndex()
  {
    if (!canDo('onlineclasses_access')) {
      prepareBlockUserMessage();
      return back();
    }

    $data['active_class']       = 'batch_reports';
    $data['title']              = getPhrase('onlineclasses_attendance');
    $data['layout']              = getLayout();
    // return view('onlineclasses.list', $data);

    $view_name = getTheme() . '::onlineclasses.attendence-list';
    return view($view_name, $data);
  }

  /**
   * This method returns the datatables data to view
   * @return [type] [description]
   */
  public function getDatatable($slug = '')
  {

    if (!canDo('onlineclasses_access')) {
      prepareBlockUserMessage();
      return back();
    }

    $records = array();

    $institute_id   = adminInstituteId();
    // var_dump(checkRole(getUserGrade(3)));
    if (isStudent()) { // Student.
      $batches  = getStudentBatches();
      $records = Onlineclass::select(['valid_from', 'class_time', 'title', 'batch_id', 'subject_id', 'topic',  'url', 'id', 'slug', 'lmsseries_id', 'lmsnotes_id', 'student_class_id', 'live_quiz_id', 'class_duration'])->whereIn('batch_id', $batches);
    } elseif (isFaculty()) { // Faculty.
      $records = Onlineclass::select(['valid_from', 'class_time', 'title', 'batch_id', 'subject_id', 'topic',  'url', 'id', 'slug', 'lmsseries_id', 'lmsnotes_id', 'student_class_id', 'live_quiz_id', 'class_duration'])
        ->where('created_by_id', Auth::id());
    } else {
      $records = Onlineclass::select(['valid_from', 'class_time', 'title', 'batch_id', 'subject_id', 'topic', 'created_by_id', 'url', 'id', 'slug', 'lmsseries_id', 'lmsnotes_id', 'student_class_id', 'live_quiz_id', 'class_duration'])
        ->where('institute_id', $institute_id);

      // echo getEloquentSqlWithBindings( $records );
    }


    $subject_id = request('subject_id');
    if (! empty($subject_id)) {
      $records->where('subject_id', $subject_id);
    }

    $class_title = request('class_title');
    if (! empty($class_title)) {
      $records->where('student_class_id', $class_title);
    }
    $batch_id = request('batch_id');
    if (! empty($batch_id)) {
      $records->where('batch_id', $batch_id);
    }
    $faculty_id = request('faculty_id');
    if (! empty($faculty_id)) {
      $records->where('created_by_id', $faculty_id);
    }

    $from_date = request('from_date');
    $to_date = request('to_date');
    if (! empty($from_date) && ! empty($to_date)) {
      $from_date = date('Y-m-d', strtotime($from_date));
      $to_date = date('Y-m-d', strtotime($to_date));

      $records->where('valid_from', '>=', $from_date);
      $records->where('valid_to', '<=', $to_date);
    } elseif (! empty($from_date)) {
      $from_date = date('Y-m-d', strtotime($from_date));
      $records->where('valid_from', '>=', $from_date);
    } elseif (! empty($to_date)) {
      $to_date = date('Y-m-d', strtotime($to_date));
      $records->where('valid_to', '<=', $to_date);
    }

    $records->orderBy('id', 'desc');
    //print_r($records->getBindings());
    //dd($records->toSql());
    // echo getEloquentSqlWithBindings( $records );
    // $records = $records->get();
    return Datatables::of($records)
      ->addColumn('action', function ($records) {

        $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">';

        $temp = '<li><a href="' . route('class.attendence', ['slug' => $records->slug]) . '"><i class="fa fa-clock-o"></i>Attendance</a></li>';

        $temp .= '<li><a href="' . route('class.absent', ['slug' => $records->slug]) . '"><i class="fa fa-circle-o"></i>Absents</a></li>';
        if (checkRole(getUserGrade(2))) {

          $temp .= '<li><a href="' . route('onlineclass.lmsnotes', ['slug' => $records->slug]) . '"><i class="fa fa-pencil"></i>update LMS Notes</a></li>';
        }


        //$temp.= '<li><a href="'.route('onlineclasses.add_live_quiz', ['slug' => $records->slug]).'"><i class="fa fa-pencil"></i>update Live Quizzes</a></li>';
        if (checkRole(getUserGrade(2))) {

          $temp .= '<li><a href="' . URL_ADMIN_ONLINECLASSES_EDIT . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>';
        }

        if (checkRole(getUserGrade(2))) {

          $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>';
        }



        $temp .= '</ul></div>';

        $link_data = $link_data . $temp;



        return $link_data;
      })
      ->editColumn('status', function ($records) {
        return ($records->status == 'Active') ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
      })

      ->editColumn('url', function ($row) {
        $str = '<p>URL:<a href="' . $row->url . '" target="_blank">View</a></p>';
        // LMS Series.
        if ($row->lmsseries_id) {
          if (isStudent()) {
            $str .= '<p>LMS Series: <a href="' . route('studentlms.subjectitems', ['slug' => $row->subject->slug, 'series_slug' => $row->lmsseries->slug]) . '" target="_blank">' . $row->lmsseries->title . '</a></p>';
          } else {
            $str .= '<p>LMS Series: <a href="' . URL_STUDENT_LMS_SERIES_VIEW . $row->lmsseries->slug . '" target="_blank">' . $row->lmsseries->title . '</a></p>';
          }
        } elseif (checkRole(getUserGrade(2))) {
          $str .= '<p><a style="color:red;" href="' . route('onlineclass.lmsnotes', ['slug' => $row->slug]) . '">Assign LMS</a></p>';
        }

        // LMS Notes.
        if ($row->lmsnotes_id) {
          if (isStudent()) {
            $str .= '<p>LMS Notes: <a href="' . route('student.lms_notes', ['slug' => $row->subject->slug]) . '" target="_blank">' . $row->lmsnotes->title . '</a></p>';
          } else {
            $str .= '<p>LMS Notes: <a href="' . route('lms.preview_notes', ['slug' => $row->lmsnotes->slug]) . '" target="_blank">' . $row->lmsnotes->title . '</a></p>';
          }
        } elseif (checkRole(getUserGrade(2))) {
          $str .= '<p><a style="color:red;" href="' . route('onlineclass.lmsnotes', ['slug' => $row->slug]) . '">Assign Notes</a></p>';
        }

        // LIVE Quiz.
        if ($row->live_quiz_id) {
          if (isStudent()) {
            $str .= '<p>Live Quiz: <a onClick="showInstructions(\'' . URL_STUDENT_TAKE_EXAM . $row->live_quiz->slug . '\')" href="javascript:void(0);">' . $row->live_quiz->title . '</a></p>';
          } else {
            $str .= '<p>Live Quiz: <a href="' . URL_QUIZ_EDIT . '/' . $row->live_quiz->slug . '" target="_blank">' . $row->live_quiz->title . '</a></p>';
          }
        } elseif (checkRole(getUserGrade(2))) {
          $str .= '<p><a style="color:red;" href="' . route('onlineclass.lmsnotes', ['slug' => $row->slug]) . '">Assign Live Quiz</a></p>';
        }
        return $str;
      })

      ->editColumn('institute_id', function ($records) {

        $institute  = getInstitute($records->institute_id);
        if ($institute)
          return '<a href = "' . URL_INSTITUTE_DETAILS . $institute->id . '">' . ucwords($institute->institute_name) . '</a>';

        return '-';
      })

      ->editColumn('valid_from', function ($row) {
        if (! empty($row->valid_from)) {
          return date('d-m-Y', strtotime($row->valid_from));
        } else {
          return $row->valid_from;
        }
      })
      ->editColumn('class_time', function ($row) {
        $class_time = date('h:i A', strtotime($row->class_time));
        if (! empty($row->class_duration)) {
          $class_time .= '<p>' . $row->class_duration . ' Mins.</p>';
        }
        return $class_time;
      })
      ->editColumn('batch_id', function ($records) {
        return $records->batch->name;
      })
      ->editColumn('created_by_id', function ($records) {
        return $records->createdby->name;
      })
      ->editColumn('title', function ($records) {
        return $records->student_class->name;
      })
      ->editColumn('topic', function ($records) {
        return $records->title;
      })
      ->editColumn('subject_id', function ($records) {
        return $records->subject->subject_title;
      })

      ->removeColumn('id')
      ->removeColumn('slug')
      ->removeColumn('lmsseries_id')
      ->removeColumn('lmsnotes_id')
      ->removeColumn('student_class_id')
      ->removeColumn('live_quiz_id')
      ->removeColumn('class_duration')
      ->rawColumns(['class_time', 'url', 'action'])
      ->make();
  }

  public function getAttendenceDatatable()
  {
    if (!canDo('onlineclasses_access')) {
      prepareBlockUserMessage();
      return back();
    }

    $records = array();

    $institute_id   = adminInstituteId();
    if (checkRole(getUserGrade(3))) { // Student.
      $batches  = getStudentBatches();
      $records = Onlineclass::select(['valid_from', 'class_time', 'title', 'batch_id', 'topic', 'id', 'slug', 'lmsseries_id', 'lmsnotes_id', 'student_class_id', 'live_quiz_id'])->whereIn('batch_id', $batches);
    } elseif (checkRole(getUserGrade(10))) { // Faculty.
      $records = Onlineclass::select(['valid_from', 'class_time', 'title', 'batch_id', 'topic',  'url', 'id', 'slug', 'lmsseries_id', 'lmsnotes_id', 'student_class_id', 'live_quiz_id'])
        ->where('created_by_id', Auth::id());
    } else {
      $records = Onlineclass::select(['valid_from', 'class_time', 'title', 'batch_id', 'subject_id', 'topic', 'created_by_id', 'url', 'id', 'slug', 'lmsseries_id', 'lmsnotes_id', 'student_class_id', 'live_quiz_id'])
        ->where('institute_id', $institute_id);
    }

    $subject_id = request('subject_id');
    if (! empty($subject_id)) {
      $records->where('subject_id', $subject_id);
    }

    $class_title = request('class_title');
    if (! empty($class_title)) {
      $records->where('student_class_id', $class_title);
    }
    $batch_id = request('batch_id');
    if (! empty($batch_id)) {
      $records->where('batch_id', $batch_id);
    }
    $faculty_id = request('faculty_id');
    if (! empty($faculty_id)) {
      $records->where('created_by_id', $faculty_id);
    }

    $from_date = request('from_date');
    $to_date = request('to_date');
    if (! empty($from_date) && ! empty($to_date)) {
      $from_date = date('Y-m-d', strtotime($from_date));
      $to_date = date('Y-m-d', strtotime($to_date));

      $records->where('valid_from', '>=', $from_date);
      $records->where('valid_to', '<=', $to_date);
    } elseif (! empty($from_date)) {
      $from_date = date('Y-m-d', strtotime($from_date));
      $records->where('valid_from', '>=', $from_date);
    } elseif (! empty($to_date)) {
      $to_date = date('Y-m-d', strtotime($to_date));
      $records->where('valid_to', '<=', $to_date);
    }

    $records->orderBy('valid_from', 'desc');
    //print_r($records->getBindings());
    //dd($records->toSql());
    return Datatables::of($records)
      ->addColumn('attendence', function ($records) {

        $count = \App\OnlineclassAttendance::where('class_id', $records->id)->groupBy('student_id')->get()->count();
        $attendence_link = '<a href="' . route('class.attendence', ['slug' => $records->slug]) . '" class="btn btn-success"></i>' . $count . '</a>';
        return $attendence_link;
      })
      ->addColumn('absents', function ($records) {

        $attendence = \App\Onlineclass::select(['oca.student_id', 'oca.created_at'])
          ->join('online_classes_attendence as oca', 'oca.class_id', '=', 'onlineclasses.id')
          ->join('users', 'users.id', '=', 'oca.student_id')
          ->where('onlineclasses.slug', $records->slug);
        if (checkRole(getUserGrade(['student']))) {
          $attendence->where('oca.student_id', \Auth::id());
        } else {
          $attendence->groupBy('oca.student_id');
        }

        $attendence = $attendence->orderBy('created_at', 'desc')->get()->pluck('student_id')->toArray();

        $absent_count = \App\BatchStudent::select(['users.name', 'users.student_class_id', 'users.course_id'])->join('onlineclasses', 'onlineclasses.batch_id', '=', 'batch_students.batch_id')
          ->join('users', 'users.id', '=', 'batch_students.user_id')
          ->whereNotIn('batch_students.user_id', $attendence)
          ->where('onlineclasses.slug', $records->slug)->get()
          ->count();
        $absents_link = '<a href="' . route('class.absent', ['slug' => $records->slug]) . '" class="btn btn-danger">' . $absent_count . '</a>';

        return $absents_link;
      })
      ->addColumn('action', function ($records) {

        $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">';

        $temp = '<li><a href="' . route('class.attendence', ['slug' => $records->slug]) . '"><i class="fa fa-clock-o"></i>Attendance</a></li>';

        // $temp .= '<li><a href="'.route('class.absent', ['slug' => $records->slug]).'"><i class="fa fa-circle-o"></i>Absents</a></li>';

        // $temp = '<li><a href="'.route('onlineclass.lmsnotes', ['slug' => $records->slug]).'"><i class="fa fa-pencil"></i>update LMS Notes</a></li>';

        //$temp.= '<li><a href="'.route('onlineclasses.add_live_quiz', ['slug' => $records->slug]).'"><i class="fa fa-pencil"></i>update Live Quizzes</a></li>';

        $temp .= '<li><a href="' . URL_ADMIN_ONLINECLASSES_EDIT . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>';



        $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>';



        $temp .= '</ul></div>';

        $link_data = $link_data . $temp;



        return $link_data;
      })
      ->editColumn('status', function ($records) {
        return ($records->status == 'Active') ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
      })

      ->editColumn('institute_id', function ($records) {

        $institute  = getInstitute($records->institute_id);
        if ($institute)
          return '<a href = "' . URL_INSTITUTE_DETAILS . $institute->id . '">' . ucwords($institute->institute_name) . '</a>';

        return '-';
      })
      ->editColumn('valid_from', function ($row) {
        if (! empty($row->valid_from)) {
          return date('d-m-Y', strtotime($row->valid_from));
        } else {
          return $row->valid_from;
        }
      })
      ->editColumn('class_time', function ($row) {
        return date('h:i A', strtotime($row->class_time));
      })
      ->editColumn('batch_id', function ($records) {
        return $records->batch->name;
      })
      ->editColumn('created_by_id', function ($records) {
        return $records->createdby->name;
      })
      ->editColumn('topic', function ($records) {
        return $records->title;
      })
      ->editColumn('subject_id', function ($records) {
        return $records->subject->subject_title;
      })
      ->removeColumn('url')
      ->removeColumn('topic')
      ->removeColumn('id')
      ->removeColumn('slug')
      ->removeColumn('lmsseries_id')
      ->removeColumn('lmsnotes_id')
      ->removeColumn('student_class_id')
      ->removeColumn('live_quiz_id')
      ->rawColumns(['attendence','absents','action'])
      ->make();
  }


  /**
   * This method loads the create view
   * @return void
   */
  public function create()
  {
    if (!canDo('onlineclasses_create')) {
      prepareBlockUserMessage();
      return back();
    }
    $data['record']           = FALSE;
    $data['active_class']       = 'onlineclasses';
    $data['title']              = 'Create Online Class';
    $data['layout']              = getLayout();
    // return view('onlineclasses.add-edit', $data);

    $view_name = getTheme() . '::onlineclasses.add-edit';
    return view($view_name, $data);
  }

  /**
   * This method loads the edit view based on unique slug provided by user
   * @param  [string] $slug [unique slug of the record]
   * @return [view with record]
   */
  public function edit($slug)
  {
    if (!canDo('onlineclasses_edit')) {
      prepareBlockUserMessage();
      return back();
    }

    $record = Onlineclass::getRecordWithSlug($slug);
    if ($isValid = $this->isValidRecord($record))
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
    $data['record']           = $record;
    $data['active_class']       = 'onlineclasses';
    $data['settings']         = FALSE;
    $data['title']              = 'Edit Online Class';
    $data['layout']             = getLayout();
    // return view('onlineclasses.add-edit', $data);

    $view_name = getTheme() . '::onlineclasses.add-edit';
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
    if (!canDo('onlineclasses_edit')) {
      prepareBlockUserMessage();
      return back();
    }

    $record = Onlineclass::getRecordWithSlug($slug);
    $rules = [
      'title'            => 'bail|required|max:50',

      'valid_from'        => 'bail|required',
      'valid_to'        => 'bail|required',
      'batch_id'       => 'bail|required',
      'class_time'       => 'bail|required',
    ];
    /**
     * Check if the title of the record is changed,
     * if changed update the slug value based on the new title
     */
    // dd($request->input('packages'));
    $name = $request->title;
    if ($name != $record->title)
      $record->slug = $record->makeSlug($name);

    //Validate the overall request
    $this->validate($request, $rules);

    $record->title            = $name;
    //$record->valid_from			= $request->valid_from;
    //$record->valid_to			= $request->valid_to;

    $valid_from = $request->valid_from;
    if (! empty($request->class_time)) {
      $valid_from .= ' ' . $request->class_time;
    }
    $record->valid_from     = $valid_from;



    $valid_to = $request->valid_to;
    if (! empty($request->valid_to_time)) {
      $valid_to .= ' ' . $request->valid_to_time;
    }
    $record->valid_to     = $valid_to;
    $record->class_time = $request->class_time;
    if (! empty($request->class_duration)) {
      $record->class_duration     = $request->class_duration;
    } else {
      $record->class_duration     = 50;
    }
    $record->subject_id = $request->subject_id;
    $record->topic = $request->topic;

    $record->url        = $request->url;
    // $record->short_description    = $request->short_description;
    // $record->description    = $request->description;
    $record->batch_id    = $request->batch_id;

    if ($request->student_class_id) {
      $record->student_class_id    = $request->student_class_id;
    } else {
      $record->student_class_id    = null;
    }

    $record->record_updated_by   = Auth::user()->id;
    if (! empty($request->created_by_id)) {
      $record->created_by_id  = $request->created_by_id;
    }
    $record->save();

    // $record->packages()->sync(array_filter((array)$request->input('packages')));

    flash('success', 'record_updated_successfully', 'success');
    return redirect(URL_ADMIN_ONLINECLASSES);
  }

  /**
   * This method adds record to DB
   * @param  Request $request [Request Object]
   * @return void
   */
  public function store(Request $request)
  {
    if (!canDo('onlineclasses_create')) {
      prepareBlockUserMessage();
      return back();
    }

    $rules = [
      'title'            => 'bail|required|max:50',

      'valid_from'        => 'bail|required',
      'valid_to'        => 'bail|required',
      'batch_id'       => 'bail|required',
      'class_time'       => 'bail|required',
      // 'packages' => 'required',
    ];
    $this->validate($request, $rules);
    $record = new Onlineclass();
    $name              =  $request->title;
    $record->title         = $name;
    $record->slug         = $record->makeSlug($name);

    $valid_from = $request->valid_from;
    if (! empty($request->class_time)) {
      $valid_from .= ' ' . $request->class_time;
    }
    $record->valid_from     = $valid_from;

    $valid_to = $request->valid_to;
    if (! empty($request->valid_to_time)) {
      $valid_to .= ' ' . $request->valid_to_time;
    }
    $record->valid_to      = $valid_to;
    $record->class_time = $request->class_time;
    if (! empty($request->class_duration)) {
      $record->class_duration     = $request->class_duration;
    } else {
      $record->class_duration     = 50;
    }
    $record->subject_id = $request->subject_id;
    $record->topic = $request->topic;

    $record->url        = $request->url;
    // $record->short_description  = $request->short_description;
    // $record->description    = $request->description;
    $record->batch_id    = $request->batch_id;

    if ($request->student_class_id) {
      $record->student_class_id    = $request->student_class_id;
    } else {
      $record->student_class_id    = null;
    }

    $record->record_updated_by   = Auth::user()->id;
    if (! empty($request->created_by_id)) {
      $record->created_by_id  = $request->created_by_id;
    } else {
      $record->created_by_id  = Auth::id();
    }
    $record->institute_id   = adminInstituteId();


    $record->save();

    // $record->packages()->sync(array_filter((array)$request->input('packages')));

    flash('success', 'record_added_successfully', 'success');
    return redirect(URL_ADMIN_ONLINECLASSES);
  }

  /**
   * Delete Record based on the provided slug
   * @param  [string] $slug [unique slug]
   * @return Boolean
   */
  public function delete($slug)
  {
    if (!canDo('onlineclasses_delete')) {
      prepareBlockUserMessage();
      return back();
    }
    /**
     * Delete the questions associated with this quiz first
     * Delete the quiz
     * @var [type]
     */
    $record = Onlineclass::where('slug', $slug)->first();
    if (!env('DEMO_MODE')) {
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
    return URL_ADMIN_ONLINECLASSES;
  }

  public function usersList()
  {
    /*
        $institute_id   = Auth::user()->institute_id;

        $data['active_class']       = 'onlineclasses';
        $data['title']              = getPhrase('onlineclasses');
        $data['layout']             = getLayout();
        $date = date('Y-m-d');
        //$data['onlineclasses']  	= Onlineclass::where('institute_id',$institute_id)->paginate(getRecordsPerPage());

        $batches = getStudentBatches();
        $data['onlineclasses'] = Onlineclass::whereIn('batch_id', $batches)->paginate(getRecordsPerPage());

        $view_name = getTheme().'::onlineclasses.users-list';
        return view($view_name, $data);
        */

    if (!canDo('onlineclasses_access')) {
      prepareBlockUserMessage();
      return back();
    }

    $data['active_class']       = 'onlineclasses';
    $data['title']              = getPhrase('onlineclasses');
    $data['layout']              = getLayout();
    // return view('onlineclasses.list', $data);

    $view_name = getTheme() . '::onlineclasses.list';
    return view($view_name, $data);
  }

  public function display($slug)
  {
    $record = Onlineclass::getRecordWithSlug($slug);
    if ($isValid = $this->isValidRecord($record))
      return redirect($isValid);

    $data['active_class']       = 'onlineclasses';
    $data['title']              = $record->title;
    $data['layout']             = getLayout();
    $data['notification']       = $record;

    // return view('onlineclasses.details', $data);

    $view_name = getTheme() . '::onlineclasses.details';
    return view($view_name, $data);
  }

  public function studentAttendence($class_id)
  {
    $student_id    =  Auth::id();
    $class_id      =  $class_id;


    $rr = \App\OnlineclassAttendance::create([
      'class_id' => $class_id,
      'student_id' => $student_id,
    ]);
    // dd( $rr );
    $record = Onlineclass::where('id', $class_id)->first();

    if ($isValid = $this->isValidRecord($record))
      return redirect($isValid);

    $data['url']       = $record->url;
    return redirect()->away($record->url);
  }

  public function classAttendence($slug)
  {
    $record = Onlineclass::getRecordWithSlug($slug);
    if ($isValid = $this->isValidRecord($record))
      return redirect($isValid);

    $data['active_class']       = 'onlineclasses';
    $data['title']              = $record->title;
    $data['layout']             = getLayout();
    $data['onlinecalss']       = $record;

    $view_name = getTheme() . '::onlineclasses.attendance';
    return view($view_name, $data);
  }

  /**
   * This method returns the datatables data to view
   * @return [type] [description]
   */
  public function classAttendenceList($slug)
  {

    $records = Onlineclass::select(['users.name', 'oca.created_at'])
      ->join('online_classes_attendence as oca', 'oca.class_id', '=', 'onlineclasses.id')
      ->join('users', 'users.id', '=', 'oca.student_id')
      ->where('onlineclasses.slug', $slug);
    if (checkRole(getUserGrade(['student']))) {
      $records->where('oca.student_id', \Auth::id());
    } else {
      $records->groupBy('oca.student_id');
    }

    $records->orderBy('created_at', 'desc');

    return Datatables::of($records)
      ->editColumn('created_at', function ($records) {
        return date('d/m/Y h:i A', strtotime($records->created_at));
      })
      ->make();
  }

  public function classAbsent($slug)
  {
    $record = Onlineclass::getRecordWithSlug($slug);
    if ($isValid = $this->isValidRecord($record))
      return redirect($isValid);

    $data['active_class']       = 'onlineclasses';
    $data['title']              = $record->title;
    $data['layout']             = getLayout();
    $data['onlinecalss']       = $record;

    $view_name = getTheme() . '::onlineclasses.absents';
    return view($view_name, $data);
  }
  /**
   * This method returns the datatables data to view
   * @return [type] [description]
   */
  public function classAbsentList($slug)
  {

    $attendence = Onlineclass::select(['oca.student_id', 'oca.created_at'])
      ->join('online_classes_attendence as oca', 'oca.class_id', '=', 'onlineclasses.id')
      ->join('users', 'users.id', '=', 'oca.student_id')
      ->where('onlineclasses.slug', $slug);
    if (checkRole(getUserGrade(['student']))) {
      $attendence->where('oca.student_id', \Auth::id());
    } else {
      $attendence->groupBy('oca.student_id');
    }

    $attendence = $attendence->orderBy('created_at', 'desc')->get()->pluck('student_id')->toArray();

    // $records = \App\BatchStudent::select(['users.name', 'users.student_class_id', 'users.course_id'])->join('onlineclasses', 'onlineclasses.batch_id', '=', 'batch_students.batch_id')
    // ->join('users', 'users.id', '=', 'batch_students.user_id')
    // ->whereNotIn('batch_students.user_id', $attendence)
    // ->where('onlineclasses.slug', $slug)
    // ->groupBy('batch_students.user_id');

    // ;

    // return Datatables::of($records)
    // ->editColumn('student_class_id', function($row)
    // {
    //     $class = \App\StudentClass::find( $row->student_class_id );
    //     if ( $class ) {
    //       return $class->name;
    //     } else {
    //       return '-';
    //     }
    // })
    // ->editColumn('course_id', function($row)
    // {
    //     $course = \App\Course::find( $row->course_id );
    //     if ( $course ) {
    //       return $course->title;
    //     } else {
    //       return '-';
    //     }
    // })
    // ->make();
    $records = \App\BatchStudent::select([
      'users.name',
      'users.student_class_id',
      'users.course_id'
    ])
      ->join('onlineclasses', 'onlineclasses.batch_id', '=', 'batch_students.batch_id')
      ->join('users', 'users.id', '=', 'batch_students.user_id')
      ->whereNotIn('batch_students.user_id', $attendence)
      ->where('onlineclasses.slug', $slug)
      ->groupBy('batch_students.user_id', 'users.name', 'users.student_class_id', 'users.course_id');

    return Datatables::of($records)
      ->addColumn('name', function ($row) {
        return $row->name;
      })
      ->editColumn('student_class_id', function ($row) {
        $class = \App\StudentClass::find($row->student_class_id);
        return $class ? $class->name : '-';
      })
      ->editColumn('course_id', function ($row) {
        $course = \App\Course::find($row->course_id);
        return $course ? $course->title : '-';
      })
      ->filterColumn('name', function ($query, $keyword) {
        $query->where('users.name', 'like', "%{$keyword}%");
      })
      ->filterColumn('student_class_id', function ($query, $keyword) {
        $query->where('users.student_class_id', 'like', "%{$keyword}%");
      })
      ->filterColumn('course_id', function ($query, $keyword) {
        $query->where('users.course_id', 'like', "%{$keyword}%");
      })
      ->make(true);
  }

  /**
   * List the categories available
   * @param  [type] $slug [description]
   * @return [type]       [description]
   */
  public function whiteBoard($slug = '')
  {
    $data['layout']             = getLayout();
    $data['title'] = 'White board';
    $data['active_class'] = 'whiteboard';
    $view_name = getTheme() . '::onlineclasses.ziteboard';
    return view($view_name, $data);
  }

  public function assignLmsNotes(Request $request, $slug)
  {

    $record = Onlineclass::getRecordWithSlug($slug);
    if ($isValid = $this->isValidRecord($record))
      return redirect($isValid);

    if ($request->isMethod('post')) {
      $update_values = [];
      if (! empty($request->lmsseries_id)) {
        $record->lmsseries_id = $request->lmsseries_id;
        $update_values['lmsseries_id'] = $request->lmsseries_id;
      }

      if (! empty($request->lmsnotes_id)) {
        $record->lmsnotes_id = $request->lmsnotes_id;
        $update_values['lmsnotes_id'] = $request->lmsnotes_id;
      }

      if (! empty($request->live_quiz_id)) {
        $record->live_quiz_id = $request->live_quiz_id;
        $update_values['live_quiz_id'] = $request->live_quiz_id;
      }
      $record->save();

      $institute_id   = adminInstituteId();
      if (! empty($request->lmsseries_id)) {
        $assigned_record = \App\BatchLms::where('batch_id', $record->batch_id)->where('institute_id', $institute_id)->where('lms_series_id', $request->lmsseries_id)->first();
        if (! $assigned_record) {
          \App\BatchLms::insert([
            'batch_id' => $record->batch_id,
            'institute_id' => $institute_id,
            'lms_series_id' => $request->lmsseries_id,
          ]);
        }
      }

      if (! empty($request->lmsnotes_id)) {
        $assigned_record = \DB::table('batch_lmsnotes')->where('batch_id', $record->batch_id)->where('institute_id', $institute_id)->where('lms_note_id', $request->lmsnotes_id)->first();
        if (! $assigned_record) {
          \DB::table('batch_lmsnotes')->insert([
            'batch_id' => $record->batch_id,
            'institute_id' => $institute_id,
            'lms_note_id' => $request->lmsnotes_id,
          ]);
        }
      }


      if (! empty($request->live_quiz_id)) {
        $assigned_record = \DB::table('batch_quizzes')
          ->where('batch_id', $record->batch_id)
          ->where('institute_id', $institute_id)
          ->where('quiz_id', $request->live_quiz_id)
          ->where('onlineclass_id', $record->id)
          ->first();
        if (! $assigned_record) {
          \DB::table('batch_quizzes')->insert([
            'batch_id' => $record->batch_id,
            'institute_id' => $institute_id,
            'quiz_id' => $request->live_quiz_id,
            'user_id' => $record->record_updated_by,
            'onlineclass_id' => $record->id,
          ]);
        }
      }


      // dd( $record->refresh() );
      flash('success', 'record_added_successfully', 'success');
      $referer = $request->referer;
      if (! empty($referer)) {
        return redirect($referer);
      } else {
        return redirect(URL_ADMIN_ONLINECLASSES);
      }
    }
    $data['layout']             = getLayout();
    $data['title'] = 'Assign LMS and Notes';
    $data['active_class'] = 'onlineclass';
    $data['record'] = $record;
    $view_name = getTheme() . '::onlineclasses.update_lms_notes';
    return view($view_name, $data);
  }

  public function getBatches($class_id)
  {
    $batches = \App\Batch::select(['id', 'name'])
      ->where('institute_id', adminInstituteId())
      ->where('student_class_id', $class_id)->get();
    if (isFaculty()) {
      $faculty_batches = getFacultyBatches();
      $batches = \App\Batch::select(['id', 'name'])->where('student_class_id', $class_id)->whereIn('id', $faculty_batches)->get();
    }
    $parents =  array();
    array_push($parents, array('id' => '', 'text' => 'Please select'));
    foreach ($batches as $key => $value) {
      $r = array('id' => $value->id, 'text' => $value->name);
      array_push($parents, $r);
    }
    return json_encode($parents);
  }

  public function getLms($subject_id, $chapter_id)
  {
    $lmsseries = \App\LmsSeries::where('subject_id', $subject_id)->where('chapter_id', $chapter_id)->get();

    $parents =  array();
    array_push($parents, array('id' => '', 'text' => 'Please select'));
    foreach ($lmsseries as $key => $value) {
      $r = array('id' => $value->id, 'text' => $value->title);
      array_push($parents, $r);
    }
    return json_encode($parents);
  }

  public function getNotes($subject_id, $chapter_id)
  {
    $notes = \App\LmsNote::where('subject_id', $subject_id)->where('chapter_id', $chapter_id)->get();

    $parents =  array();
    array_push($parents, array('id' => '', 'text' => 'Please select'));
    foreach ($notes as $key => $value) {
      $r = array('id' => $value->id, 'text' => $value->title);
      array_push($parents, $r);
    }
    return json_encode($parents);
  }

  public function getUser($user_id)
  {
    $user = \App\User::find($user_id)->toJson();
    return $user;
  }

  public function getFacultyBatches($batch_id)
  {
    $batches = \App\User::where('institute_id', adminInstituteId())
      ->join('batch_faculty', 'batch_faculty.user_id', '=', 'users.id')
      ->where('batch_id', $batch_id)
      ->groupBy('users.id')
      ->get()->pluck('name', 'id')->toArray();

    $parents =  array();
    array_push($parents, array('id' => '', 'text' => 'Please select'));
    if (! empty($batches)) {
      foreach ($batches as $id => $name) {
        $r = array('id' => $id, 'text' => $name);
        array_push($parents, $r);
      }
    }
    return json_encode($parents);
  }

  public function getFacultySubjects($user_id)
  {
    $subjects = \App\Subject::join('users_subjects', 'users_subjects.subject_id', '=', 'subjects.id')
      ->where('user_id', $user_id)
      //->groupBy('users.id')
      ->get()->pluck('subject_title', 'id')->toArray();

    $parents =  array();
    array_push($parents, array('id' => '', 'text' => 'Please select'));
    if (! empty($subjects)) {
      foreach ($subjects as $id => $name) {
        $r = array('id' => $id, 'text' => $name);
        array_push($parents, $r);
      }
    }
    return json_encode($parents);
  }

  public function updateLiveQuiz(Request $request, $slug)
  {

    $record = \App\Onlineclass::getRecordWithSlug($slug);
    if ($isValid = $this->isValidRecord($record))
      return redirect($isValid);

    if ($request->isMethod('post')) {

      $saved_series = $request->saved_series;
      $batch_id = $request->batch_id;
      $is_popquiz = $request->is_popquiz;

      $batch_quizses = \App\BatchQuizzes::where('institute_id', $record->institute_id)
        ->where('batch_id', $record->batch_id)
        ->where('onlineclass_id', $record->id);
      if (isFaculty()) {
        $batch_quizses->where('user_id', \Auth::id());
      }
      $batch_quizses->delete();

      if (! empty($saved_series)) {
        foreach ($saved_series as $key => $quiz_id) {
          $quiz = \App\Quiz::find($quiz_id);
          $check = \App\BatchQuizzes::where('institute_id', $record->institute_id)
            ->where('batch_id', $record->batch_id)
            ->where('quiz_id', $quiz_id)
            ->where('onlineclass_id', $record->id)
            ->where('category_id', $quiz->category_id);
          if (isFaculty()) {
            $check->where('user_id', \Auth::id());
          }
          $check = $check->first();
          if ($check) {
            $check->is_popquiz = $is_popquiz[$quiz_id];
            $check->save();
          } else {
            $batch_quiz               = new \App\BatchQuizzes();
            $batch_quiz->quiz_id      = $quiz_id;
            if ($quiz) {
              $batch_quiz->category_id  = $quiz->category_id;
            }
            $batch_quiz->batch_id     = $batch_id;
            $batch_quiz->onlineclass_id     = $record->id;
            $batch_quiz->institute_id = $record->institute_id;
            $batch_quiz->user_id      = $record->record_updated_by;
            $batch_quiz->date_time = date('Y-m-d') . ' ' . $record->class_time;
            $batch_quiz->is_popquiz = $is_popquiz[$quiz_id];
            $batch_quiz->save();
          }
        }
      }

      flash('success', 'batches_updated_successfully', 'success');
      return redirect()->route('onlineclasses.index');
    }
    $data['record']           = $record;

    $data['layout']             = getLayout();
    $data['title'] = 'Update Live Quiz';
    $data['active_class'] = 'onlineclass';
    $data['record'] = $record;
    $view_name = getTheme() . '::onlineclasses.update_live_quiz';
    return view($view_name, $data);
  }

  public function updateLiveQuiz2(Request $request, $slug)
  {

    $record = \App\Onlineclass::getRecordWithSlug($slug);
    if ($isValid = $this->isValidRecord($record))
      return redirect($isValid);

    if ($request->isMethod('post')) {

      $saved_series = $request->saved_series;
      $batch_id = $request->batch_id;
      $is_popquiz = $request->is_popquiz;

      $batch_quizses = \App\BatchQuizzes::where('institute_id', $record->institute_id)
        ->where('batch_id', $record->batch_id)
        ->where('onlineclass_id', $record->id);
      if (isFaculty()) {
        $batch_quizses->where('user_id', \Auth::id());
      }
      $batch_quizses->delete();

      if (! empty($saved_series)) {
        foreach ($saved_series as $key => $quiz_id) {
          $quiz = \App\Quiz::find($quiz_id);
          $check = \App\BatchQuizzes::where('institute_id', $record->institute_id)
            ->where('batch_id', $record->batch_id)
            ->where('quiz_id', $quiz_id)
            ->where('onlineclass_id', $record->id)
            ->where('category_id', $quiz->category_id);
          if (isFaculty()) {
            $check->where('user_id', \Auth::id());
          }
          $check = $check->first();
          if ($check) {
            $check->is_popquiz = $is_popquiz[$quiz_id];
            $check->save();
          } else {
            $batch_quiz               = new \App\BatchQuizzes();
            $batch_quiz->quiz_id      = $quiz_id;
            if ($quiz) {
              $batch_quiz->category_id  = $quiz->category_id;
            }
            $batch_quiz->batch_id     = $batch_id;
            $batch_quiz->onlineclass_id     = $record->id;
            $batch_quiz->institute_id = $record->institute_id;
            $batch_quiz->user_id      = $record->record_updated_by;
            $batch_quiz->date_time = date('Y-m-d') . ' ' . $record->class_time;
            $batch_quiz->is_popquiz = $is_popquiz[$quiz_id];
            $batch_quiz->save();
          }
        }
      }

      flash('success', 'batches_updated_successfully', 'success');
      return redirect()->route('onlineclasses.index');
    }
    $data['record']           = $record;

    $data['layout']             = getLayout();
    $data['title'] = 'Update Live Quiz';
    $data['active_class'] = 'onlineclass';
    $data['record'] = $record;
    $view_name = getTheme() . '::onlineclasses.update_live_quiz2';
    return view($view_name, $data);
  }

  /**
   * Display a Import Users page
   *
   * @return Response
   */
  public function importClasses()
  {
    if (!checkRole(getUserGrade(2))) {
      prepareBlockUserMessage();
      return back();
    }

    $data['records']      = FALSE;
    $data['active_class'] = 'onlineclasses';
    $data['heading']      = getPhrase('onlineclasses');
    $data['title']        = getPhrase('import_onlineclasses');
    $data['layout']        = getLayout();
    // return view('users.import.import', $data);

    $view_name = getTheme() . '::onlineclasses.import.import';
    return view($view_name, $data);
  }

  public function readExcel(Request $request)
  {

    $columns = array(
      'excel'  => 'bail|required',
    );

    $this->validate($request, $columns);

    if (!checkRole(getUserGrade(2))) {
      prepareBlockUserMessage();
      return back();
    }
    $success_list = [];
    $failed_list = [];

    try {
      if (Input::hasFile('excel')) {
        $path = Input::file('excel')->getRealPath();
        $data = Excel::load($path, function ($reader) {})->get();

        $user_record = array();
        $users = array();
        $isHavingDuplicate = 0;
        if (!empty($data) && $data->count()) {

          foreach ($data as $key => $value) {

            foreach ($value as $record) {
              unset($user_record);

              $user_record['title'] = $record->title;
              $user_record['topic'] = $record->topic;
              $user_record['url'] = $record->url;
              $user_record['valid_from'] = str_replace('/', '-', $record->valid_from);
              $user_record['valid_to'] = str_replace('/', '-', $record->valid_to);
              $user_record['class_time'] = str_replace('/', '-', $record->class_time);

              $user_record['batch_id'] = (int) $record->batch_id;
              $user_record['created_by_id'] = (int) $record->faculty_id;
              $user_record['subject_id'] = (int) $record->subject_id;
              $user_record['student_class_id'] = (int) $record->student_class_id;

              $user_record = (object)$user_record;
              $failed_length = count($failed_list);
              if ($this->isRecordExists($record)) {
                $isHavingDuplicate = 1;
                $temp = array();
                $temp['record'] = $user_record;
                $temp['type'] = 'Record already exists';
                $failed_list[$failed_length] = (object)$temp;
                continue;
              }

              if (!$this->isValidImportRecord($record, 'faculty_batch')) {
                $isHavingDuplicate = 1;
                $temp = array();
                $temp['record'] = $user_record;
                $temp['type'] = 'Batch not assigned to the faculty';
                $failed_list[$failed_length] = (object)$temp;
                continue;
              }

              if (!$this->isValidImportRecord($record, 'class_batch')) {
                $isHavingDuplicate = 1;
                $temp = array();
                $temp['record'] = $user_record;
                $temp['type'] = 'Batch not belongs to the class';
                $failed_list[$failed_length] = (object)$temp;
                continue;
              }

              if (!$this->isValidImportRecord($record, 'user_record')) {
                $isHavingDuplicate = 1;
                $temp = array();
                $temp['record'] = $user_record;
                $temp['type'] = 'User does not exists';
                $failed_list[$failed_length] = (object)$temp;
                continue;
              }

              if (!$this->isValidImportRecord($record, 'url')) {
                $isHavingDuplicate = 1;
                $temp = array();
                $temp['record'] = $user_record;
                $temp['type'] = 'URL can not be empty';
                $failed_list[$failed_length] = (object)$temp;
                continue;
              }

              $users[] = $user_record;
            }
          }
          if ($this->addClass($users)) {
            $success_list = $users;
          }
        }
      }



      $this->excel_data['failed'] = $failed_list;
      $this->excel_data['success'] = $success_list;

      flash('success', 'record_added_successfully', 'success');
      $this->downloadExcel();
    } catch (Exception $e) {
      if (getSetting('show_foreign_key_constraint', 'module')) {

        flash('oops...!', $e->errorInfo, 'error');
      } else {
        flash('oops...!', 'improper_sheet_uploaded', 'error');
      }

      return back();
    }

    // URL_USERS_IMPORT_REPORT
    $data['failed_list']   =   $failed_list;
    $data['success_list']  =    $success_list;
    $data['records']      = FALSE;
    $data['layout']      = getLayout();
    $data['active_class'] = 'users';
    $data['heading']      = getPhrase('users');
    $data['title']        = getPhrase('report');

    // return view('users.import.import-result', $data);

    $view_name = getTheme() . '::onlineclasses.import.import-result';
    return view($view_name, $data);
  }

  public function isValidImportRecord($record, $type)
  {

    if ('faculty_batch' === $type) {
      $faculty = \App\User::where('id', (int) $record->faculty_id)->first();

      if ($faculty) {
        $faculty_batches = $faculty->faculty_batches()->get()->pluck('name', 'id')->toArray();

        if (in_array($record->batch_id, array_keys($faculty_batches))) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    } elseif ('class_batch' === $type) {
      $class_batch = \App\Batch::where('id', (int) $record->batch_id)->where('student_class_id', $record->student_class_id)->first();
      if ($class_batch) {
        return true;
      } else {
        return false;
      }
    } elseif ('user_record' === $type) {
      $faculty = \App\User::where('id', (int) $record->faculty_id)->first();
      // dd(getEloquentSqlWithBindings(\App\User::where('id', (int) $record->faculty_id)));
      if ($faculty) {
        return true;
      } else {
        return false;
      }
    } elseif ('url' === $type) {
      $url = $record->url;
      if (empty($url)) {
        $faculty = \App\User::where('id', (int) $record->faculty_id)->first();
        if ($faculty) {
          $url = $faculty->online_url;
        }
      }
      if (! empty($url)) {
        return true;
      } else {
        return false;
      }
    }
  }

  public function addClass($records)
  {
    if (! empty($records)) {

      foreach ($records as $request) {
        $record           = new \App\Onlineclass();
        $name           = $request->title;
        $record->title     = $name;

        $record->topic = ($request->topic) ? $request->topic : $name;
        $record->slug           = $record::makeSlug($name);

        $url = $request->url;
        if (empty($url)) {
          $faculty = \App\User::where('id', (int) $record->created_by_id)->first();
          if ($faculty) {
            $url = $faculty->online_url;
          }
        }
        if (empty($url)) {
          $url = url('/');
        }
        $record->url    = $url;
        $record->valid_from    = date('Y-m-d', strtotime($request->valid_from)) . ' ' . date('H:i', strtotime($request->class_time)) . ':00';
        $record->valid_to = date('Y-m-d', strtotime($request->valid_to));
        $record->class_time        = date('H:i', strtotime($request->class_time)) . ':00';

        $record->batch_id  = $request->batch_id;
        $record->created_by_id  = $request->created_by_id;
        $record->subject_id          = $request->subject_id;
        $record->student_class_id        = $request->student_class_id;
        $record->institute_id   = adminInstituteId();

        $record->save();
      }
    }
    return true;
  }

  /**
   * This method verifies if the record exists with the email or user name
   * If Exists it returns true else it returns false
   * @param  [type]  $value [description]
   * @param  string  $type  [description]
   * @return boolean        [description]
   */
  public function isRecordExists($record)
  {
    return \App\Onlineclass::where('title', $record->title)
      ->where('url', $record->url)
      ->where('valid_from', $record->valid_from)
      ->where('valid_to', $record->valid_to)
      ->where('class_time', $record->class_time)
      ->where('batch_id', $record->batch_id)
      ->where('created_by_id', $record->faculty_id)
      ->where('subject_id', $record->subject_id)
      ->where('student_class_id', $record->student_class_id)
      ->get()->count();
  }

  public function getFailedData()
  {
    return $this->excel_data;
  }

  public function downloadExcel()
  {
    Excel::create('users_report', function ($excel) {
      $excel->sheet('Failed', function ($sheet) {
        $sheet->row(1, array('Reason', 'title', 'topic', 'url', 'valid_from', 'valid_to', 'class_time', 'batch_id', 'faculty_id', 'subject_id', 'student_class_id'));
        $data = $this->getFailedData();
        $cnt = 2;
        // dd($data['failed']);
        foreach ($data['failed'] as $data_item) {
          $item = $data_item->record;
          $sheet->appendRow($cnt++, array($data_item->type, $item->title, $item->topic, $item->url, $item->valid_from, $item->valid_to, $item->class_time, $item->batch_id, $item->created_by_id, $item->subject_id, $item->student_class_id));
        }
      });

      $excel->sheet('Success', function ($sheet) {
        $sheet->row(1, array('title', 'topic', 'url', 'valid_from', 'valid_to', 'class_time', 'batch_id', 'faculty_id', 'subject_id', 'student_class_id'));
        $data = $this->getFailedData();
        // dd( $data );
        $cnt = 2;
        foreach ($data['success'] as $data_item) {
          $item = $data_item;
          $sheet->appendRow($cnt++, array($item->title, $item->topic, $item->url, $item->valid_from, $item->valid_to, $item->class_time, $item->batch_id, $item->created_by_id, $item->subject_id, $item->student_class_id));
        }
      });
    })->download('xlsx');

    return TRUE;
  }

  public function classEndAlerts()
  {
    $batches = getFacultyBatches();
    $onlineclasses = \App\Onlineclass::whereIn('batch_id', $batches)->whereNotNull('class_time')->whereNotNull('valid_from')->whereNotNull('valid_to')->whereRaw("'" . date('Y-m-d') . '\' BETWEEN DATE(valid_from) AND DATE(valid_to)')->where('created_by_id', \Auth::id());

    $notified = \DB::table('online_classes_alerts')->whereIn('class_id', $onlineclasses->get()->pluck('id')->toArray())->get()->pluck('class_id')->toArray();
    // print_r($notified);
    if (! empty($notified)) {
      $onlineclasses = $onlineclasses->whereNotIn('id', $notified);
    }

    $response = '';
    //echo getEloquentSqlWithBindings( $onlineclasses );
    foreach ($onlineclasses->get() as $row) {
      $class_duration = $row->class_duration;
      if (empty($class_duration)) {
        $class_duration = 50;
      }
      // echo $row->class_time . '#' . date('H:i:s', strtotime("+$class_duration minutes", strtotime(date('H:i:s'))) );
      $class_endtime = date('H:i:s', strtotime("+$class_duration minutes", strtotime(date($row->class_time))));

      $before_minutes = $class_duration - 10;
      $class_endtime_before_minutes = date('H:i:s', strtotime("+$before_minutes minutes", strtotime(date($row->class_time))));
      // echo date('H:i:s') . '$$' . $row->class_time . '##' . $class_endtime . '@@' . $class_endtime_before_minutes;

      $class_time_stamp = strtotime($row->class_time);
      $class_endtime_before_minutes_stamp = strtotime($class_endtime_before_minutes);
      $current_time_stamp = strtotime(date('H:i:s'));
      $class_endtime_stamp = strtotime($class_endtime);

      // echo date('H:i:s') . '$$' . $class_time_stamp . '##' . $class_endtime_before_minutes_stamp . '@@' . $current_time_stamp . '@@@' . $class_endtime_stamp;

      if ($class_endtime < date('H:i:s')) {
        continue;
      } elseif ($current_time_stamp >= $class_time_stamp && $current_time_stamp >= $class_endtime_before_minutes_stamp) {

        $response = 'Your class will be end in 5 minutes';

        \DB::table('online_classes_alerts')->insert([
          'class_id' => $row->id,
          'faculty_id' => \Auth::id(),
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s'),
        ]);
      }
    }
    return $response;
  }
}
