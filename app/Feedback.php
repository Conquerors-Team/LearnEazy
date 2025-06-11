<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasSlug;
    protected $table = 'feedbacks';

 	public static function boot()
    {
        parent::boot();
        Feedback::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return Feedback::where('slug', '=', $slug)->first();
    }
}
