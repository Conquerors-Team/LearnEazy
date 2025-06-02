 					

 				

					<div class="row">

 					 <fieldset class="form-group col-md-4">

						{{ Form::label('name', getphrase('name')) }}

						<span class="text-red">*</span>

						{{ Form::text('name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('batch_name'),

							'ng-model'=>'name', 

							'ng-pattern'=>getRegexPattern('name'), 

							'required'=> 'true', 

							'ng-class'=>'{"has-error": batches.name.$touched && batches.name.$invalid}',

							'ng-minlength' => '4',

							'ng-maxlength' => '40',

							)) }}

						<div class="validation-error" ng-messages="batches.name.$error" >

	    					{!! getValidationMessage()!!}

	    					{!! getValidationMessage('pattern')!!}

	    					{!! getValidationMessage('minlength')!!}

	    					{!! getValidationMessage('maxlength')!!}

						</div>

					</fieldset>


					<fieldset class="form-group col-md-4">

						{{ Form::label('capacity', getphrase('capacity')) }}

						<span class="text-red">*</span>

						{{ Form::number('capacity', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 50,

							'ng-model'=>'capacity', 


							'required'=> 'true', 

							'ng-class'=>'{"has-error": batches.capacity.$touched && batches.capacity.$invalid}',

						

							)) }}

						<div class="validation-error" ng-messages="batches.capacity.$error" >

	    					{!! getValidationMessage()!!}

	    				</div>

					</fieldset>


					<fieldset class="form-group col-md-4">

						{{ Form::label('fee_perhead', getphrase('fee_per_student')) }}

						<span class="text-red">*</span>

						{{ Form::number('fee_perhead', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 1000,

							'ng-model'=>'fee_perhead', 


							'required'=> 'true', 

							'ng-class'=>'{"has-error": batches.fee_perhead.$touched && batches.fee_perhead.$invalid}',

						

							)) }}

						<div class="validation-error" ng-messages="batches.fee_perhead.$error" >

	    					{!! getValidationMessage()!!}

	    				</div>

					</fieldset>

				    </div>


				  <div class="row input-daterange" >

				 	<?php 
				 	$date_from = date('Y-m-d');
				 	$date_to = date('Y-m-d');
				 	if($record)
				 	{
				 		$date_from = $record->start_date;
				 		$date_to = $record->end_date;
				 	}
				 	 ?>
				 	 <fieldset class="form-group col-md-3">
						{{ Form::label('start_date', getphrase('start_date')) }}
						<span class="text-red">*</span>
						{{ Form::text('start_date', $value = $date_from , $attributes = array('class'=>'input-sm form-control', 'placeholder' => '2015/7/17')) }}
					</fieldset>

					<fieldset class="form-group col-md-3">
						{{ Form::label('end_date', getphrase('end_date')) }}
						<span class="text-red">*</span>
						{{ Form::text('end_date', $value = $date_to , $attributes = array('class'=>'input-sm form-control', 'placeholder' => '2015/7/17')) }}
		 			</fieldset>

		 			 <fieldset class="form-group col-md-3">
						
						{{ Form::label('start_time', getphrase('start_time')) }}
						
						{{ Form::select('start_time', $slots_times, null, ['class'=>'form-control','placeholder'=>'select',
						    'ng-model'=>'start_time', 
                            
                            'required'=> 'true',

                            'ng-class'=>'{"has-error": batches.start_time.$touched && batches.start_time.$invalid}',

							 ])}}

						<div class="validation-error" ng-messages="batches.start_time.$error" >

	    					{!! getValidationMessage()!!}

	    				</div>
	    			</fieldset>


	    			 <fieldset class="form-group col-md-3">
						
						{{ Form::label('end_time', getphrase('end_time')) }}
						
						{{ Form::select('end_time', $slots_times, null, ['class'=>'form-control','placeholder'=>'select',
						    'ng-model'=>'end_time', 
                            
                            'required'=> 'true',

                            'ng-class'=>'{"has-error": batches.end_time.$touched && batches.end_time.$invalid}',

							 ])}}

						<div class="validation-error" ng-messages="batches.end_time.$error" >

	    					{!! getValidationMessage()!!}

	    				</div>
	    			</fieldset>

			</div>



						<div class="buttons text-center">

							<button class="btn btn-lg btn-success button"

							ng-disabled='!batches.$valid'>{{ $button_name }}</button>

						</div>

		 