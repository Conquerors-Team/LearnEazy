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
							@if(checkRole(getUserGrade(2), 'lms_series_access'))
							<li><a href="{{URL_LMS_SERIES}}">LMS {{ getPhrase('series')}}</a></li>
							@endif
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

 <div class="panel panel-custom col-lg-8 col-lg-offset-2">
 <div class="panel-heading"> <div class="pull-right messages-buttons"> <a href="{{URL_LMS_SERIES}}" class="btn btn-primary button">{{ getPhrase('list')}}</a> </div><h1>{{ $title }}  </h1></div>
 <div class="panel-body">
{{ Form::model($record,
						array('url' => URL_LMS_SERIES_UPDATE_SERIES_COURSES.$record->slug,
						'method'=>'post', 'files' => true, 'name'=>'formLms ', 'novalidate'=>'')) }}
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
		'readonly' => 'true'
		)) }}
		<div class="validation-error" ng-messages="formCategories.title.$error" >
		{!! getValidationMessage()!!}
		{!! getValidationMessage('minlength')!!}
		{!! getValidationMessage('maxlength')!!}
		{!! getValidationMessage('pattern')!!}
	</div>
</fieldset>

<fieldset class="form-group">
	{{ Form::label('class', getphrase('class')) }}
	<span class="text-red">*</span>
	{{Form::select('class', $classes, null, ['class'=>'form-control select2', 'name'=>'class', 'id' => 'class', 'required' => 'true'])}}
</fieldset>


@if ($courses && $courses->count() > 0 )
<fieldset class="form-group">
	{{ Form::label('courses', getphrase('courses')) }}
	<button type="button" class="btn btn-primary btn-xs" id="selectbtn-courses">
        {{ getPhrase('select_all') }}
    </button>
    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-courses">
        {{ getPhrase('deselect_all') }}
    </button>
	<span class="text-red">*</span>

	{{Form::select('courses[]', $courses, null, ['class'=>'form-control select2', 'name'=>'courses[]', 'multiple'=>'true', 'id' => 'courses', 'required' => 'true'])}}

</fieldset>

<div class="buttons text-center">

							<button class="btn btn-lg btn-success button" >Update</button>

						</div>
@endif

{!! Form::close() !!}
</div>

				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->

@stop
@section('footer_scripts')

  <script src="{{JS}}select2.js"></script>
  <script>
      $('.select2').select2({
       placeholder: "Please select",
    });

    $("#selectbtn-courses").click(function(){
        $("#courses > option").prop("selected","selected");
        $("#courses").trigger("change");
    });
    $("#deselectbtn-courses").click(function(){
        $("#courses > option").prop("selected","");
        $("#courses").trigger("change");
    });

    $('#class').change(function() {
    	window.location = '{{URL_LMS_SERIES_UPDATE_SERIES_COURSES . $record->slug}}/' + $(this).val();
    });

    </script>
@stop
