@extends($layout)
<link href="{{CSS}}bootstrap-datepicker.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">

@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="/"><i class="mdi mdi-home"></i></a> </li>
						<!-- 	@if(checkRole(getUserGrade(2), 'coupon_codes')) -->
							<li><a href="{{URL_PACKAGES}}">{{ getPhrase('packages')}}</a></li>
							<!-- @endif -->
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

				<div class="panel panel-custom col-lg-8 col-lg-offset-2">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
						<!-- 	@if(checkRole(getUserGrade(2), 'coupon_codes')) -->
							<a href="{{URL_PACKAGES}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
							<!-- @endif -->
						</div>

					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body" >
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record,
						array('url' => URL_PACKAGES_EDIT.$record->id,
						'method'=>'patch', 'files' => true, 'name'=>'formQuiz ', 'novalidate'=>'')) }}
					@else
						{!! Form::open(array('url' => URL_PACKAGES_ADD, 'method' => 'POST', 'files' => true,'name'=>'formQuiz ', 'novalidate'=>'')) !!}
					@endif


					 @include('package.form_elements',
					 array('button_name'=> $button_name),
					 array('record' 		=> $record))

					{!! Form::close() !!}
					</div>

				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
@stop

@section('footer_scripts')
 @include('common.validations');
@include('common.alertify')

<script src="{{JS}}datepicker.min.js"></script>
 <script src="{{JS}}bootstrap-toggle.min.js"></script>
 <script src="{{JS}}select2.js"></script>
 <script>
 	  $('.input-daterange').datepicker({
        autoclose: true,
        startDate: "0d",
         format: '{{getDateFormat()}}',
    });

 	  $('.select2').select2({
       placeholder: "Please select",
    });

    $("#selectbtn-permissions").click(function(){
        $("#permissions > option").prop("selected","selected");
        $("#permissions").trigger("change");
    });
    $("#deselectbtn-permissions").click(function(){
        $("#permissions > option").prop("selected","");
        $("#permissions").trigger("change");
    });

 </script>
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
@stop

