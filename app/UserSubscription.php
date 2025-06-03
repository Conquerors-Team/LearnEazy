<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $table = 'site_user_subscription';

    public static function boot()
    {
        parent::boot();
        UserSubscription::observe(new \App\Observers\UserActionsObserver);
    }

}
