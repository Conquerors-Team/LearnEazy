@extends($layout)
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')


<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							<a href="{{route('onlineclasses.index')}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>&nbsp;|&nbsp;
							<a href="{{route('class.attendence', ['slug' => $onlinecalss->slug])}}" class="btn  btn-primary button" >{{ getPhrase('attendance')}}</a>
						</div>
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div>
							<b>Details:</b>
							<p>url: {{$onlinecalss->url}}</p>

							<p>Faculty: {{$onlinecalss->createdby->name}}</p>
							<p>Class time: {{date('h:i A', strtotime($onlinecalss->class_time))}}</p>
							<p>Subject: {{$onlinecalss->subject->subject_title}}</p>
							<p>Topic: {{$onlinecalss->topic}}</p>
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>{{ getPhrase('name')}}</th>
									<th>{{ getPhrase('class')}}</th>
									<th>{{ getPhrase('course')}}</th>
								</tr>
							</thead>

						</table>
						</div>

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
@endsection


@section('footer_scripts')

 @include('common.datatables', array('route'=> route('class.absent.list', ['slug' => $onlinecalss->slug]), 'route_as_url' => 'TRUE'))

@stop
