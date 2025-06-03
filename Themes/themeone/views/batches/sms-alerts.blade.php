@extends($layout)

 @section('header_scripts')
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">
 @endsection

@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="/"><i class="mdi mdi-home"></i></a> </li>
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

				<div class="panel panel-custom col-lg-12" >
					<div class="panel-heading">
					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body" >
					<?php $button_name = getPhrase('update'); ?>
					{{ Form::model($record,
						array('url' => url()->current(),
						'method'=>'post', 'files' => true, 'name'=>'frmBatches', 'novalidate'=>'')) }}
						<div class="row">
							<fieldset class="form-group">
								{{ Form::label('batches', getphrase('batches')) }}
								<button type="button" class="btn btn-primary btn-xs" id="selectbtn-batches">
							        {{ getPhrase('select_all') }}
							    </button>
							    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-batches">
							        {{ getPhrase('deselect_all') }}
							    </button>
								<span class="text-red">*</span>
								<?php
								$institute_id   = adminInstituteId();
								$batches = \App\Batch::where('status', 'active')->where('institute_id', $institute_id)->get()->pluck('name', 'id')->toArray();
								$selected_batches = \App\Batch::where('status', 'active')->where('institute_id', $institute_id)->where('enable_sms_alerts', 'yes')->get()->pluck('id')->toArray();
								// print_r($selected_batches);
								?>
								{{Form::select('batches[]', $batches, $selected_batches, ['class'=>'form-control select2', 'name'=>'batches[]', 'multiple'=>'true', 'id' => 'batches', 'required' => 'true'])}}
								<div class="validation-error" ng-messages="frmBatches.batches.$error" >
			    					{!! getValidationMessage()!!}
								</div>
							</fieldset>
					</div>

					<div class="buttons text-center">

							<button class="btn btn-lg btn-success button"

							ng-disabled='!frmBatches.$valid'>{{ $button_name }}</button>

						</div>

					{!! Form::close() !!}
					</div>

				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
@stop

@section('footer_scripts')

 @include('common.validations')
<script src="{{JS}}select2.js"></script>

 <script>
 	  $('.select2').select2({
       placeholder: "Please select",
    });

 	  $("#selectbtn-batches").click(function(){
        $("#batches > option").prop("selected","selected");
        $("#batches").trigger("change");
    });
    $("#deselectbtn-batches").click(function(){
        $("#batches > option").prop("selected","");
        $("#batches").trigger("change");
    });

 </script>

@stop

