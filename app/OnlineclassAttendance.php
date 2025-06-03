<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OnlineclassAttendance extends Model
{
    // protected $primaryKey = null;
     protected $fillable = ['class_id', 'student_id'];
     protected $table= "online_classes_attendence";

    public static function boot()
    {
        parent::boot();
        OnlineclassAttendance::observe(new \App\Observers\UserActionsObserver);
    }
}
