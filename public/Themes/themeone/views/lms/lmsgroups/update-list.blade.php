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

							<li><a href="{{URL_LMS_GROUPS}}">{{ getPhrase('lms_groups')}}</a></li>

							<li class="active">{{isset($title) ? $title : ''}}</li>

						</ol>

					</div>

				</div>

					@include('errors.errors')

				<?php $settings = ($record) ? $settings : ''; ?>

				<div class="panel panel-custom" ng-init="initAngData({{$settings}});" >

					<div class="panel-heading">

						<div class="pull-right messages-buttons">

							<a href="{{URL_LMS_GROUPS}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>

						</div>

					<h1>{{ $title }}  </h1>

					</div>

					<div class="panel-body" >

					<?php $button_name = getPhrase('create'); ?>

					 		<div class="row">

							<fieldset class="form-group col-md-6">
								{{ Form::label('subject', getphrase('subjects')) }}
								<span class="text-red">*</span>
								{{Form::select('subject', $subjects, null, ['class'=>'form-control', 'ng-model' => 'subject_id',
								'placeholder' => 'Select', 'ng-change'=>'subjectChanged(subject_id)', 'id' => 'subject_id' ])}}
							</fieldset>

							<fieldset class="form-group col-md-6">
							{{ Form::label('chapter_id', getphrase('chapter')) }} <span class="text-red">*</span>
							<select class='form-control' name="chapter_id" id="chapter_id" ng-change="getChaptersTopics()" ng-model="chapter_id">
								<option ng-repeat="item in chapters" value="@{{item.id}}">
							    	@{{item.text}}
							    </option>
							</select>
							</fieldset>

							<fieldset class="form-group col-md-6">
							{{ Form::label('topic_id', getphrase('topic')) }} <span class="text-red">*</span>
							<select class='form-control' name="topic_id" id="topic_id" ng-model="topic_id" ng-change="getContents()">
								<option ng-repeat="item in topics" value="@{{item.id}}">
							    	@{{item.text}}
							    </option>
							</select>
							</fieldset>



								<div class="col-md-12">

							<div ng-if="examSeries!=''" class="vertical-scroll" >



								<h4 ng-if="categoryItems.length>0" class="text-success">{{getPhrase('total_items')}}: @{{ categoryItems.length}} </h4>



								<table

								  class="table table-hover">



									<th>{{getPhrase('title')}}</th>

									<th>{{getPhrase('code')}}</th>

									<th>{{getPhrase('type')}}</th>





									<th>{{getPhrase('action')}}</th>



									<tr ng-repeat="item in categoryItems | filter : {content_type: content_type} | filter:search_term  track by $index">



										<td

										title="@{{item.title}}" >

										@{{item.title}}

										</td>

										<td>@{{item.code}}</td>

										<td>@{{item.content_type}}</td>

										{{-- <td><img src="{{IMAGE_PATH_UPLOAD_LMS_CONTENTS}}@{{item.image}}" height="50" width="50" /> --}}</td>

										<td><a



										ng-click="addToBag(item);" class="btn btn-primary" >{{getPhrase('add')}}</a>



										  </td>



									</tr>

								</table>

								</div>





					 			</div>





					 		</div>



					</div>



				</div>

			</div>

			<!-- /.container-fluid -->

		</div>

		<!-- /#page-wrapper -->

@stop

@section('footer_scripts')

@include('lms.lmsgroups.scripts.js-scripts')

@stop



@section('custom_div_end')

 </div>

@stop