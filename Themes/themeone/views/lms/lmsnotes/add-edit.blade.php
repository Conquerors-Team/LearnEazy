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

							@if(canDo('lms_notes_access'))
							<li><a href="{{URL_LMS_NOTES}}">LMS {{ getPhrase('notes')}}</a></li>
							@endif
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

 <div class="panel panel-custom col-lg-8 col-lg-offset-2">
 <div class="panel-heading"> <div class="pull-right messages-buttons"> @if(canDo('lms_notes_access'))<a href="{{URL_LMS_NOTES}}" class="btn btn-primary button">{{ getPhrase('list')}}</a> @endif</div><h1>{{ $title }}  </h1></div>
 <div class="panel-body">
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record,
						array('url' => URL_LMS_NOTES_EDIT.$record->slug,
						'method'=>'patch', 'files' => true, 'name'=>'formLms', 'id'=>'formLms', 'novalidate'=>'')) }}
					@else
						{!! Form::open(array('url' => URL_LMS_NOTES_ADD, 'method' => 'POST', 'files' => true, 'name'=>'formLms', 'id'=>'formLms', 'novalidate'=>'')) !!}
					@endif


					 @include('lms.lmsnotes.form_elements',
					 array('button_name'=> $button_name),
					 array('record'=>$record,
					 'categories' => $categories,
					 'subjects' => $subjects,
					 'chapters' => $chapters,
					 'topics' => $topics,
					 ))

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
 <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.22/dist/katex.min.css">
<script src="https://cdn.jsdelivr.net/npm/katex@0.16.22/dist/katex.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.formula.min.js"></script>
 @include('common.editor');
 @include('common.alertify')

 @include('lms.lmsnotes.scripts.js-scripts');

@stop

