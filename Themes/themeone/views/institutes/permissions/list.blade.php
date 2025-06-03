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

						<a href="{{URL_PERMISSIONS_ADD}}" class="btn  btn-primary button" >{{ getPhrase('add_class')}}</a>

						</div>


						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div>
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									@if(checkRole(getUserGrade(2)))
                                    <th>{{ getPhrase('title')}}</th>
                                    @endif
									<th>{{ getPhrase('module')}}</th>
									<th>{{ getPhrase('status')}}</th>
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
  @include('common.datatables', array('route'=>URL_PERMISSIONS_GETDATA,'route_as_url'=>TRUE,'table_columns' => ['title','module','status','action']))
 @include('common.deletescript', array('route'=>URL_PERMISSIONS_DELETE ))
@stop
