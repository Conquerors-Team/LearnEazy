@extends('layouts.student.studentlayout')
@section('header_scripts')
<link href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">

<style type="text/css">
  @if( ! empty( $subject->color_code ) )
  .nav-tabs {
    border-bottom: 1px solid {{$subject->color_code}} !important;
  }
  .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    border-top:    1px solid {{$subject->color_code}} !important;
    border-right:  1px solid {{$subject->color_code}} !important;
    border-left: 1px solid {{$subject->color_code}} !important;
  }
  .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
    background: {{$subject->color_code}} !important;
  }
  @endif
</style>

<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
<!-- <link rel="stylesheet" type="text/css" href="https://kenwheeler.github.io/slick/slick/slick-theme.css"/> -->

<style type="text/css">
  @if( ! empty( $subject->color_code ) )
  .nav-tabs {
    border-bottom: 1px solid {{$subject->color_code}} !important;
  }
  .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    border-top:    1px solid {{$subject->color_code}} !important;
    border-right:  1px solid {{$subject->color_code}} !important;
    border-left: 1px solid {{$subject->color_code}} !important;
  }
  .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
    background: {{$subject->color_code}} !important;
  }
  @endif
  .package-details{
    padding: 10px;
    border: 1px solid;
    border-radius: 5px;
  }
</style>
@stop
@section('content')
<style>

  h2{
  text-align:center;
  padding: 20px;
}
/* Slider */





    .slick-slide {
      margin: 0px 20px;
    }

    .slick-slide img {
      width: 100%;
    }

    .slick-prev:before,
    .slick-next:before {
      color: black;
    }


    .slick-slide {
      transition: all ease-in-out .3s;
      opacity: .2;
      border: 3px solid {{$subject->color_code}};
      border-radius: 15px;
      padding: 10px;
      text-align:center;
    }

    .slick-active {
      opacity: .6;
    }

    .slick-current {
      opacity: 1;
    }
</style>

