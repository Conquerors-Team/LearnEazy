<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use \App;

class Payment extends Model
{
	protected $table = 'payments';

    public static function boot()
    {
        parent::boot();
        Payment::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return Payment::where('slug', '=', $slug)->first();
    }

    public function updateTransactionRecords($records_type)
    {
        $records = \DB::table('payments')
        // ->where('created_at', '>=', \Carbon\Carbon::now()->subHour())
        ->where('updated_at', '>', 'DATE_SUB(NOW(),INTERVAL -1 HOUR)')
        ->where('payment_status', '=', PAYMENT_STATUS_PENDING);

        if($records_type=='online')
        {
            $records->where('payment_gateway','!=','offline');
        }
        else if($records_type=='offline')
        {
            $records->where('payment_gateway','=','offline');
        }
        else {
            $records->where('user_id','=',$records_type);
        }

        return $records->get();
    }

    /**
     * This method checks the item is purchased or not
     * If purchased, it validates the date is valid to use
     * If valid, it return TRUE
     * ELSE it returns FALSE
     * @param  [type]  $item_id   [description]
     * @param  string  $item_type [description]
     * @param  string  $user_id   [description]
     * @return boolean            [description]
     */
    public static function isItemPurchased($item_id, $item_type = 'combo', $user_id='')
    {
        if($user_id=='')
            $user_id = Auth::user()->id;

          $date = date('Y-m-d');
        $count = 0;


        if ( $item_type == 'paidcontent') {
            $subscription_records = Payment::where('start_date','<=',$date)
                          ->where('end_date','>=',$date)
                          ->where('user_id','=',$user_id)
                          ->where('plan_type','=','paidcontent')
                          ->where('payment_status', 'success')
                          ->get();
        } else {
            $subscription_records = Payment::where('start_date','<=',$date)
                          ->where('end_date','>=',$date)
                          ->where('user_id','=',$user_id)
                          ->get();
                      }

        foreach($subscription_records as $record)
        {
            if($record->plan_type == 'combo') {
               if($item_type == $record->plan_type)
               {
                    if($item_id == $record->item_id)
                        return TRUE;
               }

              if($item_type == 'exam' )
              {
                 $combo_record = App\ExamSeries::where('id','=',$record->item_id)->first();
                $combo_data = DB::table('examseries_data')->select('*')
                ->where('examseries_id','=',$combo_record->id)
                ->where('quiz_id','=',$item_id)
                ->get();
                if($combo_data)
                    return TRUE;
              }

            }
            else if($record->plan_type == 'paidcontent')
            {
                if($item_type == $record->plan_type)
               {

                    if($item_id == $record->item_id)
                        return TRUE;
               } else if($item_type == 'paidcontent' ) {
                    $paidcontent_record = App\StudentPaidContent::where('id','=',$record->item_id)->first();

                    $paidcontent_data = DB::table('student_paid_contents_data')->select('*')
                    ->where('student_paid_contents_id','=',$paidcontent_record->id)
                    ->where('item_id','=',$item_id)
                    ->get();

                    if($paidcontent_data)
                        return TRUE;
                }
            }
            else if($record->plan_type == 'exam')
            {
                if($record->item_id == $item_id ){

                    return TRUE;
                }
            }
            else if($record->plan_type == 'lms')
            {
                if($record->item_id == $item_id )
                    return TRUE;
            }

        }
        return FALSE;

    }

    /**
     * This method checks the item is purchased or not
     * If purchased, it validates the date is valid to use
     * If valid, it return TRUE
     * ELSE it returns FALSE
     * @param  [type]  $item_id   [description]
     * @param  string  $item_type [description]
     * @param  string  $user_id   [description]
     * @return boolean            [description]
     */
    public static function isItemPurchasedNotTrail($item_id, $item_type = 'combo', $user_id='')
    {
        if($user_id=='')
            $user_id = Auth::user()->id;

          $date = date('Y-m-d');
        $count = 0;


        if ( $item_type == 'paidcontent') {
            $subscription_records = Payment::where('start_date','<=',$date)
                          ->where('end_date','>=',$date)
                          ->where('user_id','=',$user_id)
                          ->where('plan_type','=','paidcontent')
                          ->where('notes','!=','Trail period')
                          ->where('payment_status', 'success')
                          ->get();
        } else {
            $subscription_records = Payment::where('start_date','<=',$date)
                          ->where('end_date','>=',$date)
                          ->where('user_id','=',$user_id)
                          ->get();
                      }

        foreach($subscription_records as $record)
        {
            if($record->plan_type == 'combo') {
               if($item_type == $record->plan_type)
               {
                    if($item_id == $record->item_id)
                        return TRUE;
               }

              if($item_type == 'exam' )
              {
                 $combo_record = App\ExamSeries::where('id','=',$record->item_id)->first();
                $combo_data = DB::table('examseries_data')->select('*')
                ->where('examseries_id','=',$combo_record->id)
                ->where('quiz_id','=',$item_id)
                ->get();
                if($combo_data)
                    return TRUE;
              }

            }
            else if($record->plan_type == 'paidcontent')
            {
                if($item_type == $record->plan_type)
               {

                    if($item_id == $record->item_id)
                        return TRUE;
               } else if($item_type == 'paidcontent' ) {
                    $paidcontent_record = App\StudentPaidContent::where('id','=',$record->item_id)->first();

                    $paidcontent_data = DB::table('student_paid_contents_data')->select('*')
                    ->where('student_paid_contents_id','=',$paidcontent_record->id)
                    ->where('item_id','=',$item_id)
                    ->get();

                    if($paidcontent_data)
                        return TRUE;
                }
            }
            else if($record->plan_type == 'exam')
            {
                if($record->item_id == $item_id ){

                    return TRUE;
                }
            }
            else if($record->plan_type == 'lms')
            {
                if($record->item_id == $item_id )
                    return TRUE;
            }

        }
        return FALSE;

    }

