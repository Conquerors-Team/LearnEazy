<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class SubjectLogos extends Model
{
    use HasSlug;
    protected $table="subjects_logos";

    public $timestamps = false;

    public static function getRecordWithSlug($id)
    {
        return SubjectLogos::where('id', '=', $id)->first();
    }
}
