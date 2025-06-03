@extends($layout)
@section('content')
<style>
	.wrapper{
	  width:60%;
	  display:block;
	  overflow:hidden;
	  margin:0 auto;
	  padding: 60px 50px;
	  background:#fff;
	  border-radius:4px;
	}

	canvas{
	  background:#fff;
	  height:400px;
	}



  </style>
  <div id="page-wrapper">
			<div class="container-fluid">
			<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							 <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li>{{ $title}}</li>
						</ol>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="panel-heading">Group details of  <font color="green">{{$group->title}}</font></div>
					<div class="panel-body" >
						@if( isset($record) )
						<div class="row">
							<div class="col-md-12">
							<div class="white_bgcurve coursesList">
							<h5>Subjects</h5>
							<div class="white_bgcurve" style="clear:both; ">
								@foreach( $record as $subject => $series)
								 <div class="col-md-3">
								 	<div class="item-image">
								 		<strong>{{$subject}}</strong>
								 		<ul>
								 		@foreach( $series as $ser)
								 			<li><a href="{{URL_STUDENT_LMS_SERIES_VIEW.$ser->slug}}" target="_blank">{{$ser->title}}</a></li>
								 		@endforeach
								 	</ul>
									</div>
								 </div>
								 @endforeach
								</div>
							</div>
						</div>
					</div>
					@else
					 <p>No LMS series assigned to this group..!
						@endif
				</div>
			</div>
		</div>
	</div>
</div>

@stop

@section('footer_scripts')
@if( ! empty( $chart_data ) )
	@include('common.chart-stack', array('chart_data'=>$chart_data,'ids' =>array('batch_report_graph'), 'scale'=>TRUE))
@endif

@stop