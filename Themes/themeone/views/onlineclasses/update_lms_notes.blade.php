@extends($layout)
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">

@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="/"><i class="mdi mdi-home"></i></a> </li>

							<li><a href="{{route('onlineclasses.index')}}">{{ getPhrase('onlineclasses')}}</a></li>

							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

 <div class="panel panel-custom col-lg-8 col-lg-offset-2">
 <div class="panel-heading"> <div class="pull-right messages-buttons"> <a href="{{route('onlineclasses.index')}}" class="btn btn-primary button">{{ getPhrase('list')}}</a> </div><h1>{{ $title }}  </h1></div>
 <div class="panel-body">
{{ Form::model($record,
						array('url' => url()->current(),
						'method'=>'post', 'files' => true, 'name'=>'formLms ', 'novalidate'=>'')) }}
<fieldset class="form-group col-md-6">
	{{ Form::label('title', getphrase('title')) }}
	<span class="text-red">*</span>
	{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('enter_course_name'),
		'ng-model'=>'title',
		'ng-pattern' => getRegexPattern('name'),
		'ng-minlength' => '2',
		'ng-maxlength' => '60',
		'required'=> 'true',
		'ng-class'=>'{"has-error": formCategories.title.$touched && formCategories.title.$invalid}',
		'disabled' => 'true'
		)) }}
		<div class="validation-error" ng-messages="formCategories.title.$error" >
		{!! getValidationMessage()!!}
		{!! getValidationMessage('minlength')!!}
		{!! getValidationMessage('maxlength')!!}
		{!! getValidationMessage('pattern')!!}
	</div>
</fieldset>

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
			'required'=> 'true',
			'disabled' => 'true',
			'ng-class'=>'{"has-error": formLms.subject_id.$touched && formLms.subject_id.$invalid}',
		]) }}
<div class="validation-error" ng-messages="formLms.subject_id.$error" >
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
		'disabled' => 'true',

		'ng-class'=>'{"has-error": formLms.batch_id.$touched && formLms.batch_id.$invalid}',
	]) }}
	<div class="validation-error" ng-messages="formLms.batch_id.$error" >
		{!! getValidationMessage()!!}
	</div>
</fieldset>

<fieldset class="form-group col-md-6">
{{ Form::label('topic', getphrase('topic')) }}

{{ Form::text('topic', $value = null, $attributes = array('class'=>'form-control', 'placeholder' => 'topic',
'disabled' => 'true',
	)) }}
</fieldset>

<input type="hidden" name="subject_id" id="subject_id" value="{{$record->subject_id}}">

<fieldset class="form-group col-md-6">
	{{ Form::label('lmsseries_id', 'LMS Series') }}
	<?php
	$lmsseries = \App\LmsSeries::where('subject_id', $record->subject_id)->orderBy('id', 'desc')->get()->pluck('title', 'id')->prepend('Please select', '');
	?>
	{{Form::select('lmsseries_id', $lmsseries, null, ['class'=>'form-control select2', 'name'=>'lmsseries_id', 'id' => 'lmsseries_id'])}}
</fieldset>

<fieldset class="form-group col-md-6">
	{{ Form::label('lmsnotes_id', 'LMS Notes') }}
	<?php
	$lmsnotes = \App\LmsNote::where('subject_id', $record->subject_id)->orderBy('id', 'desc')->get()->pluck('title', 'id')->prepend('Please select', '');
	?>
	{{Form::select('lmsnotes_id', $lmsnotes, null, ['class'=>'form-control select2', 'name'=>'lmsnotes_id', 'id' => 'lmsnotes_id'])}}
</fieldset>

<fieldset class="form-group col-md-6">
	{{ Form::label('live_quiz_id', 'Live Quiz') }}
	<?php
	$live_quizzes = \App\Quiz::where('category_id', QUIZTYPE_LIVEQUIZ)->orderBy('id', 'desc')->get()->pluck('title', 'id')->prepend('Please select', '');
	if ( isFaculty() ) {
	$live_quizzes = \App\Quiz::where('category_id', QUIZTYPE_LIVEQUIZ)->where('record_updated_by', \Auth::id())->orderBy('id', 'desc')->get()->pluck('title', 'id')->prepend('Please select', '');
	}
	?>
	{{Form::select('live_quiz_id', $live_quizzes, null, ['class'=>'form-control select2', 'name'=>'live_quiz_id', 'id' => 'live_quiz_id'])}}
</fieldset>

<div class="buttons text-center">
<input type="hidden" name="referer" value="{{request()->headers->get('referer')}}">
							<button class="btn btn-lg btn-success button" >Update</button>

						</div>


{!! Form::close() !!}
</div>

				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->

@stop
@section('footer_scripts')
@include('onlineclasses.scripts');
  <script src="{{JS}}select2.js"></script>
  <script>
      $('.select2').select2({
       placeholder: "Please select",
    });


    </script>
@stop
