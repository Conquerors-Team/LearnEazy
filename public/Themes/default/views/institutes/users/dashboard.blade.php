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


				 	 <div class="col-md-3">
						<div class="card card-green text-xs-center">
							<div class="card-block">
								<h4 class="card-title">{{ App\User::where('institute_id',$institute_id)
				 				                                    ->where('id','!=',$user->id)
				 				                                    ->get()
				 				                                    ->count()
				 				                                }}</h4>
								<p class="card-text">{{ getPhrase('users')}}</p>
							</div>
							<a class="card-footer text-muted" href="{{URL_USERS}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>


					 <div class="col-md-3">
						<div class="card card-blue text-xs-center">
							<div class="card-block">
							<h4 class="card-title">{{ App\Batch::where('institute_id',$institute_id)
				 				                                    ->get()
				 				                                    ->count()
				 				                                }}</h4>
								<p class="card-text">{{ getPhrase('batches')}}</p>
							</div>
							<a class="card-footer text-muted" href="{{URL_BATCHS}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>


					 <div class="col-md-3">
						<div class="card card-red text-xs-center">
							<div class="card-block">
							<h4 class="card-title">
				 					@if(shareData())
				 					{{ App\QuizCategory::get()->count() }}</h4>
				 					@else
				 					{{ App\QuizCategory::where('institute_id',$institute_id)->get()->count() }}</h4>
				 					@endif
								<p class="card-text">{{ getPhrase('quiz_categories')}}</p>
							</div>
							<a class="card-footer text-muted" href="{{URL_QUIZ_CATEGORIES}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>


					 <div class="col-md-3">
						<div class="card card-yellow text-xs-center">
							<div class="card-block">
							<h4 class="card-title">{{ App\Quiz::where('institute_id',$institute_id)
				 				                                    ->get()->count()}}</h4>
								<p class="card-text">{{ getPhrase('quizzes')}}</p>
							</div>
							<a class="card-footer text-muted" href="{{URL_QUIZZES}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>


					 <div class="col-md-3">
						<div class="card card-black text-xs-center">
							<div class="card-block">
							<h4 class="card-title">
				 					@if(shareData())
				 					{{ App\Subject::get()->count()}}</h4>
				 					@else
				 					{{ App\Subject::where('institute_id',$institute_id)->get()->count()}}</h4>
				 					@endif
								<p class="card-text">{{ getPhrase('subjects')}}</p>
							</div>
							<a class="card-footer text-muted" href="{{URL_SUBJECTS}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>


					<div class="col-md-3">
						<div class="card card-red text-xs-center">
							<div class="card-block">
							<h4 class="card-title">
				 					@if(shareData())
				 					{{ App\Topic::get()->count() }}</h4>
				 					@else
				 					{{ App\Topic::where('institute_id',$institute_id)->get()->count() }}</h4>
				 					@endif
								<p class="card-text">{{ getPhrase('topics')}}</p>
							</div>
							<a class="card-footer text-muted" href="{{URL_TOPICS}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>


					<div class="col-md-3">
						<div class="card card-green text-xs-center">
							<div class="card-block">
								<h4 class="card-title">
				 					@if(shareData())
				 					{{ App\QuestionBank::get()->count() }}</h4>
				 					@else
				 					{{ App\QuestionBank::where('institute_id',$institute_id)->get()->count() }}</h4>
				 					@endif
								<p class="card-text">{{ getPhrase('questions')}}</p>
							</div>
							<a class="card-footer text-muted" href="{{URL_QUIZ_QUESTIONBANK}}">
								{{ getPhrase('view_all')}}
							</a>
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
