<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;

class Institute extends Model
{
    use HasSlug;
    protected $table="institutes";

    protected $dates = ['valid_until'];

    public static function boot()
    {
        parent::boot();
        Institute::observe(new \App\Observers\UserActionsObserver);
    }

    public static function addRecord($ins_name, $address,$user_record, $valid_until = '')
    {
    	$record                    = new Institute();
    	$record->user_id           = $user_record->id;
    	$record->institute_name    = $ins_name;
        $record->institute_address = $address;
    	$record->phone             = $user_record->phone;
        if ( Auth::check() ) {
            $record->updated_by        = Auth::user()->id;
            $record->status            = APPROVE;
        } else { // Which means institute registering from front end.
            $valid_until = now()->addDays(DEFAULT_VALIDITY_DAYS);
            $record->valid_until = $valid_until;
            $record->status = 0;
        }
        /*
        if ( ! empty( $valid_until ) ) {
            $record->valid_until = date('Y-m-d', strtotime( $valid_until ) );
        }
        */
    	$record->save();
        $user_record->institute_id  = $record->id;
        $user_record->save();

        // Permission for institute.
        if ( Auth::check() ) {
            $permissions = Permission::where('status', 'active')->get()->pluck('id')->toArray();
            $record->permissions()->sync( $permissions );
        } else {
            // Package Permissions.
            $permissions = Package::join('packages_permissions as pp', 'packages.id', '=', 'pp.package_id')->where('is_default', 'yes')->get()->pluck('permission_id')->toArray();
            if ( empty( $permissions ) ) {
                $permissions = Permission::where('default_for_institute', 'yes')->get()->pluck('id')->toArray();
            }
            $record->permissions()->sync( $permissions );
        }

    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_institute');
    }

    public function alerts_enabled()
    {
        return $this->belongsToMany(AlertEnabled::class, 'alerts_enabled', 'alert_id', 'batch_id');
    }

}
