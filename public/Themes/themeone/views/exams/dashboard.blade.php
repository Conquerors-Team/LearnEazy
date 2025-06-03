@extends($layout)
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')


<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li><a href="{{route('exams.questionbank')}}">Exams</a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">

					<div class="panel-body packages">
						<div class="col-md-3 col-sm-6">
					 		<div class="media state-media box-ws">
					 			<div class="media-left">
					 				<a href="{{route('mastersettings.subjects')}}"><div class="state-icn bg-icon-pink"><i class="icon-books"></i></div></a>
					 			</div>
					 			<div class="media-body">
					 				<h4 class="card-title">{{ App\Subject::where('institute_id', Auth::user()->institute_id)->get()->count()}}</h4>
									<a href="{{route('mastersettings.subjects')}}">{{ getPhrase('subjects')}}</a>
					 			</div>
					 		</div>
					 	</div>

					 	<div class="col-md-3 col-sm-6">
					 		<div class="media state-media box-ws">
					 			<div class="media-left">
					 				<a href="{{route('mastersettings.chapters_index')}}"><div class="state-icn bg-icon-pink"><i class="icon-books"></i></div></a>
					 			</div>
					 			<div class="media-body">
					 				<h4 class="card-title">{{ App\Chapter::where('institute_id', Auth::user()->institute_id)->get()->count()}}</h4>
									<a href="{{route('mastersettings.chapters_index')}}">{{ getPhrase('Chapters')}}</a>
					 			</div>
					 		</div>
					 	</div>

					 	<div class="col-md-3 col-sm-6">
					 		<div class="media state-media box-ws">
					 			<div class="media-left">
					 				<a href="{{route('mastersettings.topics')}}"><div class="state-icn bg-icon-pink"><i class="icon-books"></i></div></a>
					 			</div>
					 			<div class="media-body">
					 				<h4 class="card-title">{{ App\Topic::where('institute_id', Auth::user()->institute_id)->get()->count()}}</h4>
									<a href="{{route('mastersettings.topics')}}">{{ getPhrase('Topics')}}</a>
					 			</div>
					 		</div>
					 	</div>

					 	<div class="col-md-3 col-sm-6">
					 		<div class="media state-media box-ws">
					 			<div class="media-left">
					 				<a href="{{route('exams.questionbank')}}"><div class="state-icn bg-icon-pink"><i class="icon-books"></i></div></a>
					 			</div>
					 			<div class="media-body">
					 				<h4 class="card-title">{{ App\QuestionBank::where('institute_id', Auth::user()->institute_id)->get()->count()}}</h4>
									<a href="{{route('exams.questionbank')}}">{{ getPhrase('questions')}}</a>
					 			</div>
					 		</div>
					 	</div>

					 	<div class="col-md-3 col-sm-6">
					 		<div class="media state-media box-ws">
					 			<div class="media-left">
					 				<a href="{{route('exams.quizzes')}}"><div class="state-icn bg-icon-pink"><i class="icon-books"></i></div></a>
					 			</div>
					 			<div class="media-body">
					 				<h4 class="card-title">{{ App\Quiz::where('institute_id', Auth::user()->institute_id)->where('category_id', '!=', QUESTIONSBANK_TYPE_TESTSERIES )->get()->count()}}</h4>
									<a href="{{route('exams.quizzes')}}">{{ getPhrase('exams')}}</a>
					 			</div>
					 		</div>
					 	</div>

					 	<div class="col-md-3 col-sm-6">
					 		<div class="media state-media box-ws">
					 			<div class="media-left">
					 				<a href="{{route('exams.test_series')}}"><div class="state-icn bg-icon-pink"><i class="icon-books"></i></div></a>
					 			</div>
					 			<div class="media-body">
					 				<h4 class="card-title">{{ App\Quiz::where('institute_id', Auth::user()->institute_id)->where('category_id', QUESTIONSBANK_TYPE_TESTSERIES)->get()->count()}}</h4>
									<a href="{{route('exams.test_series')}}">{{ getPhrase('test_series')}}</a>
					 			</div>
					 		</div>
					 	</div>

					 	<div class="col-md-3 col-sm-6">
					 		<div class="media state-media box-ws">
					 			<div class="media-left">
					 				<a href="{{route('lms.content')}}"><div class="state-icn bg-icon-pink"><i class="icon-books"></i></div></a>
					 			</div>
					 			<div class="media-body">
					 				<h4 class="card-title">{{ App\LmsContent::where('institute_id', Auth::user()->institute_id)->get()->count()}}</h4>
									<a href="{{route('lms.content')}}">LMS Content</a>
					 			</div>
					 		</div>
					 	</div>

					 	<div class="col-md-3 col-sm-6">
					 		<div class="media state-media box-ws">
					 			<div class="media-left">
					 				<a href="{{route('lms.series.index')}}"><div class="state-icn bg-icon-pink"><i class="icon-books"></i></div></a>
					 			</div>
					 			<div class="media-body">
					 				<h4 class="card-title">{{ App\LmsSeries::where('institute_id', Auth::user()->institute_id)->get()->count()}}</h4>
									<a href="{{route('lms.series.index')}}">LMS Series</a>
					 			</div>
					 		</div>
					 	</div>

					 	<div class="col-md-3 col-sm-6">
					 		<div class="media state-media box-ws">
					 			<div class="media-left">
					 				<a href="{{route('lms.notes')}}"><div class="state-icn bg-icon-pink"><i class="icon-books"></i></div></a>
					 			</div>
					 			<div class="media-body">
					 				<h4 class="card-title">{{ App\LmsNote::where('institute_id', Auth::user()->institute_id)->get()->count()}}</h4>
									<a href="{{route('lms.notes')}}">LMS Notes</a>
					 			</div>
					 		</div>
					 	</div>

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
@endsection



