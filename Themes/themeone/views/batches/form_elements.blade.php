



					<div class="row">

 					 <fieldset class="form-group col-md-3">

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

					<fieldset class="form-group col-md-3">
						{{ Form::label('student_class_id', getphrase('Class')) }}
						<span class="text-red">*</span>
						<?php
						$institute_id   = adminInstituteId();
						$classes = \App\StudentClass::where('institute_id', $institute_id)->get()->pluck('name', 'id')->prepend('Please select', '')->toArray();

						?>
						{{ Form::select('student_class_id', $classes, null, ['class'=>'form-control','placeholder'=>'select',
						    'ng-model'=>'student_class_id',
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": batches.student_class_id.$touched && batches.student_class_id.$invalid}',

							 ])}}
						<div class="validation-error" ng-messages="batches.student_class_id.$error" >
	    					{!! getValidationMessage()!!}
	    				</div>
					</fieldset>

					<fieldset class="form-group col-md-3">
						{{ Form::label('course_id', getphrase('Course')) }}
						<span class="text-red">*</span>
						<?php
						// $classes = \App\Course::where('institute_id', adminInstituteId())->get()->pluck('title', 'id')->toArray();

						$courses = \App\Course::where('institute_id', $institute_id)->get()->pluck('title', 'id')->prepend('Please select', '')->toArray();

						?>
						{{ Form::select('course_id', $courses, null, ['class'=>'form-control','placeholder'=>'select',
						    'ng-model'=>'course_id',
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": batches.course_id.$touched && batches.course_id.$invalid}',

							 ])}}
						<div class="validation-error" ng-messages="batches.course_id.$error" >
	    					{!! getValidationMessage()!!}
	    				</div>
					</fieldset>


					<fieldset class="form-group col-md-3">

						{{ Form::label('capacity', getphrase('capacity')) }}



						{{ Form::text('capacity', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 50,

							'ng-model'=>'capacity',

							'ng-class'=>'{"has-error": batches.capacity.$touched && batches.capacity.$invalid}',

							)) }}

						<div class="validation-error" ng-messages="batches.capacity.$error" >

	    					{!! getValidationMessage()!!}

	    				</div>

					</fieldset>


					<input type="hidden" name="fee_perhead" id="fee_perhead" value="1">


				    </div>


				  <div class="row input-daterange" >

				 	<?php
				 	$date_from = date('Y-m-d');
				 	$date_to = date('Y-m-d');
				 	$date_to = date('Y-m-d', strtotime($date_from. ' + 30 day'));
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

