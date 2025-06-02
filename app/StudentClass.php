<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    // protected $appends = ['name_institute'];

    public static function boot()
    {
        parent::boot();
        StudentClass::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return StudentClass::where('slug', '=', $slug)->first();
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'student_classes_courses');
    }

    public function institute() {
        return $this->belongsTo(Institute::class, 'institute_id')->withDefault();
    }

    public function getNameInstituteAttribute() {
        $institute = $this->institute->institute_name;
        $name = $this->name;
        if ( ! empty( $institute ) ) {
            $name .= ' ('. $institute .')';
        }
        return $name;
    }
}
