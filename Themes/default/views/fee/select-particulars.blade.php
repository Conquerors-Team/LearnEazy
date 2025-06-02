@extends($layout)
@section('content')

<div id="page-wrapper" ng-controller="feePayController">
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

                @include('errors.errors')
                                
                <!-- /.row -->
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        
                       
                        <h1>{{ $title }}</h1>
                    </div>
                    <div class="panel-body">
                         {!! Form::open(array('url' => URL_STUDENT_FEE_PAY_ADD, 'method' => 'POST','name'=>'formSchedule', 'novalidate'=>'')) !!}
                        <div class="row">

                        <fieldset class="form-group col-md-6">

                          {{ Form::label('batch_id', getphrase('batches')) }}

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

                        <fieldset ng-if="selected_batch" class="form-group col-md-6">
                          <span class="text-red">*</span>

                           <label for = "batch_student_id">{{getPhrase('select_student')}}</label>
                          <select 
                          name      = "batch_student_id" 
                          id        = "batch_student_id" 
                          class     = "form-control" 
                          ng-model  = "batch_student_id" 
                          ng-change = "getStuduntFeeDetails(selected_batch, batch_student_id)"
                          ng-options= "option.id as option.name for option in students track by option.id">
                          <option value="">{{getPhrase('select')}}</option>

                          </select>

                      </fieldset> 

                      <input type="hidden" name="fee_payment_record" value="@{{student_data.id}}" id="fee_payment_record">
                  
                  </div>

                  <div class="row" ng-if="selected_studentid">
                    <div class="col-md-12">
                    <div class="btn btn-primary panel-btn collapsed" data-toggle="collapse" data-target="#student_details_box">{{getPhrase('student_details')}} <span class="dc-caret">
                   <i class="fa fa-angle-down" aria-hidden="true"></i></span></div>

                      <div class="collapse panel-expand-box" id="student_details_box">
                      <div class="row">
                          <div class="col-md-2">
                               <div class="profile-details text-center" ng-if="student_data.image==''">
                            <div class="profile-img"><img src="{{IMAGE_PATH_PROFILE_DEFAULT}}" alt=""  style="width: 100px;height: 100px;"></div>
                        </div>
                            <div class="profile-details text-center" ng-if="student_data.image!=''">
                            <div class="profile-img"><img src="{{IMAGE_PATH_PROFILE}}@{{student_data.image}}" alt=""  style="width: 100px;height: 100px;"></div>
                        </div>
                             <b class="text-center" style="display: block;"> 
                             @{{student_data.name | uppercase}}
                             </b>
                        </div>
                          
                          <div class="col-md-10">
                             <div class="row">
                                
                                 <div class="col-md-6">
                                      <table class="table panel-table">
                                         <tbody>
                                            
                                             <tr>
                                                 <th>{{getPhrase('email')}}</th>
                                                 <td>@{{student_data.email}}</td>
                                             </tr>
                                             <tr>
                                                 <th>{{getPhrase('phone')}}</th>
                                                 <td>@{{student_data.phone}} </td>
                                             </tr>
                                         </tbody>
                                     </table>
                                 </div>
                             </div>
                          </div>
                      </div>
                      </div>
                      </div>
                  </div>
          
                  <br>
                  {{-- Fee History and Add Discount To Student --}}
                  <div class="row" ng-if="selected_studentid">
                    <div class="col-md-12">
                       <div class="btn btn-primary panel-btn collapsed" data-toggle="collapse" data-target="#fee_history">{{getPhrase('fee_paid_history_and_add_discount')}} <span class="dc-caret">
                          <i class="fa fa-angle-down" aria-hidden="true"></i></span>
                        </div>

                        <div class="collapse panel-expand-box" id="fee_history">
                           <div class="row">

                              <div class="col-md-8">
                                  <table class="table panel-table">
                                    <thead>
                                      <th>S.No</th>
                                      <th>{{getPhrase('date')}}</th>
                                      <th>{{getPhrase('amount_to_pay')}}</th>
                                      <th>{{getPhrase('paid_amount')}}</th>
                                      <th>{{getPhrase('balance')}}</th>
                                      
                                    </thead>
                                    <tbody>
                                      <tr ng-repeat="record in feerecords_data | filter:search track by $index" ng-if="feerecords_data.length > 0">

                                        <td>@{{$index+1}}</td>
                                        <td>@{{record.paid_date}}</td>
                                        <td>@{{record.amount}}</td>
                                        <td>@{{record.paid_amount}}</td>
                                        <td>@{{record.balance}}</td>

                                      </tr>

                                      <tr ng-if="feerecords_data.length == 0">
                                        <td></td>
                                        <td></td>
                                        <td>{{getPhrase('no_data_available')}}</td>
                                        <td></td>
                                        <td></td>
                                      </tr>

                                    </tbody>
                                   
                                 </table>
                                
                              </div>

                              <div class="col-md-4">

                                 

                                  
                                    <p><strong>Amount to Pay : </strong>{{$currency}}  @{{total_amount_pay | currency : '' : 2 }}</p>
                                    <p><strong>Discount : </strong>{{$currency}}  @{{student_data.discount | currency : '' : 2 }}</p>
                                   
                                  

                                     <fieldset class="form-group" ng-if="total_amount_pay > 0">
            
                                      {{ Form::label('discount', getphrase('add_discount_amount')) }}
                                      
                                       {{ Form::number('discount', null, 
                                      ['class'=>'form-control',
                                      "id"=>"discount", 
                                      'required'=> 'true',
                                      'min'=>'0',
                                    
                                        ])}} 

                                    </fieldset>


                                     <div ng-if="total_amount_pay > 0"  class="buttons text-center" >

                                          <a class="btn btn-sm btn-primary" href="javascript:void(0)" onclick="showConfirm()">{{ getPhrase('add_discount') }}</a>
                                       
                                  </div>

                                
                                
                              </div>
                         
                          
                         
                          </div>
                       </div>
                     </div>
                  </div>

                  <br>

                  <div class="row">
                   <div class="col-md-12">
                  <?php 

                    $minimum_percentage = 100; 

                  ?>
          
                  </div>
                  </div>
                 

                 <div class="row" ng-if="selected_studentid">
                  <div class="col-md-12">
                    <table class="table table-bordered">
                      <tbody>
                        <tr>
                          <td><strong>{{getphrase('payment_mode')}}</strong></td>
                          <td> 
                           
                            {{ Form::select('payment_mode', $payment_ways, null, 
                            ['class'=>'form-control',
                            "id"=>"payment_mode", 
                            "ng-model"=>"payment_mode", 
                            'required'=> 'true',
                            'ng-init' =>'payment_mode="cash"',
                             
                             'ng-class'=>'{"has-error": formSchedule.payment_mode.$touched && formSchedule.payment_mode.$invalid}',
                         ])}}
                          </td>
                          </tr>
                        
                      

                        <tr>
                          <td><strong>Amount to Pay</strong></td>
                          <td><strong>{{$currency}} @{{total_amount_pay | currency : '' : 2 }}</strong></td>
                        </tr>

                       {{--  <tr>
                          <td><strong>{{getphrase('discount')}}</strong></td>
                          <td>
                           
                            {{ Form::number('discount', null, 
                            ['class'=>'form-control',
                            "id"=>"discount", 
                            "ng-model"=>"discount", 
                            'required'=> 'true',
                            'min'=>'0',
                            'string-to-number'=>'discount',
                            'ng-change'=>'afterDiscount(discount,total_amount_pay)',
                             
                             'ng-class'=>'{"has-error": formSchedule.discount.$touched && formSchedule.discount.$invalid}',
                         ])}} 
                          </td>
                        </tr> --}}

                        

                         <tr>
                          <td><strong>Total Amount</strong></td>
                          <td><strong>{{$currency}} @{{total_amount_pay | currency : '' : 2 }}</strong></td>
                        </tr>

                       {{--   <tr>
                          <td><strong>After Discount</strong></td>
                          <td><strong>{{$currency}} @{{final_pay | currency : '' : 2 }}</strong></td>
                        </tr>
 --}}
                        <tr>
                          <td><strong>Enter amount</strong></td>
                          <td><strong>
                            <input autofocus="true" ng-model="paid_amount" ng-change="validateAmount(final_pay, paid_amount,{{$minimum_percentage}})" type="number" name="pay_amount" min="0">
                          </strong>
                        </td>
                        </tr>
                        <tr>
                          <td><strong>Notes</strong></td>
                          <td><textarea name="notes" class="form-control"></textarea></td>
                        </tr>
                      </tbody>
                      
                    </table>
                  </div>
                  </div>



                  <input type="hidden" name="paid_percentage" value="@{{paid_percentage}}">
                  
                  {{--   <div ng-if="total_amount_pay > 0"  class="buttons text-center" >
                            <button class="btn btn-lg btn-success button"
                            ng-disabled='!show_pay_button'>{{ getPhrase('pay_now') }}</button>
                         
                    </div> --}}

                     <div ng-if="total_amount_pay > 0"  class="buttons text-center" >
                            <button class="btn btn-lg btn-success button">{{ getPhrase('pay_now') }}</button>
                         
                    </div>

                         {!! Form::close() !!}

                  </div>

                </div>
            </div>
            <!-- /.container-fluid -->

            <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm" style="width: 600px;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" align="center">{{getPhrase('add_discount')}}</h4>
      </div>
      <div class="modal-body">

        {!! Form::open(array('url' => URL_ADD_DISCOUNT_TO_STUDENT, 'method' => 'POST','name'=>'formDiscount','id'=>'formDiscount','novalidate'=>'')) !!}


        <h4 align="center">{{getPhrase('are_you_sure_to_add_discount_for_student')}}</h4>
        <input type="hidden" name="user_discount" id="user_discount" value="0">
        <input type="hidden" name="feeid" id="feeid" value="0">
       </div>

      

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-right" >{{getPhrase('yes')}}</button>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{getPhrase('no')}}</button>
      </div>
    </div>
     {!! Form::close() !!}

  </div>
</div>

        </div>
@endsection
 

@section('footer_scripts')
@include('fee.scripts.js-scripts')

<script>
  function showConfirm() {
     var mydiscount   = $('#discount').val();
     var feeid   = $('#fee_payment_record').val();
     $('#user_discount').val(mydiscount);
     $('#feeid').val(feeid);
     $('#myModal').modal('show');
  }

 
 </script>

 
@stop
