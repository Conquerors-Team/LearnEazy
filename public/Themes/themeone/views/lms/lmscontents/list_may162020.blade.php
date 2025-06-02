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

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">

						<div class="pull-right messages-buttons">

							<?php
			                $institute_id   = adminInstituteId();
			                if(checkRole(getUserGrade(3))) {
			                	$subjects = \App\Subject::where('institute_id', $institute_id)->get();
			                } else {
			                	if ( shareData('share_subjects') ) {
			                		$subjects = \App\Subject::whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
			                	} else {
			                		$subjects = \App\Subject::where('institute_id', $institute_id)->get();
			                	}
			                }

			                $chapters = $topics = $sub_topics = [];

			                $selected_subject = $selected_chapter = $selected_topic = $selected_sub_topic = '';

			                $str_subjects = $str_chapters = $str_topics = $str_sub_topics = $str_type = '';
			                $params = request()->query();

		                	if ( request('content_type') ) {
		                		$str_subjects .= '&content_type=' . request('content_type');
		                		$str_chapters .= '&content_type=' . request('content_type');
		                		$str_topics .= '&content_type=' . request('content_type');
		                		$str_sub_topics .= '&content_type=' . request('content_type');
		                	}
		                	if ( request('subject') ) {
		                		$str_type .= '&subject=' . request('subject');
		                	}
		                	if ( request('chapter') ) {
		                		$str_type .= '&chapter=' . request('chapter');
		                	}
		                	if ( request('topic') ) {
		                		$str_type .= '&topic=' . request('topic');
		                	}
		                	if ( request('sub_topic') ) {
		                		$str_type .= '&sub_topic=' . request('sub_topic');
		                	}


			                if ( request()->filled('subject') ) {
			                	$selected_subject = \App\Subject::find( request('subject') );
			                	$str_chapters .= '&subject=' . request('subject');

			                	if ( ! in_array( 'subject', array_keys( $params ) ) ) {
			                		$str_subjects .= '&subject=' . request('subject');
			                	}
			                	if(checkRole(getUserGrade(3))) {
			                		$chapters = \App\Chapter::where('subject_id', request('subject'))->get();
			                	} elseif ( shareData('share_subjects') ) {
			                		$chapters = \App\Chapter::where('subject_id', request('subject'))->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
			                	} else {
			                		$chapters = \App\Chapter::where('subject_id', request('subject'))->where('institute_id', $institute_id)->get();
			                	}
			                }

			                if ( request()->filled('subject') && request()->filled('chapter') ) {
			                	$selected_chapter = \App\Chapter::where( 'subject_id', request('subject') )->where('id', request('chapter'))->first();
			                	$str_topics = '&subject='.request('subject').'&chapter=' . request('chapter');
			                	if ( request('content_type') ) {
			                		$str_topics .= '&content_type=' . request('content_type');
			                	}
			                	if(checkRole(getUserGrade(3))) {
			                		$topics = \App\Topic::where('subject_id', request('subject'))->where('chapter_id', request('chapter'))->get();
			                	} elseif ( shareData('share_subjects') ) {
			                		$topics = \App\Topic::where('subject_id', request('subject'))->where('chapter_id', request('chapter'))->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
			                	} else {
			                		$topics = \App\Topic::where('subject_id', request('subject'))->where('chapter_id', request('chapter'))->where('institute_id', $institute_id)->get();
			                	}
			                }


			                if ( request()->filled('subject') && request()->filled('chapter') && request()->filled('topic') ) {
			                	$selected_topic = \App\Topic::where( 'subject_id', request('subject') )->where('chapter_id', request('chapter'))->where('id', request('topic'))->first();
			                	$str_sub_topics = '&subject='.request('subject').'&chapter=' . request('chapter') . '&topic=' . request('topic');
			                	if ( request('content_type') ) {
			                		$str_sub_topics .= '&content_type=' . request('content_type');
			                	}

			                	if(checkRole(getUserGrade(3))) {
			                		$sub_topics = \App\Topic::where('subject_id', request('subject'))->where('chapter_id', request('chapter'))->where('parent_id', request('topic'))->get();
			                	} elseif ( shareData('share_subjects') ) {
			                		$sub_topics = \App\Topic::where('subject_id', request('subject'))->where('chapter_id', request('chapter'))->where('parent_id', request('topic'))->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
			                	} else {
			                		$sub_topics = \App\Topic::where('subject_id', request('subject'))->where('chapter_id', request('chapter'))->where('parent_id', request('topic'))->where('institute_id', $institute_id)->get();
			                	}
			                }

			                if ( request()->filled('subject') && request()->filled('chapter') && request()->filled('topic') && request()->filled('sub_topic') ) {
			                	$selected_sub_topic = \App\Topic::where( 'subject_id', request('subject') )->where('chapter_id', request('chapter'))->where('parent_id', request('topic'))->where('id', request('sub_topic'))->first();
			                }

			                ?>
			                 <?php $settings = getSettings('lms');?>
			                <div class="btn-group">
				              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				                <i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Type&nbsp;<span class="caret"></span>
				              </button>
				              <ul class="dropdown-menu">
				                @foreach( $settings->content_types as $type_val => $type_str )
				                	<li><a href="{{URL_LMS_CONTENT}}?content_type={{$type_val}}@if($str_type){{$str_type}}@endif">{{$type_str}}</a></li>
				                @endforeach
				              </ul>
				            </div>

			                @if( $subjects->count() > 0 )
							<div class="btn-group">
				              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				                <i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Subject&nbsp;<span class="caret"></span>
				              </button>
				              <ul class="dropdown-menu">
				                @foreach( $subjects as $subject )
				                	<li><a href="{{URL_LMS_CONTENT}}?subject={{$subject->id}}@if($str_subjects){{$str_subjects}}@endif">{{$subject->subject_title}}</a></li>
				                @endforeach
				              </ul>
				            </div>
				            @endif

				            @if( count( $chapters ) > 0 )
							<div class="btn-group">
				              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				                <i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Chapter&nbsp;<span class="caret"></span>
				              </button>
				              <ul class="dropdown-menu">
				                @foreach( $chapters as $chapter )
				                	<li><a href="{{URL_LMS_CONTENT}}?chapter={{$chapter->id}}@if($str_chapters){{$str_chapters}}@endif">{{$chapter->chapter_name}}</a></li>
				                @endforeach
				              </ul>
				            </div>
				            @endif

				            @if( count( $topics ) > 0 )
							<div class="btn-group">
				              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				                <i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Topic&nbsp;<span class="caret"></span>
				              </button>
				              <ul class="dropdown-menu">
				                @foreach( $topics as $topic )
				                	<li><a href="{{URL_LMS_CONTENT}}?topic={{$topic->id}}@if($str_topics){{$str_topics}}@endif">{{$topic->topic_name}}</a></li>
				                @endforeach
				              </ul>
				            </div>
				            @endif

				            @if( count( $sub_topics ) > 0 )
							<div class="btn-group">
				              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				                <i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Sub-Topic&nbsp;<span class="caret"></span>
				              </button>
				              <ul class="dropdown-menu">
				                @foreach( $sub_topics as $topic )
				                	<li><a href="{{URL_LMS_CONTENT}}?sub_topic={{$topic->id}}@if($str_sub_topics){{$str_sub_topics}}@endif">{{$topic->topic_name}}</a></li>
				                @endforeach
				              </ul>
				            </div>
				            @endif

				            <a href="{{URL_LMS_CONTENT_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
						</div>
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<?php $content_type = ''; ?>
						@if( request('content_type') )
							<?php
							$url = URL_LMS_CONTENT;
							$content_type = '';
							if( $selected_sub_topic ) {
								$url = URL_LMS_CONTENT . '?subject=' . $selected_sub_topic->subject_id . '&chapter='.$selected_sub_topic->chapter_id.'&topic='.$selected_sub_topic->parent_id . $content_type;
							} elseif( $selected_topic ) {
								$url = URL_LMS_CONTENT . '?subject=' . $selected_topic->subject_id . '&chapter='.$selected_topic->chapter_id . $content_type;
							} elseif( $selected_chapter ) {
								$url = URL_LMS_CONTENT . '?subject=' . $selected_chapter->subject_id . '&chapter=' . $selected_chapter->id;
							} elseif( $selected_subject ) {
								$url = URL_LMS_CONTENT . '?content_type=' . request('content_type');
							}
							$content_type = '&content_type=' . request('content_type');
							?>
							<a href="{{$url}}">{{ucfirst(request('content_type'))}}&nbsp;<i class="fa fa-times"></i></a>&nbsp;->&nbsp;
						@endif
						@if( $selected_subject )
							<a href="{{URL_LMS_CONTENT}}@if( request('content_type') )?content_type={{request('content_type')}}@endif">{{$selected_subject->subject_title}}&nbsp;<i class="fa fa-times"></i></a>
						@endif
						@if( $selected_chapter )
							&nbsp;->&nbsp; <a href="{{URL_LMS_CONTENT}}?subject={{$selected_chapter->subject_id . $content_type}}">{{$selected_chapter->chapter_name}}&nbsp;<i class="fa fa-times"></i></a>
						@endif
						@if( $selected_topic )
							&nbsp;->&nbsp; <a href="{{URL_LMS_CONTENT}}?subject={{$selected_topic->subject_id}}&chapter={{$selected_topic->chapter_id . $content_type}}">{{$selected_topic->topic_name}}&nbsp;<i class="fa fa-times"></i></a>
						@endif
						@if( $selected_sub_topic )
							&nbsp;->&nbsp; <a href="{{URL_LMS_CONTENT}}?subject={{$selected_sub_topic->subject_id}}&chapter={{$selected_sub_topic->chapter_id}}&topic={{$selected_sub_topic->parent_id . $content_type}}">{{$selected_sub_topic->topic_name}}&nbsp;<i class="fa fa-times"></i></a>
						@endif
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

 @include('common.datatables', array('route'=>'lmscontent.dataTable', 'search_columns' => ['subject' => request('subject'),'chapter' => request('chapter'),'topic' => request('topic'),'sub_topic' => request('sub_topic'), 'content_type' => request('content_type')]))
 @include('common.deletescript', array('route'=>URL_LMS_CONTENT_DELETE))

@stop
