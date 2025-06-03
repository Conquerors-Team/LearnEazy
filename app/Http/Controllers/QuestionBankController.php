<?php
namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Subject;
use App\QuestionBank;
use App\QuestionbankCategory;
use Yajra\Datatables\Datatables;
use DB;
use Image;
use ImageSettings;
use File;
use Illuminate\Support\Facades\Validator;
use Input;
use Excel;
use Exception;
use Illuminate\Support\Arr;

class QuestionBankController extends Controller
{
 public $excel_data = array();
  public $columns = '';
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
        if(!canDo('exam_question_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('question_subjects');
        $data['layout']   =  getLayout();
      // return view('exams.questionbank.list', $data);

         $view_name = getTheme().'::exams.questionbank.list';
        return view($view_name, $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable(Request $request)
    {
      if(!canDo('exam_question_access'))
      {
        prepareBlockUserMessage();
        return back();
      }

      $institute_id   = adminInstituteId();
      if ( ! empty( request('institute') ) ) {
        $institute_id = request('institute');
      }
      if(checkRole(getUserGrade(3))){
             $records = Subject::select(['institute_id',
            'subject_title', 'subject_code', 'id','slug', 'is_lab', 'updated_at']);
             if ( ! empty( request('institute') ) ) {
              $records->where('institute_id', request('institute'));
             }
      }else{

         if(shareData('share_subjects')){
               $records = Subject::select([
            'institute_id','subject_title', 'subject_code', 'id','slug', 'is_lab', 'updated_at'])
               ->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])
               ;
         }
         else{

                $records = Subject::select([
            'subject_title', 'subject_code', 'id','slug', 'is_lab', 'updated_at'])
                ->where('institute_id',$institute_id);

         }

      }

      $subject_id = request('subject');
      if ( ! empty( $subject_id ) ) {
        $records->where('id', $subject_id);
      }

      if(checkRole(getUserGrade(10))) { // Faculty.
        $subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
        $records->whereIn('id', $subjects);
      }

      $records->orderBy('updated_at', 'desc');

        $table = Datatables::of($records)
        ->addColumn('action', function ($records) {


            return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">

                        <li><a href="'.URL_QUESTIONBANK_VIEW.$records->slug.'"><i class="fa fa-eye"></i>'.getPhrase("view_questions").'</a></li>

                        <li><a href="'.URL_QUESTIONBANK_ADD_QUESTION.$records->slug.'"><i class="fa fa-plus-circle"></i>'.getPhrase("add").'</a></li>

                        </ul>
                    </div>';
            })
        ->editColumn('subject_title', function($records) {
            return '<a href="'.URL_QUESTIONBANK_VIEW.$records->slug.'">'.$records->subject_title.'</a>';
        })
         ->editColumn('institute_id', function($records){

            $institute  = getInstitute($records->institute_id);
            if($institute)
            return '<a href = "'.URL_INSTITUTE_DETAILS.$institute->id.'">'.ucwords($institute->institute_name).'</a>';

             return '-';
        })
        ->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('is_lab')
        ->removeColumn('updated_at')
        ->rawColumns(['institute_id', 'subject_title','action']);

        return $table->make();
    }

      /**
     * Questions listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function show($slug)
    {

      if(!canDo('exam_question_view'))
      {
        prepareBlockUserMessage();
        return back();
      }

      $subject = Subject::getRecordWithSlug($slug);

      if($isValid = $this->isValidRecord($subject))
        return redirect($isValid);

        $data['active_class'] = 'exams';
        $data['title']        = $subject->subject_title.' '.getPhrase('questions');
        $data['subject']      = $subject;
        $data['layout']   =  getLayout();

      // return view('exams.questionbank.questions', $data);

         $view_name = getTheme().'::exams.questionbank.questions';
        return view($view_name, $data);
    }

      /**
     * Questions listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function view($subject_slug, $slug)
    {

      if(!canDo('exam_question_view'))
      {
        prepareBlockUserMessage();
        return back();
      }

      $subject = Subject::getRecordWithSlug($subject_slug);

      if($isValid = $this->isValidRecord($subject))
        return redirect($isValid);

      $question = \App\QuestionBank::getRecordWithSlug($slug);

      if($isValid = $this->isValidRecord($question))
        return redirect($isValid);
      $data['question'] = $question;


        $data['active_class'] = 'exams';
        $data['title']        = $subject->subject_title.' '.getPhrase('questions');
        $data['subject']      = $subject;
        $data['layout']   =  getLayout();

      // return view('exams.questionbank.questions', $data);

         $view_name = getTheme().'::exams.questionbank.view';
        return view($view_name, $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getQuestions(Request $request,$slug)
    {
      // dd(request('difficulty_level'));

      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

        $subject = Subject::getRecordWithSlug($slug);

        // dd(QuestionBank::first());

        $isValid = $this->isValidRecord($subject);
      if($isValid)
        return redirect($isValid);

         $institute_id   = adminInstituteId();

         if ( ! empty( request('institute') ) ) {
        $institute_id = request('institute');
      }

         if(checkRole(getUserGrade(3))){

             $records = QuestionBank::join(
            'subjects', 'questionbank.subject_id', '=', 'subjects.id')
        ->join(
            'topics', 'questionbank.topic_id', '=', 'topics.id'
            )
        ->join('chapters', 'questionbank.chapter_id','chapters.id')
        ->select(['questionbank.question_code','questionbank.question','questionbank.questionbank_category_id','questionbank.slug',
            'questionbank.updated_at'])
        ->where('questionbank.subject_id','=', $subject->id);
         }
         else{

            if(shareData('share_questions')){

                  $records = QuestionBank::join(
            'subjects', 'questionbank.subject_id', '=', 'subjects.id')
        ->join(
            'topics', 'questionbank.topic_id', '=', 'topics.id'
            )
        ->select(['questionbank.question_code','questionbank.question','questionbank.questionbank_category_id','questionbank.slug',
            'questionbank.updated_at'])
        ->where('questionbank.subject_id','=', $subject->id)
        ->whereIn('questionbank.institute_id', [$institute_id, OWNER_INSTITUTE_ID]);
            }
            else{

               $records = QuestionBank::join(
            'subjects', 'questionbank.subject_id', '=', 'subjects.id')
        ->join(
            'topics', 'questionbank.topic_id', '=', 'topics.id'
            )
        ->join('chapters', 'questionbank.chapter_id','chapters.id')
        ->select(['questionbank.question_code', 'questionbank.question','questionbank.questionbank_category_id','questionbank.slug',
            'questionbank.updated_at'])
        ->where('questionbank.subject_id','=', $subject->id)
        ->where('questionbank.institute_id',$institute_id);

            }

         }

         if ( ! empty( request('topic_id') ) ) {
        $topic_id = request('topic_id');
        $records = $records->where('questionbank.topic_id',$topic_id);
      }

      if ( ! empty( request('topic_id') ) ) {
        $topic_id = request('topic_id');
        $records = $records->where('questionbank.topic_id',$topic_id);
      }

      if ( ! empty( request('chapter_id') ) ) {
        $chapter_id = request('chapter_id');
        $records = $records->where('questionbank.chapter_id',$chapter_id);
      }

      if( !empty(request('sub_topic_id') ) ) {
        $sub_topic_id = request('sub_topic_id');

        $records = $records->where('questionbank.sub_topic_id',$sub_topic_id);
      }

      if( !empty(request('difficulty_level') ) ) {
        $difficulty_level = request('difficulty_level');

        $records = $records->where('questionbank.difficulty_level',$difficulty_level);
      }

      if(checkRole(getUserGrade(10)))
      {
        $records = $records->where('questionbank.created_by_id', \Auth::user()->id);
      }

        $records->orderBy('updated_at','desc');

        $table = Datatables::of($records)->removeColumn('slug')
        ->addColumn('action', function ($records) use($subject) {
            $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">';

                        $temp = '<li><a href="'.route('exams.questionbank.view-question', ['subject_slug' => $subject->slug, 'slug' => $records->slug]).'"><i class="fa fa-eye"></i>'.getPhrase("view").'</a></li>';

        if(checkRole(getUserGrade(2))) {

          $institute_id   = adminInstituteId();


          if($records->institute_id  == $institute_id || checkRole(getUserGrade(2))){
            $temp.= '<li><a href="'.URL_QUESTIONBANK_EDIT_QUESTION.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
          }

          elseif(!shareData('share_questions') || checkRole(getUserGrade(2))){
            $link_data  = '-';
            return $link_data;
          }
      }

        if(checkRole(getUserGrade(9))) {

          $institute_id   = adminInstituteId();


          if($records->institute_id  == $institute_id || checkRole(getUserGrade(1))){
          $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
          }
          elseif(!shareData('share_questions') || checkRole(getUserGrade(1))){

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
        ->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('updated_at')

           ->editColumn('institute_id', function($records){

            $institute  = getInstitute($records->institute_id);
            if($institute)
            return '<a href = "'.URL_INSTITUTE_DETAILS.$institute->id.'">'.ucwords($institute->institute_name).'</a>';

             return '-';
        })
        ->editColumn('questionbank_category_id', function($records) {

          $category = QuestionbankCategory::where('id',$records->questionbank_category_id)->first();
          if($category)
            return $category->category;
        });

        // ->editColumn('question_type', function($results){
        //  return ucfirst($results->question_type);
        //  })
        // ->editColumn('question', function($results){
        //  //return '<span title="'.$results->question.'">'.str_limit($results->question, 10).'</span>';
        //   return str_limit(strip_tags($results->question), 10);
        // })
        // ->editColumn('difficulty_level',function($results){
        //     return ucfirst($results->difficulty_level);
        // });
        return $table->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create($slug)
    {
      if(!canDo('exam_question_create'))
      {
        prepareBlockUserMessage();
        return back();
      }

      $subject = Subject::getRecordWithSlug($slug);

      if($isValid = $this->isValidRecord($subject))
        //return redirect($isValid);

        if(shareData() || checkRole(getUserGrade(3))){
        $topics = $subject->topics()->where('parent_id','=','0')->get();

        }
        else{
             $institute_id   = adminInstituteId();
      $topics = $subject->topics()->where('parent_id','=','0')->where('institute_id',$institute_id)->get();

        }

        $topics = \App\Topic::where('subject_id', $subject->id)->get()->map(function ($entry, $group) {
              $topic_name = $entry->topic_name;
              if ( $entry->parent_id > 0 ) {
                $parenttopic = \App\Topic::find( $entry->parent_id );
                if ( $parenttopic ) {
                  $topic_name = $parenttopic->topic_name . ' => ' . $topic_name;
                }
              }
              $item = ['id' => $entry->id, 'topic_name' => $topic_name];
              return $item;
              //return $entry->parent_id == 0 ? $entry->topic_name : 'Child';
          })->pluck('topic_name', 'id')->prepend(getPhrase('select'), '');

      if(!$topics->count()) {
      /**
       * If no topics available in selected subject,
       * redirect back with message to update topics
       */
        $message =
        $subject->subject_title.'  have no topics, please add topics to upload questions';
        flash('Ooops...!', $message, 'overlay');
        return back();
      }


