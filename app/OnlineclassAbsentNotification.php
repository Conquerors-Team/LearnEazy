<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class OnlineclassAbsentNotification extends Model
{
    use HasSlug;
    // protected $primaryKey = null;
     protected $fillable = ['class_id', 'student_id', 'notified_sms', 'notified_email'];
     protected $table= "online_classes_absentnotifications";

    public static function boot()
    {
        parent::boot();
        OnlineclassAbsentNotification::observe(new \App\Observers\UserActionsObserver);
    }
}
