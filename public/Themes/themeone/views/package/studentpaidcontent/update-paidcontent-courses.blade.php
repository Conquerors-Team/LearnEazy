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

							<li><a href="{{route('studentpaidcontent.index')}}">{{ getPhrase('paid_content')}}</a></li>

							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

 <div class="panel panel-custom col-lg-8 col-lg-offset-2">
 <div class="panel-heading"> <div class="pull-right messages-buttons"> <a href="{{route('studentpaidcontent.index')}}" class="btn btn-primary button">{{ getPhrase('list')}}</a> </div><h1>{{ $title }}  </h1></div>
 <div class="panel-body">
{{ Form::model($record,
						array('url' => url()->current(),
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
	{{ Form::label('courses', getphrase('courses')) }}
	<span class="text-red">*</span>
	{{Form::select('courses[]', $courses, null, ['class'=>'form-control select2', 'name'=>'courses[]', 'id' => 'lmsseries', 'required' => 'true', 'multiple'=>'true',])}}
</fieldset>




<div class="buttons text-center">

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

  <script src="{{JS}}select2.js"></script>
<script>
      $('.select2').select2({
       placeholder: "Please select",
    });

    </script>
@stop
