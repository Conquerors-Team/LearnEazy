<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetitiveExamTypes extends Model
{
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
