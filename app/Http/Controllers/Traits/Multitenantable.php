<?php
namespace App\Http\Controllers\Traits;
use Illuminate\Database\Eloquent\Builder;
use Auth;

trait Multitenantable {

    protected static function bootMultitenantable()
    {
        if (Auth::check() && ! defined('CRON_JOBS')) {
            static::addGlobalScope('share_data', function (Builder $builder) {
                $table = $builder->getModel()->getTable();
                $builder->whereIn($table . '.institute_id', [Auth::user()->institute_id, OWNER_INSTITUTE_ID]);
            });
        }
    }
}