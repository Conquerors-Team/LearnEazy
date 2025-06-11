<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class RenewalRequest extends Model
{
    use HasSlug;
    protected $table= "renewal_requests";

    protected $fillable = ['message', 'institute_id', 'created_by_id', 'message_id', 'reply_id', 'replied'];

    public static function boot()
    {
        parent::boot();
        RenewalRequest::observe(new \App\Observers\UserActionsObserver);
    }


    public function institute() {
    	return $this->belongsTo(Institute::class, 'institute_id')->withDefault();
    }

    public function createdby() {
    	return $this->belongsTo(User::class, 'created_by_id')->withDefault();
    }
}
