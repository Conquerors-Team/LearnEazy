
				    <fieldset class="form-group">
						{{ Form::label('subject_title', getphrase('subject_title')) }}
						<span class="text-red">*</span>
						{{ Form::text('subject_title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Maths',
							'ng-model'=>'subject_title',
							'ng-pattern' => getRegexPattern('name'),
							'required'=> 'true',
							'ng-class'=>'{"has-error": formSubjects.subject_title.$touched && formSubjects.subject_title.$invalid}',
							'ng-minlength' => '2',
							'ng-maxlength' => '40',
						)) }}
						<div class="validation-error" ng-messages="formSubjects.subject_title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('pattern')!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
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
						<div class="validation-error" ng-messages="formSubjects.subject_code.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('pattern')!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
						</div>
					</fieldset>

					<fieldset class="form-group">
						{{ Form::label('color_code', getphrase('color_code')) }}
						{{ Form::text('color_code', $value = null , $attributes = array('class'=>'form-control colorpicker', 'placeholder' => '#ffee23',
							'ng-model'=>'color_code',
							'ng-class'=>'{"has-error": formSubjects.color_code.$touched && formSubjects.color_code.$invalid}',
							'readonly' => 'readonly'
						)) }}
						<div class="validation-error" ng-messages="formSubjects.color_code.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

 					<!-- <fieldset class="form-group" >
				   	{{ Form::label('catimage', getphrase('image')) }}
				         <input type="file" class="form-control" name="catimage"
				         accept=".png,.jpg,.jpeg" id="image_input">
				    </fieldset> -->

				    <fieldset class="form-group">
						{{ Form::label('subjects_logos_id', getphrase('image')) }}
						<span class="text-red">*</span>
						<?php
						$subject_logos = \DB::table('subjects_logos')->get()->pluck('title', 'id')->prepend('Please select', '');
						?>
						{{Form::select('subjects_logos_id', $subject_logos, null, ['class'=>'form-control', 'id'=>'subjects_logos_id',
							'ng-model'=>'subjects_logos_id',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formTopics.subjects_logos_id.$touched && formTopics.subjects_logos_id.$invalid}'
						])}}
						 <div class="validation-error" ng-messages="formTopics.subjects_logos_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

				     <fieldset class="form-group" id="img_preview">
					@if($record)
				   		@if($record->image)
				         <?php $examSettings = getExamSettings(); ?>
				         <img src="{{ PREFIX.$examSettings->subjectsImagepath.$record->image }}" height="100" width="100" >
				         @endif
				     @endif
				    </fieldset>


					</fieldset>
						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button"
							ng-disabled='!formSubjects.$valid'>{{ $button_name }}</button>
						</div>
