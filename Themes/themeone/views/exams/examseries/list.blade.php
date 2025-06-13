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
							@if(canDo('exam_series_create'))
							<a href="{{URL_EXAM_SERIES_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
							@endif
						</div>
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div>
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>

									@if(checkRole(getUserGrade(3)) || shareData())
                                        <th>{{ getPhrase('institute')}}</th>
                                    @endif

									<th>{{ getPhrase('title')}}</th>
									<th>{{ getPhrase('image')}}</th>
									<th>{{ getPhrase('is_paid')}}</th>
									<th>{{ getPhrase('cost')}}</th>
									<th>{{ getPhrase('validity')}}</th>
									<th>{{ getPhrase('total_exams')}}</th>
									<th>{{ getPhrase('total_questions')}}</th>

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

@if(checkRole(getUserGrade(3)) || shareData())
 @include('common.datatables', array('route'=>URL_EXAM_SERIES_AJAXLIST, 'route_as_url' => TRUE,'table_columns' => ['institute_id','title','image','is_paid','cost','validity','total_exams','total_questions','action']))
@else
@include('common.datatables', array('route'=>URL_EXAM_SERIES_AJAXLIST, 'route_as_url' => TRUE,'table_columns' => ['title','image','is_paid','cost','validity','total_exams','total_questions','action']))
 @include('common.deletescript', array('route'=>URL_EXAM_SERIES_DELETE))
@endif
@stop
