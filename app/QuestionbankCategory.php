<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionbankCategory extends Model
{
    protected $table = 'questionbank_categories';

    public static function boot()
    {
        parent::boot();
        QuestionbankCategory::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return QuestionbankCategory::where('slug', '=', $slug)->first();
    }

    public function contents()
    {
        return $this->hasMany('App\Questionbank', 'questionbank_category_id');
    }
}
