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
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						@if(checkRole(getUserGrade(9)))
						<div class="pull-right messages-buttons">
							<a href="{{URL_QUESTIONBAMK_IMPORT}}" class="btn  btn-primary button" >{{ getPhrase('import_questions')}}</a>
							<a href="{{URL_SUBJECTS_ADD}}" class="btn  btn-primary button" >{{ getPhrase('add_subject')}}</a>
						</div>
						@endif
						<h1>{{ $title }}</h1>
					</div>
					@if(checkRole(getUserGrade(9)))
						@include('common.search-form', ['url' => url()->current(), 'show_content_types' => 'FALSE', 'show_chapters' => 'FALSE', 'show_topics' => 'FALSE', 'show_sub_topics' => 'FALSE','show_question_category' =>'FALSE','show_question_actegory'=>'FALSE','show_difficulty_level'=>'FALSES'])
					@endif

					<div class="panel-body packages">
						<div>
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>

								    @if(checkRole(getUserGrade(3)) || shareData('share_subjects'))
                                        <th>{{ getPhrase('institute')}}</th>
                                    @endif

									<th>{{ getPhrase('subject')}}</th>
									<th>{{ getPhrase('code')}}</th>
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
	
 @include('common.filter-scripts')	

 @include('common.datatables', array('route'=> 'exams.questionbank.getList', 'search_columns' => ['subject' => request('subject_id'),'chapter' => request('chapter_id'),'topic' => request('topic_id'),'sub_topic' => request('sub_topic_id'), 'institute' => request('institute_id')], 'table_columns' => ['institute_id','subject_title','subject_code','action']))
 @include('common.deletescript', array('route'=> URL_QUESTIONBANK_DELETE))

@stop
