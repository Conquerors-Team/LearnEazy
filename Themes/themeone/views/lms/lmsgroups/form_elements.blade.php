
 					 <fieldset class="form-group">

						{{ Form::label('title', getphrase('group_name')) }}
						<span class="text-red">*</span>
						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control',
						'placeholder' => getPhrase('enter_category_name'),
						'ng-model'=>'title',
							'required'=> 'true',
							'ng-pattern' => getRegexPattern("name"),
							'ng-minlength' => '2',
							'ng-maxlength' => '60',
							'ng-class'=>'{"has-error": formLms.title.$touched && formLms.title.$invalid}',

						)) }}
						<div class="validation-error" ng-messages="formLms.title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
	    					{!! getValidationMessage('pattern')!!}
						</div>
					</fieldset>



				     <fieldset class="form-group" >
					@if($record)
				   		@if($record->image)

				         <img src="{{ IMAGE_PATH_UPLOAD_LMS_CATEGORIES.$record->image }}" height="100" width="100">
				         @endif
				     @endif
				    </fieldset>


					<fieldset class="form-group">

						{{ Form::label('description', getphrase('description')) }}

						{{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control', 'rows'=>'5', 'placeholder' => 'Description')) }}
					</fieldset>

					</fieldset>
						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button"
							ng-disabled='!formLms.$valid'>{{ $button_name }}</button>
						</div>
