@extends($layout)

@section('header_scripts')
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

                            <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>

                            <!-- <li><a href="{{URL_STUDENT_SUBJECTS}}"><i class="icon-books"></i>&nbsp;Subjects</a> </li> -->

                            <li class="active"> {{ $title }} </li>

                        </ol>

                    </div>

                </div>

                <!-- /.row -->

                <div class="panel panel-custom">

                    <div class="panel-heading">

                        <?php
                        $student_batches = getStudentBatches();
                        $institute_id   = adminInstituteId();
                        $examSettings = getExamSettings();
                        ?>
                        <div>
                        <img src="{{ PREFIX.$examSettings->subjectsImagepath.$subject->image }}" height="100" width="100" >
                        <span style="color:{{$subject->color_code}}">{{ $subject->subject_title }}</span>
                        </div>

                        <ul class="nav nav-tabs">
                          @if(isOnlinestudent())
                          <li><a href="{{URL_STUDENT_EXAMS.$subject->slug . '/chapter'}}">Chapter Tests</span></a></li>
                          @else
                          <li><a href="{{URL_STUDENT_EXAMS.$subject->slug . '/subject'}}">Exams</span></a></li>
                          @endif
                          <li class="active"><a href="{{route('student.lms_notes', $subject->slug)}}">Notes</span></a></li>
                          <li><a href="{{route('studentlms.subjectitems', ['slug' => $subject->slug])}}">LMS</a></li>
                        </ul>

                    </div>

                    @if ( $series )
                        <div class="panel-body packages">
                            <p><b style="font-weight: 600;">{{$series->title}}</b>
                            <a href="javascript:void(0);" onclick="javascript:history.back();" class="pull-right">Back</a>
                            </p>

                            @if( $series )
                                @include('student.notes.series-items', array('series'=>$series))
                            @else
                            <div>No content found</div>
                            @endif
                        </div>
                    @else
                    <div class="panel-body packages">
                        <div class="row library-items">
                            <?php $settings = getSettings('lms');
                            $chapters = $subject->chapters()->select(['chapters.*'])->join('lmsseries', 'lmsseries.chapter_id', '=', 'chapters.id')->join('batch_lmsseries', 'batch_lmsseries.lms_series_id', '=', 'lmsseries.id')->whereIn('batch_lmsseries.batch_id', $student_batches)->groupBy('lmsseries.chapter_id')->get();
                            ?>
                            @forelse( $chapters as $chapter )
                            <?php
                            // $topics = $chapter->topics()->get();
                            $lmsseries = $chapter->topics()->select(['lmsseries.*'])->join('lmsseries', 'lmsseries.topic_id', '=', 'topics.id')->join('batch_lmsseries', 'batch_lmsseries.lms_series_id', '=', 'lmsseries.id')->whereIn('batch_lmsseries.batch_id', $student_batches)->get();
                            // dd( $chapters );
                            ?>
                            <div>
                            <li class="list-group-item"><b style="color: {{$subject->color_code}}">{{$chapter->chapter_name}}</b></li>
                            </div>
                            @if ( $lmsseries->count() > 0 )
                            <section class="customer-logos slider">
                                @forelse( $lmsseries as $single )
                                    <div class="slide">
                                        @if( isOnlinestudent() )
                                            @if($single->is_paid && !isItemPurchased($single->id, 'paidcontent') )
                                            <p>
                                            <a  href="javascript:void(0);" onclick="suggestPackage('{{$single->slug}}', 'lmsseries')">Buy now</a>
                                            </p>
                                            <p style="font-size: x-large;">{{$single->title}}</p>
                                            @else
                                            <p><a href="{{route('studentlms.subjectitems', ['slug' => $subject->slug, 'series_slug' => $single->slug])}}" style="color: #337ab7;">View More</a></p>
                                            <p style="font-size: x-large;">{{$single->title}}</p>
                                            @endif
                                        @else
                                        <p><a href="{{route('studentlms.subjectitems', ['slug' => $subject->slug, 'series_slug' => $single->slug])}}" style="color: #337ab7;">View More</a></p>
                                        <p style="font-size: x-large;">{{$single->title}}</p>
                                        @endif
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
                    @endif
                    </div>
                </div>
            </div>
</div>
<!-- /#page-wrapper -->

@include('student.packages-modal')

@stop

@section('footer_scripts')

@include('student.packages-modal-scripts')
<!-- <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script> -->
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