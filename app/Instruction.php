<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
    protected $table="instructions";

    public static function boot()
    {
        parent::boot();
        Instruction::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return Instruction::where('slug', '=', $slug)->first();
    }
}
