<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasSlug;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['group'];
    
}