<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a> </li>
							<!-- <li><a href="/exams/student/learning-subjects"> {{getPhrase('subjects')}} </a> </li> -->
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<?php
			            $examSettings = getExamSettings();
			            $student_batches = getStudentBatches();
			            ?>
			            <div>
			            <img src="{{ PREFIX.$examSettings->subjectsImagepath.$subject->image }}" height="100" width="100" >
			            <span style="color:{{$subject->color_code}}">{{ $subject->subject_title }}</span>
			            </div>

						<ul class="nav nav-tabs">
			              @if(isOnlinestudent())
                          <li><a href="{{URL_STUDENT_EXAMS.$subject->slug . '/subject'}}">Chapter Tests</span></a></li>
                          @else
                          <li><a href="{{URL_STUDENT_EXAMS.$subject->slug . '/subject'}}">Exams</span></a></li>
                          @endif
			              <li class="active"><a href="{{route('student.lms_notes', $subject->slug)}}">Notes</span></a></li>
			              <li><a href="{{route('studentlms.subjectitems', ['slug' => $subject->slug])}}">LMS</a></li>
			            </ul>
					</div>

					<div class="panel-body packages">
                        <div class="row library-items">
                            <?php $settings = getSettings('lms');
                            if ( isOnlinestudent() ) {
                            	$student_courses = getStudentClasses('courses');
						        $date = date('Y-m-d');
						        $user_id = Auth::user()->id;

                            	$chapters = $subject->chapters()
                            	->select(['chapters.*'])
                            	->join('lms_notes', 'lms_notes.chapter_id', '=', 'chapters.id')

                            	->join('student_paid_contents_data as spcd', 'spcd.item_id', '=', 'lms_notes.id')
								->join('student_paid_contents_courses as spcc', 'spcc.student_paid_contents_id', '=', 'spcd.student_paid_contents_id')
								->join('student_paid_contents', 'student_paid_contents.id', '=', 'spcc.student_paid_contents_id')
								->join('payments as p', 'p.item_id', '=', 'spcd.student_paid_contents_id')

								->whereIn('spcc.course_id', $student_courses)
								->where('student_paid_contents.total_items', '>', 0)
						        ->where('student_paid_contents.status', 'active')
						        ->where('spcd.item_type', 'lmsnotes')
						        ->where('p.end_date','>=',$date)
						        ->where('p.user_id','=',$user_id)
						        ->where('p.plan_type','=','paidcontent')

						        //->groupBy('spcd.item_id')
                            	//->groupBy('lms_notes.chapter_id')
                            	// ->groupBy('spcd.item_id')
                            	->groupBy('chapters.id')
                            	->get();


                            } else {
                            	$chapters = $subject->chapters()->select(['chapters.*'])->join('lms_notes', 'lms_notes.chapter_id', '=', 'chapters.id')->join('batch_lmsnotes', 'batch_lmsnotes.lms_note_id', '=', 'lms_notes.id')->whereIn('batch_lmsnotes.batch_id', $student_batches)->groupBy('lms_notes.chapter_id')->get();
                        	}
                            ?>
                            @forelse( $chapters as $chapter )
                            <?php
                            if ( isOnlinestudent() ) {
                            	$validPackages = validPackages();

                            	$lmsseriesnotes = $chapter->topics()->select(['lms_notes.*'])
								->join('lms_notes', 'lms_notes.topic_id', '=', 'topics.id')
								->join('student_paid_contents_data as spcd', 'spcd.item_id', '=', 'lms_notes.id')
								->join('subjects', 'subjects.id', '=', 'lms_notes.subject_id')
								->join('chapters', 'chapters.id', '=', 'lms_notes.chapter_id');
								if ( ! empty( $validPackages )) {
									$lmsseriesnotes = $lmsseriesnotes->whereIn('spcd.student_paid_contents_id', $validPackages);
								}
								$lmsseriesnotes = $lmsseriesnotes->groupBy('lms_notes.id')
                            	->get()
                            	;
                            } else {
                            $lmsseriesnotes = $chapter->topics()->select(['lms_notes.*'])
                            	->join('lms_notes', 'lms_notes.topic_id', '=', 'topics.id')->join('batch_lmsnotes', 'batch_lmsnotes.lms_note_id', '=', 'lms_notes.id')
                            	->whereIn('batch_lmsnotes.batch_id', $student_batches)
                            	->get();
                            }
                            ?>
                            <div>
                            <li class="list-group-item"><b style="color: {{$subject->color_code}}">{{$chapter->chapter_name}}</b></li>
                            </div>
                            @if ( $lmsseriesnotes->count() > 0 )
                            <section class="customer-logos slider">
                                @forelse( $lmsseriesnotes as $single )
                                    <div class="slide">
                                        <p><a href="{{route('studentlmsnotes.subjectitems', ['slug' => $subject->slug, 'series_slug' => $single->slug])}}" style="color: #337ab7;">View More</a></p>
                                        <p style="font-size: x-large;">{{$single->title}}</p>
                                    </div>
                                @empty
                                    <div class="slide"><p>No Series</p></div>
                                @endforelse
                            </section>
                            @else
                                <section class="customer-logos slider">
                                    <div class="slide"><p>No Series</p></div>
                                </section>
                            @endif

                        @empty
                            No Chapters
                        @endforelse
                        </div>
                    </div>


					<?php
					// dd( $notes );
					/*
					?>
					<div id="accordion">
						@forelse( $notes as $content )
						<h3>{{$content->title}}</h3>
						<div>
							<p>
								<?php
						        $url = url('public/uploads/lms/notes/' . $content->file_path);
						        if ( $content->content_type == 'url' ) {
						            $url = $content->file_path;
						        }
						        ?>
						        @if( in_array( $content->content_type, ['video', 'video_url', 'audio'] ) )
						            <?php
						                $video_src = $content->file_path;
						                if($content->content_type=='video') {
						                    $video_src = IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->file_path;
						                }
						            ?>
						            <video id="my-video" class="video-js vjs-big-play-centered" autoplay controls preload="auto" width="300" height="264"
						              poster="" data-setup='{"aspectRatio":"640:267", "playbackRates": [1, 1.5, 2] }'>
						                <source src="{{$video_src}}" type='video/mp4'>
						                <p class="vjs-no-js">
						                  To view this video please enable JavaScript, and consider upgrading to a web browser that
						                  <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
						                </p>
						            </video>
						        @elseif( $content->content_type == 'iframe' && preg_match('/iframe/',$content->file_path) )
						            {!! $content->file_path !!}
						        @else
						            <iframe width="100%" height="560" src="{{$url}}" frameborder="0" allowfullscreen></iframe>
						        @endif
							</p>
						</div>
						@empty
							Notes not found for this subject
						@endforelse
					</div>

					@if(count($notes))
					{!! $notes->links() !!}
					@endif
					<?php */ ?>

				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
@endsection


@section('footer_scripts')
	 <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	 <script src="{{JS}}select2.js"></script>
	 <script type="text/javascript">
	 	$('.select2').select2({
	       placeholder: "Select",
	       tags: true
	    });
	 </script>

	 <script>
$( function() {
  $( "#accordion" ).accordion();
} );
</script>
	@include('student.notes.scripts.js-scripts')

	@include('student.packages-modal-scripts')

	<script src="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
	<script >

	    $(document).ready(function(){
	    $('.slider').slick({
	        infinite: true,
	        slidesToShow: 4,
	        slidesToScroll: 1,
	        //autoplay: true,
	        //autoplaySpeed: 1500,
	        arrows: true,
	        dots: false,
	        pauseOnHover: false,
	        responsive: [{
	            breakpoint: 768,
	            settings: {
	                slidesToShow: 4
	            }
	        }, {
	            breakpoint: 520,
	            settings: {
	                slidesToShow: 3
	            }
	        }]
	    });
	});

	function showInstructions(url) {
	  width = screen.availWidth;
	  height = screen.availHeight;
	  window.open(url,'_blank',"height="+height+",width="+width+", toolbar=no, top=0,left=0,location=no,menubar=no, directories=no, status=no, menubar=no, scrollbars=yes,resizable=no");

	    runner();
	}
	  </script>
@stop
