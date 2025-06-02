@extends('layouts.sitelayout')

@section('content')


    <!-- banner part start-->
    <section class="banner_part">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-xl-6">
                    <div class="banner_text">
                        <div class="banner_text_iner">
                            <h5>The best Learning Platform for classes 8th – 12th</h5>
                            <h1>Experiment, Observe and Conclude – The Right way of Learning</h1>
                            <p>Making you child's learning a memorable journey with best teaching solutions</p>
                           <!--  <a href="#" class="btn_1">View Course </a>
                            <a href="#" class="btn_2">Get Started </a> -->
                            <br>

                            {!! Form::open(array('route' => ['user-otp.register'], 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"loginform", 'name'=>"registrationForm")) !!}
                            @include('errors.errors')
                            <p class="animation ph-container" data-animation="zoomIn" data-animation-delay="1.5s" style="z-index: 99!important;position: relative!important; height: 35px; margin-bottom: 40px;">
                                  <input id="phone" name="phone" type="tel" placeholder="Phone Number" style="height: 40px;">
                                    <!-- <span id="valid-msg" class="hide">Valid</span> -->
                                    <!-- <span id="error-msg" class="hide">Invalid number</span> -->
                                    <button type="submit" class="btn-gradient">SIGN UP</button>

                                </p>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner part start-->


       <!-- feature_part start-->
    <section class="feature_part">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5">
                    <div class="section_tittle text-center">
                        <!-- <p>popular courses</p> -->
                        <h2>Salient Features</h2>
                    </div>
                </div>
            </div>
            <div class="row">
               <div class="col-sm-6 col-xl-4">
                    <div class="single_feature">
                        <div class="single_feature_part">
                            <span class="single_feature_icon"><img src="{{themes('site/img/3.png')}}" class="ani-img"></span>
                            <h4>Live Classes</h4>
                            <p>Join India’s best faculties in live classes with interactive animations and live quizzes.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="single_feature">
                        <div class="single_feature_part">
                            <span class="single_feature_icon"><img src="{{themes('site/img/2.png')}}" class="ani-img"></span>
                            <h4>Interactive Animations</h4>
                            <p>Learn concepts through experimentation with our interactive animations.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="single_feature">
                        <div class="single_feature_part">
                            <span class="single_feature_icon"><img src="{{themes('site/img/3.png')}}" class="ani-img"></i></span>
                            <h4>Learning Content</h4>
                            <p>Best content apt for consolidation of various concepts.</p>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-6 col-xl-4">
                    <div class="single_feature">
                        <div class="single_feature_part single_feature_part_2">
                            <span class="single_feature_icon"><img src="{{themes('site/img/4.png')}}" class="ani-img"></span>
                            <h4>Practice Tests</h4>
                            <p>Assess your abilities with our micro level testing.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="single_feature">
                        <div class="single_feature_part single_feature_part_2">
                            <span class="single_feature_icon"><img src="{{themes('site/img/4.png')}}" class="ani-img"></span>
                            <h4>Virtual Assistant</h4>
                            <p>Any doubt while learning, take the help of our virtual assistant.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- upcoming_event part start-->

    <!-- feature_part start-->
   <!--  <section class="feature_part">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-xl-3 align-self-center">
                    <div class="single_feature_text ">
                        <h2>Salient <br> Features</h2>
                        <p>Set have great you male grass yielding an yielding first their you're
                            have called the abundantly fruit were man </p>
                     
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="single_feature">
                        <div class="single_feature_part">
                            <span class="single_feature_icon"><i class="ti-layers"></i></span>
                            <h4>Better Future</h4>
                            <p>Set have great you male grasses yielding yielding first their to
                                called deep abundantly Set have great you male</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="single_feature">
                        <div class="single_feature_part">
                            <span class="single_feature_icon"><i class="ti-new-window"></i></span>
                            <h4>Qualified Trainers</h4>
                            <p>Set have great you male grasses yielding yielding first their to called
                                deep abundantly Set have great you male</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="single_feature">
                        <div class="single_feature_part single_feature_part_2">
                            <span class="single_service_icon style_icon"><i class="ti-light-bulb"></i></span>
                            <h4>Job Oppurtunity</h4>
                            <p>Set have great you male grasses yielding yielding first their to called deep
                                abundantly Set have great you male</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- upcoming_event part start-->
