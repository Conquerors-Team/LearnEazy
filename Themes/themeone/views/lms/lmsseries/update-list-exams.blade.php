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

							<div class="vertical-scroll" >

								<table

								  class="table table-hover">

								  <thead>
								  <tr>
									<th>{{getPhrase('title')}}</th>

									<th>{{getPhrase('code')}}</th>

									<th>{{getPhrase('type')}}</th>

									<th>{{getPhrase('quiz')}}</th>
								</tr>
								</thead>

								<tbody>

									<?php
									if(checkRole(getUserGrade(3))){
										$quizzes = \App\Quiz::select('quizzes.*')
										->join('quizzes_subjects AS qs', 'qs.quiz_id', '=', 'quizzes.id')
										->where('qs.subject_id', $record->subject_id)
										->where('quizzes.category_id', QUIZTYPE_LMS)
										->get()->pluck('title', 'id')->prepend('Select quiz', '')->toArray();
									} elseif(shareData('share_exams')){
										$quizzes = \App\Quiz::select('quizzes.*')
										->join('quizzes_subjects AS qs', 'qs.quiz_id', '=', 'quizzes.id')
										->where('qs.subject_id', $record->subject_id)
										->where('quizzes.category_id', QUIZTYPE_LMS)
										->whereIn('institute_id', [adminInstituteId(), OWNER_INSTITUTE_ID])->get()->pluck('title', 'id')->prepend('Select quiz', '')->toArray();
									}else {
										$quizzes = \App\Quiz::select('quizzes.*')
										->join('quizzes_subjects AS qs', 'qs.quiz_id', '=', 'quizzes.id')
										->where('qs.subject_id', $record->subject_id)
										->where('quizzes.category_id', QUIZTYPE_LMS)
										->where('institute_id', adminInstituteId())
										->get()->pluck('title', 'id')->prepend('Select quiz', '')->toArray();
									}

									?>

									@forelse($series as $item)
										<tr>
											<td>{{$item->title}}</td>
											<td>{{$item->code}}</td>
											<td>{{$item->content_type}}</td>
											<td>
												<?php
												$quiz_id = null;
												if ( $item->quiz_id ) {
													$quiz_id = $item->quiz_id;
												}
												?>
												{{Form::select('quizzes['.$item->lmscontent_id.']', $quizzes, $quiz_id, ['class'=>'form-control'
												]) }}
												<input type="hidden" name="sort_order[]" value="{{$item->lmscontent_id}}">
											</td>
										</tr>
									@empty

									@endforelse

								</tbody>
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
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
	$('tbody').sortable();
</script>

@stop



@section('custom_div_end')

 </div>

@stop