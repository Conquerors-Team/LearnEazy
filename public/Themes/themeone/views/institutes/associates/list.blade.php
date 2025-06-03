@extends('layouts.admin.adminlayout')
@section('header_scripts')
@stop
@section('content')


<div id="page-wrapper" ng-controller="associates">

			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							@if(checkRole(getUserGrade(3), 'institute_view'))
							<li><a href="{{URL_VIEW_INSTITUES}}">{{getPhrase('institutes')}}</a> </li>
							 @endif
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
	<div class="panel panel-custom col-md-9 col-lg-offset-1" ng-init="initAngData({{$institutes}},{{$associates}});">
					<div class="panel-heading">


				<h1>{{ucwords($record->institute_name)}} {{ $title }}</h1>
			</div>
			<div class="panel-body">

				{!! Form::open(array('url' => URL_ADD_ASSOCIATES, 'method' => 'POST', 'novalidate'=>'','name'=>'associates')) !!}

               <input type="hidden" name="institute_id" value="{{$record->id}}">
				<div class="row">



		 	<h4>{{getPhrase('institute_name')}}</h4>

		   <div ng-show="result_data.length>0" class="row">

		   <div class="col-sm-4 col-sm-offset-8">
		            <div class="input-group">
		                    <input type="text" ng-model="search" class="form-control input-lg" placeholder="{{getPhrase('search')}}" name="search" />
		                    <span class="input-group-btn">
		                        <button class="btn btn-primary btn-lg" type="button">
		                            <i class="glyphicon glyphicon-search"></i>
		                        </button>
		                    </span>
		                </div>
		        </div>
		   </div>

		   </div>


		 <div ng-repeat="institute in result_data | filter:search track by $index" class="row">

		 	<div class="col-md-6">

		 	    <input id="@{{institute.id}}" value="@{{institute.id}}" name="institutes_ids[@{{institute.id}}]" type="checkbox">

                  <label for="@{{institute.id}}">
                     <span class="fa-stack checkbox-button"><i class="mdi mdi-check active"></i></span>
                  <span>@{{institute.institute_name}}</span>

               </label>



		   </div>

		   <div class="col-md-6">

              <input id="@{{institute.user_id}}" value="@{{institute.id}}" name="is_twoway[@{{institute.id}}]" type="checkbox">

                  <label for="@{{institute.user_id}}">
                     <span class="fa-stack checkbox-button"><i class="mdi mdi-check active"></i></span>
                  <span>{{getPhrase('is_twoway')}}</span>

               </label>



		   </div>

		 </div>

		<div class="buttons text-center">

			<button class="btn btn-lg btn-success button">{{ getPhrase('update') }}</button>

		 </div>

          {!! Form::close() !!}

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->


		</div>
@endsection


@section('footer_scripts')

@include('institutes.scripts.associate-js-scripts')

@stop
