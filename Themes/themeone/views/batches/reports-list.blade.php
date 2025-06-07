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



					<div class="panel-body packages">
						<div >
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Date</th>
					 				<th>Batch name</th>
					 				<!-- @if( ! isFaculty() )
					 				<th>Faculty</th>
					 				@endif -->
					 				<th>Exam</th>
					 				<th>Reports</th>
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
 @include('common.datatables', array('route'=>route('batch.get_reports'), 'route_as_url' => TRUE,'table_columns'=>['created_at','name','user_id','action']))
@stop

