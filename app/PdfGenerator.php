<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
 
class PdfGenerator extends Model
{
  use HasSlug;
	public $html_data = '';
  
  public function generatePDF()
  {
   
  }
}
