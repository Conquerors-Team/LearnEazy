<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatchLms extends Model
{
    protected $table="batch_lmsseries";

    public $timestamps = false;

    protected $fillable = ['lms_series_id'];

    public static function boot()
    {
        parent::boot();
        BatchLms::observe(new \App\Observers\UserActionsObserver);
    }

    public function batch() {
    	return $this->belongsTo(Batch::class, 'batch_id')->withDefault();
    }
    public function user() {
    	return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
