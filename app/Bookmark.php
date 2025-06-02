<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = 'bookmarks';

    public static function boot()
    {
        parent::boot();
        Bookmark::observe(new \App\Observers\UserActionsObserver);
    }

}
