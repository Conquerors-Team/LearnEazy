@inject('request', 'Illuminate\Http\Request')

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

					@if(checkRole(getUserGrade(1)))
						@include('common.search-form', ['url' => URL_QUESTION_BANK_MANAGEMENT, 'show_content_types' => 'FALSE', 'show_chapters' => 'FALSE', 'show_topics' => 'FALSE', 'show_sub_topics' => 'FALSE','show_batch_assigned'=> 'FALSE'])
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
									<th>{{ getPhrase('lms_quiz')}}</th>
									<th>{{ getPhrase('exams')}}</th>
									<th>{{ getPhrase('test_series')}}</th>
									<th>{{ getPhrase('Total')}}</th>
								</tr>
							</thead>

							<tbody>
								<?php
								$grand_total = 0;
								$lms_quiz_questions_grand_total = 0;
								$exam_questions_grand_total = 0;
								$test_series_questions_grand_total = 0;
								?>

								@foreach($chapters as $chapter)
										<?php
											$topics = App\Topic::where('chapter_id',$chapter->id)->where('parent_id', 0)->orderBy('sort_order')->get();
										?>
								<tr>


									<td><!--CHAPTERS AND TOPICS-->
										<p>
											@if($chapter->subject)
											<b>SUBJECT: {{$chapter->subject->subject_title}}({{$chapter->subject->id}})</b><br>
											@endif
											<b>CHAPTER: {{$chapter->chapter_name}}({{$chapter->id}})</b></p>

										<p><b>TOPICS:</b></p>
										<ol>
										@foreach($topics as $topic)
											<li>{{$topic->topic_name}}({{$topic->id}})</li>
											<?php
											$subtopics = App\Topic::where('chapter_id',$chapter->id)->where('parent_id', $topic->id)->orderBy('sort_order')->get();
											?>
											@if( $subtopics->count() > 0 )
												<ol>
													@foreach($subtopics as $subtopic)
														<li>{{$subtopic->topic_name}}({{$subtopic->id}})</li>
													@endforeach
												</ol>
											@endif
										@endforeach
										</ol>
									</td>
									<td><!-- LMS QUIZ-->
										<p>
										<?php
										$lms_quiz_questions_count = App\QuestionBank::where('question_bank_type_id', QUESTIONSBANK_TYPE_LMSQUIZ)->where('chapter_id',$chapter->id);
										if ( $request->get('subject_id') > 0 ) {
											$lms_quiz_questions_count->where('subject_id', $request->get('subject_id'));
										}
										if ( $request->get('question_category_id') > 0 ) {
											$lms_quiz_questions_count->where('questionbank_category_id', $request->get('question_category_id'));
										}
										if ( $request->get('difficulty_level') ) {
											$lms_quiz_questions_count->where('difficulty_level', $request->get('difficulty_level'));
										}
										$lms_quiz_questions_count = $lms_quiz_questions_count->count();
										?>
										{{$lms_quiz_questions_count}}</p>
										<p>&nbsp;</p>
										<ul style="list-style: none;">
										@foreach($topics as $topic)
											<?php
											$exam_series_topics_count = App\QuestionBank::where('question_bank_type_id', QUESTIONSBANK_TYPE_LMSQUIZ)->where('topic_id', $topic->id);
											if ( $request->get('subject_id') > 0 ) {
												$exam_series_topics_count->where('subject_id', $request->get('subject_id'));
											}
											if ( $request->get('question_category_id') > 0 ) {
												$exam_series_topics_count->where('questionbank_category_id', $request->get('question_category_id'));
											}
											if ( $request->get('difficulty_level') ) {
												$exam_series_topics_count->where('difficulty_level', $request->get('difficulty_level'));
											}
											$exam_series_topics_count = $exam_series_topics_count->count();
											?>
											<li>{{$exam_series_topics_count}}</li>
											<?php
											$subtopics = App\Topic::where('chapter_id',$chapter->id)->where('parent_id', $topic->id)->orderBy('sort_order')->get();
											?>
											@if( $subtopics->count() > 0 )
												@foreach($subtopics as $subtopic)
													<li>
														<?php
														$exam_subtopics_count = App\QuestionBank::where('question_bank_type_id', QUESTIONSBANK_TYPE_LMSQUIZ)->where('topic_id', $subtopic->id);
														if ( $request->get('subject_id') > 0 ) {
															$exam_subtopics_count->where('subject_id', $request->get('subject_id'));
														}
														if ( $request->get('question_category_id') > 0 ) {
															$exam_subtopics_count->where('questionbank_category_id', $request->get('question_category_id'));
														}
														if ( $request->get('difficulty_level') ) {
															$exam_subtopics_count->where('difficulty_level', $request->get('difficulty_level'));
														}
														$exam_subtopics_count = $exam_subtopics_count->count();
														?>
														{{$exam_subtopics_count}}
													</li>
												@endforeach
											@endif
										@endforeach
										</ul>
									</td>
									<td><!--EXAMS-->
										<p>
										<?php
										$exam_questions_count = App\QuestionBank::where('question_bank_type_id', QUESTIONSBANK_TYPE_EXAM)->where('chapter_id',$chapter->id);
										if ( $request->get('subject_id') > 0 ) {
											$exam_questions_count->where('subject_id', $request->get('subject_id'));
										}
										if ( $request->get('question_category_id') > 0 ) {
											$exam_questions_count->where('questionbank_category_id', $request->get('question_category_id'));
										}
										if ( $request->get('difficulty_level') ) {
											$exam_questions_count->where('difficulty_level', $request->get('difficulty_level'));
										}
										$exam_questions_count = $exam_questions_count->count();
										?>
										{{$exam_questions_count}}</p>
										<p>&nbsp;</p>
										<ul style="list-style: none;">
										@foreach($topics as $topic)
										<?php
										$exam_series_topics_count = App\QuestionBank::where('question_bank_type_id', QUESTIONSBANK_TYPE_EXAM)->where('topic_id', $topic->id);
										if ( $request->get('subject_id') > 0 ) {
											$exam_series_topics_count->where('subject_id', $request->get('subject_id'));
										}
										if ( $request->get('question_category_id') > 0 ) {
											$exam_series_topics_count->where('questionbank_category_id', $request->get('question_category_id'));
										}
										if ( $request->get('difficulty_level') ) {
											$exam_series_topics_count->where('difficulty_level', $request->get('difficulty_level'));
										}
										$exam_series_topics_count = $exam_series_topics_count->count();
										?>
										<li>{{$exam_series_topics_count}}</li>
										<?php
										$subtopics = App\Topic::where('chapter_id',$chapter->id)->where('parent_id', $topic->id)->orderBy('sort_order')->get();
										?>
										@if( $subtopics->count() > 0 )
											@foreach($subtopics as $subtopic)
												<li>
													<?php
													$exam_subtopics_count = App\QuestionBank::where('question_bank_type_id', QUESTIONSBANK_TYPE_EXAM)->where('topic_id', $subtopic->id);
													if ( $request->get('subject_id') > 0 ) {
														$exam_subtopics_count->where('subject_id', $request->get('subject_id'));
													}
													if ( $request->get('question_category_id') > 0 ) {
														$exam_subtopics_count->where('questionbank_category_id', $request->get('question_category_id'));
													}
													if ( $request->get('difficulty_level') ) {
														$exam_subtopics_count->where('difficulty_level', $request->get('difficulty_level'));
													}
													$exam_subtopics_count = $exam_subtopics_count->count();
													?>
													{{$exam_subtopics_count}}</li>
											@endforeach
										@endif
										@endforeach
										</ul>

									</td>
									<td><!--TEST SERIES-->
										<p>
										<?php
										$test_series_questions_count = App\QuestionBank::where('question_bank_type_id', QUESTIONSBANK_TYPE_TESTSERIES)->where('chapter_id',$chapter->id);
										if ( $request->get('subject_id') > 0 ) {
											$test_series_questions_count->where('subject_id', $request->get('subject_id'));
										}
										if ( $request->get('question_category_id') > 0 ) {
											$test_series_questions_count->where('questionbank_category_id', $request->get('question_category_id'));
										}
										if ( $request->get('difficulty_level') ) {
											$test_series_questions_count->where('difficulty_level', $request->get('difficulty_level'));
										}
										$test_series_questions_count = $test_series_questions_count->count();
										?>
										{{$test_series_questions_count}}</p>
										<p>&nbsp;</p>
										<ul style="list-style: none;">
										@foreach($topics as $topic)
											<?php
												$exam_series_topics_count = App\QuestionBank::where('question_bank_type_id', QUESTIONSBANK_TYPE_TESTSERIES)->where('topic_id', $topic->id);
												if ( $request->get('subject_id') > 0 ) {
													$exam_series_topics_count->where('subject_id', $request->get('subject_id'));
												}
												if ( $request->get('question_category_id') > 0 ) {
													$exam_series_topics_count->where('questionbank_category_id', $request->get('question_category_id'));
												}
												if ( $request->get('difficulty_level') ) {
													$exam_series_topics_count->where('difficulty_level', $request->get('difficulty_level'));
												}
												$exam_series_topics_count = $exam_series_topics_count->count();
											?>
											<li>{{$exam_series_topics_count}}</li>
											<?php
											$subtopics = App\Topic::where('chapter_id',$chapter->id)->where('parent_id', $topic->id)->orderBy('sort_order')->get();
											?>
											@if( $subtopics->count() > 0 )
												@foreach($subtopics as $subtopic)
													<li>
														<?php
														$exam_subtopics_count = App\QuestionBank::where('question_bank_type_id', QUESTIONSBANK_TYPE_TESTSERIES)->where('topic_id', $subtopic->id);
														if ( $request->get('subject_id') > 0 ) {
															$exam_subtopics_count->where('subject_id', $request->get('subject_id'));
														}
														if ( $request->get('question_category_id') > 0 ) {
															$exam_subtopics_count->where('questionbank_category_id', $request->get('question_category_id'));
														}
														if ( $request->get('difficulty_level') ) {
															$exam_subtopics_count->where('difficulty_level', $request->get('difficulty_level'));
														}
														$exam_subtopics_count = $exam_subtopics_count->count();
														?>
														{{$exam_subtopics_count}}</li>
												@endforeach
											@endif
										@endforeach
										</ul>
									</td>

									<td><!--TOTAL-->
									<?php
									$grand_total = $grand_total + ($lms_quiz_questions_count + $exam_questions_count + $test_series_questions_count);

									$lms_quiz_questions_grand_total += $lms_quiz_questions_count;
									$exam_questions_grand_total += $exam_questions_count;
									$test_series_questions_grand_total += $test_series_questions_count;
									?>
									{{$lms_quiz_questions_count + $exam_questions_count + $test_series_questions_count }}</td>
								</tr>
									@endforeach
								<tr>
									<td><h3>Grand Total</h3></td>
									<td><h3>{{$lms_quiz_questions_grand_total}}</h3></td>
									<td><h3>{{$exam_questions_grand_total}}</h3></td>
									<td><h3>{{$test_series_questions_grand_total}}</h3></td>
									<td><h3>{{$grand_total}}</h3></td>
								</tr>
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
