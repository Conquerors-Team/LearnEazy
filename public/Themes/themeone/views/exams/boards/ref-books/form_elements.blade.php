
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

						{{ Form::label('title', getphrase('author_name')) }}
						<span class="text-red">*</span>
						{{ Form::text('author_name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('enter_author_name'),
							'ng-model'=>'author_name',
							'ng-pattern' => getRegexPattern('name'),
							'ng-minlength' => '2',
							'ng-maxlength' => '60',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formCategories.author_name.$touched && formCategories.author_name.$invalid}',

							)) }}
							<div class="validation-error" ng-messages="formCategories.title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
	    					{!! getValidationMessage('pattern')!!}
						</div>
					</fieldset>

					<fieldset class="form-group" >
				   {{ Form::label('fileinput', getphrase('attachments')) }}
				         <input type="file" class="form-control" name="fileinput"
				         accept=".png,.jpg,.jpeg,.pdf,.docx" id="image_input">


				    </fieldset>
				     <fieldset class="form-group" >
					@if($record)
				   		@if($record->
				   		file_input)
				         <?php $examSettings = getExamSettings(); ?>

                          <a href="{{ url(PREFIX.$examSettings->courseImagepath.$record->file_input) }}" target="_blank">{{$record->file_input}}</a>
				         @endif
				     @endif


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
