@extends($layout)
@section('header_scripts')

@stop
@section('content')


<div id="page-wrapper">
	<div class="container-fluid">

		<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>

					@if(checkRole(getUserGrade(2)))
						@include('common.search-form', ['url' => url()->current(), 'show_content_types' => 'FALSE', 'show_chapters' => 'FALSE', 'show_topics' => 'FALSE', 'show_sub_topics' => 'FALSE','show_batch_assigned'=> 'FALSE','show_question_actegory'=>'FALSE','show_difficulty_level'=>'FALSE'])
				    @endif


			<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div>
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>{{ getPhrase('chapters_and_topics')}}</th>
								</tr>
							</thead>

							<tbody>


								@foreach($chapters as $chapter)
										<?php
											$topics = App\Topic::where('chapter_id',$chapter->id)->get();
										?>

								<tr>


									<td>
										<p>Subject: {{$chapter->subject->subject_title}}({{$chapter->subject_id}})</p>
										<p>Chapter: {{$chapter->chapter_name}}({{$chapter->id}})</p>
										<p>Topics:</p>
										<ol>
										@foreach($topics as $topic)
											<li>{{$topic->topic_name}}({{$topic->id}})</li>
										@endforeach
										</ol>
									</td>

								</tr>
									@endforeach
							</tbody>



						</table>
						</div>

					</div>
				</div>

		</div>
	</div>

@endsection


@section('footer_scripts')
	@include('common.filter-scripts')
@stop
