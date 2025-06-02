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

				<!-- @include('common.search-form', ['url' => url()->current(), 'show_content_types' => 'FALSE']) -->

				<!-- /.row -->

				<div class="panel panel-custom">

					<div class="panel-heading">

						<h1>{{ $title }}</h1>

					</div>


	{!! Form::open(array('url' => url()->current(), 'method' => 'POST', 'name'=>'htmlform ','id'=>'htmlform', 'novalidate'=>'')) !!}





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

<!-- <submit type="submit" class="btn btn-primary pull-right" >{{getPhrase('add')}}</submit> -->
<button class="btn btn-lg btn-success button" type="submit">Add</button>
<br>
  </div>

					 </div>

					</div>

			<input type="hidden" name="class_id" value="{{$record->id}}">
			<input type="hidden" name="batch_id" value="{{$record->batch_id}}">

					{!! Form::close() !!}


				</div>

			</div>

			<!-- /.container-fluid -->

		</div>

@endsection





@section('footer_scripts')
   @include('common.filter-scripts')

   @include('common.datatables', array('route'=>'exams.quiz.getlist', 'search_columns' => ['type' => 'live_quizzes', 'callfrom' => 'live_quizzes', 'class_id' => $record->id, 'subject' => request('subject_id'),'chapter' => request('chapter_id'),'topic' => request('topic_id'),'sub_topic' => request('sub_topic_id'),'content_type' => request('content_type'), 'institute' => request('institute_id')]))
@stop

