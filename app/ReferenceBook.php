<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferenceBook extends Model
{
    // protected $appends = ['name_institute'];
    protected $table = 'reference_books';

    public static function boot()
    {
        parent::boot();
        BoardChapter::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return BoardChapter::where('id', '=', $slug)->first();
    }
}