      // $settings['total_answers'] = 0;
      // $settings['question_type'] = '';

      $data['chapters']           = \App\Chapter::where('status', 'Active')->where('subject_id', $subject->id)->get()->pluck('chapter_name', 'id')->prepend(getPhrase('select'), '');
      $data['topics']           = array_pluck($topics, 'topic_name', 'id');

      $data['topics'] = $topics;
      $data['record']           = FALSE;
      $data['active_class']       = 'exams';
      $data['title']              = getPhrase('upload_question');
      $data['subject']      = $subject;
      $data['layout']   =  getLayout();
      // $data['settings']      = json_encode($settings);
      // return view('exams.questionbank.add-edit', $data);
// dd($data);
         $view_name = getTheme().'::exams.questionbank.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {


       if(!canDo('exam_question_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }
      // $record = QuestionBank::getRecordWithSlug($slug);
      $record = QuestionBank::withoutGlobalScope('status')->where('slug', $slug)->first();

      if($isValid = $this->isValidRecord($record))
        return redirect($isValid);

      $subject = $record->subject()->first();

       if(shareData() || checkRole(getUserGrade(3))){
        $topics = $subject->topics()->where('parent_id','=','0')->get();

        }
        else{
             $institute_id   = adminInstituteId();
      $topics = $subject->topics()->where('parent_id','=','0')->where('institute_id',$institute_id)->get();

        }

      if(!$topics->count()) {
      /**
       * If no topics available in selected subject,
       * redirect back with message to update topics
       */
        $message =
        $subject->subject_title.'  have no topics, please add topics to upload questions';
        flash('Ooops...!', $message, 'overlay');
        return back();
      }

      // dd($record);
       $settings['total_answers']         = $record->total_answers;
       $settings['total_correct_answers'] = $record->total_correct_answers;
       $settings['correct_answers']       = json_decode($record->correct_answers);
       $settings['question_type']         = $record->question_type;
       $settings['answers']               = json_decode(str_replace( '\r\n', '', $record->answers));

      // dd($settings);
      $data['chapters']           = \App\Chapter::where('status', 'Active')->where('subject_id', $subject->id)->get()->pluck('chapter_name', 'id')->prepend(getPhrase('select'), '');
      $data['topics']           = array_pluck($topics, 'topic_name', 'id');

      $data['record']           = $record;
      $data['active_class']     = 'master_settings';
      $data['title']            = getPhrase('edit_question');
      $data['subject']          = $subject;
      $settings                 = (object)$settings;
      $data['settings']         = json_encode($settings);
      $data['layout']   =  getLayout();

         $view_name = getTheme().'::exams.questionbank.add-edit';
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

        if(!canDo('exam_question_edit'))
      {
        prepareBlockUserMessage();
        return back();
      }
        // $record                 = QuestionBank::where('slug', $slug)->get()->first();
        $record = QuestionBank::withoutGlobalScope('status')->where('slug', $slug)->first();

        $rules['questionbank_category_id']         = 'required|integer';
        $rules['chapter_id']         = 'required|integer';
        $rules['topic_id']         = 'required|integer';
        $rules['question']          = 'required';
        $rules['marks']             = 'required|integer';
        // $rules['question_code']     = 'required|unique:questionbank,question_code,' . $record->id;
        if ( $request->question_bank_type_id == QUESTIONSBANK_TYPE_TESTSERIES ) {
          $rules['competitive_exam_type_id']          = 'required';
        }
        if ( $request->competitive_exam_type_id == QUESTIONSBANK_TYPE_PREVIOUSYEAR_JEE
          || $request->competitive_exam_type_id == QUESTIONSBANK_TYPE_PREVIOUSYEAR_NEET
        ) {
          $rules['year']          = 'required';
        }

        /**
         * As we are disableing the question type in edit,
         * we need to get the type of the question for existing record
         * Assign the question type to a varable $current_question_type
         */
        $current_question_type = $record->question_type;

       if($current_question_type == 'radio') {
            $rules['total_answers']     = 'bail|required|integer';
            $rules = $this->validateRadioQuestions($request, $rules);
        }

        if($current_question_type == 'checkbox') {
            $rules['total_answers']     = 'bail|required|integer';
            $rules = $this->validateCheckboxQuestions($request, $rules);
        }
         if($current_question_type == 'match') {
            $rules['total_answers']     = 'bail|required|integer';
            $rules = $this->validateMatchQuestions($request, $rules);
        }



        /**
         * As it is fill in the blanks type of question
         * there are no no. of options fields, it only contains the answers
         * so ignore the validation for total_answers
         */
        if($current_question_type == 'blanks') {
            $rules = $this->validateBlankQuestions($request, $rules);
        }

        $this->validate($request, $rules);

        // dd($request->all());

        DB::beginTransaction();
        try{
        $name             = $request->question;

       /**
        * As we are maintaining unique slug for each question,
        * if the question is changed no need worry,
        * we can continue with the existing old slug
        */
        $request->question_type = $record->question_type;
        $record->question               = $name;
        $record->subject_id             = $request->subject_id;
        $record->questionbank_category_id             = $request->questionbank_category_id;
        $record->chapter_id             = $request->chapter_id;
        // $record->topic_id               = $request->topic_id;

        $topic_id = $request->topic_id;
        $sub_topic_id = null;
        $topic = \App\Topic::find( $topic_id );
        if ( $topic && $topic->parent_id > 0 ) {
          $sub_topic_id = $topic->id;
          $topic_id = $topic->parent_id;
        }
        $record->topic_id  = $topic_id;
        $record->sub_topic_id  = $sub_topic_id;

        $record->question_bank_type_id       = $request->question_bank_type_id;
        if ( ! empty($request->competitive_exam_type_id)) {
          $record->competitive_exam_type_id       = $request->competitive_exam_type_id;
        }
        if ( ! empty($request->year)) {
          $record->year       = $request->year;
        }

        $record->question               = $request->question;
        $record->difficulty_level       = $request->difficulty_level;
        $record->hint                   = $request->hint;
        $record->explanation            = $request->explanation;
        $record->marks                  = $request->marks;

        /*
        $subject = \App\Subject::find( $request->subject_id );
        if ( $subject ) {
          $count = \App\QuestionBank::where('subject_id', $request->subject_id)->count();
          $record->question_code                  = $subject->subject_title . $count;
        } else {
          //$record->question_code                  = $request->question_code;
        }
        */

        $record->time_to_spend          = $request->time_to_spend;
        if($request->has('question_l2'))
        $record->question_l2            = $request->question_l2;
    if($request->has('explanation_l2'))
        $record->explanation_l2         = $request->explanation_l2;

         if($current_question_type == 'radio'){
             $record->total_answers          = $request->total_answers;
            $record->correct_answers        = $request->correct_answers;
            $record->total_correct_answers  = $request->total_correct_answers;
         }

        if($current_question_type == 'checkbox'){
            $record->total_answers          = $request->total_answers;
            $record->correct_answers        = $this->prepareMultiAnswers($request);
            $record->total_correct_answers  = $request->total_correct_answers;
        }

        if($current_question_type == 'blanks'){
            $record->total_answers          = $request->total_correct_answers;
            $record->correct_answers        = $this->prepareMultiAnswers($request);
            $record->total_correct_answers  = $request->total_correct_answers;
        }

         if($current_question_type == 'match'){
            $record->total_answers          = $request->total_answers;;
            $record->total_correct_answers  = $request->total_answers;
            $record->correct_answers        = $this->prepareMatchAnswers($request);
       }

       if($current_question_type == 'para'   ||
          $current_question_type == 'video'  ||
          $current_question_type == 'audio'
        ){
            $record->total_answers          = $request->total_answers;;
            $record->total_correct_answers  = $request->total_answers;
            $record->correct_answers        = $this->prepareParaAnswers($request);
       }

       $record->status = 1;
        // Save data with no images
        $record->save();


          // Update data with images
        if($request->hasFile('question_file')) {

          $record->question_file          = $this->processUpload($request, $record, 'question_file', 'question');
        }


       if($request->hasFile($record->explanation_file))
        $record->explanation_file          = $this->processUpload($request, $record, 'explanation_file', 'explanation');


        if($current_question_type == 'match'){
            $record->answers     = $this->prepareMatchQuestionOptions($request, $record);
        }
        else if($current_question_type == 'para' || $current_question_type == 'video'|| $current_question_type == 'audio' )

            $record->answers     = $this->prepareParaQuestionOptions($request, $record);
        else
          $record->answers              = $this->prepareOptions($request, $record);

        $record->save();
        flash('success','record_added_successfully', 'success');
         DB::commit();
      }
     catch(Exception $e)
     {
      DB::rollBack();
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash('oops...!',$e->errorInfo, 'error');
       }
       else {
          flash('oops...!','improper_data_in_the_question', 'error');
       }
     }

    $subject_id = $request->subject_id;
    if ( ! empty( $subject_id ) ) {
      $buttontype = $request->buttontype;

      $subject = \App\Subject::find( $subject_id );
      if ( $subject ) {
        if ( $buttontype == 'updatenew' ) {
          return redirect()->route('exams.addquestion', ['slug' => $subject->slug]);
        } elseif( $buttontype == 'updatenewmeta' ) {
          if ( $record ) {
            $newrecord = $record->replicate();
            $newrecord->slug = md5(mt_rand() . microtime());
            $newrecord->question = '';
            // The Question ID is also not changing while we use continue with meta.
            $newrecord->question_code = \App\QuestionBank::count() + 1;
            // When we create with meta...even questions, options and solutions are also displayed, only the filters should be displayed..
            $newrecord->total_answers = '';
            $newrecord->total_answers = '';
            $newrecord->total_correct_answers = '';
            $newrecord->correct_answers = '';

            // $newrecord->difficulty_level = null;
            $newrecord->hint = '';
            $newrecord->explanation = '';
            $newrecord->marks = '';
            $newrecord->time_to_spend = '';
            $newrecord->question_l2 = null;
            $newrecord->explanation_l2 = null;

            $newrecord->question_file = '';
            $newrecord->explanation_file = null;
            $newrecord->answers = '';
            // $newrecord->question_bank_type_id = null;
            $newrecord->competitive_exam_type_id = null;
            $newrecord->year = null;
            $newrecord->status = 0;
            $newrecord->created_by_id = \Auth::id();
            $newrecord->save();
            return redirect()->route('exams.editquestion', ['slug' => $newrecord->slug]);
          } else {
            return redirect(URL_QUESTIONBANK_VIEW . $subject->slug);
          }
        } else {
          return redirect(URL_QUESTIONBANK_VIEW . $subject->slug);
        }
      } else {
        return redirect(URL_QUIZ_QUESTIONBANK);
      }
    } else {
      return redirect(URL_QUIZ_QUESTIONBANK);
    }

    }

