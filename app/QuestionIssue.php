<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionIssue extends Model
{
    protected $table = 'quiz_question_issues';

    public static function boot()
    {
        parent::boot();
        QuestionIssue::observe(new \App\Observers\UserActionsObserver);
    }

}
