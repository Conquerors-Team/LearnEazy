

					<div class="row">
 					 @if($exam_type == 'test_series')
					<fieldset class="form-group col-md-3">
						{{ Form::label('display_type', getphrase('display_type')) }}
						<span class="text-red">*</span>
						<?php
						// chapter,subject,previousyear,grand
						$display_types = [
							'' => 'Please select',
							'subject' => 'Subject-wise test',
							'chapter' => 'Chapter-wise test',
							'previousyear' => 'Previous Year test',
							'grand' => 'Grand test',
						]
						?>
						{{Form::select('display_type', $display_types, null, ['class'=>'form-control','ng-model'=>'display_type'])}}
					</fieldset>
					@endif

					<fieldset class="form-group col-md-3" ng-if="display_type == 'subject' || display_type == 'chapter'">
							{{ Form::label('subject_id', getphrase('subject')) }}
							<span class="text-red">*</span>
							<?php
							$subjects = \App\Subject::where('institute_id', OWNER_INSTITUTE_ID)->get()->pluck('subject_title', 'id')->prepend('Please select', '');
							?>
							{{Form::select('subject_id', $subjects, null, ['class'=>'form-control','ng-model'=>'subject_id', 'id' => 'subject_id', 'onChange' => 'getSubjectChapters()'])}}
					</fieldset>

					<fieldset class="form-group col-md-3" ng-if="display_type == 'chapter'">
							{{ Form::label('chapter_id', getphrase('chapter')) }}
							<span class="text-red">*</span>
							<?php
							$chapters = ['' => 'Please select'];
							if ( $record ) {
								$chapters = \App\Chapter::where('subject_id', $record->subject_id)->get()->pluck('chapter_name', 'id')->prepend('Please select', '');
							}
							?>
							{{Form::select('chapter_id', $chapters, null, ['class'=>'form-control','ng-model'=>'chapter_id', 'id' => 'chapter_id'])}}
					</fieldset>

					<fieldset class="form-group col-md-3" ng-if="display_type == 'previousyear'">
					{{ Form::label('year', getphrase('year')) }}
					<span class="text-red">*</span>
					<?php
					$years = ['' => 'Please select'];
					for($y = date('Y'); $y >= 1990; $y-- ) {
						$years[ $y ] = $y;
					}
					?>
					{{Form::select('year', $years, null, ['class'=>'form-control', "id"=>"year", "ng-model" => "year"])}}
					</fieldset>

					<fieldset class="form-group col-md-3" ng-if="display_type == 'previousyear' || display_type == 'grand'">
							{{ Form::label('chapter_id', getphrase('competitive_type')) }}
							<span class="text-red">*</span>
							<?php
							$competitive_types = ['' => 'Please select'];

								$competitive_types = \App\CompetitiveExamTypes::all()->pluck('title', 'id')->prepend('Please select', '');
							?>
							{{Form::select('competitive_exam_type_id', $competitive_types, null, ['class'=>'form-control','ng-model'=>'chapter_id', 'id' => 'competitive_exam_type_id'])}}
					</fieldset>

 					 <fieldset class="form-group col-md-6">

						{{ Form::label('title', getphrase('title')) }}
						<span class="text-red">*</span>
						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('quiz_title'),
							'ng-model'=>'title',
							'ng-pattern'=>getRegexPattern('name'),
							'required'=> 'true',
							'ng-class'=>'{"has-error": formQuiz.title.$touched && formQuiz.title.$invalid}',
							'ng-minlength' => '4',
							'ng-maxlength' => '40',
							)) }}
						<div class="validation-error" ng-messages="formQuiz.title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('pattern')!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
						</div>
					</fieldset>

					@if($exam_type != 'test_series')
					<fieldset class="form-group col-md-6">
						{{ Form::label('category_id', getphrase('category')) }}
						{{Form::select('category_id', $categories, null, ['class'=>'form-control'])}}
					</fieldset>
					@endif


				    </div>



				<div class="row">
	  				 <fieldset class="form-group col-md-3">
							{{ Form::label('dueration', getphrase('duration')) }}
							<span class="text-red">*</span>
							{{ Form::number('dueration', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('enter_value_in_minutes'),
								'min'=>1,
							'ng-model'=>'dueration',
							'required'=> 'true',
							'string-to-number'=> 'true',
							'ng-class'=>'{"has-error": formQuiz.dueration.$touched && formQuiz.dueration.$invalid}',

							)) }}
						<div class="validation-error" ng-messages="formQuiz.dueration.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>
					</fieldset>
					<fieldset class="form-group col-md-3">
							{{ Form::label('marks_per_question', getphrase('marks_per_question')) }}
							<span class="text-red">*</span>
							{{ Form::number('marks_per_question', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('enter_value_in_minutes'),
							'min'=>0,
							'ng-model'=>'marks_per_question',
							'string-to-number'=> 'true',
							'step' => '0.01',
							'ng-class'=>'{"has-error": formQuiz.marks_per_question.$touched && formQuiz.marks_per_question.$invalid}',

							)) }}
						<div class="validation-error" ng-messages="formQuiz.marks_per_question.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>
					</fieldset>
	  				<fieldset class="form-group col-md-3">
							{{ Form::label('total_marks', getphrase('total_marks')) }}
							<span class="text-red">*</span>
							{{ Form::text('total_marks', $value = null , $attributes = array('class'=>'form-control' ,'placeholder' => getPhrase('It will be updated by adding the questions'))) }}
					</fieldset>
					 <fieldset class="form-group col-md-3">

						{{ Form::label('pass_percentage', getphrase('pass_percentage')) }}

						{{ Form::number('pass_percentage', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '40',
							'min'=>'1',
							'max' =>'100',
						'ng-model'=>'pass_percentage',
						'string-to-number'=> 'true',

						'ng-class'=>'{"has-error": formQuiz.pass_percentage.$touched && formQuiz.pass_percentage.$invalid}',

							)) }}
						<div class="validation-error" ng-messages="formQuiz.pass_percentage.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>
				</fieldset>
				</div>

				<div class="row">

  				 <fieldset   class="form-group col-md-6">

						{{ Form::label('negative_mark', getphrase('negative_mark')) }}
						<span class="text-red">*</span>
						{{ Form::number('negative_mark', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '40',
							'min'=>'0',
							'max' =>'100',
						'ng-model'=>'negative_mark',
						'required'=> 'true',
						'string-to-number'=> 'true',
						'ng-class'=>'{"has-error": formQuiz.negative_mark.$touched && formQuiz.negative_mark.$invalid}',

							)) }}
						<div class="validation-error" ng-messages="formQuiz.negative_mark.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>
				</fieldset>
					<fieldset class="form-group col-md-6">

						{{ Form::label('instructions_page_id', getphrase('instructions_page')) }}
						<span class="text-red">*</span>
						{{Form::select('instructions_page_id', $instructions, null, ['class'=>'form-control'])}}

					</fieldset>

				</div>

				<div class="row input-daterange" >
		 	<?php
		 	$date_from = date('Y/m/d');
		 	$date_to = date('Y/m/d');
		 	if($record)
		 	{
		 		$date_from = $record->start_date;
		 		$date_to = $record->end_date;
		 	}
		 	 ?>
		 	 <fieldset class="form-group col-md-4">
				{{ Form::label('start_date', getphrase('start_date')) }}
				{{ Form::text('start_date', $value = $date_from , $attributes = array('class'=>'input-sm form-control', 'placeholder' => '2015/7/17')) }}
			</fieldset>

			<fieldset class="form-group col-md-4">
				{{ Form::label('end_date', getphrase('end_date')) }}
				{{ Form::text('end_date', $value = $date_to , $attributes = array('class'=>'input-sm form-control', 'placeholder' => '2015/7/17')) }}
 			</fieldset>

 			 <fieldset class="form-group col-md-4">

						{{ Form::label('start_time', getphrase('start_time')) }}

						{{ Form::select('start_time', $slots_times, null, ['class'=>'form-control','placeholder'=>'select',
						    'ng-model'=>'start_time',


                            'ng-class'=>'{"has-error": formQuiz.start_time.$touched && formQuiz.start_time.$invalid}',

							 ])}}

						<div class="validation-error" ng-messages="formQuiz.start_time.$error" >

	    					{!! getValidationMessage()!!}

	    				</div>
	    			</fieldset>

			</div>

				<div  class="row">


					<input type="hidden" name="is_paid" id="is_paid" ng-model="is_paid" value="0">


					<div ng-if="is_paid==1">
	  				 <fieldset class="form-group col-md-3">

							{{ Form::label('validity', getphrase('validity')) }}
							<span class="text-red">*</span>
							{{ Form::number('validity', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('validity_in_days'),
							'ng-model'=>'validity',
							'string-to-number'=> 'true',
							'min'=>'1',

							'required'=> 'true',
							'ng-class'=>'{"has-error": formQuiz.validity.$touched && formQuiz.validity.$invalid}',

							)) }}
						<div class="validation-error" ng-messages="formQuiz.validity.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>
					</fieldset>
	  				 <fieldset class="form-group col-md-3">

						{{ Form::label('cost', getphrase('cost')) }}
						<span class="text-red">*</span>
						{{ Form::number('cost', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '40',
							'min'=>'0',

						'ng-model'=>'cost',
						'required'=> 'true',
						'string-to-number'=> 'true',
						'ng-class'=>'{"has-error": formQuiz.cost.$touched && formQuiz.cost.$invalid}',

							)) }}
						<div class="validation-error" ng-messages="formQuiz.cost.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>
				</fieldset>
				</div>
				</div>

				<input type="hidden" name="show_in_front" value="0">
				 <?php

                   $options  = array(
                   	'0'=>'No',
                   	'1'=>'Yes',
                                     );

                   ?>

                   <div class="row">

					<fieldset class="form-group col-md-3">
						{{ Form::label('exam_type', getphrase('exam_type')) }}
						<span class="text-red">*</span>
						{{Form::select('exam_type', $exam_types, null, ['class'=>'form-control','ng-model'=>'exam_type'])}}
					</fieldset>




					<?php $language_options = array('0'=>'No', '1'=>'Yes', );?>

					 <fieldset class="form-group col-md-6" style="display: none;">
						{{ Form::label('has_language', 'It has other language?') }}
						<span class="text-red">*</span>
						{{Form::select('has_language', $language_options, null, ['class'=>'form-control',
						'ng-model'=>'has_language',

						'ng-class'=>'{"has-error": formQuiz.has_language.$touched && formQuiz.has_language.$invalid}',

						]) }}
						<div class="validation-error" ng-messages="formQuiz.has_language.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

                     <?php $languages_data = getLangugesOptions(); ?>

					 <fieldset class="form-group col-md-6" ng-if="has_language == 1">
						{{ Form::label('language_name', 'It has other language?') }}
						<span class="text-red">*</span>
						{{Form::select('language_name', $languages_data, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'ng-model'=>'language_name',
						'required'=> 'true',
						'ng-class'=>'{"has-error": formQuiz.language_name.$touched && formQuiz.language_name.$invalid}',

						]) }}
						<div class="validation-error" ng-messages="formQuiz.language_name.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>


					</fieldset>

					</div>



						<div class="row">

				 <fieldset class="form-group col-md-6" >

				   {{ Form::label('category', getphrase('image')) }}
				         <input type="file" class="form-control" name="examimage"
				         accept=".png,.jpg,.jpeg" id="image_input">


				    </fieldset>

				     <fieldset class="form-group col-md-6" >
					@if($record)
				   		@if($record->image)
				         <?php $examSettings = getExamSettings(); ?>
				         <img src="{{ PREFIX.$examSettings->categoryImagepath.$record->image }}" height="100" width="100" >

				         @endif
				     @endif


				    </fieldset>

					</div>


				  <div class="row">

					<fieldset class="form-group col-md-12">

						{{ Form::label('description', getphrase('description')) }}

						{{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control', 'rows'=>'5', 'placeholder' => getPhrase('description'))) }}
					</fieldset>

			</div>




						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button"
							ng-disabled='!formQuiz.$valid'>{{ $button_name }}</button>
						</div>

