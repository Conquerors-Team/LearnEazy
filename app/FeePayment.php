<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
	use HasSlug;
    protected $table="fee_payments";

    public static function addFeeRecord($bacth_student, $fee_amount)
    {
    	$feerecord                    = new FeePayment();
    	$feerecord->batch_student_id  = $bacth_student->id;
    	$feerecord->user_id           = $bacth_student->user_id;
    	$feerecord->batch_id          = $bacth_student->batch_id;
    	$feerecord->institute_id      = $bacth_student->institute_id;
        $feerecord->amount            = $fee_amount;
    	$feerecord->balance           = $fee_amount;
    	$feerecord->save();
    }
  
}
