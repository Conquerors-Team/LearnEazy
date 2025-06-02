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
                            @if(canDo('exams_create'))
							<a href="{{URL_QUIZ_ADD}}/live_quizzes" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
                            @endif
						</div>

						<h1>{{ $title }}</h1>

					</div>

					<div class="panel-body packages">

						<div>

						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">

							<thead>

								<tr>
									<th>{{ getPhrase('title')}}</th>

									<th>{{ getPhrase('duration')}}</th>

									<th>{{ getPhrase('display_type')}}</th>

									<!-- <th>{{ getPhrase('is_paid')}}</th> -->

									<th>{{ getPhrase('total_marks')}}</th>

									<th>{{ getPhrase('exam_type')}}</th>

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

 @include('common.datatables', array('route'=>URL_QUIZ_GETLIST . '/live_quizzes', 'route_as_url' => TRUE))

 @include('common.deletescript', array('route'=>URL_QUIZ_DELETE))
@stop

