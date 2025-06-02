@extends('layouts.institute.institutelayout')
@section('content')

<div id="page-wrapper">
			<div class="container-fluid">
			<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							 
							<li><i class="fa fa-home"></i> {{ $title}}</li>
						</ol>
					</div>
				</div>

				 <div class="row">

				 	<?php 

				 	     $institute_id  = adminInstituteId();
				 	     $user          = Auth::user();
				 	 ?>

				 <div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_USERS}}"><div class="state-icn bg-icon-info"><i class="fa fa-users"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">{{ App\User::where('institute_id',$institute_id)
				 				                                    ->where('id','!=',$user->id)
				 				                                    ->get()
				 				                                    ->count()
				 				                                }}</h4>
								<a href="{{URL_USERS}}">{{ getPhrase('users')}}</a>
				 			</div>
				 		</div>
				 	</div>

				 	 <div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_BATCHS}}"><div class="state-icn bg-icon-success"><i class="fa fa-sitemap"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">{{ App\Batch::where('institute_id',$institute_id)
				 				                                    ->get()
				 				                                    ->count()
				 				                                }}</h4>
								<a href="{{URL_BATCHS}}">{{ getPhrase('student_batches')}}</a>
				 			</div>
				 		</div>
				 	</div>

					<div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_QUIZ_CATEGORIES}}"><div class="state-icn bg-icon-pink"><i class="fa fa-list-alt"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">
				 					@if(shareData())
				 					{{ App\QuizCategory::get()->count() }}</h4>
				 					@else
				 					{{ App\QuizCategory::where('institute_id',$institute_id)->get()->count() }}</h4>
				 					@endif
								<a href="{{URL_QUIZ_CATEGORIES}}">{{ getPhrase('exam_categories')}}</a>
				 			</div>
				 		</div>
				 	</div>
				 	<div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_QUIZZES}}"><div class="state-icn bg-icon-purple"><i class="fa fa-desktop"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">{{ App\Quiz::where('institute_id',$institute_id)
				 				                                    ->get()->count()}}</h4>
								<a href="{{URL_QUIZZES}}">{{ getPhrase('exams')}}</a>
				 			</div>
				 		</div>
				 	</div>
				 <div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_SUBJECTS}}"><div class="state-icn bg-icon-success"><i class="fa fa-book"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">
				 					@if(shareData())
				 					{{ App\Subject::get()->count()}}</h4>
				 					@else
				 					{{ App\Subject::where('institute_id',$institute_id)->get()->count()}}</h4>
				 					@endif
								<a href="{{URL_SUBJECTS}}">{{ getPhrase('subjects')}}</a>
				 			</div>
				 		</div>
				 	</div>


				 	 <div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_TOPICS}}"><div class="state-icn bg-icon-purple"><i class="fa fa-list"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">
				 					@if(shareData())
				 					{{ App\Topic::get()->count() }}</h4>
				 					@else
				 					{{ App\Topic::where('institute_id',$institute_id)->get()->count() }}</h4>
				 					@endif
								<a href="{{URL_TOPICS}}">{{ getPhrase('topics')}}</a>
				 			</div>
				 		</div>
				 	</div>


				 	 <div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_QUIZ_QUESTIONBANK}}"><div class="state-icn bg-icon-orange"><i class="fa fa-question-circle"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">
				 					@if(shareData())
				 					{{ App\QuestionBank::get()->count() }}</h4>
				 					@else
				 					{{ App\QuestionBank::where('institute_id',$institute_id)->get()->count() }}</h4>
				 					@endif
								<a href="{{URL_QUIZ_QUESTIONBANK}}">{{ getPhrase('questions')}}</a>
				 			</div>
				 		</div>
				 	</div>

                </div>
		 
			<!-- /.container-fluid -->
 <div class="row">

 	<div class="col-md-6">
  				  <div class="panel panel-primary dsPanel">
				    <div class="panel-heading"><i class="fa fa-pie-chart"></i> {{getPhrase('quizzes_usage')}}</div>
				    <div class="panel-body" >
				    	<canvas id="demanding_quizzes" width="100" height="60"></canvas>
				    </div>
				  </div>
				</div>
				
				
				<div class="col-md-6">
  				  <div class="panel panel-primary dsPanel">
				    <div class="panel-heading"><i class="fa fa-pie-chart"></i> {{getPhrase('paid_quizzes_usage')}}</div>
				    <div class="panel-body" >
				    	<canvas id="demanding_paid_quizzes" width="100" height="60"></canvas>
				    </div>
				  </div>
				</div>
			</div>
			<div class="row">

				<div class="col-md-6 col-lg-5">
  				  <div class="panel panel-primary dsPanel">
				    <div class="panel-heading"><i class="fa fa-bar-chart-o"></i> {{getPhrase('payment_statistics')}}</div>
				    <div class="panel-body" >
				    	<canvas id="payments_chart" width="100" height="60"></canvas>
				    </div>
				  </div>
				</div>
				<div class="col-md-6 col-lg-3">
  				  <div class="panel panel-primary dsPanel">
				    <div class="panel-heading"><i class="fa fa-bar-chart-o"></i>{{$chart_heading}}</div>
				    <div class="panel-body" >
						
						<?php $ids=[];?>
						@for($i=0; $i<count($chart_data); $i++)
						<?php 
						$newid = 'myChart'.$i;
						$ids[] = $newid; ?>
						
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<canvas id="{{$newid}}" width="100" height="110"></canvas>
								</div>
							</div>
						</div>

						@endfor
				    </div>
				  </div>
				</div>


				<div class="col-md-6 col-lg-4">
  				  <div class="panel panel-primary dsPanel">
				    <div class="panel-heading"><i class="fa  fa-line-chart"></i> {{getPhrase('payment_monthly_statistics')}}</div>
				    <div class="panel-body" >
				    	<canvas id="payments_monthly_chart" width="100" height="60"></canvas>
				    </div>
				  </div>
				</div>

				
 

 
				
	</div>
</div>
		<!-- /#page-wrapper -->

@stop

@section('footer_scripts')
 
 @include('common.chart', array($chart_data,'ids' =>$ids))
 @include('common.chart', array('chart_data'=>$payments_chart_data,'ids' =>array('payments_chart'), 'scale'=>TRUE))
 @include('common.chart', array('chart_data'=>$payments_monthly_data,'ids' =>array('payments_monthly_chart'), 'scale'=>true))
 @include('common.chart', array('chart_data'=>$demanding_quizzes,'ids' =>array('demanding_quizzes')))
 @include('common.chart', array('chart_data'=>$demanding_paid_quizzes,'ids' =>array('demanding_paid_quizzes')))
 

@stop
