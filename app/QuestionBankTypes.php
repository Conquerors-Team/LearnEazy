<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class QuestionBankTypes extends Model
{
    use HasSlug;
    protected $table = "question_bank_types";

    public static function boot()
    {
        parent::boot();
        QuestionBankTypes::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($id)
    {
        return QuestionBankTypes::where('id', '=', $id)->first();
    }


}
