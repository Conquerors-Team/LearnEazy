@extends('layouts.admin.adminlayout')
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">

@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							@if(canDo('board_access'))
							<li><a href="{{URL_BOARDS_CLASSES}}">{{ getPhrase('boards')}}</a> </li>
							@endif
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
				@include('errors.errors')
				<div class="panel panel-custom col-lg-6 col-lg-offset-3">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							@if(canDo('board_access'))
							<a href="{{URL_BOARDS_CLASSES}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
							@endif
						</div>
					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body  form-auth-style" >
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record,
						array('url' => URL_BOARD_CLASSES_EDIT.'/'.$record->id,
						'method'=>'patch', 'files' => true, 'novalidate'=>'','name'=>'formCategories')) }}
					@else
						{!! Form::open(array('url' => URL_BOARD_CLASSES_ADD, 'method' => 'POST', 'files' => true, 'novalidate'=>'','name'=>'formCategories')) !!}
					@endif

					 @include('exams.boards.board-classes.form_elements',
					 array('button_name'=> $button_name),
					 array('record' => $record))
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
 @include('common.alertify')
   <script src="{{JS}}select2.js"></script>
  <script>
      $('.select2').select2({
       placeholder: "Please select",
    });

    $("#selectbtn-subjects").click(function(){
        $("#subjects > option").prop("selected","selected");
        $("#subjects").trigger("change");
    });
    $("#deselectbtn-subjects").click(function(){
        $("#subjects > option").prop("selected","");
        $("#subjects").trigger("change");
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
