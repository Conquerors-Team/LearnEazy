<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasSlug;
    protected $table = 'site_user_subscription';

    public static function boot()
    {
        parent::boot();
        UserSubscription::observe(new \App\Observers\UserActionsObserver);
    }

}
