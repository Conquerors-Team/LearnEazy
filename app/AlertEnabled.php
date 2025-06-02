<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlertEnabled extends Model
{
    protected $table="alerts_enabled";

    public static function boot()
    {
        parent::boot();
        AlertEnabled::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($id)
    {
        return AlertEnabled::where('id', '=', $id)->first();
    }

    public function alerts_enabled()
    {
        return $this->belongsToMany(Permission::class, 'permission_users');
    }
}
