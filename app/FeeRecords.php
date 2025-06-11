<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class FeeRecords extends Model
{
	use HasSlug;
    protected $table="fee_payment_records";

    public function getTotalPaid($user_id, $batch_id, $fee_id)
    {
    	
    	$total_paid   = FeeRecords::where('fee_payment_id',$fee_id)
    	                            ->where('user_id',$user_id)
    	                            ->where('batch_id', $batch_id)
    	                            ->sum('paid_amount');
    	// dd($total_paid);                            
    	return $total_paid;                            
    }


}
