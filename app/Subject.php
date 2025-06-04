<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Topic;
use App\Http\Controllers\Traits\HasSlug;
class Subject extends Model
{
  use HasSlug;
    public static function boot()
    {
        parent::boot();
        Subject::observe(new \App\Observers\UserActionsObserver);
    }

    public function topics()
    {
    	return $this->hasMany('App\Topic');
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id')->withDefault();
    }

    public function questions()
    {
        return $this->hasMany('App\QuestionBank');
    }

    public function course()
    {
    	return $this->belongsToMany('App\Course','course_subject','subject_id','academic_course_id')->withPivot('year','semister', 'sessions_needed')->withTimestamps();
    }



    public static function getRecordWithSlug($slug)
    {
        return Subject::where('slug', '=', $slug)->first();
    }

     public static function getRecordWithId($id)
    {
        return Subject::where('id', '=', $id)->first();
    }

    public static function getName($subject_id)
    {
        return Subject::where('id',$subject_id)->first()->subject_title;
    }

    public function getQuizzes()
    {
        return $this->hasMany('App\Quiz', 'category_id');
    }

    public function chapters() {
        return $this->hasMany(Chapter::class, 'subject_id');
    }

    public function subject_notes()
    {
        $institute_id   = adminInstituteId();
        if(checkRole(['admin'])){
            return $this->hasMany(LmsNote::class, 'subject_id');
        } else {
            return $this->hasMany(LmsNote::class, 'subject_id')->where('lms_notes.institute_id', $institute_id);
        }
    }

    public function subject_quizzes()
    {
        $batches  = getStudentBatches('subjects');

        return \App\Quiz::join('batch_quizzes','batch_quizzes.quiz_id','=','quizzes.id')
        ->where('start_date','<=',date('Y-m-d'))
        ->where('end_date','>=',date('Y-m-d'))
        ->where('total_questions','>','0')
        ->whereIn('batch_quizzes.batch_id',$batches)
        ->get();


    }
}
