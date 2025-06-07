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
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>

				@include('common.search-form', ['url' => URL_LMS_CONTENT, 'show_question_actegory' => 'FALSE', 'show_difficulty_level' => 'FALSE', 'show_sub_topics' => 'FALSE'])

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							@if(canDo('lms_content_create'))
				            <a href="{{URL_LMS_CONTENT_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
				            @endif
						</div>
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">

						<div>
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									@if(checkRole(getUserGrade(3)) || shareData('share_lms_contents'))
                                        <th>{{ getPhrase('institute')}}</th>
                                    @endif
									<th>{{ getPhrase('title')}}</th>
									<th>{{ getPhrase('image')}}</th>
									<th>{{ getPhrase('type')}}</th>
									<th>{{ getPhrase('subject')}}</th>
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

@if(checkRole(getUserGrade(3)) || shareData('share_lms_contents'))
    @include('common.datatables', [
        'route' => 'lmscontent.dataTable',
        'search_columns' => [
            'subject' => request('subject_id'),
            'chapter' => request('chapter_id'),
            'topic' => request('topic_id'),
            'sub_topic' => request('sub_topic_id'),
            'content_type' => request('content_type'),
            'institute' => request('institute_id')
        ],
        'table_columns' => [
            'institute_id', 'title', 'image', 'content_type', 'subject_title', 'action'
        ]
    ])
@else
    @include('common.datatables', [
        'route' => 'lmscontent.dataTable',
        'search_columns' => [
            'subject' => request('subject_id'),
            'chapter' => request('chapter_id'),
            'topic' => request('topic_id'),
            'sub_topic' => request('sub_topic_id'),
            'content_type' => request('content_type'),
            'institute' => request('institute_id')
        ],
        'table_columns' => [
            'title', 'image', 'content_type', 'subject_title', 'action'
        ]
    ])
@endif

 @include('common.deletescript', array('route'=>URL_LMS_CONTENT_DELETE))

@stop
