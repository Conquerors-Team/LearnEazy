@extends('layouts.sitelayoutnew')

@section('content')

<!-- banner part start-->
    <section class="banner_part_inst">
        <div class="container my-lg-5 py-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
          <img src="{{themes('site/img/institute.png')}}" class="img-fluid">
        </div>
                <div class="col-lg-6 order-lg-first">
                    <div class="banner_text pt-4 pt-lg-0">
                        <div class="banner_text_iner">
                            <h1>Run &amp; Organize Your Institute From Anywhere</h1>
                            <p>Comprehensive institute Management System with Live Classes &amp; LMS</p>
                            <a href="https://learneazy.org/institute#" class="btn_1 bg-royal" data-target="#registerModal" data-toggle="modal">Book A Free Demo </a>
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
            <div class="row">
               <div class="col-sm-6 col-xl-3">
                    <div class="single_feature">
                        <div class="single_feature_part">
                            <span class="single_feature_icon bg-orange"><img src="{{themes('site/img/1.png')}}" class="ani-img"></span>
                            <h4>Digitize Your Organization</h4>
                            <p>Administrate, Teach, Monitor and run day to day activities online. </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="single_feature">
                        <div class="single_feature_part">
                            <span class="single_feature_icon bg-orange"><img src="{{themes('site/img/2.png')}}" class="ani-img"></span>
                            <h4>Enticing Learning Tools</h4>
                            <p>Teachers can engage and involve students live classes with best tools.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="single_feature">
                        <div class="single_feature_part">
                            <span class="single_feature_icon bg-orange"><img src="{{themes('site/img/3.png')}}" class="ani-img"></span>
                            <h4 style="padding-bottom: 25px;">Live Class Rooms</h4>
                            <p>Bring your classroom online with best class management and increase your market reach. </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="single_feature">
                        <div class="single_feature_part single_feature_part_2">
                            <span class="single_feature_icon bg-orange"><img src="{{themes('site/img/4.png')}}" class="ani-img"></span>
                            <h4>Monitor Student Performance</h4>
                            <p>With our in-depth analytics track your students’ performance at every stage of learning. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- upcoming_event part start-->

    <!-- learning part start-->
    <section class="advance_feature learning_part">
        <div class="container">
            <div class="row align-items-sm-center align-items-xl-stretch" style="margin-bottom: 130px;">
                <div class="col-md-6 col-lg-6">
                    <div class="learning_member_text">
                       <!--  <h5>Advance feature</h5> -->
                        <h2 class="text-navy">Live Classes</h2>
                        <p>Schedule and Conduct live classes for the students from anywhere and access all the teaching tools in a single window. </p>

                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="learning_img">
                        <img src="{{themes('site/img/live-classes.png')}}" alt="" style="height:350px; float: right;">
                    </div>
                </div>
            </div>
           <div class="clearfix"></div>
             <div class="row align-items-sm-center align-items-xl-stretch" style="margin-bottom: 130px;">
                <div class="col-lg-6 col-md-6">
                    <div class="learning_img">
                        <img src="{{themes('site/img/quality-content.png')}}" alt="" style="height: 300px; float: left;">
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="learning_member_text">
                       <!--  <h5>Advance feature</h5> -->
                        <h2 class="text-navy">Quality Content</h2>
                        <p>Readily available learning material and question bank will help institutes in simplifying academic programs. </p>

                    </div>
                </div>

            </div>
            <div class="row align-items-sm-center align-items-xl-stretch" style="margin-bottom: 130px;">
                <div class="col-md-6 col-lg-6">
                    <div class="learning_member_text">
                       <!--  <h5>Advance feature</h5> -->
                        <h2 class="text-navy">Animations &amp; Live Quizzes</h2>
                        <p>The interactive animations help students in visualizing the concepts and gives the scope for experiment. Students can attempt the live quiz conducted by teacher during the class.</p>

                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="learning_img">
                        <img src="{{themes('site/img/live-quiz.png')}}" alt="" style="height: 300px; float: right;">
                    </div>
                </div>
            </div>
           <div class="clearfix"></div>
             <div class="row align-items-sm-center align-items-xl-stretch" style="margin-bottom: 130px;">
                <div class="col-lg-6 col-md-6">
                    <div class="learning_img">
                        <img src="{{themes('site/img/exam-portal.png')}}" alt="" style="height: 300px; float: left;">
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="learning_member_text">
                       <!--  <h5>Advance feature</h5> -->
                        <h2 class="text-navy">Exam Portal</h2>
                        <p>Create an exam or quiz and assign it to the students from anywhere at anytime with a simple ease.</p>

                    </div>
                </div>

            </div>
              <div class="clearfix"></div>
            <div class="row align-items-sm-center align-items-xl-stretch">
                <div class="col-md-6 col-lg-6">
                    <div class="learning_member_text">
                       <!--  <h5>Advance feature</h5> -->
                        <h2 class="text-navy">Reports &amp; Analytics</h2>
                        <p>Teachers will get the live reports of the quizzes during the live classes. Learneazy provides in depth analysis of the students’ performance in various exams, which will be helpful for the teacher to guide the students.</p>

                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="learning_img">
                        <img src="{{themes('site/img/reports.png')}}" alt="" style="height: 300px; float: right;">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- learning part end-->



    <section class="special_cource padding_top text-center" style="margin-bottom: 30px">
      <div class="container">
        <div class="row">
          <div class="col-md-3">
            <img alt="" src="{{themes('site/img/attendance.png')}}">
            <dl>
              <dt class="text-center">Attendance</dt>
              <dd>Monitor your student attendance for the classes.</dd>
            </dl>
          </div>
          <div class="col-md-3">
            <img alt="" src="{{themes('site/img/from-series.png')}}">
            <dl>
              <dt class="text-center">Exam Series</dt>
              <dd>Monetize your exams online to your students.</dd>
            </dl>
          </div>
          <div class="col-md-3">
            <img alt="" src="{{themes('site/img/online-course.png')}}">
            <dl>
              <dt class="text-center">Online Courses</dt>
              <dd>Create any number of courses for the students.</dd>
            </dl>
          </div>
          <div class="col-md-3">
            <img alt="" src="{{themes('site/img/class-management.png')}}">
            <dl>
              <dt class="text-center">Class Management</dt>
              <dd>Mange your courses, batches and academic program with ease.</dd>
            </dl>
          </div>
        </div>
      </div>
    </section>

@stop