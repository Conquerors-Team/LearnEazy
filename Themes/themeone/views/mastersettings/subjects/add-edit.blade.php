@extends($layout)
@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							@if(canDo('subject_access'))
							<li><a href="{{URL_SUBJECTS}}">{{ getPhrase('subjects')}}</a> </li>
							  @endif
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

				<div class="panel panel-custom col-lg-6 col-lg-offset-3">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							@if(canDo('subject_access'))
							<a href="{{URL_SUBJECTS}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
							  @endif
						</div>
					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body  form-auth-style" id="app">
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record,
						array('url' => URL_SUBJECTS_EDIT.'/'. $record->slug,
						'method'=>'patch', 'name'=>'formSubjects ', 'novalidate'=>'')) }}
					@else
						{!! Form::open(array('url' => URL_SUBJECTS_ADD, 'method' => 'POST', 'name'=>'formSubjects ', 'novalidate'=>'')) !!}
					@endif

					 @include('mastersettings.subjects.form_elements',
					 array('button_name'=> $button_name, 'record' => $record),
					 array())

					{!! Form::close() !!}


					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
@stop
@section('footer_scripts')

 <script>
 /**
  * This method validates the maximum and pass marks of a given subject
  * @return {[type]} [description]
  */
 function validateMarks()
 {
 	$passmarks = $('#pass_marks');
 	$maxmarks = $('#maximum_marks');
 	maximum_marks = parseInt($maxmarks.val(),10);
 	pass_marks = parseInt($passmarks.val(),10);

 	//Check if Maximum Mark is a valid integer greater than 0
 	if(isNaN(maximum_marks) || maximum_marks <= 0){
 		alert('{{getPhrase("please_enter_valid_maximum_marks")}}');
 		$maxmarks.val(0);
 		return;
 	}
 	//Check if Pass Mark is a valid integer greater than 0
 	if(isNaN(pass_marks)){
 		alert('{{getPhrase("please_enter_valid_pass_marks")}}');
 		$passmarks.val(0);
 		return;
 	}

 	//Compare the Maximum mark and Pass mark and give tha appropriate message
 	if(pass_marks > maximum_marks)
 	{
 		alert('{{getPhrase("pass_marks_cannot_be_greater_than_maximum_marks")}}');
 		$passmarks.val(0);
 	}

 }

 </script>

@include('common.alertify')
  <script>
 	var file = document.getElementById('image_input');

file.onchange = function(e){
    var ext = this.value.match(/\.([^\.]+)$/)[1];
    switch(ext)
    {
        case 'jpg':
        case 'jpeg':
        case 'png':


            break;
        default:
           alertify.error("{{getPhrase('file_type_not_allowed')}}");
            this.value='';
    }
};
 </script>
  @include('common.validations');

<link href="{{ themes('plugins/colorpicker/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
<script src="{{ themes('plugins/colorpicker/bootstrap-colorpicker.min.js') }}"></script>
<script>
    $('.colorpicker').colorpicker();
</script>

 @stop