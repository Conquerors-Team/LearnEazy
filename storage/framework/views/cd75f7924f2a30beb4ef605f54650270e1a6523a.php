

                        <fieldset class="form-group">

                        	<label for="institute_name"><?php echo e(getPhrase('institute_name')); ?></label>
                        	<span style="color: red;">*</span>

						   <?php echo e(Form::text('institute_name', $value = $ins_name , $attributes = array('class'=>'form-control',

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

                        </fieldset>


                        <fieldset class="form-group">

                        	<label for="institute_address"><?php echo e(getPhrase('institute_address')); ?></label><span style="color: red;">*</span>

						   <?php echo e(Form::textarea('institute_address', $value = $ins_address , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("institute_address"),

									'ng-model'=>'institute_address',

									'rows'=>'3',

									'cols'=>'15',

                                    'required'=> 'true',

									'ng-class'=>'{"has-error": registrationForm.institute_address.$touched && registrationForm.institute_address.$invalid}',


								))); ?>


									<div class="validation-error" ng-messages="registrationForm.institute_address.$error" >

										<?php echo getValidationMessage(); ?>




									</div>

                        </fieldset>

                          <fieldset class="form-group">

                        	<label for="name"><?php echo e(getPhrase('name')); ?></label><span style="color: red;">*</span>

						   <?php echo e(Form::text('name', $value = null , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("name"),

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

                      </fieldset>


                        <fieldset class="form-group">

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

                      </fieldset>


                         <fieldset class="form-group">

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


                      </fieldset>

                      <?php if(checkRole(getUserGrade(2))): ?>
						<fieldset class="form-group">
	                        <?php echo e(Form::label('status', getphrase('approved'))); ?>

							<span class="text-red">*</span>
							<?php
							$login_enabled = [
								1 => 'Activate',
								0 => 'Block',
							];
							?>
							<?php echo e(Form::select('status', $login_enabled, null, ['class'=>'form-control',
								'ng-model'=>'status',
								'required'=> 'true',
								'ng-class'=>'{"has-error": formUsers.status.$touched && formUsers.status.$invalid}'

							 ])); ?>

							  <div class="validation-error" ng-messages="formUsers.status.$error" >
		    					<?php echo getValidationMessage(); ?>

		    				</div>
						</fieldset>
						<?php else: ?>
						<?php
						$login_enabled = 0;
						if ( $record ) {
							$login_enabled = $record->login_enabled;
						}
						?>
						<input type="hidden" name="status" id="status" value="<?php echo e($status); ?>">
						<?php endif; ?>

                        <?php if(!$record): ?>

                          <fieldset class="form-group">

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



                      </fieldset>


                          <fieldset class="form-group">

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


                       </fieldset>

                       <?php endif; ?>

                    <fieldset class="form-group">
					<?php echo e(Form::label('valid_until', getphrase('valid_until'))); ?>

					<?php echo e(Form::text('valid_until', $value = null, $attributes = array('class'=>'form-control datepicker1', 'placeholder' => '2015/7/17'))); ?>

					</fieldset>


					<fieldset class="form-group">
                        <?php echo e(Form::label('package_id', getphrase('package'))); ?>

						<!-- <span class="text-red">*</span> -->
						<?php
						$institute_id   = adminInstituteId();
						$packages = \App\Package::where('institute_id', $institute_id)->where('package_for', 'institute');
						$packages = $packages->get()->pluck('title', 'id')->prepend('Please select', '')->toArray();
						?>
						<?php echo e(Form::select('package_id', $packages, $value = null, ['class'=>'form-control',
							'ng-model'=>'package_id',
							'ng-class'=>'{"has-error": formUsers.package_id.$touched && formUsers.package_id.$invalid}'

						 ])); ?>

						  <div class="validation-error" ng-messages="formUsers.package_id.$error" >
	    					<?php echo getValidationMessage(); ?>

	    				</div>
					</fieldset>


                        <fieldset class="form-group">

                        	<label for="phone"><?php echo e(getPhrase('phone')); ?></label><span style="color: red;">*</span>

						   <?php echo e(Form::number('phone', $value = null , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("phone"),

									'ng-model'=>'phone',

                                    'required'=> 'true',

									'ng-class'=>'{"has-error": registrationForm.phone.$touched && registrationForm.phone.$invalid}',


								))); ?>


									<div class="validation-error" ng-messages="registrationForm.phone.$error" >

										<?php echo getValidationMessage(); ?>


                                 </div>

                       </fieldset>

                        <fieldset class="form-group">

                        	<label for="address"><?php echo e(getPhrase('address')); ?></label><span style="color: red;">*</span>

						   <?php echo e(Form::textarea('address', $value = null , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("address"),

									'ng-model'=>'address',

									'rows'=>'3',

									'cols'=>'15',

                                    'required'=> 'true',

									'ng-class'=>'{"has-error": registrationForm.address.$touched && registrationForm.address.$invalid}',


								))); ?>


									<div class="validation-error" ng-messages="registrationForm.address.$error" >

										<?php echo getValidationMessage(); ?>




									</div>

                     </fieldset>




                      <?php if(!$record): ?>
                      	<div class="text-center mt-2">
                      		<button type="submit" class="btn button btn-primary btn-lg" ng-disabled='!registrationForm.$valid'>
                      		<?php echo e($button_name); ?></button>
                      	</div>
                      <?php else: ?>

                      <div class="text-center mt-2">
                      		<button type="submit" class="btn button btn-primary btn-lg" >
                      		<?php echo e($button_name); ?></button>
                      	</div>

                      <?php endif; ?>


