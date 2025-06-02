@extends($layout)

@if( $subject )
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
@endif

@section('content')

@if( $subject )
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
@endif

  <div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					 <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
					 <li><a href="{{route('lms-groups.index')}}">LMS groups</a> </li>
					 @if( $subject )
					 <li><a href="{{route('lms-groups.show', ['slug' => $group->slug])}}">{{$group->title}}</a> </li>
					 @endif
					<li>{{ $title}}</li>
				</ol>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel-heading">Details of  @if($subject) {{$subject->subject_title}} in @endif <font color="green">{{$group->title}} Group</font>
				</div>
				<div class="panel-body" >
					<?php
					$institute_id   = adminInstituteId();

			 		$faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
			 		$lmssubjects = \App\LmsGroup::select(['subjects.*', 'lmsgroups.slug as group_slug'])
			 			->join('lmsseries_lmsgroups', 'lmsseries_lmsgroups.lmsgroups_id', '=', 'lmsgroups.id')
			 			->join('lmsseries', 'lmsseries.id', '=', 'lmsseries_lmsgroups.lmsseries_id')
						->join('subjects', 'subjects.id', '=', 'lmsseries.subject_id')
			 			->whereIn('lmsseries.subject_id', $faculty_subjects)
			 			->where('lmsgroups.institute_id', $institute_id)
			 			->groupBy('lmsseries.subject_id')->orderBy('lmsgroups.updated_at', 'desc')->get();
			 		?>

			 	<div class="row">
					<div class="col-md-12">
						@if( $subject_slug )
							 <div class="row library-items">
							 <?php $settings = getSettings('lms');
	                        $chapters = $subject->chapters()->select(['chapters.*'])
	                        ->join('lmsseries', 'lmsseries.chapter_id', '=', 'chapters.id')
	                        ->join('lmsseries_lmsgroups', 'lmsseries_lmsgroups.lmsseries_id', '=', 'lmsseries.id')
	                        ->join('lmsgroups', 'lmsgroups.id', '=', 'lmsseries_lmsgroups.lmsgroups_id')
	                        ->where('lmsgroups.slug', $group->slug)
	                        ->groupBy('lmsseries.chapter_id')
	                        ->get();
	                        /*
	                        echo printSql($subject->chapters()->select(['chapters.*'])
	                        ->join('lmsseries', 'lmsseries.chapter_id', '=', 'chapters.id')
	                        ->join('lmsseries_lmsgroups', 'lmsseries_lmsgroups.lmsseries_id', '=', 'lmsseries.id')
	                        ->join('lmsgroups', 'lmsgroups.id', '=', 'lmsseries_lmsgroups.lmsgroups_id')
	                        ->where('lmsgroups.slug', $group->slug)
	                        ->groupBy('lmsseries.chapter_id'));
	                        */
	                        ?>
	                        @forelse( $chapters as $chapter )
	                        <?php
	                        // $topics = $chapter->topics()->get();
	                        $lmsseries = $chapter->topics()->select(['lmsseries.*'])
	                        	->join('lmsseries', 'lmsseries.topic_id', '=', 'topics.id')
	                        	->join('lmsseries_lmsgroups', 'lmsseries_lmsgroups.lmsseries_id', '=', 'lmsseries.id')
	                        	->groupBy('lmsseries_lmsgroups.lmsseries_id')
	                        	->get();
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
						@else
						<div class="white_bgcurve coursesList">
							<h5>Subjects</h5>
							<div class="white_bgcurve" style="clear:both; ">
								@forelse( $lmssubjects as $subject )
								<?php
								$settings = getExamSettings();
								$image = $settings->defaultCategoryImage;
								if(isset($subject->image) && $subject->image!='') {
									$image = $subject->image;
								}
								?>
								<div class="col-md-3">
								<div class="item-image">
								<img src="{{ PREFIX.$settings->subjectsImagepath.$image}}" alt="{{$subject->subject_title}}" width="150">
								</div>
								<a href="{{route('lms-groups.show', ['slug' => $subject->group_slug, 'subject_slug' => $subject->slug])}}" title="{{$subject->subject_title}}">{{$subject->subject_title}}</a>
								</div>
								@empty
									<p>No Subjects</p>
								@endforelse
							</div>
						</div>
						@endif
					</div>
			</div>

				</div>
			</div>
		</div>
	</div>
</div>

@stop

@section('footer_scripts')
@if( ! empty( $chart_data ) )
	@include('common.chart-stack', array('chart_data'=>$chart_data,'ids' =>array('batch_report_graph'), 'scale'=>TRUE))
@endif

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


  </script>

@stop