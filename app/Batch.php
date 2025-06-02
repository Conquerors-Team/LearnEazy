<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table="batches";

    public static function boot()
    {
        parent::boot();
        Batch::observe(new \App\Observers\UserActionsObserver);
    }

    public function course() {
    	return $this->belongsTo(Course::class, 'course_id')->withDefault();
    }

    public function student_class() {
        return $this->belongsTo(StudentClass::class, 'student_class_id')->withDefault();
    }

    public function lmsseries() {
    	return $this->belongsToMany(LmsSeries::class, 'batch_lmsseries');
    }

    public function lmsnotes() {
    	return $this->belongsToMany(LmsNote::class, 'batch_lmsnotes');
    }

    public function quizzes() {
        return $this->belongsToMany(Quiz::class, 'batch_quizzes');
    }

    public function students() {
        return $this->belongsToMany(User::class, 'batch_students');
    }

}
