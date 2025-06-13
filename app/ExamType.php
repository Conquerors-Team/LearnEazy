<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    use HasSlug;
    protected $table="examtypes";
    protected $fillable = ['title', 'description', 'status'];
}
