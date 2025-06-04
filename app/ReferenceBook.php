<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\HasSlug;
class ReferenceBook extends Model
{

      use HasSlug;
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
