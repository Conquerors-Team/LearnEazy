@extends($layout)

@section('header_scripts')

@stop

@section('content')

<div id="page-wrapper" ng-controller="batchesController">

			<div class="container-fluid">

				<!-- Page Heading -->

				<div class="row">

					<div class="col-lg-12">

						<ol class="breadcrumb">

							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
						  @if(canDo('institute_batch_access'))
              <li><a href="{{URL_BATCHS}}">{{ getPhrase('batches')}}</a></li>
              @endif

							<li>{{ $title }}</li>

						</ol>

					</div>

				</div>

				<!-- /.row -->

				<div class="panel panel-custom" ng-init="setPreSelectedData('{{$record->user_id}}','{{$record->institute_id}}','{{$record->id}}')">

					<div class="panel-heading">

						<h1>{{ $title }}</h1>

					</div>


	{!! Form::open(array('url' => URL_UPDATE_STUDENT_TO_BATCH, 'method' => 'POST', 'name'=>'htmlform ','id'=>'htmlform', 'novalidate'=>'')) !!}

					<div class="panel-body instruction">

                     <div>


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
					 <br>

   <div ng-if="result_data.length!=0">
   <div>

    <div class="row vertical-scroll">



    <table class="table table-bordered" style="border-collapse: collapse;">
    <thead>

        <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('sno')}}</b></th>
        <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('name')}}</b></th>
        <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('email')}}</b></th>
        @if(checkRole(getUserGrade(9)))
        <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('Remove_all')}}</b>
        	<input type="checkbox" name="add_all" value="{{$record->id}}" style="display: block;" ng-click="toggleSelect()">
        </th>
        @endif


    </thead>
    <tbody>

    <tr ng-repeat="user in result_data | filter:search track by $index">


             <td style="border:1px solid #000;text-align: center;" >@{{$index+1}}</td>
            <td style="border:1px solid #000;text-align: center;"><a target="_blank" href="{{URL_USER_DETAILS}}@{{user.slug}}">@{{user.name}}</a></td>

        <td style="border:1px solid #000;text-align: center;">@{{user.email}}</td>
        @if(checkRole(getUserGrade(9)))
        <td style="border:1px solid #000;text-align: center;">
        	 <input id="@{{user.id}}" value="@{{user.id}}" name="user_ids[]" type="checkbox" style="display: block;"
        	 ng-model="user.mycheck" >
        </td>
        @endif






    </tr>

    </tbody>
    </table>
</div>
 </div>
 @if(checkRole(getUserGrade(9)))
	<button ng-if="result_data.length!= 0" class="btn btn-primary pull-right" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">{{getPhrase('remove_students')}}</button>
  @endif

<br>
  </div>
<div ng-if="result_data.length == 0 " class="text-center" >{{getPhrase('no_data_available')}}</div>


					 </div>



					</div>

			<input type="hidden" name="batch_id" value="{{$record->id}}">

					{!! Form::close() !!}


				</div>

			</div>

			<!-- /.container-fluid -->




<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm" style="width: 600px;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" align="center">{{getPhrase('remove_students_from_batch')}}</h4>
      </div>
      <div class="modal-body">
        <h4 align="center">{{getPhrase('are_you_sure_to_remove_students_from_this_batch')}}</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-right" ng-click="printIt()">{{getPhrase('yes')}}</button>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{getPhrase('no')}}</button>
      </div>
    </div>

  </div>
</div>

		</div>

@endsection





@section('footer_scripts')

   @include('batches.scripts.js-scripts-1')
@stop

