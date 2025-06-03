@extends('layouts.admin.adminlayout')

<link href="{{CSS}}bootstrap-datepicker.css" rel="stylesheet">
@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>

							@if(canDo('institute_view'))
						<li><a href="{{URL_VIEW_INSTITUES}}">{{ getPhrase('institutes')}}</a> </li>
						     @endif
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
				@include('errors.errors')
				<div class="panel panel-custom col-lg-8 col-lg-offset-2">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							@if(canDo('institute_view'))
							<a href="{{URL_VIEW_INSTITUES}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
							@endif
						</div>
					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body  form-auth-style" >
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>

						{{ Form::model($record,
						array('url' => URL_EDIT_INSTITUTE_DETAILS.$record->institute_id,
						'method'=>'patch',  'novalidate'=>'','name'=>'registrationForm','name'=>'formLanguage ')) }}

					@else


						{!! Form::open(array('url' => URL_ADD_INSTITUTE_REGISTER, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'',  'name'=>"registrationForm")) !!}
					@endif

					 @include('institutes.registration',
					 array(['button_name'=> $button_name,'record'=>$record,'ins_name'=>$ins_name,'ins_address'=>$ins_address]))
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

 	  $('.datepicker1').datepicker({
        autoclose: true,
        startDate: "0d",
         format: '{{getDateFormat()}}',
    });
 </script>
@stop
