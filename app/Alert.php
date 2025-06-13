<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasSlug;
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
