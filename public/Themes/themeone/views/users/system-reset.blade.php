 @extends($layout)
 @section('header_scripts')

@stop
@section('content')
<div id="page-wrapper">
	<div class="container-fluid">
	<!-- Page Heading -->
	<div class="row">
	<div class="col-lg-12">
	<ol class="breadcrumb">
	<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i> </a> </li>

	<li><a href="javascript:void(0);">{{ $title }}</a> </li>
	</ol>
	</div>

	</div>

	<div class="panel panel-custom">
	<div class="panel-heading">
		<h1>System Reset</h1>
	</div>
	<div class="panel-body">
		<div class="alert alert-warning" role="alert">
          <span style="font-size: 18px;">System reset warning<br/>
          Please make sure you should know what you are doing. It will delete all the data. Are you sure you want to continue? </span>
        </div>

		{!! Form::open(['method' => 'POST', 'route' => ['users.system-reset'],'class'=>'formvalidation', 'id' => 'frmReset']) !!}

		{!! Form::submit('Reset', ['class' => 'btn btn-danger wave-effect systemReset buttons', 'name' => 'reset']) !!}
        <a href="{{ route('user.dashboard') }}" class="btn btn-warning buttons">Cancel</a>
        {!! Form::close() !!}
	</div>
	</div>

	</div>
	</div>
@endsection
