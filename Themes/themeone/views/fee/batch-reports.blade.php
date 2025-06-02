@extends($layout)
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
                    <div class="panel-body">

                         {!! Form::open(array('url' => URL_PRINT_BATCH_FEE_REPORTS, 'method' => 'POST','name'=>'formSchedule', 'novalidate'=>'','id'=>'printReports','target'=>'_blank' )) !!}

                        <div class="row">

                        <fieldset class="form-group col-md-6 col-md-offset-3">

                          {{ Form::label('batch_id', getphrase('select_batch')) }}

                          <span class="text-red">*</span>

                          {{Form::select('batch_id', $batches, null, ['class'=>'form-control', 'id'=>'batch_id',
                            'ng-model'=>'batch_id',
                            'placeholder'=>'Select',
                            "ng-change" => "getStudents(batch_id)",
                            'required'=> 'true', 
                            'ng-class'=>'{"has-error": formQuiz.batch_id.$touched && formQuiz.batch_id.$invalid}'
                          ])}}

                           <div class="validation-error" ng-messages="formQuiz.batch_id.$error" >
                              {!! getValidationMessage()!!}
                          </div>

                        </fieldset>

                      </div>

                      <br>
                      <br>

            <div ng-if="result_data.length > 0 ">

              <div class="row">

                <table class="table table-bordered" style="border-collapse: collapse;">
                <thead>
                    <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('sno')}}</b></th>
                    <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('name')}}</b></th>
                    <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('amount')}}</b></th>
                    <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('paid_amount')}}</b></th>
                    <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('discount')}}</b></th>
                    <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('balance')}}</b></th>
                    
                   
                </thead>
                <tbody>

            <tr ng-repeat="user in result_data | filter:search track by $index">

            <td style="border:1px solid #000;text-align: center;" >@{{$index+1}}</td>
            <td style="border:1px solid #000;text-align: center;">
             <a target="_blank" href="{{URL_USER_DETAILS}}@{{user.slug}}">@{{user.name}}</a></td>
        
            <td style="border:1px solid #000;text-align: center;">{{$currency}} @{{user.amount}}</td>
            <td style="border:1px solid #000;text-align: center;">{{$currency}} @{{user.paid_amount}}</td>
            <td style="border:1px solid #000;text-align: center;">{{$currency}} @{{user.discount}}</td>
            <td style="border:1px solid #000;text-align: center;">{{$currency}} @{{user.balance}}</td>
            
            
                 </tr> 
     
             </tbody>
        </table>

  </div>

    <br>

    <a ng-if="result_data.length > 0" class="btn btn-primary pull-right" ng-click="printIt()" >Print</a>
  </div>
    <div ng-if="result_data.length == 0" class="text-center" >{{getPhrase('no_data_available')}}</div> 

                       
                  
                  </div>

                {!! Form::close() !!}

                  </div>

                </div>
            </div>
            <!-- /.container-fluid -->


        </div>
@endsection
 

@section('footer_scripts')

@include('fee.scripts.fee-reports-js-scripts')

@stop
