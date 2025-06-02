@extends($layout)
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">

@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							@if(canDo('classes_access'))

							<li><a href="{{URL_INSTITUTE_CLASSES}}">{{ getPhrase('classes')}}</a> </li>
							@endif
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

			 <div class="panel panel-custom col-lg-8 col-lg-offset-2">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							@if(canDo('classes_access'))
							<a href="{{URL_INSTITUTE_CLASSES}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
							@endif
						</div>
					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body">
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record,
						array('url' => URL_INSTITUTE_CLASS_EDIT. '/' . $record->slug,
						'method'=>'patch' ,'novalidate'=>'','name'=>'instituteBranch', 'files'=>'true')) }}
					@else
						{!! Form::open(array('url' => URL_INSTITUTE_CLASS_ADD, 'method' => 'POST',
						'novalidate'=>'','name'=>'instituteBranch ', 'files'=>'true')) !!}
					@endif

					 @include('institutes.classes.form_elements',
					 array('button_name'=> $button_name))

					{!! Form::close() !!}


					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
@stop
@section('footer_scripts')

	@include('common.validations')

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

	    </script>

@stop
