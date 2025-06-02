
 					 <fieldset class="form-group">
						{{ Form::label('title', getphrase('title')) }}
						<span class="text-red">*</span>
						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('enter_course_name'),
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
                        {{ Form::label('student_class', getphrase('class')) }}
						<span class="text-red">*</span>

						<?php
						$institute_id   = adminInstituteId();
						$classes = \App\StudentClass::where('institute_id', $institute_id)->get();
						$classes = $classes->pluck('name', 'id')->prepend('Please select', '')->toArray();
						?>
						{{Form::select('student_class_id', $classes, $value = null, ['class'=>'form-control',
							'ng-model'=>'student_class_id',
							'ng-class'=>'{"has-error": formCategories.student_class_id.$touched && formCategories.student_class_id.$invalid}'

						 ])}}
						  <div class="validation-error" ng-messages="formCategories.student_class_id.$error" >
	    					{!! getValidationMessage()!!}
	    				</div>
					</fieldset>


					<fieldset class="form-group">
						{{ Form::label('subjects', getphrase('subjects')) }}
						<button type="button" class="btn btn-primary btn-xs" id="selectbtn-subjects">
					        {{ getPhrase('select_all') }}
					    </button>
					    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-subjects">
					        {{ getPhrase('deselect_all') }}
					    </button>
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
						{{ Form::label('fee_percourse', getphrase('fee_for_course')) }}

						{{ Form::text('fee_percourse', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 1000,
							'ng-model'=>'fee_percourse',
							'step' => '0.01',
							'ng-class'=>'{"has-error": batches.fee_percourse.$touched && batches.fee_percourse.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="batches.fee_percourse.$error" >
	    					{!! getValidationMessage()!!}
	    				</div>
					</fieldset>

 					  <fieldset class="form-group" >
				   {{ Form::label('catimage', getphrase('image')) }}
				         <input type="file" class="form-control" name="catimage"
				         accept=".png,.jpg,.jpeg" id="image_input">


				    </fieldset>

				     <fieldset class="form-group" >
					@if($record)
				   		@if($record->image)
				         <?php $examSettings = getExamSettings(); ?>
				         <img src="{{ PREFIX.$examSettings->courseImagepath.$record->image }}" height="100" width="100" >

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
							ng-disabled='!formCategories.title.$valid'>{{ $button_name }}</button>
						</div>
