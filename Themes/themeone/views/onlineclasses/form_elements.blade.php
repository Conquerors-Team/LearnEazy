



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

							'ng-maxlength' => '50',

							)) }}

						<div class="validation-error" ng-messages="formNotifications.title.$error" >

	    					{!! getValidationMessage()!!}

	    					{!! getValidationMessage('pattern')!!}

	    					{!! getValidationMessage('minlength')!!}

	    					{!! getValidationMessage('maxlength')!!}

						</div>

					</fieldset>

					<fieldset class="form-group col-md-6" >
						{{ Form::label('student_class_id', 'Student class') }}

						<?php
						$student_classes = \App\StudentClass::where('institute_id', adminInstituteId())->get()->pluck('name', 'id')->toArray();
						?>
						{{Form::select('student_class_id', $student_classes, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'ng-model'=>'student_class_id',
						'id' => 'student_class_id',
						'onChange' => 'classChanged(student_class_id)',

							'ng-class'=>'{"has-error": formLms.student_class_id.$touched && formLms.student_class_id.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.student_class_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

					<fieldset class="form-group col-md-6" >
						{{ Form::label('batch_id', 'Batch') }}
						<span class="text-red">*</span>
						<?php
						if ( isFaculty() ) {
							$batches = Auth::user()->faculty_batches()->get()->pluck('name', 'id')->toArray();
						} else {
							$batches = \App\Batch::where('institute_id', adminInstituteId())->get()->pluck('name', 'id')->toArray();
						}
						?>
						{{Form::select('batch_id', $batches, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'ng-model'=>'batch_id',
						'id' => 'batch_id',
							'required'=> 'true',
							'onChange' => 'batchChanged()',
							'ng-class'=>'{"has-error": formLms.batch_id.$touched && formLms.batch_id.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.batch_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>



					@if(Auth::user()->role_id == 8)
					<input type="hidden" name="created_by_id" id="created_by_id" value="{{\Auth::id()}}">
					@else
					<fieldset class="form-group col-md-6" >
						{{ Form::label('created_by_id', 'Faculty') }}
						<!-- <span class="text-red">*</span> -->
						<?php
						$faculty = \App\User::where('institute_id', adminInstituteId())->where('role_id', 8)->get()->pluck('name', 'id')->toArray();
						if(checkRole(getUserGrade(10))) {
							$faculty = \App\User::where('id', \Auth::id())->where('role_id', 8)->get()->pluck('name', 'id')->toArray();
						}
						?>
						{{Form::select('created_by_id', $faculty, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'ng-model'=>'created_by_id',
						'id' => 'created_by_id',
							'required'=> 'true',
							'onChange' => 'facultyChanged()',
							'ng-class'=>'{"has-error": formLms.created_by_id.$touched && formLms.created_by_id.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.created_by_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
					@endif

				<fieldset class="form-group col-md-6">
				{{ Form::label('url', getphrase('url')) }}
				<?php
				$url = \Auth::user()->online_url;
				if ( $record ) {
					$url = $record->url;
				}
				?>
				{{ Form::text('url', $value = $url, $attributes = array('class'=>'form-control', 'placeholder' => 'www.sitename.com',
				'id' => 'online_url',
					)) }}
				</fieldset>

				 </div>

				 <fieldset class="form-group col-md-6">
				{{ Form::label('subject_id', getphrase('subject')) }}
				<?php
				$institute_id   = adminInstituteId();
				$subjects = \App\Subject::get()->pluck('subject_title', 'id')->toArray();
				if(shareData('share_subjects')){
					$subjects = \App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get()->pluck('subject_title', 'id')->toArray();
				} else {
					$subjects = \App\Subject::where('institute_id', $institute_id)->get()->pluck('subject_title', 'id')->toArray();
				}
				if(isFaculty()) {
					$faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
      				$subjects = \App\Subject::whereIn('id', $faculty_subjects)->get()->pluck('subject_title', 'id')->toArray();
				}
				?>
				{{Form::select('subject_id', $subjects, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'ng-model'=>'subject_id',
						'id' => 'subject_id',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.subject_id.$touched && formLms.subject_id.$invalid}',
						]) }}
				<div class="validation-error" ng-messages="formLms.subject_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
				</fieldset>

				<fieldset class="form-group col-md-6">
				{{ Form::label('topic', getphrase('topic')) }}

				{{ Form::text('topic', $value = null, $attributes = array('class'=>'form-control', 'placeholder' => 'topic',
					)) }}
				</fieldset>


				<fieldset class="form-group col-md-4">
                        {{ Form::label('class_duration', getphrase('class_duration')) }} (In Minutes)
                        {{ Form::text('class_duration', $value = null, $attributes = array('id' => 'class_duration', 'class'=>'form-control', 'placeholder' => '50')) }}
                 </fieldset>









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



				<fieldset class="form-group col-md-4">
				{{ Form::label('valid_from', getphrase('valid_from')) }}
				{{ Form::text('valid_from', $value = $date_from , $attributes = array('id' => 'valid_from', 'class'=>'input-sm form-control', 'placeholder' => '2015/7/17')) }}
				</fieldset>






  				 <fieldset class="form-group col-md-4">
                        {{ Form::label('valid_to', getphrase('valid_to')) }}
                        {{ Form::text('valid_to', $value = $date_to , $attributes = array('id' => 'valid_to', 'class'=>'input-sm form-control', 'placeholder' => '2015/7/17 15:10:2')) }}
                 </fieldset>



	    		<fieldset class="form-group col-md-4">
						{{ Form::label('class_time', getphrase('class_time')) }}
						{{ Form::select('class_time', $slots_times, $value = null, ['class'=>'form-control','placeholder'=>'select',
						    'ng-model'=>'class_time',
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": batches.class_time.$touched && batches.class_time.$invalid}',
							 ])}}
						<div class="validation-error" ng-messages="batches.class_time.$error" >
	    					{!! getValidationMessage()!!}
	    				</div>
	    		</fieldset>





				</div>



  				  	<div class="row">

					<fieldset class="form-group  col-md-6">
						{{ Form::label('short_description', getphrase('short_description')) }}
						{{ Form::textarea('short_description', $value = null , $attributes = array('class'=>'form-control', 'rows'=>'5', 'placeholder' => getPhrase('short_description'))) }}
					</fieldset>

					<fieldset class="form-group  col-md-6">



						{{ Form::label('description', getphrase('description')) }}



						{{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'5', 'placeholder' => getPhrase('description'))) }}

					</fieldset>



					</div>





						<div class="buttons text-center">

							<button class="btn btn-lg btn-success button"

							ng-disabled='!formNotifications.$valid'>{{ $button_name }}</button>

						</div>