    /**
     * This method checks the item is purchased or not
     * If purchased, it validates the date is valid to use
     * If valid, it return TRUE
     * ELSE it returns FALSE
     * @param  [type]  $item_id   [description]
     * @param  string  $item_type [description]
     * @param  string  $user_id   [description]
     * @return boolean            [description]
     */
    public static function isTrailUsed($item_id, $item_type = 'combo', $user_id='')
    {
        if($user_id=='')
            $user_id = Auth::user()->id;

          $date = date('Y-m-d');
        $count = 0;


        if ( $item_type == 'paidcontent') {
            $subscription_records = Payment::where('start_date','<=',$date)
                          ->where('end_date','>=',$date)
                          ->where('user_id','=',$user_id)
                          ->where('plan_type','=','paidcontent')
                          ->where('notes','=','Trail period')
                          ->where('payment_status', 'success')
                          ->get();
        } else {
            $subscription_records = Payment::where('start_date','<=',$date)
                          ->where('end_date','>=',$date)
                          ->where('user_id','=',$user_id)
                          ->get();
                      }

        foreach($subscription_records as $record)
        {
            if($record->plan_type == 'combo') {
               if($item_type == $record->plan_type)
               {
                    if($item_id == $record->item_id)
                        return TRUE;
               }

              if($item_type == 'exam' )
              {
                 $combo_record = App\ExamSeries::where('id','=',$record->item_id)->first();
                $combo_data = DB::table('examseries_data')->select('*')
                ->where('examseries_id','=',$combo_record->id)
                ->where('quiz_id','=',$item_id)
                ->get();
                if($combo_data)
                    return TRUE;
              }

            }
            else if($record->plan_type == 'paidcontent')
            {
                if($item_type == $record->plan_type)
               {

                    if($item_id == $record->item_id)
                        return TRUE;
               } else if($item_type == 'paidcontent' ) {
                    $paidcontent_record = App\StudentPaidContent::where('id','=',$record->item_id)->first();

                    $paidcontent_data = DB::table('student_paid_contents_data')->select('*')
                    ->where('student_paid_contents_id','=',$paidcontent_record->id)
                    ->where('item_id','=',$item_id)
                    ->get();

                    if($paidcontent_data)
                        return TRUE;
                }
            }
            else if($record->plan_type == 'exam')
            {
                if($record->item_id == $item_id ){

                    return TRUE;
                }
            }
            else if($record->plan_type == 'lms')
            {
                if($record->item_id == $item_id )
                    return TRUE;
            }

        }
        return FALSE;

    }

       /**
     * This method checks the item is purchased or not
     * If purchased, it validates the date is valid to use
     * If valid, it return TRUE
     * ELSE it returns FALSE
     * @param  [type]  $item_id   [description]
     * @param  string  $item_type [description]
     * @param  string  $user_id   [description]
     * @return boolean            [description]
     */
    public static function isTrailUsedPWA($item_id, $item_type = 'combo', $user_id)
    {


          $date = date('Y-m-d');
        $count = 0;


        if ( $item_type == 'paidcontent') {
            $subscription_records = Payment::where('start_date','<=',$date)
                          ->where('end_date','>=',$date)
                          ->where('user_id','=',$user_id)
                          ->where('plan_type','=','paidcontent')
                          ->where('notes','=','Trail period')
                          ->where('payment_status', 'success')
                          ->get();
        } else {
            $subscription_records = Payment::where('start_date','<=',$date)
                          ->where('end_date','>=',$date)
                          ->where('user_id','=',$user_id)
                          ->get();
                      }

        foreach($subscription_records as $record)
        {
            if($record->plan_type == 'combo') {
               if($item_type == $record->plan_type)
               {
                    if($item_id == $record->item_id)
                        return TRUE;
               }

              if($item_type == 'exam' )
              {
                 $combo_record = App\ExamSeries::where('id','=',$record->item_id)->first();
                $combo_data = DB::table('examseries_data')->select('*')
                ->where('examseries_id','=',$combo_record->id)
                ->where('quiz_id','=',$item_id)
                ->get();
                if($combo_data)
                    return TRUE;
              }

            }
            else if($record->plan_type == 'paidcontent')
            {
                if($item_type == $record->plan_type)
               {

                    if($item_id == $record->item_id)
                        return TRUE;
               } else if($item_type == 'paidcontent' ) {
                    $paidcontent_record = App\StudentPaidContent::where('id','=',$record->item_id)->first();

                    $paidcontent_data = DB::table('student_paid_contents_data')->select('*')
                    ->where('student_paid_contents_id','=',$paidcontent_record->id)
                    ->where('item_id','=',$item_id)
                    ->get();

                    if($paidcontent_data)
                        return TRUE;
                }
            }
            else if($record->plan_type == 'exam')
            {
                if($record->item_id == $item_id ){

                    return TRUE;
                }
            }
            else if($record->plan_type == 'lms')
            {
                if($record->item_id == $item_id )
                    return TRUE;
            }

        }
        return FALSE;

    }



