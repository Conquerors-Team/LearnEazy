	

                        <fieldset class="form-group">

                        	<label for="institute_name">{{getPhrase('institute_name')}}</label>
                        	<span style="color: red;">*</span>

						   {{ Form::text('institute_name', $value = $ins_name , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("institute_name"),

									'ng-model'=>'institute_name',

									'ng-pattern' => getRegexPattern('name'),

									'required'=> 'true', 

									'ng-class'=>'{"has-error": registrationForm.institute_name.$touched && registrationForm.institute_name.$invalid}',

									'ng-minlength' => '4',

								)) }}

									<div class="validation-error" ng-messages="registrationForm.institute_name.$error" >

										{!! getValidationMessage()!!}

										{!! getValidationMessage('minlength')!!}

										{!! getValidationMessage('pattern')!!}

									</div>

                        </fieldset>


                        <fieldset class="form-group">

                        	<label for="institute_address">{{getPhrase('institute_address')}}</label><span style="color: red;">*</span>

						   {{ Form::textarea('institute_address', $value = $ins_address , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("institute_address"),

									'ng-model'=>'institute_address',

									'rows'=>'3', 

									'cols'=>'15',

                                    'required'=> 'true', 

									'ng-class'=>'{"has-error": registrationForm.institute_address.$touched && registrationForm.institute_address.$invalid}',


								)) }}

									<div class="validation-error" ng-messages="registrationForm.institute_address.$error" >

										{!! getValidationMessage()!!}

										

									</div>

                        </fieldset>
                         
                          <fieldset class="form-group">

                        	<label for="name">{{getPhrase('name')}}</label><span style="color: red;">*</span>

						   {{ Form::text('name', $value = null , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("name"),

									'ng-model'=>'name',

									'ng-pattern' => getRegexPattern('name'),

									'required'=> 'true', 

									'ng-class'=>'{"has-error": registrationForm.name.$touched && registrationForm.name.$invalid}',

									'ng-minlength' => '4',

								)) }}

									<div class="validation-error" ng-messages="registrationForm.name.$error" >

										{!! getValidationMessage()!!}

										{!! getValidationMessage('minlength')!!}

										{!! getValidationMessage('pattern')!!}

									</div>

                      </fieldset>


                        <fieldset class="form-group">

                          <label for="username">{{getPhrase('username')}}</label><span style="color: red;">*</span>

                         {{ Form::text('username', $value = null , $attributes = array('class'=>'form-control',

								'placeholder' => getPhrase("username"),

								'ng-model'=>'username',

								'required'=> 'true', 

								'ng-class'=>'{"has-error": registrationForm.username.$touched && registrationForm.username.$invalid}',

								'ng-minlength' => '4',

							)) }}

						<div class="validation-error" ng-messages="registrationForm.username.$error" >

							{!! getValidationMessage()!!}

							{!! getValidationMessage('minlength')!!}

							{!! getValidationMessage('pattern')!!}

						</div>

                      </fieldset>


                         <fieldset class="form-group">
                        	
                          <label for="email">{{getPhrase('email')}}</label><span style="color: red;">*</span>

                        {{ Form::email('email', $value = null , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("email"),

									'ng-model'=>'email',

									'required'=> 'true', 

									'ng-class'=>'{"has-error": registrationForm.email.$touched && registrationForm.email.$invalid}',

								)) }}

							<div class="validation-error" ng-messages="registrationForm.email.$error" >

								{!! getValidationMessage()!!}

								{!! getValidationMessage('email')!!}

							</div>


                      </fieldset>

                        @if(!$record)

                          <fieldset class="form-group">
                        	
                          <label for="password">{{getPhrase('password')}}</label><span style="color: red;">*</span>

					    {{ Form::password('password', $attributes = array('class'=>'form-control instruction-call',

								'placeholder' => getPhrase("password"),

								'ng-model'=>'registration.password',

								'required'=> 'true', 

								'ng-class'=>'{"has-error": registrationForm.password.$touched && registrationForm.password.$invalid}',

								'ng-minlength' => 5

							)) }}

						<div class="validation-error" ng-messages="registrationForm.password.$error" >

							{!! getValidationMessage()!!}

							{!! getValidationMessage('password')!!}

						</div>



                      </fieldset>


                          <fieldset class="form-group">
                        	
                       <label for="password_confirmation">{{getPhrase('password_confirmation')}}</label><span style="color: red;">*</span>

                       {{ Form::password('password_confirmation', $attributes = array('class'=>'form-control instruction-call',

								'placeholder' => getPhrase("password_confirmation"),

								'ng-model'=>'registration.password_confirmation',

								'required'=> 'true', 

								'ng-class'=>'{"has-error": registrationForm.password_confirmation.$touched && registrationForm.password_confirmation.$invalid}',

								'ng-minlength' => 5,

								'compare-to' =>"registration.password"

							)) }}

						<div class="validation-error" ng-messages="registrationForm.password_confirmation.$error" >

							{!! getValidationMessage()!!}

							{!! getValidationMessage('minlength')!!}

							{!! getValidationMessage('confirmPassword')!!}

						</div>


                       </fieldset>

                       @endif


                        <fieldset class="form-group">

                        	<label for="phone">{{getPhrase('phone')}}</label><span style="color: red;">*</span>

						   {{ Form::number('phone', $value = null , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("phone"),

									'ng-model'=>'phone',

                                    'required'=> 'true', 

									'ng-class'=>'{"has-error": registrationForm.phone.$touched && registrationForm.phone.$invalid}',


								)) }}

									<div class="validation-error" ng-messages="registrationForm.phone.$error" >

										{!! getValidationMessage()!!}

                                 </div>

                       </fieldset>

                        <fieldset class="form-group">

                        	<label for="address">{{getPhrase('address')}}</label><span style="color: red;">*</span>

						   {{ Form::textarea('address', $value = null , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("address"),

									'ng-model'=>'address',

									'rows'=>'3', 

									'cols'=>'15',

                                    'required'=> 'true', 

									'ng-class'=>'{"has-error": registrationForm.address.$touched && registrationForm.address.$invalid}',


								)) }}

									<div class="validation-error" ng-messages="registrationForm.address.$error" >

										{!! getValidationMessage()!!}

										

									</div>

                     </fieldset>

                      @if(!$record)
                      	<div class="text-center mt-2">
                      		<button type="submit" class="btn button btn-primary btn-lg" ng-disabled='!registrationForm.$valid'>
                      		{{ $button_name }}</button>
                      	</div>
                      @else

                      <div class="text-center mt-2">
                      		<button type="submit" class="btn button btn-primary btn-lg" >
                      		{{ $button_name }}</button>
                      	</div>
                      
                      @endif	