    /**
     * This method adds record to DB with the following steps
     * 1 Validate Request
     * 2 Save Data and get ID of that record
     * 3 Process question image and upload if exists
     * 4 Process Option images and upload if exists
     * 5 Update the same record as files got uploaded uploaded
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request, $slug = '')
    {
    /**
     * Validation for the Master Data of a question
     */
        if(!canDo('exam_question_create'))
      {
        prepareBlockUserMessage();
        return back();
      }

      
      $rules['questionbank_category_id']         = 'bail|required|integer';
      $rules['chapter_id']         = 'bail|required|integer';
      $rules['topic_id']      = 'bail|required|integer';
      $rules['question']      = 'bail|required';
      $rules['marks']       = 'bail|required|integer';
      $rules['question_type']   = 'bail|required';
      //$rules['question_code']     = 'bail|required|unique:questionbank,question_code';

      if ( $request->question_bank_type_id == QUESTIONSBANK_TYPE_TESTSERIES ) {
          $rules['competitive_exam_type_id']          = 'required';
        }
        if ( $request->competitive_exam_type_id == QUESTIONSBANK_TYPE_PREVIOUSYEAR_JEE
          || $request->competitive_exam_type_id == QUESTIONSBANK_TYPE_PREVIOUSYEAR_NEET
        ) {
          $rules['year']          = 'required';
        }

