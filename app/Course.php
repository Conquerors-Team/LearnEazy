<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasSlug;
    public static function boot()
    {
        parent::boot();
        Course::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return Course::where('slug', '=', $slug)->first();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'courses_subjects');
    }

    public function student_class()
    {
        return $this->belongsTo(StudentClass::class, 'student_class_id')->withDefault([
        	'name' => '',
        ]);
    }
}
