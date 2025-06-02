<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectLogos extends Model
{
    protected $table="subjects_logos";

    public $timestamps = false;

    public static function getRecordWithSlug($id)
    {
        return SubjectLogos::where('id', '=', $id)->first();
    }
}
