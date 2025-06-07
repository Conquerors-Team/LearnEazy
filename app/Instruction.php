<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
    use HasSlug;
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
