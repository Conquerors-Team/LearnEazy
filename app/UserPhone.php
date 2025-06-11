<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class UserPhone extends Model
{
    use HasSlug;
    protected $table='users_phones';

    protected $fillable = ['mobile_number', 'country_code', 'status', 'otp', 'otp_used'];

    public static function boot()
    {
        parent::boot();
        UserPhone::observe(new \App\Observers\UserActionsObserver);
    }
}