        if($request->question_type == 'radio') {
            $rules['total_answers']     = 'bail|required|integer|min:1';
            $rules = $this->validateRadioQuestions($request, $rules);
        }

        if($request->question_type == 'checkbox') {
            $rules['total_answers']     = 'bail|required|integer|min:1';
            $rules = $this->validateCheckboxQuestions($request, $rules);
        }

        if($request->question_type == 'match') {
            $rules['total_answers']     = 'bail|required|integer|min:1';
            $rules = $this->validateMatchQuestions($request, $rules);
        }

       if($request->question_type == 'para') {
            $rules['total_answers']     = 'bail|required|integer|min:1';
            $rules['total_para_options']     = 'bail|required|integer|min:1';

        }

        if($request->question_type == 'video' ||
          $request->question_type == 'audio') {
            $rules['total_answers']     = 'bail|required|integer|min:1';
            $rules['total_para_options'] = 'bail|required|integer|min:1';
        }

        /**
         * As it is fill in the blanks type of question
         * there are no no. of options fields, it only contains the answers
         * so ignore the validation for total_answers
         */
      if($request->question_type == 'blanks') {
            $rules = $this->validateBlankQuestions($request, $rules);
             $rules['total_correct_answers']     = 'bail|required|integer';

      }



    $this->validate($request, $rules);


