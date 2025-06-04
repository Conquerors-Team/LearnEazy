<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Image;
use ImageSettings;
use File;
use Input;
use App\Http\Controllers\Traits\HasSlug;
class Package extends Model
{
      use HasSlug;
    protected $table= "packages";

    public static function boot()
    {
        parent::boot();
        Package::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return Package::where('id', '=', $slug)->first();
    }

    protected  $examSettings;

    public function setExamSettings()
    {
        $this->examSettings = getExamSettings();
    }

    public function getExamSettings()
    {
        return $this->examSettings;
    }

    /**
     * Check the validity of the coupon by comparing the code string,
     * Start and End date for that coupon
     * @param  [type] $code [description]
     * @return [type]       [description]
     */
    public function checkValidity($code, $item_type='exam')
    {
        $coupon_record = Package::where('coupon_code', '=', $code)->first();
        $applicable_categories = [];
        if(!$coupon_record)
            return FALSE;
        if($coupon_record->coupon_code_applicability)
        {
            $applicable_categories = (array) json_decode($coupon_record->coupon_code_applicability)->categories;
        }

    	$record = Package::where('coupon_code', '=', $code)
    						->where('valid_from','<=',date('Y-m-d'))
    						->where('valid_to', '>=', date('Y-m-d'))
                            ->where('status','=','Active')
                           ->first();
                           // return $record;
        if($record)
        {
            if(in_array($item_type, $applicable_categories))
                return $record;
        }
        // return FALSE;
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'packages_permissions');
    }
}
