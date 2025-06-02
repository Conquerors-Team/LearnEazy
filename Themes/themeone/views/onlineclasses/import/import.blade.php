@extends($layout)

@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="/"><i class="mdi mdi-home"></i></a> </li>
							@if(canDo('onlineclasses_access'))
							<li><a href="{{URL_ADMIN_ONLINECLASSES}}">{{ getPhrase('Onlineclasses')}}</a></li>
							 @endif
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

	<div class="panel panel-custom col-lg-6 col-lg-offset-3">
					<div class="panel-heading">

						@if(canDo('onlineclasses_access'))
						<div class="pull-right messages-buttons">
							<a href="{{URL_ADMIN_ONLINECLASSES}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
						</div>
						@endif

					<h1>{{ $title }}  </h1>
					</div>

					<div class="panel-body text-center">

					<a href="{{DOWNLOAD_LINK_ONLINECLASSES_IMPORT_EXCEL}}" class="btn btn-info">{{getPhrase('download_template')}}
					</a>

					<?php $button_name = getPhrase('upload'); ?>

					{!! Form::open(array('route' => ['onlineclasses.import_post'], 'method' => 'POST', 'novalidate'=>'','name'=>'formUsers ', 'files'=>'true')) !!}
					<fieldset >
					<label class="margintop30">Upload Excel</label>
					{!! Form::file('excel', array('class'=>'form-control','id'=>'excel_input', 'accept'=>'.xls,.xlsx', 'required'=>'true')) !!}
					</fieldset>

						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button"
							ng-disabled='!formUsers.$valid'>{{ $button_name }}</button>
						</div>


					{!! Form::close() !!}
					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
@endsection

@section('footer_scripts')
 @include('common.validations')
   @include('common.alertify')
 <script>
 	var file = document.getElementById('excel_input');

file.onchange = function(e){
    var ext = this.value.match(/\.([^\.]+)$/)[1];
    switch(ext)
    {
        case 'xls':
        case 'xlsx':

            break;
        default:
              alertify.error("{{getPhrase('file_type_not_allowed')}}");
            this.value='';
    }
};
 </script>
@stop