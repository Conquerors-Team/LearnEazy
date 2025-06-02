<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OnlineclassAbsentNotification extends Model
{
    // protected $primaryKey = null;
     protected $fillable = ['class_id', 'student_id', 'notified_sms', 'notified_email'];
     protected $table= "online_classes_absentnotifications";

    public static function boot()
    {
        parent::boot();
        OnlineclassAbsentNotification::observe(new \App\Observers\UserActionsObserver);
    }
}
