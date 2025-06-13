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
							<li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>
								
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						
						<div class="pull-right messages-buttons">
							 
							<a href="{{URL_LMS_CATEGORIES_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
							 
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
									<th>{{ getPhrase('category')}}</th>
									<th>{{ getPhrase('image')}}</th>
									<th>{{ getPhrase('description')}}</th>
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
 @include('common.datatables', array('route'=>'lmscategories.dataTable','table_columns' => ['institute_id', 'category', 'image', 'description',  'action']))
@else
@include('common.datatables', array('route'=>'lmscategories.dataTable','table_columns' => [ 'category', 'image', 'description',  'action']))
@endif 
@include('common.deletescript', array('route'=>URL_LMS_CATEGORIES_DELETE))

@stop
