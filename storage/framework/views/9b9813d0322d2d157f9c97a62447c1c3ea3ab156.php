<?php $__env->startSection('content'); ?>

 <section class="pricing-columns pricing-section">
  <div class="container">
        <div class="section-title">
          <h3>LEARNEAZY <span>Register with Mobile OTP</span></h3>
        </div>

      <section class="contact-section">

        <div class="container-fluid">
          <div class="row cs-row whiteBg marginbot0" style="padding-top: 50px; padding-bottom: 50px;">
         	 <div class="col-md-3 registerBg">
            <!-- <div class="signup__overlay">
                <div class="thumbnail__content">
                    <h1 class="heading--primary">Welcome to LERN EASY</h1>
                    <div class="loginLeft_text">
                    <h2 class="heading--secondary">Register to :</h2>
                    <ul class="how-to-join">
                        <li>Get Access to the complete exam calendar</li>
                        <li>Get indepth information on every stage of the exam cycle</li>
                        <li>Apply directly to the  colleges which accept your exam score</li>
                        <li>Get personalised information about upcoming exams</li>
                        <li>Know about the trending exam of the month</li>

                    </ul>
                  </div>
                </div>
            </div> -->
          </div>

        <div class="col-lg-6">
        	<!-- <div class="col-md-12">
        	<span class="pull-right" style="margin-top:10px;">
          	<?php if(request()->segment(2) == null): ?>
          	<a href="<?php echo e(route('user.register', ['role' => 'institute'])); ?>" class="pull-right btn_1">Click to Register as Institute</a>
          	<?php else: ?>
          	<a href="<?php echo e(route('user.register')); ?>" class="pull-right btn_1">Click to Register as Student</a></h2>
          	<?php endif; ?>
          </span>
      </div> -->
        	<div class="cs-box-resize-sign login-box">
                   <h4 class="text-center login-head"><?php echo e(getPhrase('create_account')); ?></h4>
                   <?php echo Form::open(array('url' => url()->current(), 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"loginform", 'name'=>"registrationForm")); ?>


                        <?php echo $__env->make('errors.errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        <?php
                        $otp_status = (session("otp_status")) ? session("otp_status") : 'start';
                        $phone = (session("phone")) ? session("phone") : old('phone');
                        $phone_status = ('sent' === $otp_status ) ? ' disabled' : '';
                        ?>
                        <div class="form-group">
                        <label for="email"><?php echo e(getPhrase('mobile_number')); ?></label><span style="color: red;">*</span>
                        <input type="hidden" name="phone_code" value="91">
                        <?php if( in_array( $otp_status, ['sent', 'verified']) ): ?>
                        <input type="number" name="phone" class="form-control" value="<?php echo e($phone); ?>" readonly="">
                        <?php else: ?>
                        <?php echo e(Form::number('phone', $value = $phone , $attributes = array('class'=>'form-control',
									'placeholder' => getPhrase("mobile_number"),
									'ng-model'=>'phone',
									'required'=> 'true',
									'ng-minlength' => '10',
									'ng-class'=>'{"has-error": registrationForm.phone.$touched && registrationForm.phone.$invalid}',
								))); ?>

							<div class="validation-error" ng-messages="registrationForm.phone.$error" >
								<?php echo getValidationMessage(); ?>

								<?php echo getValidationMessage('minlength'); ?>

							</div>
						<?php endif; ?>
                        </div>

                        <?php if( 'sent' === $otp_status ): ?>
                        	<div class="form-group">
	                        <label for="email">OTP</label><span style="color: red;">*</span>
	                        <input type="hidden" name="phone_code" value="91">
	                        <?php echo e(Form::number('otp', $value = null , $attributes = array('class'=>'form-control',
										'placeholder' => 'OTP',
										'required'=> 'true',
										'ng-class'=>'{"has-error": registrationForm.otp.$touched && registrationForm.phone.$invalid}',
									))); ?>

								<div class="validation-error" ng-messages="registrationForm.otp.$error" >
									<?php echo getValidationMessage(); ?>

								</div>
	                        </div>
                        <?php elseif( 'verified' === $otp_status ): ?>

                       <!--  <div class="form-group">
						<label for="register_type">Register As</label><span style="color: red;">*</span>
						<?php
						$register_types = [
							'' => 'Please select',
							'student' => 'Student',
							'institute' => 'Institute',
						];
						?>
						<?php echo e(Form::select('register_type', $register_types, null, ['class'=>'form-control',
						'ng-model'=>'register_type',
						'required'=> 'true',
						'ng-class'=>'{"has-error": registrationForm.register_type.$touched && registrationForm.register_type.$invalid}',
						])); ?>

						<div class="validation-error" ng-messages="registrationForm.register_type.$error" >
						<?php echo getValidationMessage(); ?>

						</div>
						</div>
 -->
                        <input type="hidden" name="register_type" id="register_type" value="student">
                        
                        <!-- <div class="form-group" ng-if="register_type == 'institute'">
                        	<label for="name"><?php echo e(getPhrase('institute_name')); ?></label><span style="color: red;">*</span>
							<?php echo e(Form::text('institute_name', $value = null , $attributes = array('class'=>'form-control',
								'placeholder' => getPhrase("institute_name"),
								'ng-model'=>'institute_name',
								'ng-pattern' => getRegexPattern('name'),
								'required'=> 'true',
								'ng-class'=>'{"has-error": registrationForm.institute_name.$touched && registrationForm.institute_name.$invalid}',
								'ng-minlength' => '4',
							))); ?>

							<div class="validation-error" ng-messages="registrationForm.institute_name.$error" >
								<?php echo getValidationMessage(); ?>

								<?php echo getValidationMessage('minlength'); ?>

								<?php echo getValidationMessage('pattern'); ?>

							</div>
                        </div> -->

                       <!--  <div class="form-group" ng-if="register_type == 'institute'">
                        	<label for="name"><?php echo e(getPhrase('institute_address')); ?></label><span style="color: red;">*</span>
							<?php echo e(Form::textarea('institute_address', $value = null , $attributes = array('class'=>'form-control',
								'placeholder' => getPhrase("institute_address"),
								'ng-model'=>'institute_address',
								'ng-pattern' => getRegexPattern('name'),
								'required'=> 'true',
								'ng-class'=>'{"has-error": registrationForm.institute_address.$touched && registrationForm.institute_address.$invalid}',
								'rows' => '4',
								'ng-minlength' => '4',
							))); ?>

							<div class="validation-error" ng-messages="registrationForm.institute_address.$error" >
								<?php echo getValidationMessage(); ?>

								<?php echo getValidationMessage('minlength'); ?>

								<?php echo getValidationMessage('pattern'); ?>

							</div>
                        </div> -->

						<div class="form-group" ng-if="register_type == 'student'">
						<label for="student_class_id"><?php echo e(getPhrase('class')); ?></label><span style="color: red;">*</span>
						 <?php
                                $classes = \App\StudentClass::where('institute_id', OWNER_INSTITUTE_ID)->get()->pluck('name', 'id')->prepend('Please select class', '');
                                ?>
                                <?php echo e(Form::select('student_class_id', $classes, null, [
                                'ng-model'=>'student_class_id',
                                'id' => 'student_class_id',
                                'class' => 'form-control',
                                'ng-class'=>'{"has-error": registrationForm.student_class_id.$touched && registrationForm.student_class_id.$invalid}',
                                'onChange' => 'getCourses()'
                                ])); ?>

						<div class="validation-error" ng-messages="registrationForm.student_class_id.$error" >
						<?php echo getValidationMessage(); ?>

						</div>
						</div>

						<div cclass="form-group" ng-if="register_type == 'student'">
                            <label for="course_id"><?php echo e(getPhrase('course')); ?></label><span style="color: red;">*</span>
                           <?php
	                            $courses = \App\Course::where('institute_id', OWNER_INSTITUTE_ID)->get()->pluck('title', 'id')->prepend('Please select course', '0');
	                            ?>
	                            <?php echo e(Form::select('course_id', $courses, null, [
	                            'ng-model'=>'course_id',
	                            'id' => 'course_id',
	                            'class' => 'form-control',
	                            'ng-class'=>'{"has-error": registrationForm.course_id.$touched && registrationForm.course_id.$invalid}',
	                            ])); ?>

                        </div>

						<div class="form-group" ng-if="register_type == 'student'">
                            <label for="student_class_id"><?php echo e(getPhrase('board')); ?></label><span style="color: red;">*</span>
                            <?php
                            $boards = \App\Board::where('status', 'active')->get()->pluck('title', 'id')->prepend('Please select board', '');
                            ?>
                            <?php echo e(Form::select('board_id', $boards, null, [ 'class' => 'form-control',
                            'ng-model'=>'board_id',
                            'id' => 'board_id',
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": registrationForm.board_id.$touched && registrationForm.board_id.$invalid}',
                            ])); ?>

                            <div class="select_arrow">
                            </div>
                        </div>


                        <div class="form-group">
                        	<label for="name"><?php echo e(getPhrase('your_name')); ?></label><span style="color: red;">*</span>
						   <?php echo e(Form::text('name', $value = null , $attributes = array('class'=>'form-control',
									'placeholder' => getPhrase("your_name"),
									'ng-model'=>'name',
									'ng-pattern' => getRegexPattern('name'),
									'required'=> 'true',
									'ng-class'=>'{"has-error": registrationForm.name.$touched && registrationForm.name.$invalid}',
									'ng-minlength' => '4',
								))); ?>

									<div class="validation-error" ng-messages="registrationForm.name.$error" >
										<?php echo getValidationMessage(); ?>

										<?php echo getValidationMessage('minlength'); ?>

										<?php echo getValidationMessage('pattern'); ?>

									</div>
                        </div>

                        <div class="form-group">
                          <label for="username"><?php echo e(getPhrase('username')); ?></label><span style="color: red;">*</span>
                         <?php echo e(Form::text('username', $value = null , $attributes = array('class'=>'form-control',
								'placeholder' => getPhrase("username"),
								'ng-model'=>'username',
								'required'=> 'true',
								'ng-class'=>'{"has-error": registrationForm.username.$touched && registrationForm.username.$invalid}',
								'ng-minlength' => '4',
							))); ?>

						<div class="validation-error" ng-messages="registrationForm.username.$error" >
							<?php echo getValidationMessage(); ?>

							<?php echo getValidationMessage('minlength'); ?>

							<?php echo getValidationMessage('pattern'); ?>

						</div>
                        </div>


                        <div class="form-group">
                        <label for="email"><?php echo e(getPhrase('email')); ?></label><span style="color: red;">*</span>
                        <?php echo e(Form::email('email', $value = null , $attributes = array('class'=>'form-control',
									'placeholder' => getPhrase("email"),
									'ng-model'=>'email',
									'required'=> 'true',
									'ng-class'=>'{"has-error": registrationForm.email.$touched && registrationForm.email.$invalid}',
								))); ?>

							<div class="validation-error" ng-messages="registrationForm.email.$error" >
								<?php echo getValidationMessage(); ?>

								<?php echo getValidationMessage('email'); ?>

							</div>
                        </div>






                          <div class="form-group">

                          <label for="password"><?php echo e(getPhrase('password')); ?></label><span style="color: red;">*</span>

					    <?php echo e(Form::password('password', $attributes = array('class'=>'form-control instruction-call',

								'placeholder' => getPhrase("password"),

								'ng-model'=>'registration.password',

								'required'=> 'true',

								'ng-class'=>'{"has-error": registrationForm.password.$touched && registrationForm.password.$invalid}',

								'ng-minlength' => 5

							))); ?>


						<div class="validation-error" ng-messages="registrationForm.password.$error" >

							<?php echo getValidationMessage(); ?>


							<?php echo getValidationMessage('password'); ?>


						</div>



                        </div>


                          <div class="form-group">

                       <label for="password_confirmation"><?php echo e(getPhrase('password_confirmation')); ?></label><span style="color: red;">*</span>

                       <?php echo e(Form::password('password_confirmation', $attributes = array('class'=>'form-control instruction-call',

								'placeholder' => getPhrase("password_confirmation"),

								'ng-model'=>'registration.password_confirmation',

								'required'=> 'true',

								'ng-class'=>'{"has-error": registrationForm.password_confirmation.$touched && registrationForm.password_confirmation.$invalid}',

								'ng-minlength' => 5,

								'compare-to' =>"registration.password"

							))); ?>


						<div class="validation-error" ng-messages="registrationForm.password_confirmation.$error" >

							<?php echo getValidationMessage(); ?>


							<?php echo getValidationMessage('minlength'); ?>


							<?php echo getValidationMessage('confirmPassword'); ?>


						</div>


                        </div>

                        <input type="hidden" name="register_type" value="<?php echo e($register_type); ?>">
                        <input type="hidden" name="register_form_type" value="mobileotp">


                         <div class="form-group">

                             <?php if($rechaptcha_status == 'yes'): ?>




				          <div class="col-md-12 form-group<?php echo e($errors->has('g-recaptcha-response') ? ' has-error' : ''); ?>" style="margin-top: 15px">



		                                <?php echo app('captcha')->display(); ?>




                               </div>


                             <?php endif; ?>


                        </div>
                        <?php endif; ?>

                      	<div class="text-center mt-2">
                      		<button type="submit" class="btn_1"><?php echo e(getPhrase('register_now')); ?></button>
                      		<?php if( ! empty( $phone ) ): ?>
                      		&nbsp;|&nbsp;
                      		<a href="<?php echo e(route('login.reset_otp_session')); ?>">Restart</a>
                      		<?php endif; ?>
                      	</div>

                    </form>
               </div>
        </div>
    </div>
    <?php echo $__env->make('site.login-register-modals', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</div>
</section>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('footer_scripts'); ?>

	<?php echo $__env->make('common.validations', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		     	
		     		<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
	function getCourses() {
  var student_class_id = $('#student_class_id').val();
  var _token   = $("input[name='_token']").val();

  $.ajax({
          url: "<?php echo e(route('class.courses')); ?>",
          type:'POST',
          data: {_token:_token, student_class_id:student_class_id},
          success: function(data) {
              if($.isEmptyObject(data.error)){
                //console.log(data.courses);
                $('#course_id').empty();
                //$('#course_id').append(data.courses);

                $.each( data.courses, function( key, value ) {
                $('#course_id').append('<option value="'+key+'">'+value+'</option>');
                });
              }else{
                  $('#loading').html("").hide();
                  printErrorMsg(data.error, 'print-error-msg-signup');
              }
          }
      });
}
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sitelayoutnew', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>