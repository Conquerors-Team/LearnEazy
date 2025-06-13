<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class ExamLanguage extends Model
{
    use HasSlug;
    protected $table = 'examlanguages';
}
