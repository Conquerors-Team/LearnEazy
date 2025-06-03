<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assosiates extends Model
{
    protected $table="institute_assosiates";
  
    public static function addRecord()
    {
    	
    	$record   = new Assosiates();
    
    }
   
}
