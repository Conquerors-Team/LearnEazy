@extends($layout)
<link href="{{CSS}}bootstrap-datepicker.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">

@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="/"><i class="mdi mdi-home"></i></a> </li>
						<!-- 	@if(checkRole(getUserGrade(2), 'coupon_codes')) -->
							<li><a href="{{URL_PACKAGES}}">{{ getPhrase('packages')}}</a></li>
							<!-- @endif -->
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

				<div class="panel panel-custom col-lg-8 col-lg-offset-2">
					<div class="panel-heading">
					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body" >

					<?php /* ?>
					<div>
						<div class="alert alert-danger">
					        @if( $user->plan_until == '')
					        <span>Free trial over. Please choose package.</span>
					        @else
					        <span>
					        	@if( $user->free_trial_days_left > 0)
					        	<span>Your free trial expires in {{$user.free_trial_days_left}} {{str_plural('day', $user.free_trial_days_left)}}
					        		Choose plan
					        	</span>
					        	@else
					        	<span>Your subscripton expired. Please choose package
					        	</span>
					        	@endif
					        </span>
					        @endif
					    </div>
					    <div class="row">
					    <div style="margin-left: 50px;">
						        <?php
						        $packages = \App\Package::where('package_for', 'institute')->where('cost', '>', 0)->where('status', 'active')->get();
						        ?>
						        @forelse( $packages as $package)
						        <div class="col-md-4">
					                <div class="row">
					                	<div class="row" style="height: 10px;">
						            		<h4>{{$package->title}}</h4>
						            	</div><br>

						            	<div class="row" style="height: 10px; ">
						            		<b>{{$package->cost}} {{$package->duration}} {{$package->duration_type}}</b>
						            	</div>
									</div><br>
						            <div class="row" style="padding: 15px;">
						            <hr />
						            {{$package->description}}
						            <hr />
					                </div>
					                <a class="btn btn-lg btn-primary" href="{{route('payments.checkout', ['type' => 'package', 'slug' => $package->slug])}}">Pay &nbsp;{{getCurrencyCode() . ' ' . $package->cost}}</a>
						        </div>
						        @empty
						        	No Packages founnd
						        @endforelse

					    </div>

					    </div>
					 </div>
					 <?php */ ?>

					 {!! Form::open(array('url' => route('package_renew.request'), 'method' => 'POST', 'files' => true,'name'=>'formQuiz ', 'novalidate'=>'')) !!}

					 	<div class="row">
					 	<fieldset class="form-group col-md-12">
							{{ Form::label('message', getphrase('message')) }}
							<span class="text-red">*</span>
							{{ Form::textarea('message', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('message'),
								'ng-model'=>'message',
								'ng-pattern'=>getRegexPattern('name'),
								'required'=> 'true',
								'ng-class'=>'{"has-error": formQuiz.message.$touched && formQuiz.message.$invalid}',
								'ng-minlength' => '4',
								'ng-maxlength' => '60',
								)) }}
							<div class="validation-error" ng-messages="formQuiz.message.$error" >
		    					{!! getValidationMessage()!!}
		    					{!! getValidationMessage('pattern')!!}
		    					{!! getValidationMessage('minlength')!!}
		    					{!! getValidationMessage('maxlength')!!}
							</div>
						</fieldset>
					</div>

						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button"
							ng-disabled='!formQuiz.$valid'>Submit Request</button>
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
 	@include('common.validations');
@stop

