@extends($layout)

 @section('custom_div')

 <div ng-controller="prepareQuestions">

 @stop

@section('content')

<div id="page-wrapper">

			<div class="container-fluid" ng-init="recordData({{$record->is_paid}});">

				<!-- Page Heading -->

				<div class="row">

					<div class="col-lg-12">

						<ol class="breadcrumb">

							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>

							<li><a href="{{URL_EXAM_SERIES}}">{{ getPhrase('exam_series')}}</a></li>

							<li class="active">{{isset($title) ? $title : ''}}</li>

						</ol>

					</div>

				</div>

					@include('errors.errors')

				<?php $settings = ($record) ? $settings : ''; ?>

				<div class="panel panel-custom" ng-init="initAngData({{$settings}});" >

					<div class="panel-heading">

						<div class="pull-right messages-buttons">

							<a href="{{URL_EXAM_SERIES}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>

						</div>

					<h1>{{ $title }}  </h1>

					</div>

					<div class="panel-body" >




					<?php $button_name = getPhrase('create'); ?>

					 		@include('common.search-form', ['url' => url()->current(), 'show_content_types' => 'FALSE'])

					 		{!! Form::open(array('url' => URL_EXAM_SERIES_UPDATE_SERIES.$record->slug, 'method' => 'POST', 'name'=>'htmlform ','id'=>'htmlform', 'novalidate'=>'')) !!}
					 		<div class="row">


								<div class="col-md-12">

							<div  >


								<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">

							<thead>

								<tr>

									@if(checkRole(getUserGrade(3)) || shareData('share_questions') )
                                        <th>{{ getPhrase('institute')}}</th>
                                    @endif

									<th>{{ getPhrase('title')}}</th>

									<th>{{ getPhrase('duration')}}</th>

									<th>{{ getPhrase('category')}}</th>

									<!-- <th>{{ getPhrase('is_paid')}}</th> -->

									<th>{{ getPhrase('total_marks')}}</th>

									<th>{{ getPhrase('exam_type')}}</th>

									<th>{{ getPhrase('action')}}</th>

								</tr>

							</thead>
						</table>
						<input type="hidden" name="series_id" value="{{$record->id}}">


								</div>

								<button class="btn btn-lg btn-success button pull-right" type="submit">Add</button>


					 			</div>





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

<!-- @include('exams.examseries.scripts.js-scripts') -->

 @include('common.filter-scripts')

 @include('common.datatables', array('route'=>'exams.quiz.getlist', 'search_columns' => ['callfrom' => 'examseries', 'series_id' => $record->id, 'subject' => request('subject_id'),'chapter' => request('chapter_id'),'topic' => request('topic_id'),'sub_topic' => request('sub_topic_id'), 'content_type' => request('content_type'), 'institute' => request('institute_id')]))

@stop



@section('custom_div_end')

 </div>

@stop