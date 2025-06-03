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
							<li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a> </li>
							<li><a href="{{URL_QUIZ_QUESTIONBANK}}">{{ getPhrase('question_subjects') }}</a></li>
							<li><a href="{{URL_QUESTIONBAMK_IMPORT}}">{{ getPhrase('import_questions') }}</a></li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">

						<div class="pull-right messages-buttons">

							<a href="{{URL_QUESTIONBANK_ADD_QUESTION.$subject->slug}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>

						</div>
						<h1>{{ $title }}</h1>
					</div>
					@if(checkRole(getUserGrade(9)))
						@include('common.search-form', ['url' => url()->current(), 'show_content_types' => 'FALSE','show_subjects' => 'FALSE','show_sub_topics' => 'FALSE','show_batch_assigned' => 'FALSE', 'subject_id' => $subject->id])
					@endif
					<div class="panel-body packages">
						<div class="table-responsive">
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
								    <!-- @if(checkRole(getUserGrade(3)) || shareData())
                                        <th>{{ getPhrase('institute')}}</th>
                                    @endif -->

									<th width="120px">{{ getPhrase('question_code')}}</th>
									<th width="620px">{{ getPhrase('question')}}</th>
									<th>{{ getPhrase('category')}}</th>
									<th>{{ getPhrase('action')}}</th>

								</tr>
							</thead>

						</table>
						</div>

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
@endsection


@section('footer_scripts')
  {{-- <script src="{{JS}}bootstrap-toggle.min.js"></script>
 	<script src="{{JS}}jquery.dataTables.min.js"></script>
	<script src="{{JS}}dataTables.bootstrap.min.js"></script> --}}

 @include('common.datatables', array('route'=>URL_QUESTIONBANK_GETQUESTION_LIST.$subject->slug, 'route_as_url' => 'TRUE', 'search_columns' => ['subject' => request('subject_id'),'chapter' => request('chapter_id'),'topic' => request('topic_id'),'sub_topic' => request('sub_topic_id'), 'institute' => request('institute_id'),'question_category_id' => request('question_category_id'), 'difficulty_level' => request('difficulty_level')]))
 @include('common.deletescript', array('route'=>URL_QUESTIONBANK_DELETE))
@include('common.filter-scripts')


@stop
