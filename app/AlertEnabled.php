<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class AlertEnabled extends Model
{
    use HasSlug;
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
