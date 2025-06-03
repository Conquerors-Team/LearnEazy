@extends($layout)

@section('header_scripts')
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">
@stop

@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="/"><i class="mdi mdi-home"></i></a> </li>

							@if(canDo('chapter_access'))
							<li><a href="{{route('mastersettings.chapters_index')}}">{{ getPhrase('topics')}}</a> </li>
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
							@if(canDo('chapter_access'))
							<a href="{{route('mastersettings.chapters_index')}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
							 @endif
						</div>
					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body  form-auth-style" ng-controller="angTopicsController">
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record,
						array('url' => url('mastersettings/chapters/edit').'/'.$record->slug,
						'method'=>'patch' ,'novalidate'=>'','name'=>'formTopics ')) }}
					@else
						{!! Form::open(array('url' => url('mastersettings/chapters/add'), 'method' => 'POST',
						'novalidate'=>'','name'=>'formTopics ')) !!}
					@endif

					 @include('mastersettings.chapters.form_elements',
					 array('button_name'=> $button_name),
					 array('subjects'=>$subjects, 'parent_topics'=>$parent_chapters))

					{!! Form::close() !!}


					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
@stop
@section('footer_scripts')
	@include('mastersettings.chapters.scripts.js-scripts');
	@include('common.validations', array('isLoaded'=>TRUE));
@stop
