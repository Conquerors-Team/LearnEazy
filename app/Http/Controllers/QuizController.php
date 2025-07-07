<?php

namespace App\Http\Controllers;

use \App;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Http\Requests;
use App\Quiz;
use App\Subject;
use App\QuestionBank;
use App\QuizCategory;
use App\Batch;
use App\BatchQuizzes;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Exception;
// use Image;
use Intervention\Image\Laravel\Facades\Image;
use ImageSettings;
use File;

class QuizController extends Controller
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
    if (!canDo('exams_access')) {
      prepareBlockUserMessage();
      return back();
    }

    $data['active_class']       = 'exams';
    $data['title']              = getPhrase('exams');
    $data['layout']   =  getLayout();

    // return view('exams.quiz.list', $data);

    $view_name = getTheme() . '::exams.quiz.list';
    return view($view_name, $data);
  }

  public function dashboard()
  {

    $data['active_class']   = 'exams';
    $data['layout']        = getLayout();
    $data['title']              = getPhrase('dashboard');
    $view_name = getTheme() . '::exams.dashboard';
    return view($view_name, $data);
  }

  public function questionBankManagement(Request $request)
  {

    $subject_id = $request->subject_id;
    $question_category_id = $request->question_category_id;
    //dd($question_category_id);

    //print_r($request->all());
    $data['active_class']  = 'exams';
    $chapters = App\Chapter::with(['subject'])->orderBy('subject_id');
    if ($request->get('institute_id')) {
      $chapters->where('institute_id', $request->get('institute_id'));
    }
    //echo printSql($chapters);
    //die();
    $chapters = $chapters->get();
    $data['chapters']      = $chapters;
    $data['layout']        = getLayout();
    $data['title']         = getPhrase('question_bank_management');

    if (! empty($subject_id)) {
      $data['chapters']      = App\Chapter::with(['subject'])->where('subject_id', $subject_id)->get();
    }

    //dd( $data['chapters'] );

    $view_name = getTheme() . '::exams.questionbank-management';
    return view($view_name, $data);
  }

  /**
   * Course listing method
   * @return Illuminate\Database\Eloquent\Collection
   */
  public function testSeries()
  {
    if (!canDo('exams_access')) {
      prepareBlockUserMessage();
      return back();
    }

    $data['active_class']       = 'test_series';
    $data['title']              = getPhrase('exams');
    $data['layout']             =  getLayout();

    // return view('exams.quiz.list', $data);

    $view_name = getTheme() . '::exams.quiz.test-series';
    return view($view_name, $data);
  }

  /**
   * Course listing method
   * @return Illuminate\Database\Eloquent\Collection
   */
  public function liveQuizzes()
  {
    if (!canDo('exams_access')) {
      prepareBlockUserMessage();
      return back();
    }

    $data['active_class']       = 'live_quizzes';
    $data['title']              = getPhrase('live_quizzes');
    $data['layout']             =  getLayout();

    // return view('exams.quiz.list', $data);

    $view_name = getTheme() . '::exams.quiz.live-quizzes';
    return view($view_name, $data);
  }

  /**
   * This method returns the datatables data to view
   * @return [type] [description]
   */
  public function getDatatable($slug = '')
  {

    if (!canDo('exams_access')) {
      prepareBlockUserMessage();
      return back();
    }

    $records = array();

    $institute_id   = adminInstituteId();

    if ($slug == '') {
      $records = Quiz::select(['title', 'dueration', 'is_paid', 'total_marks', 'exam_type', 'tags', 'quizzes.slug', 'category_id', 'id', 'display_type'])
        ->where('quizzes.institute_id', $institute_id)
        ->whereNull('quizzes.display_type') // To differentiate from 'test series' quizzes
        ->where('category_id', '!=', QUIZTYPE_LIVEQUIZ);
    } else {
      if ($slug == 'live_quizzes') {
        $records = Quiz::select(['title', 'dueration', 'is_paid', 'total_marks', 'exam_type', 'tags', 'quizzes.slug', 'category_id', 'id', 'display_type'])
          ->whereNull('quizzes.display_type') // To differentiate from 'test series' quizzes
          ->where('quizzes.institute_id', $institute_id)
          ->where('category_id', QUIZTYPE_LIVEQUIZ);
      } else {
        $records = Quiz::select(['title', 'dueration', 'is_paid', 'total_marks', 'exam_type', 'tags', 'quizzes.slug', 'category_id', 'id', 'display_type'])
          ->whereNotNull('quizzes.display_type') // To differentiate from 'test series' quizzes
          ->where('quizzes.institute_id', $institute_id)
          // ->where('category_id', '!=', QUIZTYPE_LIVEQUIZ)
        ;
      }
    }

    if (isFaculty()) {
      $records->where('record_updated_by', \Auth::id());
    }

    $subject_id = request('subject');
    $chapter_id = request('chapter');
    $topic_id = request('topic');
    $sub_topic_id = request('sub_topic');
    $callfrom = request('callfrom');

    $records->orderBy('quizzes.updated_at', 'desc');

    return Datatables::of($records)
      ->addColumn('action', function ($records) use ($callfrom, $institute_id) {

        if ($callfrom == 'live_quizzes') {
          $class_id  = request('class_id');
          $batch_id = '';
          $onlineclass = \App\Onlineclass::find($class_id);
          if ($onlineclass) {
            $batch_id = $onlineclass->batch_id;
          }
          $assigned = DB::table('batch_quizzes')
            ->where('quiz_id', $records->id)
            ->where('institute_id', $institute_id)
            ->where('batch_id', $batch_id)
            ->where('onlineclass_id', $class_id)
            ->first();
          $selected = '';
          $is_popquiz_no = ' selected';
          $is_popquiz_yes = '';
          if ($assigned) {
            $selected = ' checked';
            if ($assigned->is_popquiz == 'yes') {
              $is_popquiz_no = '';
              $is_popquiz_yes = ' selected';
            }
          }

          return '<input type="checkbox" name="saved_series[' . $records->id . ']" value="' . $records->id . '" style="display:block;"' . $selected . '>&nbsp;
            <select name="is_popquiz[' . $records->id . ']">
              <option value="no"' . $is_popquiz_no . '>No</option>
              <option value="yes"' . $is_popquiz_yes . '>Yes</option>
            </select>
            ';
        } elseif ($callfrom == 'examseries') {
          $series_id = request('series_id');
          $assigned = DB::table('examseries_data')->where('examseries_id', $series_id)->where('institute_id', $institute_id)->where('quiz_id', $records->id)->first();
          $selected = '';
          if ($assigned) {
            $selected = ' checked';
          }
          //dd( $records->id );

          return '<input type="checkbox" name="saved_series[' . $records->id . ']" value="' . $records->id . '" style="display:block;"' . $selected . '>';
        } else {
          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                           <li><a href="' . URL_QUIZ_UPDATE_QUESTIONS . $records->slug . '"><i class="fa fa-spinner"></i>' . getPhrase("update_questions") . '</a></li>

                           <li><a href="' . route('exams.quiz.update_batches', $records->slug) . '"><i class="fa fa-spinner"></i>' . getPhrase("update_batches") . '</a></li>

                            <li><a href="' . URL_QUIZ_EDIT . '/' . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>';

          $temp = '';
          if (checkRole(getUserGrade(1))) {
            $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>';
          }

          $temp .= '</ul></div>';

          $link_data .= $temp;
          return $link_data;
        }
      })
      ->editColumn('is_paid', function ($records) {
        return ($records->is_paid) ? '<span class="label label-primary">' . getPhrase('paid') . '</span>' : '<span class="label label-success">' . getPhrase('free') . '</span>';
      })
      ->editColumn('title', function ($records) {
        $str = '<a href="' . URL_QUIZ_UPDATE_QUESTIONS . $records->slug . '">' . $records->title . '</a>';
        $subjects = '';
        if (count($records->subjects) > 0) {
          $subjects = '<p><span class="label label-info label-many">' . implode(
            '</span><span class="label label-info label-many"> ',
            $records->subjects->pluck('subject_title')->toArray()
          ) . '</span></p>';
        }
        return $str . $subjects;
      })

      ->editColumn('exam_type', function ($records) {
        return App\ExamType::where('code', $records->exam_type)->first()->title;
      })
      ->editColumn('institute_id', function ($records) {

        $institute  = getInstitute($records->institute_id);
        if ($institute)
          return '<a href = "' . URL_INSTITUTE_DETAILS . $institute->id . '">' . ucwords($institute->institute_name) . '</a>';

        return '-';
      })
      ->editColumn('is_paid', function ($records) {
        if ($records->display_type) {
          return $records->display_type;
        } else {
          return '-';
        }
      })

      ->removeColumn('id')
      ->removeColumn('slug')
      ->removeColumn('tags')
      // ->removeColumn('category_id')
      ->removeColumn('display_type')
      ->rawColumns(['title', 'action'])
      ->make();
  }

  /**
   * This method loads the create view
   * @return void
   */
  public function create($exam_type = '')
  {
    if (!canDo('exams_create')) {
      prepareBlockUserMessage();
      return back();
    }
    $data['record']             = FALSE;
    if ('test_series' === $exam_type) {
      $data['active_class']       = 'test_series';
    } elseif ('live_quizzes' == $exam_type) {
      $data['active_class']       = 'live_quizzes';
    } else {
      $data['active_class']       = 'exams';
    }
    $institute_id   = adminInstituteId();

    if ('live_quizzes' == $exam_type) {
      $data['categories']       = QuizCategory::where('id', QUIZTYPE_LIVEQUIZ)->get()->pluck('category', 'id')->toArray();
    } else {
      $data['categories']       = QuizCategory::get()->pluck('category', 'id')->toArray();
    }

    $data['instructions']       = App\Instruction::where('institute_id', $institute_id)->get()->pluck('title', 'id')->toArray();

    $data['exam_types']         = App\ExamType::where('status', '=', 1)->get()->pluck('title', 'code')->toArray();
    $data['title']              = getPhrase('create_exam');

    $batches           = Batch::where('institute_id', $institute_id)->get()->pluck('name', 'id')->toArray();

    $data['batches']   = $batches;
    $data['pre_data']  = array();
    $data['slots_times']    = makeTimeSlots();
    $data['layout']   =  getLayout();
    $data['exam_type'] = $exam_type;

    $view_name = getTheme() . '::exams.quiz.add-edit';
    return view($view_name, $data);
  }

  /**
   * This method loads the edit view based on unique slug provided by user
   * @param  [string] $slug [unique slug of the record]
   * @return [view with record]
   */
  public function edit($slug)
  {
    if (!canDo('exams_edit')) {
      prepareBlockUserMessage();
      return back();
    }

    $record = Quiz::getRecordWithSlug($slug);
    if ($isValid = $this->isValidRecord($record))
      return redirect($isValid);

    $data['record']           = $record;
    $data['exam_type'] = ! empty($record->display_type) ? 'test_series' : '';
    if ($record->category_id == QUIZTYPE_LIVEQUIZ) {
      $data['exam_type'] = 'live_quizzes';
    }
    if ('test_series' === $data['exam_type']) {
      $data['active_class']     = 'test_series';
    } elseif ('live_quizzes' === $data['exam_type']) {
      $data['active_class']     = 'live_quizzes';
    } else {
      $data['active_class']     = 'exams';
    }
    $data['settings']         = FALSE;
    $institute_id   = adminInstituteId();

    //$data['categories']       = array_pluck(QuizCategory::get(), 'category', 'id');

    if ('live_quizzes' == $data['exam_type']) {
      $data['categories'] = Arr::pluck(
        QuizCategory::where('id', QUIZTYPE_LIVEQUIZ)->get(),
        'category',
        'id'
      );
    } else {
      $data['categories'] = Arr::pluck(
        QuizCategory::get(),
        'category',
        'id'
      );
    }

    $data['instructions'] = Arr::pluck(
      \App\Instruction::where('institute_id', $institute_id)->get(),
      'title',
      'id'
    );



    $data['exam_types']   = App\ExamType::get()->pluck('title', 'code')->toArray();
    $data['title']        = getPhrase('edit_exam');
    $batches              = Batch::where('institute_id', $institute_id)->get()->pluck('name', 'id')->toArray();
    $data['batches']      = $batches;
    $data['pre_data']     = BatchQuizzes::where('quiz_id', $record->id)->pluck('batch_id')->toArray();
    $data['slots_times']  = makeTimeSlots();
    $data['layout']   =  getLayout();



    $view_name = getTheme() . '::exams.quiz.add-edit';
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
    // dd($request->all());
    if (!canDo('exams_edit')) {
      prepareBlockUserMessage();
      return back();
    }

    $record = Quiz::getRecordWithSlug($slug);
    $rules = [
      'title'               => 'bail|required|max:40',
      'dueration'           => 'bail|required|integer',
      //'pass_percentage'     => 'bail|required|numeric|max:100|min:1' ,
      // 'category_id'         => 'bail|required|integer' ,
      'instructions_page_id' => 'bail|required|integer',
    ];

    if ($request->display_type == 'chapter') {
      $rules['subject_id'] = 'required';
      $rules['chapter_id'] = 'required';
    }
    if ($request->display_type == 'subject') {
      $rules['subject_id'] = 'required';
    }

    if ($request->display_type == 'previousyear') {
      $rules['year'] = 'required';
    }

    /*
      if($request->quiz_applicability == 1){
        $rules['batch_id'] = 'required';
      }
      */

    /**
     * Check if the title of the record is changed,
     * if changed update the slug value based on the new title
     */
    $name = $request->title;
    if ($name != $record->title)
      $record->slug = $record->makeSlug($name, TRUE);

    //Validate the overall request
    $this->validate($request, $rules);

    if ($request->show_in_front == 1) {

      if ($request->is_paid == 1) {
        flash('Ooops...!', 'practice_exam_must_be_non_paid_exam', 'overlay');
        return back();
      } elseif ($request->exam_type != 'NSNT') {
        flash('Ooops...!', 'practice_exam_must_be_no_section_no_timer_exam', 'overlay');
        return back();
      }
    }
    $record->title        = $name;
    if (! empty($request->category_id)) {
      $record->category_id    = $request->category_id;
    } else {
      $record->category_id    = null;
    }
    $record->dueration      = $request->dueration;
    // $record->total_marks    = $request->total_marks;
    $record->marks_per_question = $request->marks_per_question;
    $record->pass_percentage  = $request->pass_percentage;
    $record->tags       = '';
    $record->is_paid      = $request->is_paid;

    $record->cost       = 0;
    $record->validity       = -1;
    if ($record->is_paid) {
      $record->cost         = $request->cost;
      $record->validity     = $request->validity;
    }

    $record->publish_results_immediately
      = 1;
    $record->having_negative_mark = 1;
    $record->negative_mark = $request->negative_mark;
    $record->instructions_page_id = $request->instructions_page_id;
    $record->show_in_front = $request->show_in_front;

    if (!$request->negative_mark)
      $record->having_negative_mark = 0;

    // $record->description    = $request->description;
    $record->record_updated_by  = Auth::user()->id;
    if (empty($record->record_created_by)) {
      $record->record_created_by   = \Auth::id();
    }

    $record->start_date = $request->start_date;
    $record->end_date = $request->end_date;
    $record->exam_type          = $request->exam_type;
    $record->has_language       = $request->has_language;
    if ($request->has_language == 1) {
      $record->language_name       = $request->language_name;
    }

    //$record->quiz_applicability   = $request->quiz_applicability;
    if ($request->start_time) {
      $record->start_time           = $request->start_time;
    }

    if ($request->display_type) {
      $record->display_type           = $request->display_type;
    }
    if ($request->chapter_id) {
      $record->chapter_id           = $request->chapter_id;
    }
    if ($request->subject_id) {
      $record->subject_id           = $request->subject_id;
    }
    if ($request->year) {
      $record->year           = $request->year;
    }

    if ($request->competitive_exam_type_id) {
      $record->competitive_exam_type_id  = $request->competitive_exam_type_id;
    }

    $record->save();

    /*
         $pre_data  = BatchQuizzes::where('quiz_id',$record->id)->pluck('id')->toArray();

         BatchQuizzes::whereIn('id',$pre_data)->delete();

         if($request->quiz_applicability == 1){
           BatchQuizzes::addRecord($record, $request->batch_id);
         }
         elseif($request->quiz_applicability == 0){
             BatchQuizzes::addForAll($record->institute_id, $record);
        }
        */


    $file_name = 'examimage';
    if ($request->hasFile($file_name)) {

      $rules = array($file_name => 'mimes:jpeg,jpg,png,gif|max:10000');
      $this->validate($request, $rules);

      $record->image      = $this->processUpload($request, $record, $file_name);
      $record->save();
    }

    flash('success', 'record_updated_successfully', 'success');
    if ($record->display_type == 'test_series') {
      return redirect()->route('exams.test_series');
    } elseif ($record->display_type == 'live_quizzes') {
      return redirect()->route('exams.live_quizzes');
    } else {
      return redirect()->route('exams.quizzes');
    }
  }

  /**
   * This method adds record to DB
   * @param  Request $request [Request Object]
   * @return void
   */
  public function store(Request $request)
  {
    // dd($request->all());
    
    if (!canDo('exams_create')) {
      prepareBlockUserMessage();
      return back();
    }


    $rules = [
      'title'               => 'bail|required|max:40',
      'dueration'           => 'bail|required|integer',
      //'category_id'         => 'bail|required|integer' ,
      'instructions_page_id' => 'bail|required|integer',
      // 'pass_percentage'     => 'bail|required|numeric|max:100|min:1' ,
      'examimage'                => 'bail|mimes:png,jpg,jpeg|max:2048'
    ];

    if ($request->display_type == 'chapter') {
      $rules['subject_id'] = 'required';
      $rules['chapter_id'] = 'required';
    }
    if ($request->display_type == 'subject') {
      $rules['subject_id'] = 'required';
    }

    if ($request->display_type == 'previousyear') {
      $rules['competitive_exam_type_id'] = 'required';
      $rules['year'] = 'required';
    }

    if ($request->display_type == 'grand') {
      $rules['competitive_exam_type_id'] = 'required';
    }

    /*
      if($request->quiz_applicability == 1){
        $rules['batch_id'] = 'required';
      }
      */
    $this->validate($request, $rules);

    if ($request->show_in_front == 1) {

      if ($request->is_paid == 1) {
        flash('Ooops...!', 'practice_exam_must_be_non_paid_exam', 'overlay');
        return back();
      } elseif ($request->exam_type != 'NSNT') {
        flash('Ooops...!', 'practice_exam_must_be_no_section_no_timer_exam', 'overlay');
        return back();
      }
    }

    DB::beginTransaction();

    try {


      $record = new Quiz();
      $name             =  $request->title;
      $record->title        = $name;
      $record->slug         = $record->makeSlug($name, TRUE);
      //$record->category_id    = $request->category_id;
      if (! empty($request->category_id)) {
        $record->category_id    = $request->category_id;
      } else {
        $record->category_id    = null;
      }
      $record->dueration      = $request->dueration;
      // $record->total_marks    = $request->total_marks;
      $record->marks_per_question = $request->marks_per_question;
      $record->pass_percentage  = $request->pass_percentage;
      $record->tags       = '';
      $record->is_paid      = $request->is_paid;
      $record->cost       = 0;
      $record->validity       = -1;
      if ($record->is_paid) {
        $record->cost         = $request->cost;
        $record->validity     = $request->validity;
      }

      $record->publish_results_immediately
        = $request->publish_results_immediately;
      $record->publish_results_immediately
        = 1;

      $record->having_negative_mark = 1;
      $record->negative_mark = $request->negative_mark;
      $record->start_date = $request->start_date;
      $record->end_date = $request->end_date;
      $record->instructions_page_id = $request->instructions_page_id;
      $record->show_in_front = $request->show_in_front;

      if (!$request->negative_mark)
        $record->having_negative_mark = 0;

      // $record->description    = $request->description;
      $record->record_updated_by  = Auth::user()->id;
      $record->record_created_by   = \Auth::id();
      $record->exam_type          = $request->exam_type;
      $record->has_language       = $request->has_language;

      if ($request->has_language == 1) {
        $record->language_name       = $request->language_name;
      }
      $record->institute_id   = adminInstituteId();


      //$record->quiz_applicability   = $request->quiz_applicability;
      if ($request->start_time) {
        $record->start_time            = $request->start_time;
      }

      if ($request->display_type) {
        $record->display_type           = $request->display_type;
      }
      if ($request->chapter_id) {
        $record->chapter_id           = $request->chapter_id;
      }
      if ($request->subject_id) {
        $record->subject_id           = $request->subject_id;
      }
      if ($request->year) {
        $record->year           = $request->year;
      }
      if ($request->competitive_exam_type_id) {
        $record->competitive_exam_type_id  = $request->competitive_exam_type_id;
      }
      // dd($record);
      $record->save();
      // dd($record);

      /*
        if($request->quiz_applicability == 1){

           BatchQuizzes::addRecord($record,$request->batch_id);

        }
        elseif($request->quiz_applicability == 0){

             BatchQuizzes::addForAll($record->institute_id, $record);
        }
        */

      $file_name = 'examimage';
      if ($request->hasFile($file_name)) {

        $rules = array($file_name => 'mimes:jpeg,jpg,png,gif|max:10000');
        $this->validate($request, $rules);
        $this->setExamSettings();
        $examSettings = $this->getExamSettings();
        $path = $examSettings->categoryImagepath;
        $this->deleteFile($record->image, $path);

        $record->image      = $this->processUpload($request, $record, $file_name);
        $record->save();
      }

      DB::commit();

      flash('success', 'record_added_successfully', 'success');
    } catch (Exception $e) {

      DB::rollBack();
      // dd($e->getMessage());
      flash('Oops...!', 'Error! Please Try again', 'error');
    }

    if ($record->display_type == 'test_series') {
      return redirect()->route('exams.test_series');
    } else {
      if ($record->category_id == QUIZTYPE_LIVEQUIZ) {
        return redirect()->route('exams.live_quizzes');
      } else {
        return redirect()->route('exams.quizzes');
      }
    }
  }

  /**
   * Delete Record based on the provided slug
   * @param  [string] $slug [unique slug]
   * @return Boolean
   */
  public function delete($slug)
  {
    if (!canDo('exams_delete')) {
      prepareBlockUserMessage();
      return back();
    }
    /**
     * Delete the questions associated with this quiz first
     * Delete the quiz
     * @var [type]
     */
    $record = Quiz::where('slug', $slug)->first();
    try {
      if (!env('DEMO_MODE')) {
        $record->delete();
      }
      $response['status'] = 1;
      $response['message'] = getPhrase('record_deleted_successfully');
    } catch (Exception $e) {
      $response['status'] = 0;
      if (getSetting('show_foreign_key_constraint', 'module'))
        $response['message'] =  $e->getMessage();
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
    return URL_QUIZZES;
  }


  /**
   * Returns the list of subjects based on the requested subject
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function getSubjectData(Request $request)
  {
    $subject_id = $request->subject_id;
    $subject = Subject::where('id', '=', $subject_id)->first();
    $topics = $subject
      ->topics()
      ->where('parent_id', '=', '0')
      ->get(['topic_name', 'id']);

    $subject_questions = $subject->questions();
    $chapter_id = $request->chapter_id;
    $topic_id = $request->topic_id;
    $question_bank_type_id = $request->question_bank_type_id;
    $competitive_exam_type_id = $request->competitive_exam_type_id;
    if (! empty($chapter_id)) {
      $subject_questions->where('questionbank.chapter_id', $chapter_id);
    }
    if (! empty($topic_id)) {
      $subject_questions->where('questionbank.topic_id', $topic_id);
    }
    if (! empty($question_bank_type_id)) {
      $subject_questions->where('questionbank.question_bank_type_id', $question_bank_type_id);
    }
    if (! empty($questionbank_category_id)) {
      $subject_questions->where('questionbank.questionbank_category_id', $questionbank_category_id);
    }


    $questions = $subject_questions->get([
      'id',
      'subject_id',
      'topic_id',
      'question_type',
      'question',
      'marks',
      'difficulty_level',
      'status'
    ]);



    return json_encode(array('topics' => $topics, 'questions' => $questions, 'subject' => $subject));
  }

  /**
   * Updates the questions in a selected quiz
   * @param  [type] $slug [description]
   * @return [type]       [description]
   */
  public function updateQuestions($slug)
  {
    if (!checkRole(getUserGrade(2))) {
      prepareBlockUserMessage();
      return back();
    }

    /**
     * Get the Quiz Id with the slug
     * Get the available questions from questionbank_quizzes table
     * Load view with this data
     */
    $record = Quiz::getRecordWithSlug($slug);
    $data['record']           = $record;
    if (empty($record->display_type)) {
      if ($record->category_id == QUIZTYPE_LIVEQUIZ) {
        $data['active_class']       = 'live_quizzes';
      } else {
        $data['active_class']       = 'exams';
      }
    } else {
      $data['active_class']       = 'test_series';
    }
    // $data['right_bar']          = FALSE;
    // $data['right_bar_path']     = 'exams.quiz.right-bar-update-questions';

    $data['settings']           = FALSE;
    $previous_questions = array();

    if ($record->total_questions > 0) {
      $questions = DB::table('questionbank_quizzes')
        ->where('quize_id', '=', $record->id)
        ->get();
      // dd($questions);
      foreach ($questions as $question) {
        $temp = array();
        $temp['id']          = $question->subject_id . $question->questionbank_id;
        $temp['subject_id']  = $question->subject_id;
        $temp['question_id'] = $question->questionbank_id;
        $temp['marks']       = $question->marks;

        $question_details         = QuestionBank::find($question->questionbank_id);
        $subject                  = $question_details->subject;

        $temp['question']         = $question_details->question;
        $temp['question_type']    = $question_details->question_type;
        $temp['difficulty_level'] = $question_details->difficulty_level;
        $temp['subject_title']    = $subject->subject_title;
        array_push($previous_questions, $temp);
      }

      $section_data = [];

      $section_wise_questions       = [];
      $settings['is_have_sections'] = 0;
      $settings['questions']        = $previous_questions;

      if ($record->exam_type != 'NSNT') {
        $settings['is_have_sections'] = 1;

        if ($record->section_data) {

          $section_data = json_decode($record->section_data);
        }

        $temp_questions = [];

        foreach ($previous_questions as $question)
          $temp_questions[$question['question_id']] = $question;


        foreach ($section_data as $sd) {
          $index = str_replace(' ', '_', $sd->section_name);
          $section_wise_questions[$index]['section_name'] = $sd->section_name;
          $section_wise_questions[$index]['section_time'] = $sd->section_time;

          foreach ($sd->questions as $q_no) {
            $section_wise_questions[$index]['questions'][] = $temp_questions[$q_no];
          }

          $index++;
        }

        $settings['questions'] = $section_wise_questions;
      }

      $settings['total_marks']  = $record->total_marks;
      $settings['section_data'] = $record->section_data;
      $data['settings']         = json_encode($settings);
    }


    $institute_id = adminInstituteId();

    // Use pluck directly on the query
    $data['subjects'] = Subject::where('institute_id', $institute_id)
      ->pluck('subject_title', 'id')
      ->toArray();

    if (isFaculty()) {
      $faculty_subjects = \App\User::find(\Auth::id())
        ->subjects()
        ->pluck('subject_id')
        ->toArray();

      $data['subjects'] = Subject::where('institute_id', $institute_id)
        ->whereIn('id', $faculty_subjects)
        ->pluck('subject_title', 'id')
        ->toArray();
    }

    if (shareData('share_subjects')) {
      $data['subjects'] = \App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])
        ->pluck('subject_title', 'id')
        ->toArray();

      if (isFaculty()) {
        $faculty_subjects = \App\User::find(\Auth::id())
          ->subjects()
          ->pluck('subject_id')
          ->toArray();

        $data['subjects'] = \App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])
          ->whereIn('id', $faculty_subjects)
          ->pluck('subject_title', 'id')
          ->toArray();
      }
    }

    $data['title']        = getPhrase('update_questions_for') . ' ' . $record->title;
    // $data['duration']      = $record->duration;
    $data['layout']   =  getLayout();

    // dd($data);

    // return view('exams.quiz.update-questions', $data);

    $view_name = getTheme() . '::exams.quiz.update-questions';
    return view($view_name, $data);
  }

  public function storeQuestions(Request $request, $slug)
  {

    // dd($request);

    if (!checkRole(getUserGrade(2))) {
      prepareBlockUserMessage();
      return back();
    }

    $added_sections  = $request->add_section_names;
    $added_times     = $request->add_section_times;

    DB::beginTransaction();

    try {

      $quiz = Quiz::getRecordWithSlug($slug);

      $quiz_id    = $quiz->id;
      $questions  = json_decode($request->saved_questions);
      // dd($questions);
      $marks = 0;
      $questions_to_update = array();
      $sections_data = array();
      $subjects = [];

      foreach ($questions as $ques_key => $q) {
        // dd($q);
        if ($quiz->exam_type != 'NSNT') {

          foreach ($q->questions as $question) {
            // dd($question);
            $temp = array();
            $temp['subject_id']       = $question->subject_id;
            $subjects[] = $question->subject_id;
            $temp['questionbank_id']  = $question->question_id;
            $temp['quize_id']         = $quiz_id;
            $temp['marks']            = $question->marks;
            $marks                   += $question->marks;

            array_push($questions_to_update, $temp);

            $key = str_replace(' ', '_', $added_sections[$ques_key]);
            // dd($key);
            $sections_data[$key]['section_name']  = $added_sections[$ques_key];
            $sections_data[$key]['section_time']  = $added_times[$ques_key];


            if (!isset($sections_data[$key]['questions']))
              $sections_data[$key]['questions'] = [];
            if (!in_array($question->question_id, $sections_data[$key]['questions']))
              array_push($sections_data[$key]['questions'], $question->question_id);
          }
        } else {

          $temp = array();
          $temp['subject_id']       = $q->subject_id;
          $subjects[] = $q->subject_id;
          $temp['questionbank_id']  = $q->question_id;
          $temp['quize_id']         = $quiz_id;
          $temp['marks']            = $q->marks;
          $marks                   += $q->marks;

          array_push($questions_to_update, $temp);
        }
      }

      $sections_data  = json_encode($sections_data);
      $total_questions = count($questions_to_update);

      $subjects = array_unique($subjects);

      //Clear all previous questions
      DB::table('questionbank_quizzes')->where('quize_id', '=', $quiz_id)->delete();
      //Insert New Questions
      DB::table('questionbank_quizzes')->insert($questions_to_update);

      $quiz->subjects()->sync($subjects);

      $quiz->total_questions = $total_questions;
      /**
       * Changing Marking scheme. Marks based on question level OR at exam level. If they ener at Exam level which means each question caries that particular marks Eg: 2 Marks per question so total marks total_queations*marks_per_question.
       */
      if (empty($quiz->marks_per_question)) {
        $quiz->total_marks     = $marks;
      } else {
        $quiz->total_marks = $total_questions * $quiz->marks_per_question;
      }
      $quiz->section_data    = $sections_data;
      $quiz->is_multisubject = (count($subjects) > 1) ? 'yes' : 'no';
      $quiz->save();

      DB::commit();
      flash('success', 'record_updated_successfully', 'success');
    } catch (Exception $e) {

      DB::rollBack();
      flash('Oops...!', 'Error! Improper Data Submitted Please Try Again', 'error');
    }

    //return redirect(URL_QUIZZES);
    if (empty($quiz->display_type)) {
      if ($quiz->category_id == QUIZTYPE_LIVEQUIZ) {
        return redirect()->route('exams.live_quizzes');
      } else {
        return redirect(URL_QUIZZES);
      }
    } else {
      return redirect()->route('exams.test_series');
    }
  }

  public function updateBatches(Request $request, $slug)
  {
    if (!canDo('exams_edit')) {
      prepareBlockUserMessage();
      return back();
    }

    $record = Quiz::getRecordWithSlug($slug);
    if ($isValid = $this->isValidRecord($record))
      return redirect($isValid);

    if ($request->isMethod('post')) {

      if ($record->category_id == QUIZTYPE_LIVEQUIZ) {
        $assign = $request->assign;
        $is_popquiz = $request->is_popquiz;
        BatchQuizzes::where('quiz_id', $record->id)->delete();
        if (! empty($assign)) {
          foreach ($assign as $key => $batch_id) {
            $batch_quiz               = new BatchQuizzes();
            $batch_quiz->quiz_id      = $record->id;
            $batch_quiz->category_id  = $record->category_id;
            $batch_quiz->batch_id     = $batch_id;
            $batch_quiz->institute_id = $record->institute_id;
            $batch_quiz->user_id      = $record->record_updated_by;

            //$time = ! empty( $times[ $batch_id ] ) ? $times[ $batch_id ] : '00:00:00';
            //$batch_quiz->date_time = $date . ' ' . $time;
            $batch_quiz->is_popquiz = $is_popquiz[$batch_id];
            $batch_quiz->save();
          }
        }
        $record->quiz_applicability = 1; // Specific Batches.
      } else {
        $record->quiz_applicability   = $request->quiz_applicability;
        $record->save();


        $pre_data  = BatchQuizzes::where('quiz_id', $record->id)->pluck('id')->toArray();

        BatchQuizzes::whereIn('id', $pre_data)->delete();

        if ($request->quiz_applicability == 1) {
          BatchQuizzes::addRecord($record, $request->batch_id);
        } elseif ($request->quiz_applicability == 0) {
          BatchQuizzes::addForAll($record->institute_id, $record);
        }
      }

      flash('success', 'batches_updated_successfully', 'success');
      if (empty($record->display_type)) {
        if ($record->category_id == QUIZTYPE_LIVEQUIZ) {
          return redirect()->route('exams.live_quizzes');
        } else {
          return redirect(URL_QUIZZES);
        }
      } else {
        return redirect()->route('exams.test_series');
      }
    }
    $data['record']           = $record;
    if (empty($record->display_type)) {
      if ($record->category_id == QUIZTYPE_LIVEQUIZ) {
        $data['active_class']     = 'live_quizzes';
      } else {
        $data['active_class']     = 'exams';
      }
    } else {
      $data['active_class']     = 'test_series';
    }
    $data['settings']         = FALSE;
    $institute_id   = adminInstituteId();

    $data['categories']   = QuizCategory::where('institute_id', $institute_id)->get()->pluck('category', 'id')->prepend('Please select', '');
    $data['instructions'] = Subject::where('institute_id', $institute_id)->get()->pluck('subject_title', 'id')->toArray();

    $data['exam_types']   = App\ExamType::get()->pluck('title', 'code')->toArray();
    $data['title']        = getPhrase('edit_exam');
    $batches              = Batch::where('institute_id', $institute_id)->get()->pluck('name', 'id')->toArray();
    $data['batches']      = $batches;
    $data['pre_data']     = BatchQuizzes::where('quiz_id', $record->id)->pluck('batch_id')->toArray();
    $data['slots_times']  = makeTimeSlots();
    $data['layout']   =  getLayout();

    if ($record->category_id == QUIZTYPE_LIVEQUIZ) {
      $view_name = getTheme() . '::exams.quiz.update-livequizzes';
    } else {
      $view_name = getTheme() . '::exams.quiz.update-batches';
    }
    return view($view_name, $data);
  }


  /**
   * Course listing method
   * @return Illuminate\Database\Eloquent\Collection
   */
  public function examTypes()
  {
    if (!checkRole(getUserGrade(2))) {
      prepareBlockUserMessage();
      return back();
    }

    $data['active_class']       = 'exams';
    $data['title']              = getPhrase('exam_types');
    $data['exam_types']         = App\ExamType::get();
    $data['layout']   =  getLayout();

    // return view('exams.exam-types', $data);

    $view_name = getTheme() . '::exams.exam-types';
    return view($view_name, $data);
  }

  public function editExamType($code)
  {

    if (!checkRole(getUserGrade(2))) {
      prepareBlockUserMessage();
      return back();
    }

    $data['active_class']       = 'exams';
    $data['title']              = getPhrase('edit_exam_type');
    $data['record']             = App\ExamType::where('code', $code)->first();
    $data['layout']   =  getLayout();

    // dd($data);

    // return view('exams.edit-exam-type', $data);

    $view_name = getTheme() . '::exams.edit-exam-type';
    return view($view_name, $data);
  }


  public function updateExamType(Request $request, $code)
  {
    if (!checkRole(getUserGrade(2))) {
      prepareBlockUserMessage();
      return back();
    }


    $record   = App\ExamType::where('code', $code)->first()->update($request->all());

    flash('success', 'exam_type_updated_successfully', 'success');
    return redirect(URL_EXAM_TYPES);
  }

  public function processUpload(Request $request, $record, $file_name)
  {
    if (env('DEMO_MODE')) {
      return;
    }

    if ($request->hasFile($file_name)) {
      $examSettings = getExamSettings();

      $destinationPath      = $examSettings->categoryImagepath;

      $fileName = $record->id . '-' . $file_name . '.' . $request->$file_name->guessClientExtension();

      $request->file($file_name)->move($destinationPath, $fileName);

      //Save Normal Image with 300x300
      // Image::make($destinationPath.$fileName)->fit($examSettings->imageSize)->save($destinationPath.$fileName);
      Image::read($destinationPath . $fileName)   // v3 uses read(), not make()
        ->cover($examSettings->imageSize, $examSettings->imageSize)


        ->save($destinationPath . $fileName);
      return $fileName;
    }
  }

  public function deleteFile($record, $path, $is_array = FALSE)
  {
    if (env('DEMO_MODE')) {
      return;
    }

    $files = array();
    $files[] = $path . $record;
    File::delete($files);
  }

  public function unPopExam($online_class_id)
  {
    $record = \App\Onlineclass::find($online_class_id);

    if ($isValid = $this->isValidRecord($record))
      return redirect($isValid);


    if ($record->live_quiz_popstatus == 'yes') {
      $record->live_quiz_popstatus = 'no';
    } else {
      $record->live_quiz_popstatus = 'yes';
    }
    $record->save();

    // dd( $record );

    flash('success', 'Record updaetd successfully', 'success');
    return redirect()->route('user.dashboard');
  }
}
