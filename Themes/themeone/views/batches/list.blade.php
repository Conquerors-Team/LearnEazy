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


						@if(canDo('institute_batch_create'))
						<div class="pull-right messages-buttons">
							<a href="{{URL_BATCHS_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
						</div>
						@endif

						<h1>{{ $title }}</h1>

					</div>

					<div class="panel-body packages">
						<div >
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">

							<thead>
								<tr>

									<!-- @if(checkRole(getUserGrade(3)) || shareData('share_batches'))
                                        <th>{{ getPhrase('institute')}}</th>
                                    @endif -->

									<th>{{ getPhrase('name')}}</th>

									<th>Assign LMS</th>

									<th>Assign Notes</th>

									<th>Exam Report</th>

									<!-- <th>{{ getPhrase('total_seats')}}</th>

									<th>{{ getPhrase('booked_seats')}}</th>

									<th>{{ getPhrase('available_seats')}}</th> -->

									<th>{{ getPhrase('action')}}</th>



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



 @include('common.datatables', array('route'=>URL_BATCHS_GETLIST, 'route_as_url' => TRUE, 'table_columns' => ['name','start_date','capacity','end_date','action']))

 @include('common.deletescript', array('route'=>URL_BATCHS_DELETE))



@stop

