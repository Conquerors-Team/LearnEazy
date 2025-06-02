<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $table="alerts";

    public static function boot()
    {
        parent::boot();
        Alert::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($id)
    {
        return Alert::where('id', '=', $id)->first();
    }

    public function alerts_enabled()
    {
        return $this->belongsToMany(AlertEnabled::class, 'alerts_enabled', 'alert_id', 'batch_id');
    }
}
