<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class StudentPaidContent extends Model
{
    protected $table = 'student_paid_contents';

    protected  $examSettings;

    public static function boot()
    {
        parent::boot();
        StudentPaidContent::observe(new \App\Observers\UserActionsObserver);
    }

    public function setExamSettings()
    {
        $this->examSettings = getExamSettings();
    }

    public function getExamSettings()
    {
        return $this->examSettings;
    }

    public static function getRecordWithSlug($slug)
    {
        return StudentPaidContent::where('slug', '=', $slug)->first();
    }

    public function lmsseries() {
        return $this->belongsToMany(LmsSeries::class, 'student_paid_contents_data', 'student_paid_contents_id', 'item_id')->where('item_type', 'lmsseries');
    }

    public function lmsnotes() {
        return $this->belongsToMany(LmsSeries::class, 'student_paid_contents_data', 'student_paid_contents_id', 'item_id')->where('item_type', 'lmsnotes');
    }

    public function exams() {
        return $this->belongsToMany(Quiz::class, 'student_paid_contents_data', 'student_paid_contents_id', 'item_id')->where('item_type', 'exams');
    }

    public function courses() {
        return $this->belongsToMany(Course::class, 'student_paid_contents_courses', 'student_paid_contents_id', 'course_id');
    }

}
