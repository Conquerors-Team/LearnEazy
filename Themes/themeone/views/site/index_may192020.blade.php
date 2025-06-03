@extends('layouts.sitelayout')

@section('content')
 <!-- Hero Header -->
    <header class="hero-header" style="background: url({{IMAGE_PATH_SETTINGS.$home_back_image}}) center center no-repeat;">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="hero-content">
                        <h1 class="cs-hero-title">{{$home_title}}</h1>
                        <div><a href="{{$home_link}}" class="btn btn-primary btn-hero" target="_blank">Get Started</a></div>
                    </div>
                </div>
                <div class="col-md-7">
                    

                        <img src="{{IMAGE_PATH_SETTINGS.$home_image}}" alt="" class="animated fadeInUp img-responsive wow" data-wow-duration="900ms" data-wow-delay="300ms"  >

                    
                </div>
            </div>
        </div>
    </header>


    {{-- Branches Information --}}
    <section class="cs-gray-bg">
        <div class="container">
            <div class="cs-row">
                <div class="row">
                    <div class="col-sm-12 text-center clearfix">
                        <h2 class="cs-section-head">{{getPhrase('our_branches')}}</h2>


                    </div>
                </div>
                <div class="row">
        
                       @if(isset($branches))

                        @foreach($branches as $institute) 
                    <div class="col-md-4 col-sm-6">
                        <!-- Product Single Item -->
                       <div class="cs-product cs-animate">
                            
                            <div class="cs-product-content">
                             <a href="" class="cs-product-title text-center">{{ucfirst($institute->institute_name)}}</a>
                             <br>
                           
                               <ul class="cs-card-actions mt-0">
                                 
                                 <li>{{getPhrase('address')}} :<b> {{$institute->institute_address}}</b></li> <br>
                                 <li>{{getPhrase('phone')}} :<b> {{$institute->phone}}</b></li> <br>
                                 <?php
                                    $today    = date('Y-m-d');
                                    $active_batches  = App\Batch::where('institute_id',$institute->id)
                                                          ->where('start_date','<=',$today)
                                                          ->get()
                                                          ->count();

                                    $upcoming_batches  = App\Batch::where('institute_id',$institute->id)
                                                          ->where('start_date','>',$today)
                                                          ->get()
                                                          ->count();                        
                                 ?>
                                 <li>{{getPhrase('active_batches')}} :<b> {{$active_batches}}</b></li> <br>
                                 <li>{{getPhrase('upcoming_batches')}} :<b> {{$upcoming_batches}}</b></li> <br>

                                </ul>
                                
                                @if($active_batches >0 || $upcoming_batches >0)
                                <div class="text-center mt-2">
                                     <a href="{{ URL_FRONTEND_BATCHES_DETAILS.$institute->id }}" class="btn btn-blue btn-sm btn-radius" style="background-color: #4786ff;">{{getPhrase('view_batches_details')}}</a>
                                </div>
                                @else

                                <div class="text-center mt-2">
                                     <a href="" class="btn btn-blue btn-sm btn-radius" style="background-color: #4786ff;">{{getPhrase('view_batches_details')}}</a>
                                </div>
                                @endif
                           
                            </div>
                        </div>
                    </div>

                        @endforeach
                     @endif  
                   
                </div>
              
            
            </div>
        </div>
    </section>
    <!-- Branches Information End -->
   

    {{-- Quizzes --}}
    <section class="cs-gray-bg">
        <div class="container">
            <div class="cs-row">
                <div class="row">
                    <div class="col-sm-12 text-center clearfix">
                        <h2 class="cs-section-head">{{getPhrase('practice_exams_and_exam_categories')}}</h2>

                        <ul class="nav nav-pills cs-nav-pills text-center">

                           @if(count($categories))

                            @foreach($categories as $category)

                               <li><a href="{{URL_VIEW_ALL_EXAM_CATEGORIES.'/'.$category->slug}}">{{$category->category}}</a></li>

                            @endforeach

                           @else
                             <h4>{{getPhrase('no_practice_exams_are_available')}}</h4> 

                           @endif  
                          
                        </ul>

                    </div>
                </div>
                <div class="row">

                       @if(isset($quizzes))

                        @foreach($quizzes as $quiz) 
                          <div class="col-md-3 col-sm-6">
                        <!-- Product Single Item -->
                       <div class="cs-product cs-animate">
                            <a href="{{URL_FRONTEND_START_EXAM.$quiz->slug}}">
                                <div class="cs-product-img">
                                    @if($quiz->image)
                                    <img src="{{IMAGE_PATH_EXAMS.$quiz->image}}" alt="exam" class="img-responsive">
                                    @else
                                    <img src="{{IMAGE_PATH_EXAMS_DEFAULT}}" alt="exam" class="img-responsive">
                                    @endif
                                </div>
                            </a>
                            <div class="cs-product-content">
                             <a href="{{URL_FRONTEND_START_EXAM.$quiz->slug}}" class="cs-product-title text-center">{{ucfirst($quiz->title)}}</a>

                               <ul class="cs-card-actions mt-0">
                                    <li>
                                        <a href="#">Marks : {{(int)$quiz->total_marks}}</a>
                                    </li>

                                    <li>  </li>

                                   
                                    <li class="cs-right">
                                        <a href="#">{{$quiz->dueration}} mins</a>

                                    </li>


                                </ul>
                                <div class="text-center mt-2">
                                     <a href="{{URL_FRONTEND_START_EXAM.$quiz->slug}}" class="btn btn-blue btn-sm btn-radius">{{getPhrase('start_exam')}}</a>
                                </div>
                            {{--   <a href="{{URL_FRONTEND_START_EXAM.$quiz->slug}}" class="cs-product-title pull-right">{{getPhrase('take_exam')}}</a> --}}
                            </div>
                        </div>
                        <!-- /Product Single Item -->
                    </div>

                        @endforeach
                     @endif  
                   
                </div>
              
              @if(count($categories))
                <div class="row text-center">
                    <ul class="list-inline top40">
                        <li><a href="{{URL_VIEW_ALL_EXAM_CATEGORIES}}" class="btn btn-primary btn-shadow">{{getPhrase('Browse_all_exams')}}</a></li>
                    </ul>
                </div>
               @endif 
            </div>
        </div>
    </section>
    <!-- /End Quizzes -->

    {{-- LMS Categories --}}

     <section class="cs-gr-bg">
        <div class="container">
            <div class="cs-row">
                <div class="row">
                    <div class="col-sm-12 text-center clearfix">
                        <h2 class="cs-section-head">LMS {{getPhrase('categories')}}</h2>

                        <ul class="nav nav-pills cs-nav-pills lms-cats text-center">
                           @if(isset($lms_cates))

                            @foreach($lms_cates as $lms_category)

                               <li><a href="{{URL_VIEW_ALL_LMS_CATEGORIES.'/'.$lms_category->slug}}">{{$lms_category->category}}</a></li>

                            @endforeach

                           @else

                             <h4>{{getPhrase('no_categories_are_available')}}</h4> 

                           @endif  
                          
                        </ul>

                    </div>
                </div>
                <div class="row">

                       @if(isset($lms_series))

                        @foreach($lms_series as $series) 
                          <div class="col-md-3 col-sm-6">
                        <!-- Product Single Item -->
                       <div class="cs-product cs-animate">
                            <a href="{{URL_VIEW_LMS_CONTENTS.$series->slug}}">
                                <div class="cs-product-img">
                                    @if($series->image)
                                    <img src="{{IMAGE_PATH_UPLOAD_LMS_SERIES.$series->image}}" alt="exam" class="img-responsive">
                                    @else
                                    <img src="{{IMAGE_PATH_EXAMS_DEFAULT}}" alt="exam" class="img-responsive">
                                    @endif
                                </div>
                            </a>
                            <div class="cs-product-content mt-0">
                             <a href="{{URL_VIEW_LMS_CONTENTS.$series->slug}}" class="cs-product-title">{{ucfirst($series->title)}}</a>

                               <div class="text-center mt-2">
                                 <a href="{{URL_VIEW_LMS_CONTENTS.$series->slug}}" class=" btn btn-blue btn-sm btn-radius">View </a>
                            </div>
                            </div>
                            
                        </div>
                        <!-- /Product Single Item -->
                    </div>

                        @endforeach
                     @endif  
                   
                </div>

                @if(isset($lms_cates))

                <div class="row text-center">
                    <ul class="list-inline top40">
                        <li><a href="{{URL_VIEW_ALL_LMS_CATEGORIES}}" class="btn btn-primary btn-shadow">{{getPhrase('Browse_all_categories')}}</a></li>
                    </ul>
                </div>

                @endif

            </div>
        </div>
    </section>

    {{-- End LMS Categories --}}

        <!-- Info Boxes -->
    <section class="cs-gray-bg">
        <div class="container">
            <div class="row cs-row">
                <div class="col-sm-4 wow fadeIn" data-wow-duration="1000ms" data-wow-delay="300ms">
                    <!-- Info Box  Centered Single Item -->
                    <div class="cs-info-box-center">
                        <img src="{{IMAGES}}icn-cup.png" alt="" class="wow scaleIn" data-wow-duration="500ms" data-wow-delay="300ms">
                        <h4>{{getPhrase('free_exams')}}</h4>
                        
                    </div>
                    <!-- /Info Box Centered  Single Item -->
                </div>
                <div class="col-sm-4 wow fadeIn" data-wow-duration="1000ms" data-wow-delay="600ms">
                    <!-- Info Box  Centered Single Item -->
                    <div class="cs-info-box-center">
                        <img src="{{IMAGES}}icn-computer.png" alt="" class="wow scaleIn" data-wow-duration="500ms" data-wow-delay="600ms">
                        <h4>{{getPhrase('paid_exams')}}</h4>
                        
                    </div>
                    <!-- /Info Box Centered  Single Item -->
                </div>
                <div class="col-sm-4 wow fadeIn" data-wow-duration="1000ms" data-wow-delay="900ms">
                    <!-- Info Box  Centered Single Item -->
                    <div class="cs-info-box-center">
                        <img src="{{IMAGES}}icn-sett.png" alt="" class="wow scaleIn" data-wow-duration="500ms" data-wow-delay="900ms">
                        <h4>{{getPhrase('learning_management_system')}}</h4>
                       
                    </div>
                    <!-- /Info Box Centered  Single Item -->
                </div>
            </div>
        </div>
    </section>
    <!-- /Info Boxes -->

@stop

@section('footer_scripts')
 
 
 <script>

  $(".cs-nav-pills li").first().addClass("active");
  $(".lms-cats li").first().addClass("active");

</script>
@stop

