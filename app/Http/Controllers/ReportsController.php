<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App;
use App\Http\Requests;
use App\User;
use App\GeneralSettings as Settings;
use App\QuizResult;
use App\Quiz;
use App\QuestionBank;

use Image;
use ImageSettings;
use Yajra\Datatables\Datatables;
use DB;
use Illuminate\Support\Facades\Hash;

use Input;

class ReportsController extends Controller
{
    public function __construct()
    {
         $currentUser = \Auth::user();

         $this->middleware('auth');

    }

    public function viewExamAnswers($exam_slug, $result_slug)
    {

    	$exam_record = Quiz::getRecordWithSlug($exam_slug);
    	if($isValid = $this->isValidRecord($exam_record))
        	return redirect($isValid);

         $result_record = QuizResult::getRecordWithSlug($result_slug);

        if($isValid = $this->isValidRecord($result_record))
        	return redirect($isValid);

        $user_details   = App\User::where('id','=',$result_record->user_id)->get()->first();


        // dd($exam_record);
        $prepared_records        = (object) $exam_record
                                    ->prepareQuestions($exam_record->getQuestions(),'examcomplted');

        $bookmarks_array = (array)$prepared_records;

        $bookmarks                   = array_pluck($bookmarks_array['questions'], 'id');

        // dd($bookmarks);
        $data['questions']       = $prepared_records->questions;
        $data['subjects']        = $prepared_records->subjects;

        $data['bookmarks']       = $bookmarks;
        $data['exam_record']        = $exam_record;
        $data['result_record']      = $result_record;
        $data['user_details']        = $user_details;
        $data['active_class']       = 'analysis';
        $data['title']              = $exam_record->title.' '.getPhrase('answers');
        $data['layout']             = getLayout();
    	// return view('student.exams.results.answers', $data);

         $view_name = getTheme().'::student.exams.results.answers';
        return view($view_name, $data);
    }

