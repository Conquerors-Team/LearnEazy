@extends($layout)

 @section('header_scripts')
 <link href="{{CSS}}bootstrap-datepicker.css" rel="stylesheet">
 @endsection


@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="/"><i class="mdi mdi-home"></i></a> </li>
							@if(canDo('institute_batch_access'))
							<li><a href="{{URL_BATCHS}}">{{ getPhrase('batches')}}</a></li>
							@endif
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

				<div class="panel panel-custom col-lg-12" >
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							@if(canDo('institute_batch_access'))
							<a href="{{URL_BATCHS}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
							@endif
						</div>
					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body" >
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record,
						array('url' => URL_BATCHS_EDIT.$record->id,
						'method'=>'patch', 'files' => true, 'name'=>'batches ', 'novalidate'=>'')) }}
					@else
						{!! Form::open(array('url' => URL_BATCHS_ADD, 'method' => 'POST', 'files' => true, 'name'=>'batches ', 'novalidate'=>'')) !!}
					@endif

					 @include('batches.form_elements',
					 array('button_name'=> $button_name,'slots_times'=>$slots_times ))

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

   <script src="{{JS}}datepicker.min.js"></script>

 <script>
 	  $('.input-daterange').datepicker({
        autoclose: true,
        startDate: "0d",
         format: 'yyyy-mm-dd',
    });
 </script>

@stop

