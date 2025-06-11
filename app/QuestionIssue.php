<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class QuestionIssue extends Model
{
    use HasSlug;
    protected $table = 'quiz_question_issues';

    public static function boot()
    {
        parent::boot();
        QuestionIssue::observe(new \App\Observers\UserActionsObserver);
    }

}