    public function viewSubjectWiseReport($exam_slug, $result_slug)
    {
        // dd($exam_slug);
        $exam_record = Quiz::getRecordWithSlug($exam_slug);

        $exam_questions_subjects = Quiz::where('quizzes.slug', $exam_slug)->rightjoin('questionbank_quizzes','questionbank_quizzes.quize_id','quizzes.id')->join('questionbank','questionbank.id','questionbank_quizzes.questionbank_id')->join('subjects','subjects.id','questionbank.subject_id')->get()->groupBy( function( $entry ) {
            return $entry->subject_title;
        });

        // dd($exam_questions_subjects);

        $exam_questions = $exam_record->getQuestions()->pluck('questionbank_id');

        if($isValid = $this->isValidRecord($exam_record))
            return redirect($isValid);

         $result_record = QuizResult::getRecordWithSlug($result_slug);

         // dd( $result_record );

         $user_details   = App\User::where('id','=',$result_record->user_id)->get()->first();

        if($isValid = $this->isValidRecord($result_record))
            return redirect($isValid);

        $correct_answer_questions = json_decode($result_record->correct_answer_questions);

        $wrong_answer_questions = json_decode($result_record->wrong_answer_questions);

        $not_answered_questions = json_decode($result_record->not_answered_questions);

        $time_spent_correct_answer_questions = json_decode($result_record->time_spent_correct_answer_questions);

        $time_spent_wrong_answer_questions = json_decode($result_record->time_spent_wrong_answer_questions);

        $time_spent_not_answered_questions = json_decode($result_record->time_spent_not_answered_questions);

        $time_spent_correct_answers = 0;

        foreach ($time_spent_correct_answer_questions as $key => $value) {

                    $value = (array)$value;

                        $time_spent_correct_answers+=$value['time_spent'];
                }
// print_r($time_spent_correct_answer_questions);
        $time_spent_wrong_answers = 0;

        foreach ($time_spent_wrong_answer_questions as $key => $value) {

                    $value = (array)$value;

                        $time_spent_wrong_answers+=$value['time_spent'];
                }

        $time_spent_skipped_questions = 0;

        foreach ($time_spent_not_answered_questions as $key => $value) {

            $value = (array)$value;

            $time_spent_skipped_questions+=$value['time_spent'];
        }

        // dd($time_spent_wrong_answer_questions);

        foreach ($exam_questions as $key => $value) {

            if( !in_array( $value,$correct_answer_questions) && !in_array( $value,$wrong_answer_questions) &&
                !in_array( $value,$not_answered_questions) ){
                array_push($not_answered_questions, $value);
            }
        }

        $subjects_report = [];

        // print_r($exam_questions_subjects);

        foreach ($exam_questions_subjects as $subject => $questions) {

            $correct_answers_marks = 0;

            $wrong_answers_marks = 0;

            $not_answered_marks = 0;

            $subject_answers_count = 0;

            $subject_total = 0;

            $time_spenton_correct_answers = 0;

            $time_spenton_wrong_answers = 0;

            $time_spenton_skipped_answers = 0;

            // print_r($questions);

            foreach ($questions as $key => $question) {

            	// dd($question);

                $question_id = $question->questionbank_id;

                if( in_array( $question_id, $correct_answer_questions ) ) {

                    $correct_answers_marks += $question->marks;

                    $subject_answers_count++;

                    $subject_total += $question->marks;

                }

                if( in_array( $question_id, $wrong_answer_questions ) ) {

                    $wrong_answers_marks-=$question->negative_mark;

                    $subject_answers_count++;

                    $subject_total -= $question->negative_mark;

                }

                if( in_array( $question_id, $not_answered_questions ) ) {

                    $not_answered_marks+=$question->marks;

                }

                foreach ($time_spent_correct_answer_questions as $key => $value) {

                    $value = (array)$value;

                    // dd($value['time_spent']);

                    if( $key == $question_id){

                        $time_spenton_correct_answers+=$value['time_spent'];
                    }
                }

                foreach ($time_spent_wrong_answer_questions as $key => $value) {

                    $value = (array)$value;

                    if( $key == $question_id){

                        $time_spenton_wrong_answers+=$value['time_spent'];
                    }
                }

                foreach ($time_spent_not_answered_questions as $key => $value) {

                    $value = (array)$value;

                    if( $key == $question_id){

                        $time_spenton_skipped_answers+=$value['time_spent'];
                    }
                }
            }

            $subjects_report[ $subject ] = [ 'correct_answers_marks' => $correct_answers_marks,
            'wrong_answers_marks' => $wrong_answers_marks,
            'not_answered_marks' => $not_answered_marks,
            'subject_answers_count' => $subject_answers_count,
            'subject_total' => $subject_total,
            'time_spenton_correct_answers' => $time_spenton_correct_answers,
            'time_spenton_wrong_answers' => $time_spenton_wrong_answers,
            'time_spenton_skipped_answers' => $time_spenton_skipped_answers
            ];
        }

        // dd($subjects_report);

            $summary_dataset = [];
            $summary_labels = [];
            $summary_dataset_labels = [getPhrase('total')];
            $summary_bgcolor = [];
            $summary_border_color = [];

              $summary_dataset = [ count($correct_answer_questions),  count($wrong_answer_questions), count($not_answered_questions)];
              // $summary_labels[]  = ['Correct answered questions','Wrong answered questions','skipped questions'];

              for ($i=0; $i <= 2; $i++) {

                $color_number = rand(0,999);;

                $summary_bgcolor[] = getColor('',$color_number);

                $summary_border_color[] = getColor('background', $color_number);

              }

              $summary_labels = [getPhrase('correct'), getPhrase('wrong'), getPhrase('not_answered')];
        // dd($subjects_report);
        $quiz_stats['data']    = (object) array(
                                        'labels'            => $summary_labels,
                                        'dataset'           => $summary_dataset,
                                        'dataset_label'     => $summary_dataset_labels,
                                        'bgcolor'           => $summary_bgcolor,
                                        'border_color'      => $summary_border_color
                                        );

           $quiz_stats['type'] = 'doughnut';

           $quiz_stats['title'] = 'Attempts based report';

           // $chart_data['labels'] = $labels;

           $chart_data['datase'] = $summary_dataset;

           $chart_data['colors'] = $summary_bgcolor;

           $chart_data['data'] = (object)$summary_dataset;




           // if($type!='')
           // $quiz_stats['title'] = getPhrase('demanding').' '.$type.' '.getPhrase('quizzes');

        $prepared_records        = (object) $exam_record
                                    ->prepareQuestions($exam_record->getQuestions(),'examcomplted');
        // dd($prepared_records);
        $data['questions']       = $prepared_records->questions;
        $data['subjects']        = $prepared_records->subjects;
        $data['subjects_report'] = $subjects_report;
        $data['marks_obtained'] = $result_record->marks_obtained;
        $data['total_questions_attempted'] = count($correct_answer_questions) + count($wrong_answer_questions);
        $data['total_questions_in_exam'] = count($exam_questions);
        $data['time_spent_correct_answers'] = $time_spent_correct_answers;
        $data['time_spent_wrong_answers'] = $time_spent_wrong_answers;
        $data['time_spent_skipped_questions'] = $time_spent_skipped_questions;
        $data['exam_record']        = $exam_record;
        $data['result_record']      = $result_record;
        $data['user_details']        = $user_details;
        $data['active_class']       = 'analysis';
        $data['title']              = $exam_record->title.' '.getPhrase('answers');
        $data['layout']             = getLayout();
        $data['chart_data']         = (object)$quiz_stats;

        // dd($data['chart_data']);
        // return view('student.exams.results.answers', $data);

         $view_name = getTheme().'::student.exams.subject-analysis.student-report';
        return view($view_name, $data);
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
    	return URL_STUDENT_EXAM_CATEGORIES;
    }
}
