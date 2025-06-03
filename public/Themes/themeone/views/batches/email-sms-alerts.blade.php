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
							<?php
							if(canDo('email_alerts') && canDo('sms_alerts')) {
								$alerts = \App\Alert::where('status', 'active')->orderBy('name')->get();
							} elseif(canDo('sms_alerts')) {
								$alerts = \App\Alert::where('status', 'active')->where('type', 'SMS')->orderBy('name')->get();
							} else {
								$alerts = \App\Alert::where('status', 'active')->where('type', 'email')->orderBy('name')->get();
							}
							?>
							@forelse( $alerts as $alert)
							<fieldset class="form-group col-lg-6">
								{{ Form::label('name', $alert->name . ' ('.$alert->type.')') }}
							</fieldset>
							@if( $alert->select_type == 'batch')
							<fieldset class="form-group col-lg-6">
								{{ Form::label('batches', getphrase('batches')) }}
								<button type="button" class="btn btn-primary btn-xs" id="selectbtn-batches_{{$alert->id}}">
							        {{ getPhrase('select_all') }}
							    </button>
							    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-batches_{{$alert->id}}">
							        {{ getPhrase('deselect_all') }}
							    </button>
								<span class="text-red">*</span>
								<?php
								$institute_id   = adminInstituteId();
								$batches = \App\Batch::where('status', 'active')->where('institute_id', $institute_id)->get()->pluck('name', 'id')->toArray();
								$selected_batches = \App\AlertEnabled::where('alert_id', $alert->id)->get()->pluck('batch_id')->toArray();
								//print_r($alert->alerts_enabled);
								?>
								{{Form::select('batches['.$alert->id.'][]', $batches, $selected_batches, ['class'=>'form-control select2', 'name'=>'batches['.$alert->id.'][]', 'multiple'=>'true', 'id' => 'batches_' .$alert->id, 'required' => 'true'])}}
								<div class="validation-error" ng-messages="frmBatches.batches.$error" >
			    					{!! getValidationMessage()!!}
								</div>
							</fieldset>
							@elseif( $alert->select_type == 'text')
							<fieldset class="form-group col-lg-6">
								{{ Form::label('enter', getphrase('enter')) }} in minutes
								<span class="text-red">*</span>
								<?php
								$selected_value = \App\AlertEnabled::where('alert_id', $alert->id)->where('institute_id', $institute_id)->first();
								if ( $selected_value ) {
									$selected_value = $selected_value->batch_id;
								}
								//print_r($alert->alerts_enabled);
								?>
								{{Form::text('batches['.$alert->id.'][]', $selected_value, ['class'=>'form-control', 'name'=>'batches['.$alert->id.'][]', 'id' => 'batches_' .$alert->id, 'required' => 'true'])}}
								<div class="validation-error" ng-messages="frmBatches.batches.$error" >
			    					{!! getValidationMessage()!!}
								</div>
							</fieldset>
							@else
							<fieldset class="form-group col-lg-6">
								{{ Form::label('select', getphrase('select')) }}
								<span class="text-red">*</span>
								<?php
								$options = [
									'no' => 'No',
									'yes' => 'Yes',
								];
								$selected_options = \App\AlertEnabled::where('alert_id', $alert->id)->where('institute_id', $institute_id)->get()->pluck('batch_id')->toArray();
								//print_r($alert->alerts_enabled);
								?>
								{{Form::select('batches['.$alert->id.'][]', $options, $selected_options, ['class'=>'form-control select2', 'name'=>'batches['.$alert->id.'][]', 'id' => 'batches_' .$alert->id, 'required' => 'true'])}}
								<div class="validation-error" ng-messages="frmBatches.batches.$error" >
			    					{!! getValidationMessage()!!}
								</div>
							</fieldset>
							@endif
							@empty
							<p>No Alerts</p>
							@endforelse
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

 	@forelse( $alerts as $alert)
 	$("#selectbtn-batches_{{$alert->id}}").click(function(){
        $("#batches_{{$alert->id}} > option").prop("selected","selected");
        $("#batches_{{$alert->id}}").trigger("change");
    });
    $("#deselectbtn-batches_{{$alert->id}}").click(function(){
        $("#batches_{{$alert->id}} > option").prop("selected","");
        $("#batches_{{$alert->id}}").trigger("change");
    });
    @empty
    @endforelse

 </script>

@stop

