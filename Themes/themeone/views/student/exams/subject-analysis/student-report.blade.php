 @extends($layout)

@section('header_scripts')



@stop

@section('content')





<div id="page-wrapper">

			<div class="container-fluid">

				<!-- Page Heading -->

				<div class="row">

					<div class="col-lg-12">

						<ol class="breadcrumb">

							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>

							<li><a href="{{URL_STUDENT_ANALYSIS_BY_EXAM.$user_details->slug}}">{{getPhrase('analysis')}}</i></a> </li>

							<li>{{ $title}}</li>

						</ol>

					</div>

				</div>



				<!-- /.row -->

				<div class="panel panel-custom">



					<div class="panel-heading">



						<h1>Subject wise report of {{$user_details->name }}</h1>

					</div>

					<div class="panel-body packages">





					<!-- <ul class="nav nav-tabs add-student-tabs">

							<li class="active"><a data-toggle="tab" href="#academic_details">{{getPhrase('marks')}}</a></li>

							<li><a data-toggle="tab" href="#personal_details">{{getPhrase('time')}}</a></li>



					</ul> -->

					<div class="tab-content tab-content-style">

							<div id="academic_details" class="tab-pane fade in active">



						<div class="table-responsive">

						<table class="table table-striped table-bordered  " cellspacing="0" width="100%">

							<thead>

								<tr>



									<th></th>

									<th>Marks</th>

									<th>No. of questions attempted</th>

								</tr>

							</thead>
							<tr>
								<td>Total</td>
								<td>{{$marks_obtained}}</td>
								<td>{{$total_questions_attempted}}</td>
							</tr>
							<?php

							foreach($subjects_report as  $subject => $details) {
								// dd($details);
								// $details = (object)$details;
							 	?>
							 	<tr>

							 		<td>{{$subject}}</td>

							 		<td>{{$details['subject_total']}}</td>

							 		<td>{{$details['subject_answers_count']}}</td>

							 	</tr>

							<?php }
							// dd($user_details);?>

						</table>

						</div>

						</div>

						</div>

						<div class="row">

						<div class="col-md-6 col-lg-5">

		  				  <div class="panel panel-primary dsPanel">

						    <div class="panel-heading"><i class="fa fa-bar-chart-o"></i> Overall quiz report</div>
						    <div class="panel-body" >
						    	<canvas id="ContactTypesdonutChart" width="100" height="60"></canvas>
						    	<div class="panel-heading">Total Questions in exam : {{$total_questions_in_exam}}</div>
						    </div>
						  </div>
						</div>

						<div class="col-md-6 col-lg-7">

		  				  <div class="panel panel-primary dsPanel">

						    <div class="panel-heading"><i class="fa fa-clock-o" aria-hidden="true"></i></i>Time wise Analysis</div>
						    <div class="panel-body" >
						    	<div class="table-responsive">

								<table class="table table-striped table-bordered  " cellspacing="0" width="100%">

								<thead>

								<tr>
									<th></th>
									<th>Correct answers</th>
									<th>Wrong answers</th>
									<th>Skipped Questiond</th>
								</tr>
								<?php

								foreach($subjects_report as  $subject => $details) {

							 	?>
								<tr>
									<td>{{$subject}}</td>
									<td>
									@if( ! empty( $details['time_spenton_correct_answers'] ) && $details['time_spenton_correct_answers'] >= 60 )
										{{round($details['time_spenton_correct_answers']/60, 2)}} min
									@else
										{{$details['time_spenton_correct_answers']}} secs
									@endif
								</td>
									<td>
									@if( ! empty( $details['time_spenton_wrong_answers'] ) && $details['time_spenton_wrong_answers'] >= 60 )
										{{round($details['time_spenton_wrong_answers']/60, 2)}} min
									@else
										{{$details['time_spenton_wrong_answers']}} secs
									@endif
								</td>
									<td>
									@if( ! empty( $details['time_spenton_skipped_answers'] ) && $details['time_spenton_skipped_answers'] >= 60 )
										{{round($details['time_spenton_skipped_answers']/60, 2)}} min
									@else
										{{$details['time_spenton_skipped_answers']}} secs
									@endif
									</td>
								</tr>

								<?php } ?>
							</thead>
						</table>
						    </div>
						  </div>
						</div>

				    	<!-- <canvas id="ContactTypesdonutChart" width="100" height="60"></canvas> -->
				    </div>

					</div>

				</div>

			</div>

			<!-- /.container-fluid -->

		</div>

@endsection





@section('footer_scripts')

 @if(isset($chart_data))
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.js"></script>
<script type="text/javascript">
	<?php
    $cdata = $chart_data;

        $cdata = $chart_data;

    $dataset = $cdata->data;

    // dd($dataset->labels);
?>
var ctx = document.getElementById("ContactTypesdonutChart").getContext("2d");

var myChart = new Chart(ctx, {
    type: 'doughnut',
     animation:{
        animateScale:true,
    },
    data: {
    	labels: {!! json_encode($dataset->labels) !!},
        datasets: [
         {
            label: {!! json_encode($dataset->dataset_label) !!},
            data: {!! json_encode($dataset->dataset) !!},
            backgroundColor: {!! json_encode($dataset->bgcolor) !!},
            borderColor: {!! json_encode($dataset->border_color) !!},
            borderWidth: 1
        },
        ],

    },
    options: {
        scales: {
            @if(isset($scale))
            xAxes: [{
                gridLines: {
                    display:false
                }
            }],
    yAxes: [{
                gridLines: {
                    display:false
                }
            }]
           @endif
        },
         title: {
            display: true,
            text: '{{ isset($cdata->title) ? $cdata->title : '' }}'
        },
        }
});
</script>


@endif

@if(isset($time_data))

	@include('common.chart', array('chart_data'=>$time_data,'ids' => $timeids));

@endif

@stop

