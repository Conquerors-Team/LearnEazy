<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\HasSlug;
class Notification extends Model
{
      use HasSlug;
     protected $table= "notifications";

    public static function boot()
    {
        parent::boot();
        Notification::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return Notification::where('slug', '=', $slug)->first();
    }

    public function batch() {
    	return $this->belongsTo(Batch::class, 'batch_id')->withDefault();
    }

    public function createdby() {
    	return $this->belongsTo(User::class, 'record_updated_by')->withDefault();
    }

    public function institute() {
        return $this->belongsTo(Institute::class, 'institute_id')->withDefault();
    }
}
