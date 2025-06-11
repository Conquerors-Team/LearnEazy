<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class UserLoginHistory extends Model
{
    use HasSlug;
    protected $table='users_login_history';

    protected $fillable = ['username', 'password', 'ipaddress', 'platform', 'platform_version', 'browser', 'browser_version', 'device_name', 'device_type', 'user_id', 'login_status',
    'robot', 'languages', 'request_headers'];

    /*
    public static function boot()
    {
        parent::boot();
        UserLoginHistory::observe(new \App\Observers\UserActionsObserver);
    }
    */
}
