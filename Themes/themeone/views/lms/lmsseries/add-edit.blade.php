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
              @if(canDo('lms_series_access'))
							<li><a href="{{route('lms.series.index')}}">LMS {{ getPhrase('series')}}</a></li>
							@endif
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

 <div class="panel panel-custom col-lg-8 col-lg-offset-2">
 <div class="panel-heading"> <div class="pull-right messages-buttons">
   @if(canDo('lms_series_access'))
  <a href="{{route('lms.series.index')}}" class="btn btn-primary button">{{ getPhrase('list')}}</a>
  @endif
  </div><h1>{{ $title }}  </h1></div>
 <div class="panel-body">
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record,
						array('url' => URL_LMS_SERIES_EDIT.$record->slug,
						'method'=>'patch', 'files' => true, 'name'=>'formLms ', 'novalidate'=>'')) }}
					@else
						{!! Form::open(array('url' => URL_LMS_SERIES_ADD, 'method' => 'POST', 'files' => true, 'name'=>'formLms ', 'novalidate'=>'')) !!}
					@endif


					 @include('lms.lmsseries.form_elements',
					 array('button_name'=> $button_name),
					 array('record'=>$record,

					 'chapters' => $chapters,
					 'topics' => $topics,

					 'categories' => $categories))

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

<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.22/dist/katex.min.css">
<script src="https://cdn.jsdelivr.net/npm/katex@0.16.22/dist/katex.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.formula.min.js"></script>

<!-- @include('common.conditional-editor') -->
@include('common.editor');

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

function getSubjectChapters()
    {
      subject_id = $('#subject_id').val();
      route = '{{url("mastersettings/chapters/get-parents-chapters")}}/'+subject_id;

      var token = $('[name="_token"]').val();

      data= {_method: 'get', '_token':token, 'subject_id': subject_id};
      $.ajax({
        url:route,
        dataType: 'json',
        data: data,
        success:function(result){
          $('#chapter_id').empty();
          for(i=0; i<result.length; i++)
            $('#chapter_id').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
          }
      });
    }

    function getChaptersTopics()
    {
      subject_id = $('#subject_id').val();
      chapter_id = $('#chapter_id').val();
      route = '{{url("mastersettings/topics/get-parents-topics-exam")}}/'+subject_id + '/' + chapter_id;

      var token = $('[name="_token"]').val();

      data= {_method: 'get', '_token':token};
      $.ajax({
        url:route,
        dataType: 'json',
        data: data,
        success:function(result){
          $('#topic_id').empty();
          for(i=0; i<result.length; i++)
            $('#topic_id').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
          }
      });
    }
 </script>
@stop

