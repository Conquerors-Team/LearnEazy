<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Assosiates extends Model
{
    use HasSlug;
    protected $table="institute_assosiates";
  
    public static function addRecord()
    {
    	
    	$record   = new Assosiates();
    
    }
   
}
