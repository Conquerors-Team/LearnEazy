@extends($layout)
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.22/dist/katex.min.css">
@stop
@section('content')


<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a> </li>
							<li><a href="{{URL_QUIZ_QUESTIONBANK}}">{{ getPhrase('question_subjects') }}</a></li>
							<li><a href="{{URL_QUESTIONBAMK_IMPORT}}">{{ getPhrase('import_questions') }}</a></li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">

						<div class="pull-right messages-buttons">

							<a href="{{URL_QUESTIONBANK_VIEW.$subject->slug}}" class="btn  btn-primary button" >{{ getPhrase('back')}}</a>

						</div>
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div class="questions">

                                    <span class="language_l1">{!! $question->question !!}   </span>
                                    @if($question->question_l2)
                                     @if($question->question_type == 'radio' || $question->question_type == 'checkbox' || $question->question_type == 'blanks' || $question->question_type == 'match')
                                   <span class="language_l2" style="display: none;"> {!! $question->question_l2 !!}   </span>
                                   @else
                                   <span class="language_l2" style="display: none;"> {!! $question->question !!}   </span>
                                     @endif
                                   @else
                                   <span class="language_l2" style="display: none;"> {!! $question->question !!}   </span>
                                   @endif

                                    <div class="row">
  <div class="col-md-8 text-center">
  @if($question->question_type!='audio' && $question->question_type !='video')
  @if($question->question_file)
  <img class="image " src="{{$image_path.$question->question_file}}" style="max-height:200px;">
  @endif
  @endif
  </div>
  <div class="col-md-4">
   <span class="pull-right">






                                   {{$question->marks}} Mark(s)</span>
  </div>
  </div>



                                    @if($question->hint)
                                    <div class="option-hints pull-right default" data-placement="left" data-toggle="tooltip" ng-show="hints" title="{{ $question->hint }}">

                                        <i class="mdi mdi-help-circle">

                                        </i>

                                    </div>
                                    @endif

                                </div>

                                <hr>

                                    <?php

                                    $image_path = PREFIX.(new App\ImageSettings())->

                                    getExamImagePath();



                                    ?>



								@include('student.exams.question_'.$question->question_type, array('question', $question, 'image_path' => $image_path,'previous_answers'=>[]  ))


                                </hr>

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
@endsection


@section('footer_scripts')
  {{-- <script src="{{JS}}bootstrap-toggle.min.js"></script>
 	<script src="{{JS}}jquery.dataTables.min.js"></script>
	<script src="{{JS}}dataTables.bootstrap.min.js"></script> --}}
 @include('common.datatables', array('route'=>URL_QUESTIONBANK_GETQUESTION_LIST.$subject->slug, 'route_as_url' => 'TRUE'))
 @include('common.deletescript', array('route'=>URL_QUESTIONBANK_DELETE))



@stop
