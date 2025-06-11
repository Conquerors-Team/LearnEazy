<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class StudentPaidContentData extends Model
{
    use HasSlug;
     protected $fillable = ['student_paid_contents_id', 'item_id','item_type'];
     protected $table= "student_paid_contents_data";

     public static function boot()
    {
        parent::boot();
        StudentPaidContentData::observe(new \App\Observers\UserActionsObserver);
    }
}
