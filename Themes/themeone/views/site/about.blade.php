@extends('layouts.sitelayoutnew')

@section('content')
    <!-- breadcrumb start-->
    <section class="breadcrumb breadcrumb_bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb_iner text-center">
                        <div class="breadcrumb_iner_item">
                            <h2>About Us</h2>
                            <p>Home<span>/</span>About Us</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb start-->


    <!-- learning part start-->
    <section class="learning_part">
        <div class="container">
            <div class="row align-items-sm-center align-items-lg-stretch">
                <div class="col-md-7 col-lg-7">
                    <div class="learning_img">
                        <img src="{{themes('site/img/learning_img.png')}}" alt="">
                    </div>
                </div>
                <div class="col-md-5 col-lg-5">
                    <div class="learning_member_text">
                        <h5>About us</h5>
                        <h2>Learning with Love
                            and Laughter</h2>
                        <p>We as a team belive every child deserves quality education enriched with experimental learning guided by best teaching methodolgy and teachers.</p>
                        <ul>
                            <li><span class="ti-pencil-alt"></span>This platform provides the best learning solutions for the students in the pursuit of realizing their dreams.</li>
                            <!-- <li><span class="ti-ruler-pencil"></span>Fly female them whales fly them day deep given                                night.</li> -->
                        </ul>
                        <!-- <a href="#" class="btn_1">Read More</a> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- learning part end-->


    <!-- feature_part start-->
    <!-- <section class="feature_part single_feature_padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-xl-3 align-self-center">
                    <div class="single_feature_text ">
                        <h2>Awesome <br> Feature</h2>
                        <p>The Schools and Teachers can monitor child's learning patterns with ease to give the best guidance.</p>
                        <a href="#" class="btn_1">Read More</a>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="single_feature">
                        <div class="single_feature_part">
                            <span class="single_feature_icon"><i class="ti-layers"></i></span>
                            <h4>Methodology</h4>
                            <p>The Teaching method of LEARNEAZY is designed by th best teaching minds of the country</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="single_feature">
                        <div class="single_feature_part">
                            <span class="single_feature_icon"><i class="ti-new-window"></i></span>
                            <h4>Virtual Assitant</h4>
                            <p>Students and Teachers can interact in live classes with best tools</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="single_feature">
                        <div class="single_feature_part single_feature_part_2">
                            <span class="single_service_icon style_icon"><i class="ti-light-bulb"></i></span>
                            <h4>Explore</h4>
                            <p>The interactive animations provide the scope for experimentation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- upcoming_event part start-->

           <!-- feature_part start-->
    <section class="feature_part" style="margin-bottom: 30px;">
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

    @include('site.login-register-modals')
@stop