@extends('layouts.sitelayout')

@section('custom_div')
 <div ng-controller="preparePacakges">
 @stop

@section('content')
    <!-- breadcrumb start-->
    <section class="breadcrumb breadcrumb_bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb_iner text-center">
                        <div class="breadcrumb_iner_item">
                            <h2>Pricing</h2>
                            <p>Home<span>/</span>Pricing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb start-->

    <!-- feature_part start-->
    <section class="pricing-columns pricing-section">
  <label class="toggler toggler--is-active" id="filt-monthly">Institute</label>
  <div class="toggle">
    <input type="checkbox" id="switcher" class="check">
    <b class="b switch"></b>
  </div>
  <label class="toggler" id="filt-hourly">Students</label>
  <div id="institute" class="wrapper-full">
    <br><br>
    <div class="container">

    <div class="panel pricing-table">
        <?php

            $packages = \App\Package::get();
            foreach($packages as $key=>$row){
              $examSettings = getExamSettings();

              if( $row->image == null){
          $row->image = "default.png";
        }

          ?>

      <div class="pricing-plan">
        <img src="{{ PREFIX.$examSettings->courseImagepath.$row->image }}" alt="" class="pricing-img" style="height: 180px;">
        <h2 class="pricing-header">{{$row->title}}<p class="grey">{{$row->short_description}}</p></h2>
        <ul class="pricing-features">
          <li class="pricing-features-item"><i class="fa fa-check" style="color:#4CAF50;"></i> Online Videos</li>
          <li class="pricing-features-item"><i class="fa fa-check" style="color:#4CAF50;"></i> LIVE Classes</li>
        </ul>
       <!--  <span class="pricing-price">Free</span> -->
        <a  href="{{route('user.register')}}" class="pricing-button pricing-price">₹ {{$row->cost}}</a>
      </div>
      <?php
       }
       ?>




    </div>
  </div>
    </div>
<div id="students" class="wrapper-full hide">
<div class="container">
    <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="text-center animation animated fadeInUp" data-animation="fadeInUp" data-animation-delay="0.01s" style="animation-delay: 0.01s; opacity: 1;">
                    <div class="heading_s1 text-center">
                        <br><br>
                        <!-- <h2>Select Classes</h2> -->
                    </div>

                   <p></p><form method="post" name="enq" class="pt-md-2">
                         <?php
                  $institute_id   = OWNER_INSTITUTE_ID;

                  $classes = \App\StudentClass::where('institute_id', $institute_id )->get()->pluck('name', 'id')->prepend('Select class', '')->toArray();

                  $courses = [];

            ?>

                        <div class="row">
                            <div class="form-group col-sm-1 txt-top"></div>
                            <div class="form-group col-sm-5">
                                <div class="custom_select">
                                	{{ Form::label('class_id', getphrase('classes')) }} <span class="text-red">*</span>
									{{Form::select('class_id', $classes, null, ['class'=>'form-control', "id"=>"class_id", 'onChange' => 'classChanged()'])}}

                                </div>
                            </div>



                             <div class="form-group col-sm-1 txt-top"></div>
                            <div class="form-group col-sm-5">
                                <div class="custom_select">
									{{ Form::label('course_id', getphrase('courses')) }} <span class="text-red">*</span>
									{{Form::select('course_id', $courses, null, ['class'=>'form-control', "id"=>"course_id",'onChange' => 'courseChanged()','placeholder' => 'Please select'])}}
                                </div>
                            </div>


                        </div>

                    </form> <p></p>
                </div>
            </div>
        </div><br><br>
    <div class="panel pricing-table" id="pack-data">
    </div>
  </div>
  </div>
</section>
  <script>
        var e = document.getElementById("filt-monthly"),
    d = document.getElementById("filt-hourly"),
    t = document.getElementById("switcher"),
    m = document.getElementById("institute"),
    y = document.getElementById("students");

e.addEventListener("click", function(){
  t.checked = false;
  e.classList.add("toggler--is-active");
  d.classList.remove("toggler--is-active");
  m.classList.remove("hide");
  y.classList.add("hide");
});

d.addEventListener("click", function(){
  t.checked = true;
  d.classList.add("toggler--is-active");
  e.classList.remove("toggler--is-active");
  m.classList.add("hide");
  y.classList.remove("hide");
});

t.addEventListener("click", function(){
  d.classList.toggle("toggler--is-active");
  e.classList.toggle("toggler--is-active");
  m.classList.toggle("hide");
  y.classList.toggle("hide");
})
    </script>
    <!-- upcoming_event part start-->
    @include('site.scripts.pricing-scripits')
    @include('site.login-register-modals')
@stop