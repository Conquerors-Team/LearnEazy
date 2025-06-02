



					<div class="row">

 					 <fieldset class="form-group col-md-6">
						{{ Form::label('title', getphrase('title')) }}
						<span class="text-red">*</span>
						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('title'),
							'ng-model'=>'title',
							'ng-pattern'=>getRegexPattern('name'),
							'required'=> 'true',
							'ng-class'=>'{"has-error": formNotifications.title.$touched && formNotifications.title.$invalid}',
							'ng-minlength' => '4',
							'ng-maxlength' => '65535',
							)) }}
						<div class="validation-error" ng-messages="formNotifications.title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('pattern')!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
						</div>
					</fieldset>

					<fieldset class="form-group col-md-6" >
						{{ Form::label('notification_for', 'notification_for') }}
						<?php
						$notification_for = ['batch' => 'Batch', 'class' => 'Class', 'faculty' => 'Faculty'];
						if(checkRole(getUserGrade(3))){
							$notification_for['allinstitutes'] = 'All institutes';
						} else {
							$notification_for['allstudents'] = 'All Students';
						}
						?>
						{{Form::select('notification_for', $notification_for, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'ng-model'=>'notification_for',
							'required' => 'true',
							'ng-class'=>'{"has-error": formLms.notification_for.$touched && formLms.notification_for.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.notification_for.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

					<fieldset class="form-group col-md-6" >
						{{ Form::label('batch_id', 'Batch') }}

						<?php
						$batches = \App\Batch::where('institute_id', adminInstituteId())->get()->pluck('name', 'id')->toArray();
						?>
						{{Form::select('batch_id', $batches, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'ng-model'=>'batch_id',

							'ng-class'=>'{"has-error": formLms.batch_id.$touched && formLms.batch_id.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.batch_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>


				<fieldset class="form-group col-md-6" >
					{{ Form::label('student_class_id', 'Class') }}

					<?php
					if(checkRole(getUserGrade(3))){
						$subjects = \App\StudentClass::get()->pluck('name_institute', 'id')->toArray();
					} else {
						$subjects = \App\StudentClass::where('institute_id', adminInstituteId())->get()->pluck('name', 'id')->toArray();
					}
					?>
					{{Form::select('student_class_id', $subjects, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
					'ng-model'=>'student_class_id',

						'ng-class'=>'{"has-error": formLms.student_class_id.$touched && formLms.student_class_id.$invalid}',
					]) }}
					<div class="validation-error" ng-messages="formLms.student_class_id.$error" >
    					{!! getValidationMessage()!!}
					</div>
				</fieldset>

				<fieldset class="form-group col-md-12">
				{{ Form::label('url', getphrase('url')) }}
				{{ Form::text('url', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'www.sitename.com',
					)) }}
				</fieldset>
			</div>
 			 <div class="row input-daterange" id="dp">

		 	<?php
		 	$date_from = date('Y/m/d');
		 	$date_to = date('Y/m/d');
		 	$slots_times = makeTimeSlots();
		 	$valid_from_time = $valid_to_time = '';

		 	if($record)
		 	{
		 		if ( ! empty( $record->valid_from ) ) {
		 			$date_from = date('Y/m/d', strtotime($record->valid_from));
		 			$valid_from_time = date('H:i', strtotime($record->valid_from)) . ':00';
		 		}
		 		if ( ! empty( $record->valid_to ) ) {
		 			$date_to = date('Y/m/d', strtotime($record->valid_to));
		 			$valid_to_time = date('H:i', strtotime($record->valid_to)) . ':00';
		 		}
		 	}

		 	 ?>



				<fieldset class="form-group col-md-3">
				{{ Form::label('valid_from', getphrase('valid_from')) }}
				{{ Form::text('valid_from', $value = $date_from , $attributes = array('id' => 'valid_from', 'class'=>'input-sm form-control', 'placeholder' => '2015/7/17')) }}
				</fieldset>
				<fieldset class="form-group col-md-3">
						{{ Form::label('valid_from_time', getphrase('valid_from_time')) }}
						{{ Form::select('valid_from_time', $slots_times, $value = $valid_from_time, ['class'=>'form-control','placeholder'=>'select',
						    'ng-model'=>'valid_from_time',
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": batches.valid_from_time.$touched && batches.valid_from_time.$invalid}',
							 ])}}
						<div class="validation-error" ng-messages="batches.valid_from_time.$error" >
	    					{!! getValidationMessage()!!}
	    				</div>
	    		</fieldset>







  				 <fieldset class="form-group col-md-3">
                        {{ Form::label('valid_to', getphrase('valid_to')) }}
                        {{ Form::text('valid_to', $value = $date_to , $attributes = array('id' => 'valid_to', 'class'=>'input-sm form-control', 'placeholder' => '2015/7/17 15:10:2')) }}
                 </fieldset>
                 <fieldset class="form-group col-md-3">
						{{ Form::label('valid_to_time', getphrase('valid_to_time')) }}
						{{ Form::select('valid_to_time', $slots_times, $value = $valid_to_time, ['class'=>'form-control','placeholder'=>'select',
						    'ng-model'=>'valid_to_time',
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": batches.valid_to_time.$touched && batches.valid_to_time.$invalid}',
							 ])}}
						<div class="validation-error" ng-messages="batches.valid_to_time.$error" >
	    					{!! getValidationMessage()!!}
	    				</div>
	    		</fieldset>



				</div>



  				  	<div class="row">

					<fieldset class="form-group  col-md-12">



						{{ Form::label('short_description', getphrase('short_description')) }}



						{{ Form::textarea('short_description', $value = null , $attributes = array('class'=>'form-control', 'rows'=>'5', 'placeholder' => getPhrase('short_description'))) }}

					</fieldset>

					<fieldset class="form-group  col-md-12">



						{{ Form::label('description', getphrase('description')) }}



						{{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'5', 'placeholder' => getPhrase('description'))) }}

					</fieldset>



					</div>





						<div class="buttons text-center">

							<button class="btn btn-lg btn-success button"

							ng-disabled='!formNotifications.$valid'>{{ $button_name }}</button>

						</div>

