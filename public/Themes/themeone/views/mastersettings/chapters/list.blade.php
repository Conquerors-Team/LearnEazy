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

				@include('common.search-form', ['url' => '/mastersettings/chapters', 'show_content_types' => 'FALSE', 'show_chapters' => 'FALSE', 'show_topics' => 'FALSE', 'show_sub_topics' => 'FALSE', 'show_question_actegory' => 'FALSE', 'show_difficulty_level' => 'FALSE' ])

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">

						<div class="pull-right messages-buttons">

							<a href="{{route('mastersettings.chapters_import')}}" class="btn  btn-primary button" >{{ getPhrase('import')}}</a>
							<a href="{{route('mastersettings.chapters_create')}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>

						</div>
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div>
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>

								   @if(shareData('share_chapters'))
                                        <th>{{ getPhrase('institute')}}</th>
                                    @endif
									<th>{{ getPhrase('subject (id)')}}</th>
									<!-- <th>{{ getPhrase('parent')}}</th> -->
									<th>{{ getPhrase('chapter (id)')}}</th>

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

 @include('common.datatables', array('route'=>'chapters.dataTable', 'search_columns' => ['subject' => request('subject_id'),'chapter' => request('chapter_id'),'topic' => request('topic_id'),'sub_topic' => request('sub_topic_id'), 'institute' => request('institute_id')]))
 @include('common.deletescript', array('route'=>url('mastersettings/chapters/delete') . '/'))

@stop
