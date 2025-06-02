<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Onlineclass extends Model
{
    protected $table= "onlineclasses";

    protected $fillable = ['lmsseries_id', 'lmsnotes_id', 'live_quiz_id'];

    public static function boot()
    {
        parent::boot();
        Onlineclass::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return Onlineclass::where('slug', '=', $slug)->first();
    }

    public function batch() {
    	return $this->belongsTo(Batch::class, 'batch_id')->withDefault();
    }

    public function createdby() {
    	return $this->belongsTo(User::class, 'created_by_id')->withDefault();
    }

    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id')->withDefault();
    }

    public function lmsseries() {
        return $this->belongsTo(LmsSeries::class, 'lmsseries_id')->withDefault();
    }

    public function lmsnotes() {
        return $this->belongsTo(LmsNote::class, 'lmsnotes_id')->withDefault();
    }

    public function attendance() {
        return $this->belongsToMany(OnlineclassAttendance::class, 'online_classes_attendence');
    }

    public function student_class() {
        return $this->belongsTo(StudentClass::class, 'student_class_id')->withDefault();
    }

    public function live_quiz() {
        return $this->belongsTo(Quiz::class, 'live_quiz_id')->withDefault();
    }

    // public function packages()
    // {
    //     return $this->belongsToMany(StudentPaidContent::class, 'packages_online','onlinepackage_id', 'studentpackage_id');
    // }
}