    DB::beginTransaction();
    try{
        $record = new QuestionBank();

        $name                   = $request->question;
        $record->question       = $name;
        $record->slug           = $record->makeSlug(getHashCode());
        $record->subject_id     = $request->subject_id;
        $record->questionbank_category_id             = $request->questionbank_category_id;
        $record->chapter_id       = $request->chapter_id;
        // $record->question_code       = $request->question_code;

        $subject = \App\Subject::find( $request->subject_id );
        if ( $subject ) {
          $count = \App\QuestionBank::where('subject_id', $request->subject_id)->count();
          $record->question_code                  = $subject->subject_code . '_' . ($count +1);
        } else {
          $record->question_code                  = \App\QuestionBank::count() + 1;
        }

        $topic_id = $request->topic_id;
        $sub_topic_id = null;
        $topic = \App\Topic::find( $topic_id );
        if ( $topic && $topic->parent_id > 0 ) {
          $sub_topic_id = $topic->id;
          $topic_id = $topic->parent_id;
        }
        $record->topic_id  = $topic_id;
        $record->sub_topic_id  = $sub_topic_id;

        $record->question_bank_type_id       = $request->question_bank_type_id;
        if ( ! empty($request->competitive_exam_type_id)) {
          $record->competitive_exam_type_id       = $request->competitive_exam_type_id;
        }
        if ( ! empty($request->year)) {
          $record->year       = $request->year;
        }

        $record->question       = $request->question;
        $record->difficulty_level   = $request->difficulty_level;
        $record->hint           = $request->hint;
        $record->explanation    = $request->explanation;
        $record->marks          = $request->marks;
        $record->question_type  = $request->question_type;
        $record->time_to_spend  = $request->time_to_spend;
        if($request->has('question_l2'))
        $record->question_l2    = $request->question_l2;
        if($request->has('explanation_l2'))
        $record->explanation_l2 = $request->explanation_l2;

        /**
         * Prepare answers data based on the type of question
         */
        if($request->question_type == 'radio'){
            $record->total_answers          = $request->total_answers;
            $record->correct_answers        = $request->correct_answers;
            $record->total_correct_answers  = $request->total_correct_answers;
        }

        if($request->question_type == 'checkbox'){
            $record->total_answers          = $request->total_answers;
            $record->correct_answers        = $this->prepareMultiAnswers($request);
            $record->total_correct_answers  = $request->total_correct_answers;
        }

       if($request->question_type == 'blanks'){
            $record->total_answers          = 0;
            $record->correct_answers        = $this->prepareMultiAnswers($request);
            $record->total_correct_answers  = $request->total_correct_answers;
       }
       if($request->question_type == 'match'){
            $record->total_answers          = $request->total_answers;
            $record->total_correct_answers  = $request->total_answers;
            $record->correct_answers        = $this->prepareMatchAnswers($request);
       }

       if($request->question_type == 'para'   ||
          $request->question_type == 'video'  ||
          $request->question_type == 'audio'
        ){
            $record->total_answers          = $request->total_answers;
            $record->total_correct_answers  = $request->total_answers;
            $record->correct_answers        = $this->prepareParaAnswers($request);
       }



        /**
         * As it is descriptive question, there will be
         * no total_answers, total_correct_answers and correct_answers
         * and
         *
         */
        if($request->question_type == 'descriptive') {

            $record->total_answers           = 0;
            $record->total_correct_answers   = 0;
            $record->correct_answers         = '';

        }

         $record->institute_id   = adminInstituteId();

         $record->status = 1;
        // Save data with no images
        $record->save();
        // Update data with images

        if($request->hasFile($record->question_file))
           $record->question_file  = $this->processUpload($request, $record, 'question_file', 'question');

        if($request->hasFile($record->explanation_file))
          $record->explanation_file = $this->processUpload($request, $record, 'explanation_file', 'explanation');

        if($request->question_type == 'match'){

            $record->answers     = $this->prepareMatchQuestionOptions($request, $record);
        }
        else if($request->question_type == 'para' || $request->question_type == 'video' || $request->question_type == 'audio'){

           $record->answers     = $this->prepareParaQuestionOptions($request, $record);
        }
        else{

           $record->answers         = $this->prepareOptions($request, $record);
        }

        $record->created_by_id = \Auth::user()->id;

        $record->save();

         flash('success','record_added_successfully', 'success');
         DB::commit();
      }
     catch(Exception $e)
     {
       // dd($e->getMessage());
      DB::rollBack();
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash('oops...!',$e->errorInfo, 'error');
       }
       else {
          // dd($e->getMessage());
          flash('oops...!','improper_data_in_the_question', 'error');
       }
     }
    if ( ! empty( $slug ) ) {
      $buttontype = $request->buttontype;
      if ( $buttontype == 'createnew' ) {
        return redirect()->route('exams.addquestion', ['slug' => $slug]);
      } elseif( $buttontype == 'createnewmeta' ) {
        if ( $record ) {
            $newrecord = $record->replicate();
            $newrecord->slug = md5(mt_rand() . microtime());
            $newrecord->question = '';

            // The Question ID is also not changing while we use continue with meta.
            $newrecord->question_code = \App\QuestionBank::count() + 1;
            // When we create with meta...even questions, options and solutions are also displayed, only the filters should be displayed..
            $newrecord->total_answers = null;
            $newrecord->total_answers = null;
            $newrecord->total_correct_answers = null;
            $newrecord->correct_answers = null;

            $newrecord->difficulty_level = null;
            $newrecord->hint = null;
            $newrecord->explanation = null;
            $newrecord->marks = null;
            $newrecord->time_to_spend = null;
            $newrecord->question_l2 = null;
            $newrecord->explanation_l2 = null;

            $newrecord->question_file = null;
            $newrecord->explanation_file = null;
            $newrecord->answers = null;
            $newrecord->question_bank_type_id = null;
            $newrecord->competitive_exam_type_id = null;
            $newrecord->year = null;

            $newrecord->created_by_id = \Auth::id();
            $newrecord->save();
            return redirect()->route('exams.editquestion', ['slug' => $newrecord->slug]);
          } else {
            return redirect(URL_QUESTIONBANK_VIEW . $slug);
          }
      } else {
        return redirect(URL_QUESTIONBANK_VIEW . $slug);
      }
    } else {
      return redirect(URL_QUIZ_QUESTIONBANK);
    }
    }

    /**
     * This method prepares the json data to be inserted in place of options
     * by processing the image information and other attributes
     * @param  [type] $request [request sent by the user]
     * @param  [type] $record  [the record which was saved to DB]
     * @return [type]          [description]
     */
    public function prepareOptions($request, $record)
    {

      $options    = $request->options;
      $optionsl2  = $request->optionsl2;
      $list       = array();

        /**
         * Get the image path from ImageSettings class
         * This destinationPath variable will be used
         * to delete an image if user edits any question and changes an image
         */
         $imageObject = new App\ImageSettings();
         $destinationPath      = $imageObject->getExamImagePath();

         /**
          * Loop the total options selected by user
          * and process each option by checking wether the image
          * has been uploaded or not
          * After this loop multiple objects will be created based on
          * the no. of options(total_answers) selected by user
          * Each object contains 3 properties
          * 1) option_value : stores the text submitted as option
          * 2) has_file     : stores if this particular option has any file
          * 3) file_name    : stores the name of file uploaded
          */
      for($index = 0; $index < $request->total_answers; $index++)
      {
            /**
             * The $answers variable is used when user edit any question
             * It will contain the previous option values
             * As it is under for loop, every option property will be checked
             * by comparing wether the file is submitted for this particular object
             * If submitted it will delete the old file and overwrite with new file
             * @var [type]
             */
            $answers = json_decode($record->answers);
            $old_has_file = isset($answers[$index]->has_file) ? $answers[$index]->has_file : 0;
            $old_file_name = isset($answers[$index]->file_name) ? $answers[$index]->file_name : '';

         $spl_char   = ['\t','\n','\b','\c','\r','\'','\\','\$','\"',"'"];
        $list[$index]['option_value']   = str_replace($spl_char,'',$options[$index]);
        $list[$index]['optionl2_value']   = str_replace($spl_char,'',$optionsl2[$index]);

        // $list[$index]['option_value']  = $options[$index];
        $list[$index]['has_file']     = $old_has_file;
        $list[$index]['file_name']    = $old_file_name;
        $file_name            = 'upload_'.$index;
        if ($request->hasFile($file_name))
        {
          $rules = array($file_name => 'mimes:jpeg,jpg,png,gif|max:10000');
          $validator = Validator::make($request->options, $rules);
          if($validator->fails())
              return '';

                  //Delete Old Files
                if($old_file_name)
                    $this->deleteExamFile($old_file_name, $destinationPath);

          // This option has the image to be uploaded,
          // so process image and update the fields
          $list[$index]['has_file']     = 1;
          $list[$index]['file_name']    = $this
                          ->processUpload($request, $record,$file_name, 'option');


        }

      }

      return json_encode($list);
    }


    /**
     * The Multi Answers will be prepared in this method
     * The format will be multiple objects with property answer
     * Need to reference this as $CorrectAnswers->answer in foreach loop
     * @param  [type] $request [description]
     * @return [type] json     [description]
     */
    public function prepareMultiAnswers($request)
    {
        $correct_answers = $request->correct_answers;

        $list = array();

        for($index = 0; $index < $request->total_correct_answers; $index++)
        {
            $list[$index]['answer']   = $correct_answers[$index];
        }

        return json_encode($list);
    }

    /**
     * In this method, the options are divided to multi dimentional array
     * Each object will have left and right as root
     * Each will have the title and options as properties
     * @param  [type] $request [description]
     * @param  [type] $record  [description]
     * @return [type]          [description]
     */
    public function prepareMatchQuestionOptions($request, $record)
    {

        $options_left   = $request->options_left;
        $options_right  = $request->options_right;

        $optionsl2_left   = $request->optionsl2_left;
        $optionsl2_right  = $request->optionsl2_right;

        $list = array();

        $list['left']['title']       = $request->title_left;
        $list['right']['title']      = $request->title_right;

        $list['left']['titlel2']     = $request->title_left_l2;
        $list['right']['titlel2']    = $request->title_right_l2;

        $list['left']['options']     = array();
        $list['right']['options']    = array();

        $list['left']['optionsl2']   = array();
        $list['right']['optionsl2']  = array();

        for($index = 0; $index < $request->total_answers; $index++)
        {
            $list['left']['options'][$index]     = $options_left[$index];
            $list['right']['options'][$index]    = $options_right[$index];

            $list['left']['optionsl2'][$index]   = $optionsl2_left[$index];
            $list['right']['optionsl2'][$index]  = $optionsl2_right[$index];

        }

        return json_encode($list);
    }

    /**
     * This method will prepare the list of answers provided with
     * match the following questions
     * These answers will directly point to the options object in the above method
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function prepareMatchAnswers($request)
    {
        $correct_answers = $request->correct_answers;

        $list = array();

        for($index = 0; $index < $request->total_answers; $index++)
        {
            $list[$index]['answer']   = $correct_answers[$index];
        }

        return json_encode($list);
    }


     /**
     * In this method, the options are divided to multi dimentional array
     * Each object will have index number as the question block
     * Each index will have the question, total_options,
     * array of options associated with that
     * Each will have the title and options as properties
     * @param  [type] $request [description]
     * @param  [type] $record  [description]
     * @return [type]          [description]
     */
    public function prepareParaQuestionOptions($request, $record)
    {

        $total_options  = $request->total_para_options;
        $questions      = $request->questions_list;
        // $questionsl2    = $request->questions_listl2;

        $list = array();

        for($index = 0; $index < $request->total_answers; $index++)
        {
            $options      = $request->options[$index];
            // $optionsl2    = $request->optionsl2[$index];
            $list_options = array();
            $list_optionsl2 = array();

            $list[$index]['question']       = $questions[$index];
            // $list[$index]['questionl2']     = $questionsl2[$index];
            $list[$index]['total_options']  = $total_options;

            for($option_number = 0; $option_number < $total_options; $option_number++){
                $list_options[$index][$option_number] = $options[$option_number];
                // $list_optionsl2[$index][$option_number] = $optionsl2[$option_number];
            }

            $list[$index]['options'] = $list_options;
            // $list[$index]['optionsl2'] = $list_optionsl2;
        }
        // dd($list);

        return json_encode($list);
    }

    /**
     * This method will prepare the list of answers provided with
     * Paragraph or video type of questions
     * These answers will directly associated with the question number order
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function prepareParaAnswers($request)
    {
        $correct_answers = $request->correct_answers;

        $list = array();

        for($index = 0; $index < $request->total_answers; $index++)
        {
            $list[$index]['answer']   = $correct_answers[$index];
            // dd($list);
        }

        return json_encode($list);
    }

    /**
     * This method process the image is being refferred
     * by getting the settings from ImageSettings Class
     * @param  Request $request   [Request object from user]
     * @param  [type]  $record    [The saved record which contains the ID]
     * @param  [type]  $file_name [The Name of the file which need to upload]
     * @param  string  $type      [Identify if it is question or an option image]
     * @return [type]             [description]
     */
     public function processUpload(Request $request, $record, $file_name, $type = 'option')
     {
       if(env('DEMO_MODE')) {
        return ;
       }

         if ($request->hasFile($file_name)) {
          $imageObject = new App\ImageSettings();
          $destinationPath      = $imageObject->getExamImagePath();
          $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();
          if($type!='option')
          {

             $fileName = $record->id.$type.'.'.$request->$file_name->guessClientExtension();

          }

          $request->file($file_name)->move($destinationPath, $fileName);

         return $fileName;
        }
     }

    /**
     * Validates the single answer type of questions and returs a validation rules
     * @param  [type] $request [Object of Request class]
     * @param  [type] $rules   [array of rules]
     * @return [array] $rules  [array of extended rules]
     */
    public function validateRadioQuestions($request, $rules)
    {
      $fileSize = (new ImageSettings())->getExamMaxFilesize();

      for($i=0; $i<$request->total_answers; $i++)
      {
        $file_name = 'upload_'.$i;

        if($request->hasFile($file_name))
        {
          $rules[$file_name] = 'mimes:jpeg,jpg,png,gif|max:'.$fileSize;
        }
      }
      $rules['correct_answers'] = 'bail|required|integer';
      return $rules;
    }

    public function validateCheckboxQuestions($request, $rules)
    {
        return $rules;
    }
    public function validateMatchQuestions($request, $rules)
    {
        $rules['title_left']    = 'required|max:30';
        $rules['title_right']   = 'required|max:30';
        return $rules;
    }

    public function validateBlankQuestions($request, $rules)
    {
        for($i=0; $i < $request->total_correct_answers; $i++)
        {
             $rules['correct_answers.'.$i] = 'required|max:30';
        }
        return $rules;
    }


    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
      if(!canDo('exam_question_delete'))
      {
        prepareBlockUserMessage();
        return back();
      }

        $qbObject = new QuestionBank();
        $record = $qbObject->getRecordWithSlug( $slug);
     try {
        if(!env('DEMO_MODE')) {

            $path   = (new App\ImageSettings())->getExamImagePath();
            $options = json_decode($record->answers);
          $this->deleteExamFile($options, $path, TRUE);
          $this->deleteExamFile($record->question_file, $path, FALSE);
            $record->delete();

        }
            $response['status'] = 1;
            $response['message'] = getPhrase('record_deleted_successfully');
            return json_encode($response);
        } catch ( \Illuminate\Database\QueryException $e) {
                 $response['status'] = 0;
           if(getSetting('show_foreign_key_constraint','module'))
            $response['message'] =  $e->errorInfo;
           else
            $response['message'] =  getPhrase('this_record_is_in_use_in_other_modules');
       }
       return json_encode($response);
    }

    public function deleteExamFile($record, $path, $is_array = FALSE)
    {
       if(env('DEMO_MODE')) {
        return ;
       }

      $files = array();
       $has_files = FALSE;
       if($is_array) {
         foreach($record as $option) {
          if(isset($option->has_file)){
          if($option->has_file)
          {
            $has_files = TRUE;
            $files[] = $path.$option->file_name;
          }
        }
           }
      }
      else {
        $has_files = TRUE;
          $files[] = $path.$record;
      }

       if($has_files)
        {
           File::delete($files);
        }
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
      return URL_QUIZ_QUESTIONBANK;
    }


      /**
    * Display a Import Questions page
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
        $data['heading']      = getPhrase('import_questions');
        $data['title']        = getPhrase('import_questions');
        $data['layout']        = getLayout();
        // return view('exams.questionbank.import.import', $data);

         $view_name = getTheme().'::exams.questionbank.import.import';
        return view($view_name, $data);
     }
    /**
     * Validates the subject
     * @param  [type]  $subject_id [description]
     * @return boolean             [description]
     */
    public function isValidSubject($subject_id)
    {
      $subject_id = (int) $subject_id;
       return Subject::where('id','=',$subject_id)->get()->count();
    }

      public function readExcel(Request $request)
     {

       if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }
       $success_list = [];
       $failed_list = [];
        $summary = [];
         try{

        if(Input::hasFile('excel')){
          $path = Input::file('excel')->getRealPath();
          $data = Excel::load($path, function($reader) {
          })->get();

          $all_records  = array();
          $excel_record = array();
          $final_records =array();
          $isHavingDuplicate = 0;
          if(!empty($data) && $data->count()){
            foreach ($data as $key => $value) {
              if(Arr::has($value,'subject_id'))
              {
                $all_records[] = $value;
              }
              else {

              foreach($value as $record)
              {
                $all_records[] = $record;
              }
            }

            }

             $questionbank = new QuestionBank();

            $summary = (object)$this->processExcelQuestions($request, $all_records);

        }
      }

       if(isset($summary->failed_list) || isset($summary->success_list)) {

       $data['failed_list']   =   $summary->failed_list;
       $data['success_list']  =    $summary->success_list;

       $this->excel_data['failed'] = $summary->failed_list;
       $this->excel_data['success'] = $summary->success_list;
       $this->excel_data['columns'] = $summary->columns_list;

         $this->downloadExcel();
       }
       else {

        flash('oops...!','improper_sheet_uploaded', 'error');
       }
      }
     catch(Exception $e)
     {
      // \Illuminate\Database\QueryException
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash('oops...!',$e->errorInfo, 'error');
       }
       else {
          flash('oops...!','improper_sheet_uploaded', 'error');
       }

       return back();
     }
        // URL_USERS_IMPORT_REPORT
       $data['failed_list']   =   $failed_list;
       $data['success_list']  =    $success_list;
       $data['records']      = FALSE;
       $data['layout']       = getLayout();
       $data['active_class'] = 'exams';
       $data['heading']      = getPhrase('upload_questions');
       $data['title']        = getPhrase('report');
       // return view('exams.questionbank.import.import-result', $data);

        $view_name = getTheme().'::exams.questionbank.import.import-result';
        return view($view_name, $data);

     }

