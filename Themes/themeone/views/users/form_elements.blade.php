

					 <fieldset class="form-group">



						{{ Form::label('name', getphrase('name')) }}

						<span class="text-red">*</span>

						{{ Form::text('name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Jack',

							'ng-model'=>'name',

							'required'=> 'true',

							'ng-pattern' => getRegexPattern("name"),

							'ng-minlength' => '2',

							'ng-maxlength' => '60',

							'ng-class'=>'{"has-error": formUsers.name.$touched && formUsers.name.$invalid}',



						)) }}

						<div class="validation-error" ng-messages="formUsers.name.$error" >

	    					{!! getValidationMessage()!!}

	    					{!! getValidationMessage('minlength')!!}

	    					{!! getValidationMessage('maxlength')!!}

	    					{!! getValidationMessage('pattern')!!}

						</div>

					</fieldset>



					<?php

					$readonly = '';

					$username_value = null;

					if($record){

						$readonly = 'readonly="true"';

						// $username_value = $record->username;

					}



					?>

					 <fieldset class="form-group">



						{{ Form::label('username', getphrase('username')) }}

						<span class="text-red">*</span>

						{{ Form::text('username', $value = $username_value , $attributes = array('class'=>'form-control', 'placeholder' => 'Jack',

							'ng-model'=>'username',

							'required'=> 'true',

							 $readonly,

							'ng-class'=>'{"has-error": formUsers.username.$touched && formUsers.username.$invalid}',



						)) }}

						<div class="validation-error" ng-messages="formUsers.username.$error" >

	    					{!! getValidationMessage()!!}

	    					{!! getValidationMessage('minlength')!!}

	    					{!! getValidationMessage('maxlength')!!}

	    					{!! getValidationMessage('pattern')!!}

						</div>

					</fieldset>


					 <fieldset class="form-group">

						<?php

						$readonly = '';

						if($record)
						{
							if(!checkRole(getUserGrade(4))) {
								$readonly = 'disabled="true"';
							}
						}
						?>

						{{ Form::label('email', getphrase('email')) }}

						<span class="text-red">*</span>

						{{ Form::text('email', $value = null, $attributes = array('class'=>'form-control', 'placeholder' => 'jack@jarvis.com',

							'ng-model'=>'email',

							'required'=> 'true',

							'ng-class'=>'{"has-error": formUsers.email.$touched && formUsers.email.$invalid}',

						 $readonly)) }}

						 <div class="validation-error" ng-messages="formUsers.email.$error" >

	    					{!! getValidationMessage()!!}

	    					{!! getValidationMessage('email')!!}

						</div>

					</fieldset>

					@if(checkRole(getUserGrade(2)))
					<fieldset class="form-group">
                        {{ Form::label('login_enabled', getphrase('activate_account')) }}
						<span class="text-red">*</span>
						<?php
						$login_enabled = [
							1 => 'Activate',
							0 => 'Block',
						];
						?>
						{{Form::select('login_enabled', $login_enabled, null, ['class'=>'form-control',
							'ng-model'=>'login_enabled',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formUsers.login_enabled.$touched && formUsers.login_enabled.$invalid}'

						 ])}}
						  <div class="validation-error" ng-messages="formUsers.login_enabled.$error" >
	    					{!! getValidationMessage()!!}
	    				</div>
					</fieldset>
					@else
					<?php
					$login_enabled = 0;
					if ( $record ) {
						$login_enabled = $record->login_enabled;
					}
					?>
					<input type="hidden" name="login_enabled" id="login_enabled" value="{{$login_enabled}}">
					@endif

					@if(!$record)
					 <fieldset class="form-group">
					 {{ Form::label('password', getphrase('password')) }}

						<span class="text-red">*</span>

						{{ Form::password('password', $attributes = array('class'=>'form-control instruction-call',

								'placeholder' => getPhrase("password"),

								'ng-model'=>'password',

								'required'=> 'true',

								'ng-class'=>'{"has-error": formUsers.password.$touched && formUsers.password.$invalid}',

								'ng-minlength' => 5

							)) }}

						<div class="validation-error" ng-messages="formUsers.password.$error" >

							{!! getValidationMessage()!!}

							{!! getValidationMessage('password')!!}

						</div>


					</fieldset>

					 <fieldset class="form-group">
					 {{ Form::label('confirm_password', getphrase('confirm_password')) }}

						<span class="text-red">*</span>

						{{ Form::password('password_confirmation', $attributes = array('class'=>'form-control instruction-call',

								'placeholder' => getPhrase("confirm_password"),

								'ng-model'=>'password_confirmation',

								'required'=> 'true',

								'ng-class'=>'{"has-error": formUsers.password_confirmation.$touched && formUsers.password.$invalid}',

								'ng-minlength' => 5

							)) }}

						<div class="validation-error" ng-messages="formUsers.password_confirmation.$error" >

							{!! getValidationMessage()!!}

							{!! getValidationMessage('password')!!}

						</div>


					</fieldset>
					@else
					<fieldset class="form-group">
					 {{ Form::label('password', getphrase('password')) }}

						{{ Form::password('password', $attributes = array('class'=>'form-control instruction-call',

								'placeholder' => getPhrase("password"),

								'ng-model'=>'password',

								'ng-class'=>'{"has-error": formUsers.password.$touched && formUsers.password.$invalid}',

								'ng-minlength' => 5

							)) }}

						<div class="validation-error" ng-messages="formUsers.password.$error" >

							{!! getValidationMessage()!!}

							{!! getValidationMessage('password')!!}

						</div>


					</fieldset>

					 <fieldset class="form-group">
					 {{ Form::label('confirm_password', getphrase('confirm_password')) }}


						{{ Form::password('password_confirmation', $attributes = array('class'=>'form-control instruction-call',

								'placeholder' => getPhrase("confirm_password"),

								'ng-model'=>'password_confirmation',


								'ng-class'=>'{"has-error": formUsers.password_confirmation.$touched && formUsers.password.$invalid}',

								'ng-minlength' => 5

							)) }}

						<div class="validation-error" ng-messages="formUsers.password_confirmation.$error" >

							{!! getValidationMessage()!!}

							{!! getValidationMessage('password')!!}

						</div>


					</fieldset>

                  @endif







					<fieldset class="form-group">

                        {{ Form::label('role', getphrase('role')) }}

						<span class="text-red">*</span>

						<?php

						$disabled = '';
                        if($record)
						$disabled = 'disabled';

						$selected = getRoleData('student');

						if($record)

							$selected = $record->role_id;

						?>

						{{Form::select('role_id', $roles, $selected, ['placeholder' => getPhrase('select_role'),'class'=>'form-control', $disabled,

							'ng-model'=>'role_id',

							'required'=> 'true',

							'ng-class'=>'{"has-error": formUsers.role_id.$touched && formUsers.role_id.$invalid}'

						 ])}}

						  <div class="validation-error" ng-messages="formUsers.role_id.$error" >

	    					{!! getValidationMessage()!!}

	    				</div>

					</fieldset>

					@if(checkRole(getUserGrade(2)))
					<fieldset class="form-group">
						{{ Form::label('subjects', getphrase('subjects')) }}
						<button type="button" class="btn btn-primary btn-xs" id="selectbtn-subjects">
					        {{ getPhrase('select_all') }}
					    </button>
					    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-subjects">
					        {{ getPhrase('deselect_all') }}
					    </button> (Only for Faculty)
						<span class="text-red">*</span>
						<?php
						$institute_id   = adminInstituteId();
						$subjects = \App\Subject::where('status', 'Active');
						if(shareData('share_subjects')){
							$subjects->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
						} else {
							$subjects->where('institute_id', $institute_id)->get();
						}

						$subjects = $subjects->pluck('subject_title', 'id')->toArray();
						?>
						{{Form::select('subjects[]', $subjects, null, ['class'=>'form-control select2', 'name'=>'subjects[]', 'multiple'=>'true', 'id' => 'subjects', 'required' => 'true'])}}
						<div class="validation-error" ng-messages="formCategories.subjects.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

					<fieldset class="form-group">
						{{ Form::label('batches', getphrase('batches')) }}
						<button type="button" class="btn btn-primary btn-xs" id="selectbtn-batches">
					        {{ getPhrase('select_all') }}
					    </button>
					    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-batches">
					        {{ getPhrase('deselect_all') }}
					    </button> (Only for Faculty)
						<span class="text-red">*</span>
						<?php
						$institute_id   = adminInstituteId();
						$batches = \App\Batch::where('institute_id', $institute_id)->get()->pluck('name', 'id')->toArray();
						?>
						{{Form::select('faculty_batches[]', $batches, null, ['class'=>'form-control select2', 'name'=>'faculty_batches[]', 'multiple'=>'true', 'id' => 'batches'])}}
						<div class="validation-error" ng-messages="formCategories.batches.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>
					@endif




					@if(checkRole(getUserGrade(2)))
					<fieldset class="form-group" ng-if="role_id == 5">
                        {{ Form::label('student_class', getphrase('student_class')) }}
						<span class="text-red">*</span>

						<?php
						$institute_id   = adminInstituteId();
						$classes = \App\StudentClass::query();
						if(shareData('share_topics')){
							$classes->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
						} else {
							$classes->where('institute_id', $institute_id)->get();
						}

						$classes = $classes->pluck('name', 'id')->prepend('Please select', '')->toArray();
						?>
						{{Form::select('student_class_id', $classes, $value = null, ['class'=>'form-control',
							'ng-model'=>'student_class_id',
							'ng-class'=>'{"has-error": formUsers.student_class_id.$touched && formUsers.student_class_id.$invalid}'

						 ])}}
						  <div class="validation-error" ng-messages="formUsers.student_class_id.$error" >
	    					{!! getValidationMessage()!!}
	    				</div>
					</fieldset>
					@endif

					<fieldset class="form-group" ng-if="role_id != 5">
						{{ Form::label('online_url', getphrase('online_url')) }}
						<span class="text-red">*</span>
						{{ Form::text('online_url', $value = null , $attributes = array('class'=>'form-control', 'placeholder' =>
						getPhrase('online_url'),
							'ng-model'=>'online_url',
							'ng-class'=>'{"has-error": formUsers.online_url.$touched && formUsers.online_url.$invalid}',
						)) }}
						<div class="validation-error" ng-messages="formUsers.online_url.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

					<fieldset class="form-group" ng-if="role_id != 5">
						{{ Form::label('white_board_code', getphrase('white_board_code')) }}
						<span class="text-red">*</span>
						{{ Form::textarea('white_board_code', $value = null , $attributes = array('class'=>'form-control', 'placeholder' =>
						getPhrase('white_board_code'),
							'ng-model'=>'white_board_code',
							'ng-class'=>'{"has-error": formUsers.online_url.$touched && formUsers.white_board_code.$invalid}',
						)) }}
						<div class="validation-error" ng-messages="formUsers.white_board_code.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

					<fieldset class="form-group">
						{{ Form::label('phone', getphrase('phone')) }}

						<span class="text-red">*</span>

						{{ Form::text('phone', $value = null , $attributes = array('class'=>'form-control', 'placeholder' =>
						getPhrase('please_enter_10-15_digit_mobile_number'),

							'ng-model'=>'phone',

							'required'=> 'true',

							'ng-pattern' => getRegexPattern("phone"),

							'ng-class'=>'{"has-error": formUsers.phone.$touched && formUsers.phone.$invalid}',


						)) }}



						<div class="validation-error" ng-messages="formUsers.phone.$error" >

	    					{!! getValidationMessage()!!}

	    					{!! getValidationMessage('phone')!!}

	    					{!! getValidationMessage('maxlength')!!}

						</div>

					</fieldset>

					<div class="row">

						<fieldset class="form-group col-sm-6">



						{{ Form::label('address', getphrase('billing_address')) }}



						{{ Form::textarea('address', $value = null , $attributes = array('class'=>'form-control','rows'=>3, 'cols'=>'15', 'placeholder' => getPhrase('please_enter_your_address'),

							'ng-model'=>'address',

							)) }}

					</fieldset>



					<fieldset class='col-sm-6'>

						{{ Form::label('image', getphrase('image')) }}

						<div class="form-group row">

							<div class="col-md-6">



					{!! Form::file('image', array('id'=>'image_input', 'accept'=>'.png,.jpg,.jpeg')) !!}

							</div>

							<?php if(isset($record) && $record) {

								  if($record->image!='') {

								?>

							<div class="col-md-6">

								<img src="{{ getProfilePath($record->image) }}" />



							</div>

							<?php } } ?>

						</div>

					</fieldset>

					  </div>



						<div class="buttons text-center">

							<button class="btn btn-lg btn-success button"

							ng-disabled='!formUsers.$valid'>{{ $button_name }}</button>

						</div>