    public static function isOnlineclassSubscribed( $user_id = '' ) {

        return TRUE;

        if ( empty( $user_id ) ) {
            $user_id = \Auth::id();
        }

        if ( isOnlinestudent() ) {
            return true;
        }
        $date = date('Y-m-d');

        $subscription_records = Payment::where('start_date','<=',$date)
                          ->where('end_date','>=',$date)
                          ->where('user_id','=',$user_id)
                          ->where('plan_type','=','paidcontent')
                          ->get();
        foreach($subscription_records as $record)
        {
            if ( $record->subscribe_onlineclasses == '1' ) {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * This method returns the overall success, pending and failed records as summary
     * @return [type] [description]
     */
    public function getSuccessFailedCount()
    {
        $data = [];

        $role_id  = Auth::user()->role_id;

         if ( checkRole(getUserGrade(1)) ) {

                $data['success_count']      = Payment::where('payment_status','=','success')->count();
                $data['cancelled_count']    = Payment::where('payment_status','=','cancelled')->count();
                $data['pending_count']      = Payment::where('payment_status','=','pending')->count();
         }
         else{

             $institute_id  = adminInstituteId();

             $data['success_count']      = Payment::where('payment_status','=','success')
                                                   ->where('institute_id',$institute_id)->count();
             $data['cancelled_count']    = Payment::where('payment_status','=','cancelled')
                                                    ->where('institute_id',$institute_id)->count();
             $data['pending_count']      = Payment::where('payment_status','=','pending')
                                                   ->where('institute_id',$institute_id)->count();
         }

        return $data;
    }

    /**
     * This method gets the overall reports of the payments group by monthly
     * @param  string $year           [description]
     * @param  string $gateway        [description]
     * @param  string $payment_status [description]
     * @return [type]                 [description]
     */
    public function getSuccessMonthlyData($year='', $gateway='',$symbol='=' ,$payment_status='success')
    {
        if($year=='')
            $year = date('Y');

        $query = 'select sum(paid_amount) as total, sum(cost) as cost, MONTHNAME(created_at) as month from payments  where YEAR(created_at) = '.$year.' and payment_status = "'.$payment_status.'" group by YEAR(created_at), MONTH(created_at)';
        if($gateway!='')
        {
            $query = 'select sum(paid_amount) as total, MONTHNAME(created_at) as month from payments  where YEAR(created_at) = '.$year.' and payment_status = "'.$payment_status.'" and payment_gateway '.$symbol.' "'.$gateway.'" group by YEAR(created_at), MONTH(created_at)';
        }

        $result = DB::select($query);
        // dd($result);
        return $result;
    }

     /**
     * This method checks the item is purchased or not
     * If purchased, it validates the date is valid to use
     * If valid, it return TRUE
     * ELSE it returns FALSE
     * @param  [type]  $item_id   [description]
     * @param  string  $item_type [description]
     * @param  string  $user_id   [description]
     * @return boolean            [description]
     */
    public static function isParentPurchased($item_id, $item_type = 'combo', $user_id='')
    {
        if($user_id=='')
            $user_id = Auth::user()->id;

          $date = date('Y-m-d');
        $count = 0;



        $subscription_records = Payment::where('start_date','<=',$date)
                          ->where('end_date','>=',$date)
                          ->where('user_id','=',$user_id)
                          ->get();

        foreach($subscription_records as $record)
        {
            if($record->plan_type == 'combo') {
               if($item_type == $record->plan_type)
               {

                    if($item_id == $record->item_id)
                        return 'purchased';
               }

              if($item_type == 'exam' )
              {
                 $combo_record = App\ExamSeries::where('id','=',$record->item_id)->first();
                $combo_data = DB::table('examseries_data')->select('*')
                ->where('examseries_id','=',$combo_record->id)
                ->where('quiz_id','=',$item_id)
                ->get();
                if($combo_data)
                    return 'purchased';
              }

            }

            else if($record->plan_type == 'exam')
            {
                if($record->item_id == $item_id ){

                    return 'purchased';
                }
            }
            else if($record->plan_type == 'lms')
            {
                if($record->item_id == $item_id )
                    return 'purchased';
            }


        }
        return 'notpurchased';

    }


}
