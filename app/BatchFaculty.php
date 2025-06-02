<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatchFaculty extends Model
{
    protected $table="batch_faculty";

    public static function boot()
    {
        parent::boot();
        BatchFaculty::observe(new \App\Observers\UserActionsObserver);
    }

    public function batch() {
    	return $this->belongsTo(Batch::class, 'batch_id')->withDefault();
    }
    public function user() {
    	return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
