@extends($layout)

 @section('custom_div')

 <div ng-controller="prepareQuestions">

 @stop

@section('content')

<div id="page-wrapper">

			<div class="container-fluid">

				<!-- Page Heading -->

				<div class="row">

					<div class="col-lg-12">

						<ol class="breadcrumb">

							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>

							<li><a href="{{URL_LMS_SERIES}}">{{ getPhrase('lms_series')}}</a></li>

							<li class="active">{{isset($title) ? $title : ''}}</li>

						</ol>

					</div>

				</div>

					@include('errors.errors')

				<?php $settings = ($record) ? $settings : ''; ?>

				<div class="panel panel-custom" ng-init="initAngData({{$settings}});" >

					<div class="panel-heading">

						<div class="pull-right messages-buttons">

							<a href="{{URL_LMS_SERIES}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>

						</div>

					<h1>{{ $title }}  </h1>

					</div>

					<div class="panel-body" >

					<?php $button_name = getPhrase('create'); ?>

					 		<div class="row">









{!! Form::open(array('url' => URL_LMS_SERIES_UPDATE_SERIES_EXAMS . $record->slug, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'',  'name'=>"registrationForm")) !!}
								<div class="col-md-12">

							<div ng-if="examSeries!=''" class="vertical-scroll" >



								<h4 ng-if="categoryItems.length>0" class="text-success">{{getPhrase('total_items')}}: @{{ categoryItems.length}} </h4>



								<table

								  class="table table-hover">



									<th>{{getPhrase('title')}}</th>

									<th>{{getPhrase('code')}}</th>

									<th>{{getPhrase('type')}}</th>





									<th>{{getPhrase('quiz')}}</th>

									<?php
									$quizzes = \App\Quiz::where('institute_id', adminInstituteId())->get()->pluck('title', 'id')->prepend('Select quiz', '')->toArray();
									?>

									@forelse($series as $item)
										<tr>
											<td>{{$item->title}}</td>
											<td>{{$item->code}}</td>
											<td>{{$item->content_type}}</td>
											<td>
												{{Form::select('quizzes['.$item->lmscontent_id.']', $quizzes, null, ['class'=>'form-control'
												]) }}
											</td>
										</tr>
									@empty

									@endforelse


								</table>

								</div>


								<div class="buttons text-center">

							<button class="btn btn-lg btn-success button" type="submit">Update</button>

						</div>


					 			</div>


{!! Form::close() !!}


					 		</div>



					</div>



				</div>

			</div>

			<!-- /.container-fluid -->

		</div>

		<!-- /#page-wrapper -->

@stop

@section('footer_scripts')

@include('lms.lmsseries.scripts.js-scripts')

@stop



@section('custom_div_end')

 </div>

@stop