<br><br><br>
         <section class="special_cource padding_top" style="margin-bottom: 30px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5">
                    <div class="section_tittle text-center">
                        <!-- <p>popular courses</p> -->
                        <h2>Special Courses</h2>
                    </div>
                </div>
            </div>
             <div class="row">
                  <div class="col-sm-6 col-lg-3">
                    <div class="single_special_cource text-center">
                         <a href="#" class="btn_4"><strong>11/12th</strong></a>
                        <img src="{{themes('site/img/sub3.jpg')}}" class="special_img" alt="">
                        <h6 class="text_margin"><b>JEE</b>-CBSE/ State Board</h6>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="single_special_cource text-center">
                         <a href="#" class="btn_4"><strong>11/12th</strong></a>
                        <img src="{{themes('site/img/sub1.jpg')}}" class="special_img" alt="">
                        <h6 class="text_margin"><b>NEET</b>-CBSE/ State Board</h6>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="single_special_cource text-center">
                         <a href="#" class="btn_4"><strong>8/9/10th</strong></a>
                        <img src="{{themes('site/img/sub2.jpg')}}" class="special_img" alt="">
                        <h6 class="text_margin">Foundation(JEE/NEET)</h6>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="single_special_cource text-center">
                         <a href="#" class="btn_4"><strong>8/9/10th</strong></a>
                        <img src="{{themes('site/img/sub1.jpg')}}" class="special_img" alt="">
                        <h6 class="text_margin">CBSE/ State Board</h6>
                    </div>
                </div>
             </div>
             <br><br>
               <!-- <div class="row">
                  <div class="col-sm-6 col-lg-3">
                    <div class="single_special_cource text-center">
                         <a href="#" class="btn_4">11/12th</a>
                        <img src="{{themes('site/img/sub3.jpg')}}" class="special_img" alt="">
                        <h6 class="text_margin">CBSE/ State Board</h6>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="single_special_cource text-center">
                         <a href="course-details.html" class="btn_4">11/12th</a>
                        <img src="{{themes('site/img/sub1.jpg')}}" class="special_img" alt="">
                        <h6 class="text_margin">CBSE/ State Board</h6>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="single_special_cource text-center">
                         <a href="course-details.html" class="btn_4">11/12th</a>
                        <img src="{{themes('site/img/sub2.jpg')}}" class="special_img" alt="">
                        <h6 class="text_margin">CBSE/ State Board</h6>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="single_special_cource text-center">
                         <a href="course-details.html" class="btn_4">11/12th</a>
                        <img src="{{themes('site/img/sub1.jpg')}}" class="special_img" alt="">
                        <h6 class="text_margin">CBSE/ State Board</h6>
                    </div>
                </div>
             </div> -->
        </div>
    </section>

    <!-- learning part start-->
    <!-- <section class="learning_part">
        <div class="container">
            <div class="row align-items-sm-center align-items-lg-stretch">
                <div class="col-md-7 col-lg-7">
                    <div class="learning_img">
                        <img src="img/learning_img.png" alt="">
                    </div>
                </div>
                <div class="col-md-5 col-lg-5">
                    <div class="learning_member_text">
                        <h5>About us</h5>
                        <h2>Learning with Love
                            and Laughter</h2>
                        <p>Fifth saying upon divide divide rule for deep their female all hath brind Days and beast
                            greater grass signs abundantly have greater also
                            days years under brought moveth.</p>
                        <ul>
                            <li><span class="ti-pencil-alt"></span>Him lights given i heaven second yielding seas
                                gathered wear</li>
                            <li><span class="ti-ruler-pencil"></span>Fly female them whales fly them day deep given
                                night.</li>
                        </ul>
                        <a href="#" class="btn_1">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- learning part end-->

    <!-- member_counter counter start -->
   <!--  <section class="member_counter">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="single_member_counter">
                        <span class="counter">1024</span>
                        <h4>All Teachers</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single_member_counter">
                        <span class="counter">960</span>
                        <h4> All Students</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single_member_counter">
                        <span class="counter">1020</span>
                        <h4>Online Students</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single_member_counter">
                        <span class="counter">820</span>
                        <h4>Ofline Students</h4>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- member_counter counter end -->

<br>
        <!-- member_counter counter start -->
        <section class="member_counter advance_feature">
            <div class="container">
                <div class="row">
                    <!-- App download -->
                    <div class="col-md-6">
                        <div class="home_container_inner">
                            <img src="{{themes('site/img/computer-screen.png')}}">
                        </div>
                    </div>
                    <div class="col-md-6 home_downloadApp_rightContent">
                        <div class="home_downloadApp_header">
                            Download the app for FREE now
                        </div>
                        <div class="home_downloadApp_trailText">
                            Get a 5-day free trial
                        </div>
                        <div class="appLinks_margin">
                            <a class="btn_2" href="#" data-animation="zoomIn" data-animation-delay="1.8s">
                                Coming Soon
                            </a>
                            <!-- <a class="appLinks_button animation" href="#" data-animation="zoomIn" data-animation-delay="1.8s">
                                <img src="img/brand-apple.png">
                            </a> -->
                        </div>
                    </div>
                    <!-- app download ends -->
                </div>
            </div>
        </section>
        <!-- member_counter counter end -->



    <!-- learning part start-->
    <section class="advance_feature learning_part">
        <div class="container">
            <div class="row align-items-sm-center align-items-xl-stretch">
                <div class="col-md-6 col-lg-6">
                    <div class="learning_member_text">
                        <h5>Advance feature</h5>
                        <h2>Advance Learning System</h2>
                        <p>It gives the scope for the studen to learn at his individual pace of learning filling the gaps in the learning.</p>
                        <div class="row">
                            <div class="col-sm-6 col-md-12 col-lg-6">
                                <div class="learning_member_text_iner">
                                    <span class="ti-pencil-alt"></span>
                                    <h4>Learn Anywhere</h4>
                                    <p>Plaform brings the realtime class room experience with latest learning tools</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-12 col-lg-6">
                                <div class="learning_member_text_iner">
                                    <span class="ti-stamp"></span>
                                    <h4>Expert Teacher</h4>
                                    <p>The plaform brings the best teaching minds in the country to the student.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="learning_img">
                        <img src="{{themes('site/img/advance_feature_img.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- learning part end-->

    <!--::blog_part end::-->
    @include('site.login-register-modals')
@stop

@section('footer_scripts')


 <script>

  $(".cs-nav-pills li").first().addClass("active");
  $(".lms-cats li").first().addClass("active");

</script>
@stop

