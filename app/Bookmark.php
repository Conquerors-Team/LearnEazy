<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasSlug;
    protected $table = 'bookmarks';

    public static function boot()
    {
        parent::boot();
        Bookmark::observe(new \App\Observers\UserActionsObserver);
    }

}
