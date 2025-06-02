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

                        <div class="row col-md-12">

                        <fieldset class="form-group col-md-6 col-md-offset-3">

                          {{ Form::label('institute_id', getphrase('select_institute')) }}

                          <span class="text-red">*</span>

                          {{Form::select('institute_id', $institutes, null, ['class'=>'form-control', 'id'=>'institute_id',
                            'ng-model'=>'institute_id',
                            'placeholder'=>'Select',
                            "ng-change" => "getBatches(institute_id)",
                            'required'=> 'true', 
                            'ng-class'=>'{"has-error": formQuiz.institute_id.$touched && formQuiz.institute_id.$invalid}'
                          ])}}

                           <div class="validation-error" ng-messages="formQuiz.institute_id.$error" >
                              {!! getValidationMessage()!!}
                          </div>

                        </fieldset>

                      </div>


                       <div class="row col-md-12">

                        <fieldset ng-if="batches.length > 0 " class="form-group col-md-6 col-md-offset-3">
                          <span class="text-red">*</span>

                           <label for = "batch_id">{{getPhrase('select_batch')}}</label>
                          <select 
                          name      = "batch_id" 
                          id        = "batch_id" 
                          class     = "form-control" 
                          ng-model  = "batch_id" 
                          ng-change = "getStudents(batch_id)"
                          ng-options= "option.id as option.name for option in batches track by option.id">
                          <option value="">{{getPhrase('select')}}</option>

                          </select>

                      </fieldset> 

                        <h4 ng-if="batches.length == 0 " align="center"> {{getPhrase('no_batches_are_available')}}</h4>

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

@include('fee.scripts.fee-institute-js-scripts')

@stop
