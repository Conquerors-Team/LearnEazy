<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class LmsCategory extends Model
{
    use HasSlug;
    protected $table = 'lmscategories';

 	public static function boot()
    {
        parent::boot();
        LmsCategory::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return LmsCategory::where('slug', '=', $slug)->first();
    }

    public function contents()
    {
        return $this->hasMany('App\LmsContent', 'category_id');
    }
}
