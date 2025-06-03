@extends($layout)
@section('content')
<style>


  </style>
<div id="page-wrapper">
			<div class="container-fluid">
			<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							 <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							 <li><a href="{{route('batch.reports')}}">Batch Reports</a> </li>
							<li>{{ $title}}</li>
						</ol>
					</div>
				</div>


			<!-- /.container-fluid -->
			<div class="row">
				@if ( ! empty( $exam_slug ) )
					<div class="col-md-12">
  				  <div class="panel panel-primary dsPanel">
				    <div class="panel-heading">{{getPhrase('batch_report')}}</div>
				    <div class="panel-body" style="overflow-x:auto;">

				    	<table class="table">

				    		<tr>
				    			<th>Question No</th>
				    			<?php foreach( $chart_data->quiz_attempted_students as $student) { ?>

				    			<th>{{$student->name}}</th>

				    			<?php } ?>
				    		</tr>
				    		<?php  $q = 0;
				    		// dd( $chart_data );
				    		for( $i = 1; $i<= $chart_data->total_questions; $i++) {
				    			//$question_data = \App\QuestionBank::where('id',$chart_data->questions_res[$q])->first();
				    			//$question_data = \App\QuestionBank::where('id',$question_id)->first();
				    		 ?>
				    		<tr>
				    			<td><a href="#question_{{$i}}">{{$i}}</a>.</td>
				    			<?php
				    			foreach( $chart_data->quiz_attempted_students as $student) {

				    					if( isset($chart_data->questions_res[$q]) && in_array($chart_data->questions_res[$q], $student->correct_answer_questions))
				    					{

						    			 echo "<td class='text-success' style='background-color:#97d881 !important; color:#ffffff !important;'>Correct</td>";
						    			}

						    			elseif ( isset($chart_data->questions_res[$q]) && in_array($chart_data->questions_res[$q], $student->wrong_answer_questions))
						    			{
						    				echo "<td class='text-danger' style='background-color:#a94442 !important; color:#ffffff !important;'>Wrong</td>";
						    			}

						    			 else {
						    			 	echo "<td class='text-warning' style='background-color:#8a6d3b !important; color:#ffffff !important;'>Skipped</td>";
						    			 }
				    			 }
				    			 ?>
				    		</tr>
				    		<?php $q++; } ?>
				    	</table>

				    	<!-- <canvas id="batch_report_graph"></canvas> -->

				    </div>

				    <div class="row">
				    	<div class="col-md-12">
				    	<?php
				    		$sno = 1;
				    		foreach( $chart_data->questions_res as $question_id) {

				    			$newid = 'myChart'.$question_id;

				    			$question_data = \App\QuestionBank::where('id',$question_id)->first();

				    			$correct_count = 0;

				    			$wrong_count = 0;

				    			$skipped_count = 0;

				    			// dd($question_id);

				    			foreach ($chart_data->quiz_attempted_students as $student) {



				    				if( !empty($student->correct_answer_questions) && in_array($question_id, $student->correct_answer_questions) ) {
				    					$correct_count++;
				    				}
				    				elseif( !empty($student->wrong_answer_questions) && in_array($question_id,$student->wrong_answer_questions) ) {
				    					$wrong_count++;
				    				}
				    				else {
				    					$skipped_count++;
				    				}

				    			}
				    			// dd($skipped_count);

				    			$summary_dataset = [ $correct_count,  $wrong_count, $skipped_count];

				    			$summary_labels = [getPhrase('correct'), getPhrase('wrong'), getPhrase('skipped')];
				    			$summary_bgcolor = ['#97d881', '#a94442', '#8a6d3b'];
				    			$summary_border_color = ['#97d881', '#a94442', '#8a6d3b'];

				    			$summary_dataset_labels = [getPhrase('total')];

				    			/*
				    			for ($i=0; $i <= 2; $i++) {

					                $color_number = rand(0,999);;

					                $summary_bgcolor[] = getColor('',$color_number);

					                $summary_border_color[] = getColor('background', $color_number);

					              }
								*/
				    			$question_stats = (object) array(
                                        'labels'            => $summary_labels,
                                        'dataset'           => $summary_dataset,
                                        'dataset_label'     => $summary_dataset_labels,
                                        'bgcolor'           => $summary_bgcolor,
                                        'border_color'      => $summary_border_color
                                        );

				    			// dd($question_stats);
				    		 ?>


				    		<div class="col-md-6">
				    		<a id="question_{{$sno}}">{{$sno++}}.{!!$question_data->question!!}</a>
				    		<canvas id="{{$newid}}" style="width:50px !important;height:50px !important;"></canvas>
				    		</div>


				    @if(isset($question_stats))
							<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.js"></script>
							<script type="text/javascript">
								<?php
							    // $cdata = $question_stats;

							    //     $cdata = $question_stats;
							// dd($question_stats);
							    $dataset = $question_stats;

							    // dd($dataset->labels);
							?>
							var ctx = document.getElementById("{{$newid}}").getContext("2d");

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
							            text: ''
							        },
							        }
							});
							</script>
@endif

				    	<?php } ?>
				    </div>
				    </div>
				  </div>
				</div>
				@else
				<div class="col-md-12">
					<div class="panel-heading">Exams for batch <font color="green">{{$batch->name}}</font></div>
					<div class="panel-body" >
						<ul>
						@forelse( $batch_exams as $exam )
							<li><a href="{{route('batches.report', ['batch_id' => $batch_id, 'exam_slug' => $exam->slug])}}">{{$exam->title}}</a></li>
						@empty
							<li>No Exams</li>
						@endforelse
						</ul>
					</div>
				</div>
				@endif

			</div>
</div>
		<!-- /#page-wrapper -->

@stop

@section('footer_scripts')
@if(isset($question_stats))
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.js"></script>
<script type="text/javascript">
	<?php
    // $cdata = $question_stats;

    //     $cdata = $question_stats;
// dd($question_stats);
    $dataset = $question_stats;

    // dd($dataset->labels);
?>
var ctx = document.getElementById("{{$newid}}").getContext("2d");

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
            text: ''
        },
        }
});
</script>
@endif
@if( ! empty( $chart_data ) )
	@include('common.chart-stack', array('chart_data'=>$chart_data,'ids' =>array('batch_report_graph'), 'scale'=>TRUE))
@endif

@stop
