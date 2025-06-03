@extends($layout)
<link href="{{CSS}}bootstrap-datepicker.css" rel="stylesheet">
@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="/"><i class="mdi mdi-home"></i></a> </li>
							@if(canDo('exam_series_access'))
							<li><a href="{{URL_PAID_CONTENT}}">{{ getPhrase('student_paid_content')}}</a></li>
							 @endif
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

				<div class="panel panel-custom col-lg-8  col-lg-offset-2" >
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							@if(canDo('exam_series_access'))
							<a href="{{URL_PAID_CONTENT}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
							@endif
						</div>
					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body" >
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record,
						array('url' => URL_PAID_CONTENT_EDIT.$record->slug,
						'method'=>'patch', 'files' => true, 'name'=>'formQuiz ', 'novalidate'=>'')) }}
					@else
						{!! Form::open(array('url' => URL_PAID_CONTENT_ADD, 'method' => 'POST', 'files' => true, 'name'=>'formQuiz ', 'novalidate'=>'')) !!}
					@endif


					 @include('package.studentpaidcontent.form_elements',
					 array('button_name'=> $button_name),
					 array('record'=>$record, 'lms_contents' => $lms_contents, 'lms_series' => $lms_series,'lms_notes' => $lms_notes))

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
 @include('common.editor');
 @include('common.alertify')

 <script src="{{JS}}datepicker.min.js"></script>
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

 $('.input-daterange').datepicker({
        autoclose: true,
        startDate: "0d",
         format: '{{getDateFormat()}}',
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

