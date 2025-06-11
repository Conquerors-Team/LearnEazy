<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

// class BatchStudent extends Model
class BatchLmsnotes  extends Model
{
    use HasSlug;
    protected $table="batch_lmsnotes";

    public static function boot()
    {
        parent::boot();
        BatchStudent::observe(new \App\Observers\UserActionsObserver);
    }

    public function batch() {
    	return $this->belongsTo(Batch::class, 'batch_id')->withDefault();
    }
    public function user() {
    	return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
