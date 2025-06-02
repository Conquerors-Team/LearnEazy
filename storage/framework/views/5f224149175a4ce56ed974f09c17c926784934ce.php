<?php $__env->startSection('content'); ?>
    <div ng-controller="preparePacakges">

    <!-- feature_part start-->
     <section class="pricing-columns pricing-section" style="padding: 120px 0 10px 0 ;">
      <h3 class="head-title-1" style="color: #ee390f; font-weight: 600;">Students</h3>
  <!-- <label class="toggler toggler--is-active" id="filt-monthly">Institute</label>
  <div class="toggle">
    <input type="checkbox" id="switcher" class="check">
    <b class="b switch"></b>
  </div>
  <label class="toggler" id="filt-hourly">Students</label>
  <div id="institute" class="wrapper-full" > -->

    </div>
<div id="students" class="wrapper-full">
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
                                  <?php echo e(Form::label('class_id', getphrase('classes'))); ?> <span class="text-red">*</span>
                  <?php echo e(Form::select('class_id', $classes, null, ['class'=>'form-control', "id"=>"class_id", 'onChange' => 'classChanged()'])); ?>


                                </div>
                            </div>



                             <div class="form-group col-sm-1 txt-top"></div>
                            <div class="form-group col-sm-5">
                                <div class="custom_select">
                  <?php echo e(Form::label('course_id', getphrase('courses'))); ?> <span class="text-red">*</span>
                  <?php echo e(Form::select('course_id', $courses, null, ['class'=>'form-control', "id"=>"course_id",'onChange' => 'courseChanged()','placeholder' => 'Please select'])); ?>

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
</div>
</section>
</div>
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
   <?php echo $__env->make('site.scripts.pricing-scripits', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sitelayoutnew', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>