public function getFailedData()
{

  return $this->excel_data;
}

public function downloadExcel()
{

    Excel::create('questions_report', function($excel) {
      $excel->sheet('Failed', function($sheet) {
        $data = $this->getFailedData();
        array_unshift($data['columns'], "Reason");

      $sheet->row(1, $data['columns']);
      $data = $this->getFailedData();
      $cnt = 2;
      foreach ($data['failed'] as $data_item) {
        $item = $data_item->record;
        $record_data = [];

        $record_data[] = $data_item->type;
        foreach($data['columns'] as $key=>$value)
         $record_data[] = $item->$value;


        $sheet->appendRow($cnt++, $record_data);


      }


    });

     $excel->sheet('Success', function($sheet) {
        $data = $this->getFailedData();
      $sheet->row(1, $data['columns']);
      $cnt = 2;
      foreach ($data['success'] as $data_item) {

        $item = (object)collect($data_item)->all();
        $record_data = [];

        foreach($data['columns'] as $key=>$value)
        {

          $item_value = $item->$value;

          if(isset($item_value)) {
            if($item_value!=NULL)
            $record_data[] = $item_value;
          }
        }
        $sheet->appendRow($cnt++, $record_data);


      }


    });

    })->download('xlsx');
}




     public function processExcelQuestions(Request $request, $data)
     {
       if(!count($data))
        return false;
     $questionbank = new QuestionBank();
      switch($request->question_type)
      {
        case 'radio':
                   $questionbank->uploadRadioQuestions($data);
                   return array(
                                'failed_list'   => $questionbank->failed_list,
                                'success_list'  => $questionbank->success_list,
                                'columns_list'  => $questionbank->getAllColumnsList($data)
                                );
          break;
        case 'checkbox':
                   $questionbank->uploadCheckboxQuestions($data);
                   return array(
                                'failed_list'   => $questionbank->failed_list,
                                'success_list'  => $questionbank->success_list,
                                 'columns_list'  => $questionbank->getAllColumnsList($data)
                                );
          break;
        case 'blanks':
                   $questionbank->uploadBlankQuestions($data);

                   return array(
                                'failed_list'   => $questionbank->failed_list,
                                'success_list'  => $questionbank->success_list,
                                 'columns_list'  => $questionbank->getAllColumnsList($data)
                                );
          break;
      }
     }





}
