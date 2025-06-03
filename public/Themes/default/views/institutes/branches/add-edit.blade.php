@extends($layout)

@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li><a href="{{URL_INSTITUTE_BRANCH}}">{{ getPhrase('branches')}}</a> </li>
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->
				
			 <div class="panel panel-custom col-lg-8 col-lg-offset-2">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							<a href="{{URL_INSTITUTE_BRANCH}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
						</div>
					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body">
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record, 
						array('url' => URL_INSTITUTE_BRANCH_EDIT.$record->id, 
						'method'=>'patch' ,'novalidate'=>'','name'=>'instituteBranch', 'files'=>'true')) }}
					@else
						{!! Form::open(array('url' => URL_INSTITUTE_BRANCH_ADD, 'method' => 'POST', 
						'novalidate'=>'','name'=>'instituteBranch ', 'files'=>'true')) !!}
					@endif

					 @include('institutes.branches.form_elements', 
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
@stop
 