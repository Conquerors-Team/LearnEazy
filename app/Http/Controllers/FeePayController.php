<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Http\Requests;
use App\Batch;
use App\User;
use App\BatchStudent;
use App\FeePayment;
use App\FeeRecords;
use App\Institute;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use Exception;

class FeePayController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }


  /**
   * Course listing method
   * @return Illuminate\Database\Eloquent\Collection
   */
  public function index()
  {

    if (!canDo('payfee_access')) {
      prepareBlockUserMessage();
      return back();
    }

    $data['active_class'] = 'fee';
    $data['layout']       = getLayout();
    $data['title']        = getPhrase('pay_fee');
    $payment_ways         = array(
      'cash'   => getPhrase('cash'),
      'online' => getPhrase('online'),
      'cheque' => getPhrase('cheque'),
      'DD'     => 'DD',
      'other'  => getPhrase('other_payment_way')
    );

    $data['payment_ways'] = $payment_ways;
    $user                 = Auth::user();
    $data['batches']      = Batch::join('batch_students', 'batch_students.batch_id', '=', 'batches.id')
      ->where('batches.user_id', $user->id)
      ->pluck('batches.name', 'batches.id')
      ->toArray();

    $data['currency']   = getCurrencyCode();

    $view_name = getTheme() . '::fee.select-particulars';
    return view($view_name, $data);
  }

  /**
   * This method get student based on batch id
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function getStudents(Request $request)
  {
    $batch_id   = $request->batch_id;
    $records    =  User::join('batch_students', 'batch_students.user_id', '=', 'users.id')
      ->where('batch_id', $batch_id)
      ->select(['batch_students.id', 'users.name'])
      ->get();

    return $records;
  }


  /**
   * This method return the selected student
   * details for paying the fee
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function studentDetails(Request $request)
  {
    // dd($request->all());
    // return $request;
    // $batch_student_id  = $request->feecategory_id;
    $batch_student_id           = $request->batch_student_id;
    // dd($batch_student_id);

    $record  = User::join('fee_payments', 'fee_payments.user_id', '=', 'users.id')
      ->where('batch_student_id', $batch_student_id)
      ->first();
      
    // dd($record);
    $fee_payments   =  FeeRecords::where('fee_payment_id', $record?->id)
      ->where('user_id', $record?->user_id)
      ->where('batch_id', $record?->batch_id)
      ->orderby('updated_at', 'desc')
      ->get();


    return json_encode(array('student_record' => $record, 'fee_payments' => $fee_payments));
  }


  /**
   * This add fee payment to student
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function payFee(Request $request)
  {
    // dd($request);

    $columns = array(

      'pay_amount' => 'numeric|min:0',
    );

    $this->validate($request, $columns);

    DB::beginTransaction();

    try {

      $feepayment                 = FeePayment::find($request->fee_payment_record);

      if ($request->pay_amount > $feepayment->amount) {
        flash('Ooops...!', 'you_are_paying_more_than_actual_fee', 'overlay');
        return redirect(URL_PAY_FEE);
      }

      $feepayment->paid_amount    = $request->pay_amount + $feepayment->paid_amount;
      $feepayment->payment_method = $request->payment_mode;
      $feepayment->paid_date      = date('Y-m-d');
      $feepayment->comments       = $request->notes;
      $feepayment->added_by       = Auth::user()->id;
      $feepayment->balance        = $feepayment->amount - $feepayment->paid_amount;
      $feepayment->save();

      $feerecord                   = new FeeRecords();
      $feerecord->fee_payment_id   = $feepayment->id;
      $feerecord->user_id          = $feepayment->user_id;
      $feerecord->batch_id         = $feepayment->batch_id;
      $feerecord->institute_id     = $feepayment->institute_id;
      $feerecord->amount           = $feepayment->amount;
      $feerecord->paid_amount      = $request->pay_amount;
      $feerecord->paid_date        = date('Y-m-d');
      $feerecord->added_by         = Auth::user()->id;
      $feerecord->save();

      $paid_amount          = $feerecord->getTotalPaid($feepayment->user_id, $feepayment->batch_id, $feepayment->id);
      $feerecord->balance   = $feepayment->amount - $paid_amount;
      $feerecord->save();


      DB::commit();
      flash('success', 'fee_paid_successfully', 'success');
    } catch (Exception $e) {
      // dd($e->getMessage());
      DB::rollBack();
      flash('Oops...!', 'Error! Please Try again', 'error');
    }

    return redirect(URL_PAY_FEE);
  }

  /**
   * This method add the discoutn for a student
   * @param Request $request [description]
   */
  public function addDiscount(Request $request)
  {


    $columns = array(

      'discount' => 'numeric|min:0',
    );

    $this->validate($request, $columns);


    $fee_paymentid  = $request->feeid;
    $discount       = $request->user_discount;

    $record   = FeePayment::find($fee_paymentid);
    if ($record) {

      if ($discount > 0 && $discount <= $record->amount) {

        $record->amount   = $record->amount - $discount;
        $record->discount = $record->discount + $discount;
        $record->balance  = $record->amount;
        $record->save();

        flash('success', 'discount_added_successfully', 'success');
        return redirect(URL_PAY_FEE);
      }
    }
    flash('error', 'imporoper_data_submitted', 'error');
    return redirect(URL_PAY_FEE);
  }


  /**
   * Get Fee reports Batch wise
   * @return [type] [description]
   */
  public function batchReports()
  {

    if (!checkRole(getUserGrade(2))) {
      prepareBlockUserMessage();
      return back();
    }

    $data['active_class'] = 'fee';
    $data['layout']       = getLayout();
    $data['title']        = getPhrase('batch_wise_fee_paid_reports');
    $user                 = Auth::user();
    $data['batches']      = Batch::join('batch_students', 'batch_students.batch_id', '=', 'batches.id')
      ->where('batches.user_id', $user->id)
      ->pluck('batches.name', 'batches.id')
      ->toArray();

    $data['currency']   = getCurrencyCode();

    $view_name = getTheme() . '::fee.batch-reports';
    return view($view_name, $data);
  }


  /**
   * Get the batch wise fee paid students
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function feePaidBatchStudents(Request $request)
  {

    $batch_id   = $request->batch_id;
    $records    =  User::join('fee_payments', 'fee_payments.user_id', '=', 'users.id')
      ->where('batch_id', $batch_id)
      // ->select(['fee_payments.id','users.name'])
      ->get();

    return $records;
  }


  /**
   * Print fee paid reports for batch
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function printReports(Request $request)
  {

    if (!checkRole(getUserGrade(2))) {
      prepareBlockUserMessage();
      return back();
    }

    $batch_id   = $request->batch_id;

    $records    =  User::join('fee_payments', 'fee_payments.user_id', '=', 'users.id')
      ->where('batch_id', $batch_id)
      ->get();

    $batch_record       = Batch::find($batch_id);
    $data['institute']  = getInstitute($batch_record->institute_id);
    $data['title']      = getPhrase('fee_paid_reports');

    if ($batch_record) {

      $data['title']   = ucwords($batch_record->name) . ' ' . getPhrase('fee_paid_reports');
    }

    $data['records']   = $records;
    $data['currency']   = getCurrencyCode();

    $view_name = getTheme() . '::fee.print-batch-reports';

    $content = \View::make($view_name, $data);

    $html_data = ($content);

    echo $html_data;

    die();
  }


  /**
   * This method get fee paid reports institute wise
   * @return [type] [description]
   */
  public function instituteReports()
  {

    if (!checkRole(getUserGrade(2))) {
      prepareBlockUserMessage();
      return back();
    }

    $data['active_class'] = 'fee';
    $data['layout']       = getLayout();
    $data['title']        = getPhrase('institute_wise_fee_paid_reports');
    $user                 = Auth::user();
    $data['institutes']   = Institute::pluck('institute_name', 'id')
      ->toArray();

    $data['currency']   = getCurrencyCode();

    $view_name = getTheme() . '::fee.institute-reports';
    return view($view_name, $data);
  }

  /**
   * Get institute batches based on selected institute
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function instituteBatches(Request $request)
  {

    $records   = Batch::where('institute_id', $request->institute_id)->get();
    return $records;
  }

  /**
   * Get the reports from date wise
   * @return [type] [description]
   */
  public function dateReports()
  {
    if (!checkRole(getUserGrade(2))) {
      prepareBlockUserMessage();
      return back();
    }

    $data['active_class'] = 'fee';
    $data['layout']       = getLayout();
    $data['title']        = getPhrase('date_wise_fee_paid_reports');
    $user                 = Auth::user();
    $data['institutes']   = Institute::pluck('institute_name', 'id')
      ->toArray();

    $data['currency']      = getCurrencyCode();
    $data['date_from']     = date('Y-m-d');
    $data['date_to']       = date('Y-m-d');


    $view_name = getTheme() . '::fee.datewise-reports';
    return view($view_name, $data);
  }

  public function getDailyReports(Request $request)
  {

    $from_date   = $request->from_date;
    $to_date     = $request->from_date;

    if (checkRole(getUserGrade(8))) {

      $institute_id  = adminInstituteId();
    } elseif ($request->has('institute_id')) {

      $institute_id  = $request->institute_id;
    } else {

      $institute_id  = 0;
    }


    if ($request->has('to_date')) {

      $to_date   = $request->to_date;

      $records  = User::join('fee_payment_records', 'fee_payment_records.user_id', '=', 'users.id')
        ->join('batches', 'batches.id', '=', 'fee_payment_records.batch_id')
        ->where('fee_payment_records.paid_date', '>=', $from_date)
        ->where('fee_payment_records.paid_date', '<=', $to_date)
        ->where('fee_payment_records.institute_id', $institute_id)
        ->select(['users.name', 'fee_payment_records.amount', 'fee_payment_records.paid_amount', 'fee_payment_records.balance', 'fee_payment_records.paid_date', 'batches.name as batchname', 'users.slug'])
        ->orderby('fee_payment_records.updated_at', 'desc')
        ->get();
    } else {

      $records  = User::join('fee_payment_records', 'fee_payment_records.user_id', '=', 'users.id')
        ->join('batches', 'batches.id', '=', 'fee_payment_records.batch_id')
        ->where('fee_payment_records.paid_date', $from_date)
        ->where('fee_payment_records.institute_id', $institute_id)
        ->select(['users.name', 'fee_payment_records.amount', 'fee_payment_records.paid_amount', 'fee_payment_records.balance', 'fee_payment_records.paid_date', 'batches.name as batchname', 'users.slug'])
        ->orderby('fee_payment_records.updated_at', 'desc')
        ->get();
    }
    $total_paid  = $records->sum('paid_amount');



    return json_encode(array('fee_payments' => $records, 'date1' => $from_date, 'date2' => $to_date, 'total_paid' => $total_paid));
  }
}
