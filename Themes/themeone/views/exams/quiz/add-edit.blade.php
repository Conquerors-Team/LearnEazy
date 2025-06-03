@extends($layout)
<link href="{{CSS}}bootstrap-datepicker.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">

<style>
.select2-container--default .select2-selection--single {    border-color: #e1e8f8;
    border-radius: 0;
    box-shadow: none;
    font-size: 15px;
    min-height: 44px;
    padding-left: 12px;
    color: #353f4d;}
</style>

@section('content')

<div id="page-wrapper">

			<div class="container-fluid">

				<!-- Page Heading -->

				<div class="row">

					<div class="col-lg-12">

						<ol class="breadcrumb">

							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
                            @if(canDo('exams_access'))
								@if ( ! empty( $exam_type ) && ('test_series' === $exam_type ) )
								<li><a href="{{route('exams.test_series')}}">{{ getPhrase('test_series')}}</a></li>
								@elseif ( ! empty( $exam_type ) && ('live_quizzes' === $exam_type ) )
								<li><a href="{{route('exams.live_quizzes')}}">{{ getPhrase('test_series')}}</a></li>
								@else
								<li><a href="{{URL_QUIZZES}}">{{ getPhrase('exams')}}</a></li>
								@endif
                            @endif
							<li class="active">{{isset($title) ? $title : ''}}</li>

						</ol>

					</div>

				</div>

					@include('errors.errors')

				<!-- /.row -->



				<div class="panel panel-custom" >

					<div class="panel-heading">

						<div class="pull-right messages-buttons">
                           @if(canDo('exams_access'))
							@if ( ! empty( $exam_type ) && ('test_series' === $exam_type ) )
							<a href="{{route('exams.test_series')}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
							@elseif ( ! empty( $exam_type ) && ('live_quizzes' === $exam_type ) )
							<a href="{{route('exams.live_quizzes')}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
							@else
							<a href="{{URL_QUIZZES}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
							@endif
                           @endif
						</div>



					<h1>{{ $title }}  </h1>

					</div>

					<div class="panel-body" >

					<?php $button_name = getPhrase('create'); ?>

					@if ($record)

					 <?php $button_name = getPhrase('update'); ?>

						{{ Form::model($record,

						array('url' => URL_QUIZ_EDIT.'/'.$record->slug,

						'method'=>'patch', 'files' => true, 'name'=>'formQuiz ', 'novalidate'=>'','files'=>TRUE)) }}

					@else

						{!! Form::open(array('url' => URL_QUIZ_ADD, 'method' => 'POST', 'files' => true, 'name'=>'formQuiz ', 'novalidate'=>'','files'=>TRUE)) !!}

					@endif





					 @include('exams.quiz.form_elements',

					 array('button_name'=> $button_name),

					 array(	'categories' => $categories,

					 		'instructions' => $instructions,

					 		'record'	=> $record,

					 		'exam_types' => $exam_types,

					 		'batches'    => $batches,

					 		'pre_data'   => $pre_data,

					 		'slots_times'   => $slots_times,
					 		'exam_type' => ! empty( $exam_type ) ? $exam_type : '',

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

 @include('common.validations')

<script src="{{JS}}datepicker.min.js"></script>
 <script src="{{JS}}select2.js"></script>



 <script>

 	  $('.input-daterange').datepicker({
        autoclose: true,
        startDate: "0d",
         format: '{{getDateFormat()}}',
    });

 	   $('.select2').select2({

       placeholder: "Select",
       tags: true

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
 </script>

@stop



