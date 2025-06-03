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
							<li>{{ $title }}teirgtjegi</li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">

						<div class="pull-right messages-buttons">
							<div class="btn-group">
				              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				                <i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Role&nbsp;<span class="caret"></span>
				              </button>
				              <ul class="dropdown-menu">
				                <li><a href="{{URL_USERS}}/user/student">Students</a></li>
				                <li><a href="{{URL_USERS}}/user/faculty">Faculty</a></li>
				                @if(checkRole(getUserGrade(1)))
				                <li><a href="{{URL_USERS}}/user/institute">Institute</a></li>
				                @endif
				              </ul>
				            </div>

				            <div class="btn-group">
				            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				                <i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Class&nbsp;<span class="caret"></span>
				              </button>
				              <ul class="dropdown-menu">
				                <?php
				                $classes = \App\StudentClass::where('institute_id', adminInstituteId())->get();
				                ?>
				                @foreach( $classes as $class )
				                	<li><a href="{{URL_USERS}}/class/{{$class->slug}}">{{$class->name}}</a></li>
				                @endforeach
				              </ul>
				            </div>

				            <div class="btn-group">
				            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				                <i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Batches&nbsp;<span class="caret"></span>
				              </button>
				              <ul class="dropdown-menu">
				                <?php
				                $batches = \App\Batch::where('institute_id', adminInstituteId())->get();
				                ?>
				                @foreach( $batches as $batch )
				                	<li><a href="{{URL_USERS}}/batch/{{$batch->id}}">{{$batch->name}}</a></li>
				                @endforeach
				              </ul>
				            </div>


				        	<a href="{{URL_USERS}}" class="btn btn-primary button" >{{ getPhrase('All')}}</a>
							<a href="{{URL_USERS_IMPORT}}" class="btn btn-primary button" >{{ getPhrase('import_excel')}}</a>
							<a href="{{URL_USERS_ADD}}" class="btn btn-primary button" >{{ getPhrase('add_user')}}</a>
							</div>
						</div>
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div>
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>{{ getPhrase('image')}}</th>
								 	<th>{{ getPhrase('name')}}</th>
								 	<th>{{ getPhrase('institute')}}</th>
									<th>{{ getPhrase('email')}}</th>
									<th>{{ getPhrase('role')}}</th>
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
 @include('common.datatables', array('route' =>URL_USERS_GETLIST . $type . '/' . $type_id, 'route_as_url' => true, 'table_columns' => ['image','name','institute_id', 'email', 'display_name', 'action']))
 @include('common.deletescript', array('route'=>URL_USERS_DELETE))
@stop
