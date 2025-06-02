
 					 <fieldset class="form-group">

						{{ Form::label('title', getphrase('title')) }}
						<span class="text-red">*</span>
						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('enter_title'),
							'ng-model'=>'title',
							'ng-pattern' => getRegexPattern('name'),
							'ng-minlength' => '2',
							'ng-maxlength' => '60',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formCategories.title.$touched && formCategories.title.$invalid}',

							)) }}
							<div class="validation-error" ng-messages="formCategories.title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
	    					{!! getValidationMessage('pattern')!!}
						</div>
					</fieldset>

					<fieldset class="form-group">
                        {{ Form::label('status', getphrase('status')) }}
						<span class="text-red">*</span>
						<?php
						$status = [
							1 => 'active',
							0 => 'inactive',
						];
						?>
						{{Form::select('status', $status, null, ['class'=>'form-control',
							'ng-model'=>'status',
							'required'=> 'ture',
							'ng-class'=>'{"has-error": formCategories.status.$touched && formCategories.status.$invalid}'

						 ])}}
						  <div class="validation-error" ng-messages="formCategories.status.$error" >
	    					{!! getValidationMessage()!!}
	    				</div>
					</fieldset>

					<fieldset class="form-group">
						{{ Form::label('subject_code', getphrase('subject_code')) }}
						{{ Form::text('subject_code', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'M1',
							'ng-model'=>'subject_code',
							'ng-pattern' => getRegexPattern('name'),
							'ng-class'=>'{"has-error": formSubjects.subject_code.$touched && formSubjects.subject_code.$invalid}',
							'ng-minlength' => '2',
							'ng-maxlength' => '10',
						)) }}
						</fieldset>
				<!-- 	<fieldset class="form-group">

						{{ Form::label('description', getphrase('description')) }}

						{{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control', 'rows'=>'5', 'placeholder' => 'Description')) }}
					</fieldset> -->

					</fieldset>
						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button"
							ng-disabled='!formCategories.title.$valid'>{{ $button_name }}</button>
						</div>
