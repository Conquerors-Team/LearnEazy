@extends($layout)
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
<style type="text/css">
  @if( ! empty( $category->color_code ) )
  .nav-tabs {
    border-bottom: 1px solid {{$category->color_code}} !important;
  }
  .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    border-top:    1px solid {{$category->color_code}} !important;
    border-right:  1px solid {{$category->color_code}} !important;
    border-left: 1px solid {{$category->color_code}} !important;
  }
  .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
    background: {{$category->color_code}} !important;
  }
  @endif
</style>

<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>

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
      border: 3px solid #ccc;
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
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>

							<!-- <li><a href="{{URL_STUDENT_SUBJECTS}}"><i class="icon-books"></i>&nbsp;Subjects</a> </li> -->

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
            <img src="{{ PREFIX.$examSettings->subjectsImagepath.$category->image }}" height="100" width="100" >
            <span style="color:{{$category->color_code}}">{{ $title }}</span>
            </div>

            <ul class="nav nav-tabs">
              @if(isOnlinestudent())
              <li class="active"><a href="{{URL_STUDENT_EXAMS.$category->slug . '/subject'}}">Chapter Tests</span></a></li>
              @else
              <li class="active"><a href="{{URL_STUDENT_EXAMS.$category->slug . '/subject'}}">Exams</span></a></li>
              @endif
              <li><a href="{{route('student.lms_notes', $category->slug)}}">Notes</span></a></li>
              <li><a href="{{route('studentlms.subjectitems', ['slug' => $category->slug])}}">LMS</a></li>
            </ul>

					</div>

          <div class="panel-body packages">
            @if( isOnlinestudent() )
            <div class="row library-items">
              <?php
              $student_courses = getStudentClasses('courses');
           
              $date = date('Y-m-d');
              $user_id = Auth::user()->id;

              $subject = $category;
              ?>

                <?php
                $chapters = $subject->chapters()
                ->select(['chapters.*'])
                ->join('quizzes as q', 'q.chapter_id', '=', 'chapters.id')
                ->join('student_paid_contents_data as spcd', 'spcd.item_id', '=', 'q.id')
                ->join('student_paid_contents_courses as spcc', 'spcc.student_paid_contents_id', '=', 'spcd.student_paid_contents_id')
                ->join('student_paid_contents', 'student_paid_contents.id', '=', 'spcc.student_paid_contents_id')
                ->join('payments as p', 'p.item_id', '=', 'spcd.student_paid_contents_id')

                ->whereIn('spcc.course_id', $student_courses)
                ->where('student_paid_contents.total_items', '>', 0)
                ->where('student_paid_contents.status', 'active')
                ->where('spcd.item_type', 'chapter-exams')
                ->where('p.end_date','>=',$date)
                ->where('p.user_id','=',$user_id)
                ->where('p.plan_type','=','paidcontent')

                ->groupBy('spcd.item_id')
                ->groupBy('q.chapter_id')
                ->get();
                // dd($chapters);
                ?>
                @forelse( $chapters as $chapter )
                  <div>
                  <li class="list-group-item"><b>{{$chapter->chapter_name}} - <span class="label label-info label-many">{{$subject->subject_title}}</span></b></li>
                  </div>
                  <?php
                  $chapter_quizzes = \App\StudentPaidContent::select(['q.title', 'q.dueration', 'q.total_questions', 'q.start_date', 'q.end_date', 'q.is_paid', 'q.total_marks','q.slug', 'q.validity','q.cost','q.start_time', 'q.id'])
                  ->join('student_paid_contents_data as spcd', 'spcd.student_paid_contents_id', '=', 'student_paid_contents.id')
                  ->join('student_paid_contents_courses as spcc', 'spcc.student_paid_contents_id', '=', 'student_paid_contents.id')
                  ->join('quizzes as q', 'q.id', '=', 'spcd.item_id')
                  ->join('quizzes_subjects','quizzes_subjects.quiz_id','=','q.id')
                  ->join('payments as p', 'p.item_id', '=', 'spcd.student_paid_contents_id')

                  ->where('spcd.item_type', 'chapter-exams')
                  ->whereIn('spcc.course_id', $student_courses)
                  ->where('student_paid_contents.total_items', '>', 0)
                  ->where('student_paid_contents.status', 'active')
                  ->groupBy('spcd.item_id')
                  ->where('p.end_date','>=',$date)
                  ->where('p.user_id','=',$user_id)
                  ->where('p.plan_type','=','paidcontent')
                  ->where('q.chapter_id','=',$chapter->id)
                  ->get();
                  ?>
                  @if ( $chapter_quizzes->count() > 0 )
                  <section class="customer-logos slider">
                      @forelse( $chapter_quizzes as $single )
                          <div class="slide">
                              <p>
                                <a onClick="showInstructions('{{URL_STUDENT_TAKE_EXAM.$single->slug}}')" href="javascript:void(0);" class="btn btn-primary">{{getPhrase("take_exam")}}/{{$single->dueration}} Min</a></p>
                              <p style="font-size: x-large;">{{$single->title}}/{{$single->total_questions}} Q</p>
                          </div>
                      @empty
                          <div class="slide"><p>No Exams</p></div>
                      @endforelse
                  </section>
                  @else
                  <section class="customer-logos slider">
                      <div class="slide"><p>No Exams</p></div>
                  </section>
                  @endif
                @empty
                @endforelse

            </div>
            @else
            <div>
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
              <thead style="display: none;">
                <tr>
                  <th>{{ getPhrase('title')}}</th>
                  <th>{{ getPhrase('marks')}}</th>
                  <th>{{ getPhrase('duration')}}</th>
                  <!--
                  <th>{{ getPhrase('category')}}</th>
                  <th>{{ getPhrase('type')}}</th>
                  <th>{{ getPhrase('total_questions')}}</th>
                -->
                  <th>{{ getPhrase('action')}}</th>

                </tr>
              </thead>
            </table>
            </div>
            @endif

          </div>

				</div>
			</div>
			<!-- /.container-fluid -->

			<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm" style="width: 600px;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">{{getPhrase('exam_timings')}}</h4>
      </div>
      <div class="modal-body">
      <h4 class="text-center" id="message"></h4>
     </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-right" data-dismiss="modal">Ok</button>
      </div>
    </div>

  </div>
</div>
		</div>
@endsection


@section('footer_scripts')

  @if($category)
	 @include('common.datatables', array('route'=>URL_STUDENT_QUIZ_GETLIST.$category->slug . '/' . $type, 'route_as_url' => TRUE, 'table_columns' => ['title','dueration','total_questions','action']))
  @else
	 @include('common.datatables', array('route'=>URL_STUDENT_QUIZ_GETLIST_ALL, 'route_as_url' => TRUE, 'table_columns' => ['title','dueration','total_questions','action']))
  @endif

  @include('common.deletescript', array('route'=>URL_QUIZ_DELETE))

<script src="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
<script>
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

function runner()
{
	url = localStorage.getItem('redirect_url');
    if(url) {
      localStorage.clear();
       window.location = url;
    }
    setTimeout(function() {
          runner();
    }, 500);

}


function showMessage(time){

    $('#myModal').modal('show');
     message  = '<h4>Exam will start at ' + time +'</h4>';
     $('#message').html(message);
}

</script>
@stop
