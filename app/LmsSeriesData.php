<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LmsSeriesData extends Model
{
    protected $table = 'lmsseries_data';

    public static function boot()
    {
        parent::boot();
        LmsSeriesData::observe(new \App\Observers\UserActionsObserver);
    }

    public function quiz()
    {
        return $this->belongsTo('App\Quiz', 'quiz_id')->withDefault();
    }

    public function series()
    {
        return $this->belongsTo('App\LmsSeries', 'lmsseries_id')->withDefault();
    }

}
