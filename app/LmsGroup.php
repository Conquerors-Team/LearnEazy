<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LmsGroup extends Model
{
    protected $table = 'lmsgroups';

 	public static function boot()
    {
        parent::boot();
        LmsGroup::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return LmsGroup::where('slug', '=', $slug)->first();
    }

    public function lmsseries()
    {
        return $this->belongsToMany(LmsSeries::class, 'lmsseries_lmsgroups', 'lmsseries_id', 'lmsgroups_id');
    }
}
