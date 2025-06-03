	<input type="hidden" name="subject_id" id="subject_id" value="{{ $subject->id }}">

	<fieldset class="form-group col-md-3">
	{{ Form::label('question_bank_type_id', getphrase('question_bank_type')) }} <span class="text-red">*</span>
	<?php
	$categries = \App\QuestionBankTypes::get()->pluck('title', 'id')->prepend(getPhrase('select'), '');
	if(!checkRole(getUserGrade(1))){
		if ( Auth::user()->institute_id != OWNER_INSTITUTE_ID) {
			$categries = \App\QuestionBankTypes::where('status', 1)->get()->pluck('title', 'id')->prepend(getPhrase('select'), '');
		}
	}
	?>
	{{Form::select('question_bank_type_id', $categries, null, ['class'=>'form-control', "id"=>"question_bank_type_id", "ng-model" => "question_bank_type_id"])}}
	</fieldset>

	<fieldset class="form-group col-md-4">
	{{ Form::label('chapter_id', getphrase('chapter')) }} <span class="text-red">*</span>
	{{Form::select('chapter_id', $chapters, null, ['class'=>'form-control', "id"=>"chapter_id", 'onChange' => 'getChaptersTopics()'])}}
	</fieldset>

	<fieldset class="form-group col-md-4">
	{{ Form::label('topic_id', getphrase('topic')) }} <span class="text-red">*</span>
	{{Form::select('topic_id', $topics, null, ['class'=>'form-control', "id"=>"topic_id"])}}
	</fieldset>

	<fieldset class="form-group col-md-4">
	{{ Form::label('questionbank_category_id', getphrase('question_category')) }} <span class="text-red">*</span>
	<?php
	$categries = \App\QuestionbankCategory::get()->pluck('category', 'id')->prepend(getPhrase('select'), '');
	?>
	{{Form::select('questionbank_category_id', $categries, null, ['class'=>'form-control', "id"=>"questionbank_category_id"])}}
	</fieldset>



	<fieldset class="form-group col-md-3" ng-if="question_bank_type_id == 3">
	{{ Form::label('competitive_exam_type_id', 'Competitive Exam type') }}
	<?php
	$categries = \App\CompetitiveExamTypes::get()->pluck('title', 'id')->prepend(getPhrase('select'), '');
	?>
	{{Form::select('competitive_exam_type_id', $categries, null, ['class'=>'form-control', "id"=>"competitive_exam_type_id", "ng-model" => "competitive_exam_type_id"])}}
	</fieldset>

	<fieldset class="form-group col-md-3" ng-if="question_bank_type_id == 3">
	{{ Form::label('yes', 'Year') }}
	<?php
	$years = ['' => 'Please select'];
	for($y = date('Y'); $y >= 1990; $y-- ) {
		$years[ $y ] = $y;
	}
	?>
	{{Form::select('year', $years, null, ['class'=>'form-control', "id"=>"year", "ng-model" => "year"])}}
	</fieldset>

	<fieldset class="form-group col-md-3" style="display: none;">
		{{ Form::label('question_code', getphrase('code')) }}
		<span class="text-red">*</span>

		{{ Form::text('question_code', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Code', 'ng-model'=>'question_code',
		'ng-class'=>'{"has-error": formQuestionBank.question_code.$touched && formQuestionBank.question_code.$invalid}',
		'id' => 'question_code',
		)) }}
	<div class="validation-error" ng-messages="formQuestionBank.question_code.$error" >
		{!! getValidationMessage()!!}
	</div>
	</fieldset>

	<?php
		$settingsObj 			= new App\GeneralSettings();
		$question_types 		= $settingsObj->getQuestionTypes();
		$exam_max_options 		= $settingsObj->getExamMaxOptions();
		$exam_difficulty_levels = $settingsObj->getDifficultyLevels();


		?>

	<fieldset class="form-group col-md-4">
	{{ Form::label('difficulty_level', getphrase('difficulty_level')) }}
	<span class="text-red">*</span>

	{{Form::select('difficulty_level',$exam_difficulty_levels , null, ['class'=>'form-control', "id"=>"difficulty_level" ])}}
	</fieldset>

	<fieldset class="form-group col-md-12">
		{{ Form::label('question', getphrase('question')) }}
		<span class="text-red">*</span>

		{{ Form::textarea('question', $value = null , $attributes = array('class'=>'form-control ckeditor', 'placeholder' => 'Your question', 'rows' => '5',
		'ng-model'=>'question',

		'ng-class'=>'{"has-error": formQuestionBank.question.$touched && formQuestionBank.question.$invalid}',
		'ng-minlength' => '4',
		'id' => 'question',
		)) }}
	<div class="validation-error" ng-messages="formQuestionBank.question.$error" >
		{!! getValidationMessage()!!}
		{!! getValidationMessage('minlength')!!}
	</div>
	</fieldset>





	<fieldset class="form-group col-md-4">
	{{ Form::label('question_type', getphrase('question_type')) }}
	<span class="text-red">*</span>
	<?php
	$readonly = "";
	if($record)
	$readonly = "disabled";
	?>
	{{Form::select('question_type',$question_types , null, ['class'=>'form-control', "id"=>"question_type", "ng-model"=>"question_type" ,
	 	'required'=> 'true',
		'ng-class'=>'{"has-error": formQuestionBank.question_type.$touched && formQuestionBank.question_type.$invalid}',
		$readonly
	])}}
	<?php if($readonly) { ?>
	<input type="hidden" name="question_type" value="{{$record->question_type}}" >
	<?php } ?>
	<div class="validation-error" ng-messages="formQuestionBank.question_type.$error" >
		{!! getValidationMessage()!!}

	</div>
	</fieldset>

	<fieldset class="form-group col-md-4" >
         {{ Form::label('question_file', getPhrase('upload') ) }}
         @{{question_type}}
         <span ng-if="question_type=='video'">{{getPhrase('supported_formats are')}} .mp4
         </span>
		 <span ng-if="question_type=='audio'">{{getPhrase('supported_formats are')}} .mp3
         </span>

         <span ng-if="question_type!='audio' && question_type!='video'">({{getPhrase('supported_formats are')}}.jpeg, .jpg, .png)
         </span>


        {{Form::file('question_file', $attributes = array('class'=>'form-control'))}}
        {{-- <p ng-if="question_type=='video'">{{getPhrase('supported_formats are')}} .mp4</p>
        <p ng-if="question_type=='audio'">{{getPhrase('supported_formats are')}} .mp3</p> --}}

        @if($record)
        @if($record->question_file)
    		@include('exams.questionbank.question_partial_image_preview', array('record'=>$record))
    	@endif
    @endif
    </fieldset>



	<fieldset class="form-group col-md-4">
		{{ Form::label('hint', getphrase('hint')) }}
		{{ Form::text('hint', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Hint for the question')) }}
	</fieldset>

	<fieldset class="form-group col-md-4">
		{{ Form::label('marks', getphrase('marks')) }}
		<span class="text-red">*</span>
		{{ Form::text('marks', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '1',
			'min'=>'1',
		'ng-model'=>'marks',
		'required'=> 'true',
		'ng-class'=>'{"has-error": formQuestionBank.marks.$touched && formQuestionBank.marks.$invalid}',
		'pattern' => '[0-9]+',
		)) }}
	<div class="validation-error" ng-messages="formQuestionBank.marks.$error" >
		{!! getValidationMessage()!!}
		{{-- {!! getValidationMessage('number')!!} --}}
	</div>
	</fieldset>

	<fieldset class="form-group col-md-12">
		{{ Form::label('explanation', getphrase('explanation')) }}
		{{ Form::textarea('explanation', $value = null , $attributes = array('class'=>'form-control ckeditor', 'placeholder' => 'Your explanation', 'rows' => '5', 'id' => 'explanation')) }}
	</fieldset>



	<fieldset class="form-group col-md-4" style="display: none;">
		{{ Form::label('time_to_spend', getphrase('time_to_spend')) }}
		<span class="text-red">*</span>
		{{ Form::text('time_to_spend', $value = 5, $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('in_seconds'),
			'min'=>'1',
		'ng-model'=>'time_to_spend',
		'required'=> 'true',
		'ng-class'=>'{"has-error": formQuestionBank.time_to_spend.$touched && formQuestionBank.time_to_spend.$invalid}',
		)) }}
	<div class="validation-error" ng-messages="formQuestionBank.time_to_spend.$error" >
		{!! getValidationMessage()!!}
		{{-- {!! getValidationMessage('number')!!} --}}
	</div>
	</fieldset>


	<!-- Load the files start as independent -->
	<fieldset class="form-group col-md-12">
	<?php

	 $image_path = ($record) ? PREFIX.(new ImageSettings())->getExamImagePath(): ''; ?>
	@include('exams.questionbank.form_elements_radio', array('image_path'=>$image_path))
	@include('exams.questionbank.form_elements_checkbox')
	@include('exams.questionbank.form_elements_blanks')
	@include('exams.questionbank.form_elements_match')
	<?php
$show = TRUE;
	if($record) {
		if($record->question_type=='match')
			$show = FALSE;
		}
		?>
		@if($show)
	@include('exams.questionbank.form_elements_para', array('record'=>$record))
		@endif
</fieldset>

	<!-- Load the files end as independent -->
        @if(!$record)
		<div class="buttons text-center col-md-12">
			<button class="btn btn-lg btn-success button"
			ng-disabled='!formQuestionBank.$valid' name="buttontype" value="create">{{ $button_name }}</button>

			<button class="btn btn-lg btn-success button"
			ng-disabled='!formQuestionBank.$valid' name="buttontype" value="createnew">Create & New</button>

			<button class="btn btn-lg btn-success button"
			ng-disabled='!formQuestionBank.$valid' name="buttontype" value="createnewmeta">Create & New With Meta</button>
		</div>
		@else
		<div class="buttons text-center col-md-12">
			<button class="btn btn-lg btn-success button" name="buttontype" value="update">{{ $button_name }}</button>

			<button class="btn btn-lg btn-success button"
			ng-disabled='!formQuestionBank.$valid' name="buttontype" value="updatenew">Update & New</button>

			<button class="btn btn-lg btn-success button"
			ng-disabled='!formQuestionBank.$valid' name="buttontype" value="updatenewmeta">Update & New With Meta</button>
		</div>
		@endif
