@extends($layout)
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">

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
							<li><a href="/"><i class="mdi mdi-home"></i></a> </li>
							<li><a href="{{route('studentpaidcontent.index')}}">{{ getPhrase('paid_content')}}</a></li>
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

<div class="panel panel-custom" ng-init="initAngData({{$settings}});" >
 <!-- <div class="panel panel-custom col-lg-8 col-lg-offset-2"> -->

				 <div class="panel-heading"> <div class="pull-right messages-buttons"> <a href="{{route('studentpaidcontent.index')}}" class="btn btn-primary button">{{ getPhrase('list')}}</a> </div><h1>{{ $title }}  </h1></div>
				 <div class="panel-body">
				<!-- {{ Form::model($record,
										array('route' => ['studentpaidcontent.series', $record->id],
										'method'=>'post', 'files' => true, 'name'=>'formLms ', 'novalidate'=>'')) }} -->
				<fieldset class="form-group">
					{{ Form::label('title', getphrase('title')) }}
					<span class="text-red">*</span>
					{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('enter_course_name'),
						'ng-model'=>'title',
						'ng-pattern' => getRegexPattern('name'),
						'ng-minlength' => '2',
						'ng-maxlength' => '60',
						'required'=> 'true',
						'ng-class'=>'{"has-error": formCategories.title.$touched && formCategories.title.$invalid}',
						'readonly' => 'true'
						)) }}
						<div class="validation-error" ng-messages="formCategories.title.$error" >
						{!! getValidationMessage()!!}
						{!! getValidationMessage('minlength')!!}
						{!! getValidationMessage('maxlength')!!}
						{!! getValidationMessage('pattern')!!}
					</div>
				</fieldset>
				<fieldset class="form-group col-md-3">
					{{ Form::label('year', getphrase('year')) }}
					<span class="text-red">*</span>
					<?php
					$years = ['' => 'Please select'];
					for($y = date('Y'); $y >= 1990; $y-- ) {
						$years[ $y ] = $y;
					}
					?>
					{{Form::select('year', $years, null, ['class'=>'form-control', "id"=>"year", "ng-model" => "year",
					'placeholder' => 'Select', 'ng-change'=>'yearChanged(year)', 'id' => 'year'])}}
					</fieldset>

					<fieldset class="form-group col-md-3">
							{{ Form::label('chapter_id', getphrase('competitive_type')) }}
							<span class="text-red">*</span>
							<?php
							$competitive_types = ['' => 'Please select'];

								$competitive_types = \App\CompetitiveExamTypes::all()->pluck('title', 'id')->prepend('Please select', '');
							?>
							{{Form::select('competitive_exam_type_id', $competitive_types, null, ['class'=>'form-control','ng-model' => "competitive_exam_type_id",'ng-change'=>'competitiveExamTypeChanged(competitive_exam_type_id)', 'id' => 'competitive_exam_type_id'])}}
					</fieldset>
					<!-- <fieldset class="form-group col-md-6">
					{{ Form::label('subject', getphrase('subjects')) }}
					<span class="text-red">*</span>
					{{Form::select('subject', $subjects, null, ['class'=>'form-control', 'ng-model' => 'subject_id',
					'placeholder' => 'Select', 'ng-change'=>'subjectChanged(subject_id)', 'id' => 'subject_id' ])}}
				</fieldset> -->

							<!-- <fieldset class="form-group col-md-6">
							{{ Form::label('chapter_id', getphrase('chapter')) }} <span class="text-red">*</span>
							<select class='form-control' name="chapter_id" id="chapter_id" ng-change="getChaptersTopics()" ng-model="chapter_id">
								<option ng-repeat="item in chapters" value="@{{item.id}}">
							    	@{{item.text}}
							    </option>
							</select>
							</fieldset> -->

							<!-- <fieldset class="form-group col-md-6">
							{{ Form::label('topic_id', getphrase('topic')) }} <span class="text-red">*</span>
							<select class='form-control' name="topic_id" id="topic_id" ng-model="topic_id" ng-change="getSubTopics()">
								<option ng-repeat="item in topics" value="@{{item.id}}">
							    	@{{item.text}}
							    </option>
							</select>
							</fieldset> -->

							<!-- <fieldset class="form-group col-md-6">
							{{ Form::label('sub_topic_id', getphrase('sub_topic')) }}
							<select class='form-control' name="sub_topic_id" id="sub_topic_id" ng-model="sub_topic_id" ng-change="getContents()">
								<option ng-repeat="item in sub_topics" value="@{{item.id}}">
							    	@{{item.text}}
							    </option>
							</select>
							</fieldset> -->




					<div class="col-md-12">

							<div ng-if="examSeries!=''" class="vertical-scroll" >



								<h4 ng-if="categoryItems.length>0" class="text-success">{{getPhrase('total_items')}}: @{{ categoryItems.length}} </h4>



								<table

								  class="table table-hover">



									<th>{{getPhrase('title')}}</th>

									<th>{{getPhrase('year')}}</th>

									<!-- <th>{{getPhrase('chapter')}}</th>

										<th>{{getPhrase('topic')}}</th> -->



									<th>{{getPhrase('action')}}</th>



									<tr ng-repeat="item in categoryItems | filter : {subject_title: subject_title} | filter:search_term  track by $index">



										<td

										title="@{{item.title}}" >

										@{{item.title}}

										</td>

										<td>@{{item.year}}</td>

										<!-- <td>@{{item.chapter_name}}</td>
										<td>@{{item.topic_name}}</td>
 -->

										<td><a



										ng-click="addToBag(item);" class="btn btn-primary" >{{getPhrase('add')}}</a>



										  </td>



									</tr>

								</table>

								</div>





					 			</div>





<!-- {!! Form::close() !!} -->
</div>


			</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->

@stop
@section('footer_scripts')
@include('package.studentpaidcontent.update-previousyear-tests.js-scripts')
  <script src="{{JS}}select2.js"></script>
<script>
      $('.select2').select2({
       placeholder: "Please select",
    });

    </script>
@stop
