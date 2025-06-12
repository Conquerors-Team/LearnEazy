@extends($layout)

@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
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

				@include('common.search-form', ['url' => URL_BATCHS_ADD_LMS . $record->id, 'show_content_types' => 'FALSE'])

				<!-- /.row -->

				<div class="panel panel-custom">

					<div class="panel-heading">
                        <div class="pull-right messages-buttons">
							 @if(canDo('lms_series_create'))
							<a href="{{URL_LMS_SERIES_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create lms series')}}</a>
							 @endif
						</div>

						<h1>{{ $title }}</h1>

					</div>


	{!! Form::open(array('url' => URL_BATCHS_ADD_LMS . $record->id, 'method' => 'POST', 'name'=>'htmlform ','id'=>'htmlform', 'novalidate'=>'')) !!}



        <div class="panel-body packages">

			<div >

		<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
			<thead>
				<tr>
					@if(checkRole(getUserGrade(3)) || shareData('share_lms_series'))
		                <th>{{ getPhrase('institute')}}</th>
		            @endif
					<th>{{ getPhrase('title')}}</th>
					<th>{{ getPhrase('image')}}</th>
					<th>{{ getPhrase('total_items')}}</th>
					<th>{{ getPhrase('type')}}</th>
					<th>{{ getPhrase('action')}}</th>
				</tr>
			</thead>

		</table>
	</div>
</div>

 </div>

<!-- <submit type="submit" class="btn btn-primary pull-right" >{{getPhrase('add')}}</submit> -->
<button class="btn btn-lg btn-success button" type="submit">Add</button>
<br>
  </div>



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
   @include('common.filter-scripts')
@if(checkRole(getUserGrade(3)) || shareData('share_lms_series'))
   @include('common.datatables', array('route'=>'lmsseries.dataTable', 'search_columns' => ['callfrom' => 'batch', 'batch_id' => $record->id, 'subject' => request('subject_id'),'chapter' => request('chapter_id'),'topic' => request('topic_id'),'sub_topic' => request('sub_topic_id'),'content_type' => request('content_type'), 'institute' => request('institute_id')],'table_columns' => ['institute_id','title','image','total_items','show_in_front','action']))
@else
@include('common.datatables', array('route'=>'lmsseries.dataTable', 'search_columns' => ['callfrom' => 'batch', 'batch_id' => $record->id, 'subject' => request('subject_id'),'chapter' => request('chapter_id'),'topic' => request('topic_id'),'sub_topic' => request('sub_topic_id'),'content_type' => request('content_type'), 'institute' => request('institute_id')],'table_columns' => ['title','image','total_items','show_in_front','action']))
@endif
   @stop

