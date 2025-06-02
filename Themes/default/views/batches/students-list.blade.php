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
							<li><a href="{{URL_BATCHS}}">{{ getPhrase('batches')}}</a></li>

							<li>{{ $title }}</li>

						</ol>

					</div>

				</div>

				<!-- /.row -->

				<div class="panel panel-custom" ng-init="setPreSelectedData('{{$record->user_id}}','{{$record->institute_id}}','{{$record->id}}')">

					<div class="panel-heading">

						<h1>{{ $title }}</h1>

					</div>


	{!! Form::open(array('url' => URL_STORE_STUDENT_TO_BATCH, 'method' => 'POST', 'name'=>'htmlform ','id'=>'htmlform', 'novalidate'=>'')) !!}

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
        <th style="border:1px solid #000;text-align: center;"><b>{{getPhrase('select_all')}}</b>
        	<input type="checkbox" name="add_all" value="{{$record->id}}" style="display: block;" ng-click="toggleSelect()">
        </th>
        
       
    </thead>
    <tbody>
   
    <tr ng-repeat="user in result_data | filter:search track by $index">

    
             <td style="border:1px solid #000;text-align: center;" >@{{$index+1}}</td>
            <td style="border:1px solid #000;text-align: center;"><a target="_blank" href="{{URL_USER_DETAILS}}@{{user.slug}}">@{{user.name}}</a></td>
        
        <td style="border:1px solid #000;text-align: center;">@{{user.email}}</td>
        <td style="border:1px solid #000;text-align: center;">
        	 <input id="@{{user.id}}" value="@{{user.id}}" name="user_ids[@{{user.id}}]" type="checkbox" style="display: block;"
        	 ng-model="user.mycheck" >
        </td>

       
      
        
        
          
    </tr> 
 
    </tbody>
    </table>
</div>
 </div>

<a ng-if="result_data.length!= 0" class="btn btn-primary pull-right" ng-click="printIt()" >{{getPhrase('add')}}</a>

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

		</div>

@endsection

 



@section('footer_scripts')
  
   @include('batches.scripts.js-scripts')
@stop

