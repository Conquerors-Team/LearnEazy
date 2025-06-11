@extends($layout)
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
<link href="{{CSS}}bootstrap-datepicker.css" rel="stylesheet">
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
							@if(canDo('onlineclasses_create') && ! isStudent())
							<a href="{{URL_ADMIN_ONLINECLASSES_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>&nbsp;|&nbsp;
							<a href="{{route('onlineclasses.import')}}" class="btn  btn-primary button" >{{ getPhrase('import')}}</a>
							@endif
						</div>

						<h1>{{ $title }}</h1>
					</div>

					<div class="panel-body packages">
						@include('onlineclasses.search-form')
						<div>
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>

									<th>{{ getPhrase('date')}}</th>
									<th>{{ getPhrase('time')}}</th>
									<th>{{ getPhrase('class')}}</th>
									<th>{{ getPhrase('batch')}}</th>
									<th>{{ getPhrase('subject')}}</th>

									<th>{{ getPhrase('topic')}}</th>
									<!-- @if(isInstitute() || isStudent() )
									<th>{{ getPhrase('faculty')}}</th>
									@endif -->
									<th>{{ getPhrase('url')}}</th>
									@if(checkRole(getUserGrade(2)))
									<th>{{ getPhrase('action')}}</th>
									@endif
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

@if(isInstitute() || isStudent())
 @include('common.datatables', array('route'=>URL_ADMIN_ONLINECLASSES_GETLIST, 'route_as_url' => TRUE, 'search_columns' => ['class_title' => request('class_title'), 'batch_id' => request('batch_id'), 'from_date' => request('from_date'), 'to_date' => request('to_date'), 'faculty_id' => request('faculty_id'), 'subject_id' => request('subject_id')],
 'table_columns' => ['valid_from', 'class_time', 'title', 'batch_id', 'subject_id', 'topic','url']))
@elseif(checkRole(getUserGrade(2)))
 @include('common.datatables', array('route'=>URL_ADMIN_ONLINECLASSES_GETLIST, 'route_as_url' => TRUE, 'search_columns' => ['class_title' => request('class_title'), 'batch_id' => request('batch_id'), 'from_date' => request('from_date'), 'to_date' => request('to_date'), 'faculty_id' => request('faculty_id'), 'subject_id' => request('subject_id')],
 'table_columns' => ['valid_from', 'class_time', 'title', 'batch_id', 'subject_id', 'topic','url','action']))
@else
@include('common.datatables', array('route'=>URL_ADMIN_ONLINECLASSES_GETLIST, 'route_as_url' => TRUE, 'search_columns' => ['class_title' => request('class_title'), 'batch_id' => request('batch_id'), 'from_date' => request('from_date'), 'to_date' => request('to_date'), 'faculty_id' => request('faculty_id'), 'subject_id' => request('subject_id')],
 'table_columns' => ['valid_from', 'class_time', 'title', 'batch_id', 'subject_id', 'topic','url']))
@endif
 @include('common.deletescript', array('route'=>URL_ADMIN_ONLINECLASSES_DELETE))

<script src="{{JS}}datepicker.min.js"></script>
<script type="text/javascript">
	$('.datepicker1').datepicker({
        autoclose: true,
        /*startDate: "0d",*/
        format: '{{getDateFormat()}}',
    });

    function showInstructions(url) {
	  width = screen.availWidth;
	  height = screen.availHeight;
	  window.open(url,'_blank',"height="+height+",width="+width+", toolbar=no, top=0,left=0,location=no,menubar=no, directories=no, status=no, menubar=no, scrollbars=yes,resizable=no");

		runner();
	}

	function runner()
	{
		url = localStorage.getItem('redirect_url');
	    if(url) {
	      localStorage.clear();
	       window.location = url;
	    }
	    setTimeout(function() {
	          runner();
	    }, 500);

	}
</script>
@stop
