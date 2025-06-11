<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class CompetitiveExamTypes extends Model
{
    use HasSlug;
    protected $table = "competitive_exam_types";

    public static function boot()
    {
        parent::boot();
        CompetitiveExamTypes::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($id)
    {
        return CompetitiveExamTypes::where('id', '=', $id)->first();
    }


}
