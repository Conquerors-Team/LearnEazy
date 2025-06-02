@extends($layout)
@section('content')
<?php $institute_id   = adminInstituteId(); ?>
<div id="page-wrapper">
			<div class="container-fluid">
			<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">

							<li><i class="fa fa-home"></i> {{ $title}}</li>
						</ol>
					</div>
				</div>

				 @if( isFaculty() )

				 <div class="row">

				 	<div class="row">
				 	<div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_BATCHS}}"><div class="state-icn bg-icon-blue"><i class="fa fa-sitemap"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">
				 						{{ Auth::user()->faculty_batches()->count()}}
				 					</h4>
								<a href="{{URL_BATCHS}}">{{ getPhrase('institute_batches')}}</a>
				 			</div>
				 		</div>
				 	</div>

				 	<div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_SUBJECTS}}"><div class="state-icn bg-icon-success"><i class="fa fa-book"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">
				 					<?php
									$faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
					      			$subjects = \App\Subject::whereIn('id', $faculty_subjects)->get();
				 					?>
				 					{{ $subjects->count()}}
				 					</h4>
								<a href="{{URL_SUBJECTS}}">{{ getPhrase('subjects')}}</a>
				 			</div>
				 		</div>
				 	</div>
				 </div>

				 <?php /*?>
				 <div class="row">
					<?php
				 	$faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
				 	// dd($faculty_subjects);
					$subjects = \App\Subject::whereIn('id', $faculty_subjects)->get();

					$records = \App\LmsGroup::join('lmsseries_lmsgroups','lmsseries_lmsgroups.lmsgroups_id','lmsgroups.id')
	                  ->join('lmsseries','lmsseries.id','lmsseries_lmsgroups.lmsseries_id')
	                  ->join('subjects','lmsseries.subject_id','subjects.id')
	                  ->whereIn('subjects.id',$faculty_subjects)
	                  ->get()
	                  ->groupBy( function( $entry ) {
	                  	return $entry->title;
	                  });
	                  // dd($records);
	                  ?>
	                  @if( isset($records) )
	                  @foreach( $records as $title => $record)
				 		<div class="col-md-2">
				 	<a href="{{URL_LMS_SERIES}}"><p style="padding: 20px; border: 1.5px solid green; border-radius: 5px">{{$title}} ({{count($record)}})</p>
				 </div>
				 @endforeach
				 @endif
				</div>
				<?php */ ?>

				<div class="col-md-12 col-sm-12 state-media box-ws">
			 		<h4 class="card-title1">Content Library</h4><a class="moreBtn" href="{{URL_LMS_GROUPS}}">View all</a>
			 		<?php
			 		$faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
			 		$lmsgroups = \App\LmsGroup::select(['lmsgroups.*'])->join('lmsseries_lmsgroups', 'lmsseries_lmsgroups.lmsgroups_id', '=', 'lmsgroups.id')->join('lmsseries', 'lmsseries.id', '=', 'lmsseries_lmsgroups.lmsseries_id')->whereIn('lmsseries.subject_id', $faculty_subjects)
			 		->where('lmsgroups.institute_id', $institute_id)
			 		->groupBy('lmsgroups_id')->orderBy('lmsgroups.updated_at', 'desc')->limit(5);
			 		?>
			 		<table class="table">
			 			<tr>
			 				<td>Title</td>
			 				<td>Count</td>
			 			</tr>
			 			@forelse( $lmsgroups->get() as $row)
			 			<tr>
			 				<td>{{$row->title}}</td>
			 				<td>
			 					<a href="{{route('lms-groups.show', ['slug' => $row->slug])}}" class="btn btn-lg btn-success button">{{\DB::table('lmsseries_lmsgroups')->where('lmsgroups_id', $row->id)->count()}}</a>
			 				</td>
			 			</tr>
			 			@empty
			 			<tr><td colspan="2" align="center">No Contents</td></tr>
			 			@endforelse
			 		</table>
			 	</div>



			 	<div class="col-md-12 col-sm-12 state-media box-ws">
			 		<h4 class="card-title1">Today's Schedule</h4>
			 		<?php
			 		$batches = getFacultyBatches();
			 		$onlineclasses = \App\Onlineclass::whereIn('batch_id', $batches)->whereNotNull('class_time')->whereNotNull('valid_from')->whereNotNull('valid_to')->whereRaw("'" . date('Y-m-d') . '\' BETWEEN DATE(valid_from) AND DATE(valid_to)')->where('created_by_id', \Auth::id());
			 		//print_r($onlineclasses->getBindings());
			 		//echo $onlineclasses->toSql();
			 		// echo getEloquentSqlWithBindings($onlineclasses);
			 		?>
			 		<table class="table">
			 			<tr>
			 				<td>Time</td>
			 				<td>Batch</td>
			 				<td>Class</td>
			 				<td>Topic</td>
			 				<td>Streaming Link</td>
			 				<td>Animations</td>
			 				<td>Faculty Notes</td>
			 				<td>Live Quiz</td>
			 			</tr>
			 			@forelse( $onlineclasses->get() as $row)
			 			<tr>
			 				<td>{{date('h:i A', strtotime($row->class_time))}}</td>
			 				<td>{{$row->batch->name}}</td>
			 				<td>{{$row->student_class->name}}</td>
			 				<td>{{$row->title}}</td>
			 				<td>
			 					<?php
			 					$class_duration = $row->class_duration;
							      if ( empty( $class_duration ) ) {
							        $class_duration = 50;
							      }
			 					?>
			 					@if ( $row->class_time < date('H:i:s', strtotime("-$class_duration minutes", strtotime(date('H:i:s'))) ) )
			 					<a href="javascript:void(0)" class="btn btn-lg btn-success button" disabled>Start</a>
			 					@else
			 					<a href="{{$row->url}}" target="_blank" class="btn btn-lg btn-success button">Start</a>
			 					@endif
			 				</td>
			 				<td>
			 					@if($row->lmsseries_id)
			 					<a href="{{URL_STUDENT_LMS_SERIES_VIEW.$row->lmsseries->slug}}" target="_blank">{{$row->lmsseries->title}}</a><p><a style="color:red;" href="{{route('onlineclass.lmsnotes', ['slug' => $row->slug])}}">Edit</a></p>
			 					@else
			 					<a style="color:red;" href="{{route('onlineclass.lmsnotes', ['slug' => $row->slug])}}">Assign</a>
			 					@endif
			 				</td>
			 				<td>
			 					@if($row->lmsnotes_id)
			 					<a href="{{route('lms.preview_notes', ['slug' => $row->lmsnotes->slug])}}" target="_blank">{{$row->lmsnotes->title}}</a><p><a style="color:red;" href="{{route('onlineclass.lmsnotes', ['slug' => $row->slug])}}">Edit</a></p>
			 					@else
			 					<a style="color:red;" href="{{route('onlineclass.lmsnotes', ['slug' => $row->slug])}}">Assign</a>
			 					@endif
			 				</td>
			 				<td>
			 					@if($row->live_quiz_id)
			 					<a href="{{URL_QUIZ_EDIT . '/' . $row->live_quiz->slug}}" target="_blank">{{$row->live_quiz->title}}</a>

			 					<?php
			 					$title = 'Pop Quiz';
			 					if ( $row->live_quiz_popstatus == 'yes' ) {
			 						$title = 'Un-Pop';
			 					}
			 					?>
			 					<a href="{{route('live-quiz.unpop', ['online_class_id' => $row->id])}}" class="btn btn-lg btn-success button">{{$title}}</a>
			 					<p><a style="color:red;" href="{{route('onlineclass.lmsnotes', ['slug' => $row->slug])}}">Edit</a></p>
			 					@else
			 					<a style="color:red;" href="{{route('onlineclass.lmsnotes', ['slug' => $row->slug])}}">Assign</a>
			 					@endif
			 				</td>
			 			</tr>
			 			@empty
			 			<tr><td colspan="7" align="center">No classes</td></tr>
			 			@endforelse
			 		</table>
			 	</div>

				 	<div class="col-md-6 col-sm-6 state-media box-ws">
				 		<h4 class="card-title1">Batch Reports</h4><a class="moreBtn" href="{{route('batch.reports')}}">View all</a>
				 		<?php
				 		$batches = Auth::user()->faculty_batches()->get()->pluck('id')->toArray();
				 		$institute_id   = adminInstituteId();
				 		/*
						$records = \App\Batch::join('batch_quizzes AS bq', 'bq.batch_id', '=', 'batches.id')
        				->join('quizzes AS q', 'q.id', '=', 'bq.quiz_id')
        				->join('quizresults AS qr', 'qr.quiz_id', '=', 'bq.quiz_id')
        				->select(['batches.name', 'q.title', 'batches.id', 'q.slug', 'qr.created_at'])
                        // ->where('q.record_updated_by', \Auth::id())
                        ->whereIn('batches.id', $batches)
						->groupBy(\DB::raw('date(qr.created_at)')) // Added by ADI
						->groupBy('batches.id') // Added by ADI
						->groupBy('q.id') // Added by ADI
                        ->limit(5);
                        */

                        /*
						$batch_records = collect(\App\Batch::join('batch_quizzes AS bq', 'bq.batch_id', '=', 'batches.id')
						->join('quizzes AS q', 'q.id', '=', 'bq.quiz_id')
						->join('quizresults AS qr', 'qr.quiz_id', '=', 'bq.quiz_id')
						->select(['qr.created_at', 'batches.name', 'q.title', 'batches.id', 'q.slug'])
						->where('q.institute_id', $institute_id)
						->whereIn('batches.id', $batches)
						->groupBy(\DB::raw('date(qr.created_at)')) // Added by ADI
						->groupBy('batches.id') // Added by ADI
						->groupBy('q.id') // Added by ADI
						->groupBy('q.id')
						->orderBy('qr.created_at', 'desc')->limit(5)->get());

						// Let us combine LMS quizzes.
                        $lmsquiz_records = collect(\App\Batch::join('batch_lmsseries AS bl', 'bl.batch_id', '=', 'batches.id')
        				->join('lmsseries_data AS lsd', 'lsd.lmsseries_id', '=', 'bl.lms_series_id')
        				->join('quizzes AS q', 'q.id', '=', 'lsd.quiz_id')
        				->join('quizresults AS qr', 'qr.quiz_id', '=', 'lsd.quiz_id')
        				->select(['batches.name', 'q.title', 'batches.id', 'q.slug', 'qr.created_at'])
                        // ->where('q.record_updated_by', \Auth::id())
                        ->whereIn('batches.id', $batches)
                        ->orderBy('qr.created_at', 'desc')
                        ->groupBy('qr.quiz_id')->limit(5)->get());

                        $records = $batch_records->merge($lmsquiz_records)->sortByDesc('created_at'); // Contains batch_records and lmsquiz_records.
                        */

                        $records = \App\Batch::join('batch_quizzes AS bq', 'bq.batch_id', '=', 'batches.id')
						->join('quizzes AS q', 'q.id', '=', 'bq.quiz_id')
						->join('quizresults AS qr', 'qr.quiz_id', '=', 'bq.quiz_id')

						->join('batch_students AS bs', function($join) {
							$join->on('bs.user_id', '=', 'qr.user_id');
							$join->on('bs.batch_id','=', 'batches.id');
						})

						->select(['qr.created_at', 'batches.name', 'q.title', 'batches.id', 'q.slug'])
						// ->where('q.institute_id', $institute_id)
						->whereIn('batches.id', $batches)
						->groupBy(\DB::raw('date(qr.created_at)')) // Added by ADI
						->groupBy('batches.id') // Added by ADI
						->groupBy('q.id') // Added by ADI
						->orderBy('qr.created_at', 'desc')->limit(5)->get();
				 		?>
				 		<table class="table">
				 			<tr>
				 				<td>Date</td>
				 				<td>Batch name</td>
				 				<td>Exam</td>
				 				<td>Reports</td>
				 			</tr>
				 			@forelse( $records as $row)
				 			<tr>
				 				<td>{{date('d-m-Y', strtotime($row->created_at))}}</td>
				 				<td>{{$row->name}}</td>
				 				<td>{{$row->title}}</td>
				 				<td><a href="{{url('batches/report/' . $row->id . '/' . $row->slug)}}" class="btn btn-lg btn-success button">View</a></td>
				 			</tr>
				 			@empty
				 			<tr><td colspan="3" align="center">No Exam results for batches</td></tr>
				 			@endforelse
				 		</table>
				 	</div>

				 	<div class="col-md-6 col-sm-6 state-media box-ws">
				 		<h4 class="card-title1">Attendance</h4><a class="moreBtn" href="{{route('onlineclasses.attendence')}}">View all</a>
				 		<?php
				 		$batches = Auth::user()->faculty_batches()->get()->pluck('id')->toArray();
        				$records = \App\Onlineclass::select(['onlineclasses.*', 'b.name'])
        				->join('batches AS b', 'b.id', '=', 'onlineclasses.batch_id')
        				->join('online_classes_attendence AS oca', 'oca.class_id', '=', 'onlineclasses.id')
        				->whereIn('batch_id', $batches)->whereNotNull('class_time')->whereNotNull('valid_from')->whereNotNull('valid_to')->where('created_by_id', \Auth::id())
        				->groupBy('oca.class_id')
        				->orderBy('oca.created_at', 'desc')
        				->limit(7);
				 		?>

				 		<table class="table1 table-responsive" style="margin:20px;">
				 			<tr>
				 				<td>Date</td>
				 				<td>Time</td>
				 				<td>Batch name</td>
				 				<td>Class title</td>

				 				<td>Attendance</td>
				 				<!-- <td>Absents</td> -->
				 			</tr>
				 			@forelse( $records->get() as $row)
				 			<tr>
				 				<?php
			 					$date = \App\OnlineclassAttendance::where('class_id', $row->id)->groupBy('student_id')->first();
			 					// dd($date);
			 					?>
				 				<td>{{date('d-m-Y', strtotime($date->created_at))}}</td>
				 				<td>{{date('h:i A', strtotime($row->class_time))}}</td>
				 				<td>{{$row->name}}</td>
				 				<td>{{$row->title}}</td>

				 				<td>
				 					<?php
				 					$count = \App\OnlineclassAttendance::where('class_id', $row->id)->groupBy('student_id')->get()->count();
				 					?>
				 					<a href="{{route('class.attendence', ['slug' => $row->slug])}}" class="btn btn-lg btn-success button">{{$count}}</a>
				 				</td>
				 					<?php /*?>
				 					<td>
				 		<?php
				 						$attendence = \App\Onlineclass::select(['oca.student_id', 'oca.created_at' ])
				        ->join('online_classes_attendence as oca', 'oca.class_id', '=', 'onlineclasses.id')
				        ->join('users', 'users.id', '=', 'oca.student_id')
				        ->where('onlineclasses.slug',$row->slug);
				        if(checkRole(getUserGrade(['student']))) {
				          $attendence->where('oca.student_id', \Auth::id());
				        } else {
				          $attendence->groupBy('oca.student_id');
				        }

				        $attendence = $attendence->orderBy('created_at', 'desc')->get()->pluck('student_id')->toArray();

				        $absent_count = \App\BatchStudent::select(['users.name', 'users.student_class_id', 'users.course_id'])->join('onlineclasses', 'onlineclasses.batch_id', '=', 'batch_students.batch_id')
				        ->join('users', 'users.id', '=', 'batch_students.user_id')
				        ->whereNotIn('batch_students.user_id', $attendence)
				        ->where('onlineclasses.slug', $row->slug)->get()
				        ->count();

				        // dd($count);
        ?>
				 					<a href="{{route('class.absent', ['slug' => $row->slug])}}" class="btn btn-lg btn-success button">{{$absent_count}}</a>
				 				</td>
				 				<?php */ ?>
				 			</tr>
				 			@empty
				 			<tr><td colspan="3" align="center">No Classes Found</td></tr>
				 			@endforelse
				 		</table>
				 	</div>

				 	<?php /* ?>
				 	<div class="col-md-6 col-sm-6 state-media box-ws">
				 		<h4 class="card-title1">Live Quizzes</h4>
				 		<?php
				 		$batches = Auth::user()->faculty_batches()->get()->pluck('id')->toArray();
        				$live_quizzes = \App\Quiz::select(['quizzes.*', 'batch_quizzes.date_time', 'batch_quizzes.batch_id', 'is_popquiz'])->join('batch_quizzes', 'batch_quizzes.quiz_id', '=', 'quizzes.id')
						 // ->where('batch_quizzes.is_popquiz', 'yes')
						 ->whereIn('batch_quizzes.batch_id', $batches)
						 ->whereDate('batch_quizzes.date_time', '=', \Carbon\Carbon::today()->toDateString())
						 ->where('quizzes.category_id', QUIZTYPE_LIVEQUIZ)
						 //->groupBy('batch_quizzes.quiz_id')
						 ->limit(5);
						 // echo $live_quizzes->toSql();
						 //echo getEloquentSqlWithBindings( $live_quizzes );
				 		?>
				 		<table class="table">
				 			<tr>
				 				<td>Date</td>
				 				<td>Batch name</td>
				 				<td>Exam</td>
				 				<td>Action</td>
				 			</tr>
				 			@forelse( $live_quizzes->get() as $row)
				 			<tr>
				 				<td>{{date('d-m-Y h:i A', strtotime($row->date_time))}}</td>
				 				<td>
				 					{{getBatchName($row->batch_id)}}</td>
				 				<td>{{$row->title}}</td>
				 				<td>
				 					<?php
				 					$title = 'Pop Quiz';
				 					if ( $row->is_popquiz == 'yes' ) {
				 						$title = 'Un-Pop';
				 					}
				 					?>

				 					<a href="{{route('live-quiz.unpop', ['batch_id' => $row->batch_id, 'quiz_id' => $row->id])}}" class="btn btn-lg btn-success button">{{$title}}</a></td>
				 			</tr>
				 			@empty
				 			<tr><td colspan="3" align="center">No Live quizzes today</td></tr>
				 			@endforelse
				 		</table>
				 	</div>
				 	<?php */ ?>
				 </div>
				 @else
				 <div class="row">

				  @if ( checkRole(getUserGrade(1)) )
				  <div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_VIEW_INSTITUES}}"><div class="state-icn bg-icon-pink"><i class="fa fa-bank"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">{{ App\Institute::get()->count()}}</h4>
								<a href="{{URL_VIEW_INSTITUES}}">{{ getPhrase('institutes')}}</a>
				 			</div>
				 		</div>
				 	</div>
				 	@endif

				 	<div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_BATCHS}}"><div class="state-icn bg-icon-blue"><i class="fa fa-sitemap"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">
				 						{{ App\Batch::where('institute_id', $institute_id)->get()->count()}}
				 					</h4>
								<a href="{{URL_BATCHS}}">{{ getPhrase('institute_batches')}}</a>
				 			</div>
				 		</div>
				 	</div>

				 	<div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_USERS}}"><div class="state-icn bg-icon-info"><i class="fa fa-users"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">
				 					@if ( checkRole(getUserGrade(1)) )
				 					{{ App\User::where('role_id','!=',7)->get()->count()}}
				 					@else
				 					{{ App\User::where('role_id','!=',7)->where('institute_id', adminInstituteId())->get()->count()}}
				 					@endif
				 					</h4>
								<a href="{{URL_USERS}}">{{ getPhrase('users')}}</a>
				 			</div>
				 		</div>
				 	</div>





				 	<div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_QUIZZES}}"><div class="state-icn bg-icon-purple"><i class="fa fa-desktop"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">
				 					{{ App\Quiz::where('institute_id', $institute_id)->get()->count()}}
				 					</h4>
								<a href="{{URL_QUIZZES}}">{{ getPhrase('exams')}}</a>
				 			</div>
				 		</div>
				 	</div>
				 <div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_SUBJECTS}}"><div class="state-icn bg-icon-success"><i class="fa fa-book"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">
				 					@if ( checkRole(getUserGrade(1)) )
				 						{{ App\Subject::where('institute_id', $institute_id)->get()->count()}}
				 					@elseif(shareData('share_subjects'))
				 					{{ App\Subject::whereIn('institute_id',[$institute_id, OWNER_INSTITUTE_ID])->get()->count()}}
				 					@else
				 					{{ App\Subject::where('institute_id', adminInstituteId())->get()->count()}}
				 					@endif
				 					</h4>
								<a href="{{URL_SUBJECTS}}">{{ getPhrase('subjects')}}</a>
				 			</div>
				 		</div>
				 	</div>


				 	 <div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_TOPICS}}"><div class="state-icn bg-icon-purple"><i class="fa fa-list"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">
				 					@if(shareData('share_topics'))
				 					{{ App\Topic::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get()->count()}}
				 					@else
				 					{{ App\Topic::where('institute_id', adminInstituteId())->get()->count()}}
				 					@endif
				 					</h4>
								<a href="{{URL_TOPICS}}">{{ getPhrase('topics')}}</a>
				 			</div>
				 		</div>
				 	</div>


				 	 <div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_QUIZ_QUESTIONBANK}}"><div class="state-icn bg-icon-orange"><i class="fa fa-question-circle"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">
				 					@if(shareData('share_questions'))
				 						{{ App\QuestionBank::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get()->count() }}
				 					@else
				 						{{ App\QuestionBank::where('institute_id', $institute_id)->get()->count()}}
				 					@endif
				 					</h4>
								<a href="{{URL_QUIZ_QUESTIONBANK}}">{{ getPhrase('questions')}}</a>
				 			</div>
				 		</div>
				 	</div>




				</div>
				@endif

			<!-- /.container-fluid -->
 @if( isAdmin() )
 <div class="row">

 	<div class="col-md-6">
  				  <div class="panel panel-primary dsPanel">
				    <div class="panel-heading"><i class="fa fa-pie-chart"></i> {{getPhrase('quizzes_usage')}}</div>
				    <div class="panel-body" >
				    	<canvas id="demanding_quizzes" width="100" height="60"></canvas>
				    </div>
				  </div>
				</div>



			</div>
			<div class="row">

				<div class="col-md-6 col-lg-5">
  				  <div class="panel panel-primary dsPanel">
				    <div class="panel-heading"><i class="fa fa-bar-chart-o"></i> {{getPhrase('payment_statistics')}}</div>
				    <div class="panel-body" >
				    	<canvas id="payments_chart" width="100" height="60"></canvas>
				    </div>
				  </div>
				</div>
				<div class="col-md-6 col-lg-3">
  				  <div class="panel panel-primary dsPanel">
				    <div class="panel-heading"><i class="fa fa-bar-chart-o"></i>{{$chart_heading}}</div>
				    <div class="panel-body" >

						<?php $ids=[];?>
						@for($i=0; $i<count($chart_data); $i++)
						<?php
						$newid = 'myChart'.$i;
						$ids[] = $newid; ?>

						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<canvas id="{{$newid}}" width="100" height="110"></canvas>
								</div>
							</div>
						</div>

						@endfor
				    </div>
				  </div>
				</div>


				<div class="col-md-6 col-lg-4">
  				  <div class="panel panel-primary dsPanel">
				    <div class="panel-heading"><i class="fa  fa-line-chart"></i> {{getPhrase('payment_monthly_statistics')}}</div>
				    <div class="panel-body" >
				    	<canvas id="payments_monthly_chart" width="100" height="60"></canvas>
				    </div>
				  </div>
				</div>


@endif



	</div>
</div>
		<!-- /#page-wrapper -->

@stop

@section('footer_scripts')
 @if( isAdmin() )
	 @include('common.chart', array($chart_data,'ids' =>$ids))
	 @include('common.chart', array('chart_data'=>$payments_chart_data,'ids' =>array('payments_chart'), 'scale'=>TRUE))
	 @include('common.chart', array('chart_data'=>$payments_monthly_data,'ids' =>array('payments_monthly_chart'), 'scale'=>true))
	 @include('common.chart', array('chart_data'=>$demanding_quizzes,'ids' =>array('demanding_quizzes')))
	 @include('common.chart', array('chart_data'=>$demanding_paid_quizzes,'ids' =>array('demanding_paid_quizzes')))
 @endif
@stop
