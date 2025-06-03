@extends($layout)

@section('header_scripts')
 <link href="{{CSS}}bootstrap-datepicker.css" rel="stylesheet"> 
 @endsection

 <style type="text/css">
   .input-sm{
    margin-top: 0px !important;
   }
 </style>

@section('content')

<div id="page-wrapper" ng-controller="feeReportsController">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">
                            <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
                            <li>{{ $title }}</li>
                        </ol>
                    </div>
                </div>
                                
                <!-- /.row -->
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        
                       
                        <h1>{{ $title }}</h1>
                    </div>
                    <div class="panel-body" ng-init = "getDailyReports('{{$date_from}}')">

                         {!! Form::open(array('url' => URL_PRINT_BATCH_FEE_REPORTS, 'method' => 'POST','name'=>'formSchedule', 'novalidate'=>'','id'=>'printReports','target'=>'_blank' )) !!}
                        


                          <?php
                             $from_day  = date('Y-m-d');
                             $to_day  = date('Y-m-d');
                          ?>
                        <div class="row" >
                         
                           <fieldset class="form-group col-md-6">
                            {{ Form::label('start_date', getphrase('from')) }}
                            <span class="text-red">*</span>
                            {{ Form::text('start_date', $value = $from_day , $attributes = array(

                              'class'        =>'input-sm form-control datepicker',
                              'ng-model'     =>'start_date',
                               'placeholder' => '2016-05-06',
                               'ng-change'   =>'getReports(start_date,end_date)'

                               )) }}
                          </fieldset>

                          <fieldset class="form-group col-md-6">
                            {{ Form::label('end_date', getphrase('to')) }}
                            <span class="text-red">*</span>
                            {{ Form::text('end_date', $value = $to_day , $attributes = array(

                              'class'=>'input-sm form-control datepicker', 
                              'ng-model'    =>'end_date',
                              'placeholder' => '2016-05-06',
                              'ng-change'   =>'getReports(start_date,end_date)'

                              )) }}
                          </fieldset>

                      </div>

                       @if(checkRole(getUserGrade(1)))

                      <div class="row col-md-12">

                        <fieldset class="form-group col-md-6 col-md-offset-3">

                          {{ Form::label('institute_id', getphrase('select_institute')) }}

                          <span class="text-red">*</span>

                          {{Form::select('institute_id', $institutes, null, [

                            'class'       =>'form-control', 
                            'id'          =>'institute_id',
                            'placeholder' =>'Select',
                            'ng-model'    =>'institute_id',
                            'ng-change'   =>'getInstituteReports(institute_id, start_date, end_date)'

                          ])}}

                          
                        </fieldset>

                   </div>

                   @endif

                      <br>
                      <br>

            <div ng-if="result_data.length > 0 ">

              <div class="row col-md-12">
                <h4 align="center">Fee Reports From @{{date_from}} Upto @{{date_to}}</h4>
                <h3 align="center">Total Paid {{$currency}}@{{total_paid}}</h3>
                <table class="table table-bordered" style="border-collapse: collapse;">
                <thead>
                    <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('sno')}}</b></th>
                    <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('name')}}</b></th>
                    <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('batch_name')}}</b></th>
                    <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('amount')}}</b></th>
                    <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('paid_amount')}}</b></th>
                    <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('balance')}}</b></th>
                    <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('paid_date')}}</b></th>
                    
                   
                </thead>
                <tbody>

            <tr ng-repeat="user in result_data | filter:search track by $index">

            <td style="border:1px solid #000;text-align: center;" >@{{$index+1}}</td>
            <td style="border:1px solid #000;text-align: center;">
             <a target="_blank" href="{{URL_USER_DETAILS}}@{{user.slug}}">@{{user.name}}</a></td>
        
            <td style="border:1px solid #000;text-align: center;">@{{user.batchname}}</td>
            <td style="border:1px solid #000;text-align: center;">{{$currency}} @{{user.amount}}</td>
            <td style="border:1px solid #000;text-align: center;">{{$currency}} @{{user.paid_amount}}</td>
            <td style="border:1px solid #000;text-align: center;">{{$currency}} @{{user.balance}}</td>
            <td style="border:1px solid #000;text-align: center;"> @{{user.paid_date}}</td>
            
            
                 </tr> 
     
             </tbody>
        </table>

  </div>

    <br>

    {{-- <a ng-if="result_data.length > 0" class="btn btn-primary pull-right" ng-click="printIt()" >Print</a> --}}
  </div>
  <div class="row col-md-12">
    
    <h4 ng-if="result_data.length == 0" align="center" >{{getPhrase('no_data_available')}}</h4> 
  </div>

                       
                  
                  </div>

                {!! Form::close() !!}

                  </div>

                </div>
            </div>
            <!-- /.container-fluid -->


        </div>
@endsection
 

@section('footer_scripts')

@include('fee.scripts.fee-datewise-js-scripts')

  <script src="{{JS}}datepicker.min.js"></script>

 <script>
    $('.datepicker').datepicker({
        autoclose: true,
        endDate: '+0d',
        format: 'yyyy-mm-dd',
    });
 </script>

@stop
