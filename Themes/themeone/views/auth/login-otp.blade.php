@extends('layouts.sitelayout')

@section('content')
   <!-- breadcrumb start-->
    <section class="breadcrumb breadcrumb_bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb_iner text-center">
                        <div class="breadcrumb_iner_item">
                            <h2>Login with mobile OTP</h2>
                            <p>Home<span>/<span>Login with mobile OTP</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb start-->

  <!-- ================ contact section start ================= -->
  <section class="contact-section">
     <div class="container-fluid">
       <div class="row cs-row whiteBg marginbot0">
           <div class="col-md-8 registerBg">
            <div class="signup__overlay">
                <div class="thumbnail__content">
                    <h1 class="heading--primary">Welcome to LERN EASY</h1>
                    <div class="loginLeft_text">
                    <h2 class="heading--secondary">Login to :</h2>
                    <ul class="how-to-join">
                        <li>Get Access to the complete exam calendar</li>
                        <li>Get indepth information on every stage of the exam cycle</li>
                        <li>Apply directly to the  colleges which accept your exam score</li>
                        <li>Get personalised information about upcoming exams</li>
                        <li>Know about the trending exam of the month</li>

                    </ul>
                  </div>
                </div>
            </div>
          </div>

        <div class="col-lg-4">
          <h4 class="text-center login-head" style="margin-top: 80px;">{{getPhrase('login')}}</h4>
          {!! Form::open(array( 'route' => ['login.post_otp'], 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"loginform", 'name'=>"loginForm")) !!}

              @include('errors.errors')

            <div class="row">

                      <div class="col-12">
                        <div class="form-group">
                          <label for="email">{{getPhrase('phone')}}:</label>

                            <?php
                          $disabled = '';
                          if ( 'yes' === $sent_otp ) {
                            $disabled = ' readonly';
                          }
                          if ( old('phone') ) {
                            $phone = old('phone');
                          }
                          ?>
                        <input type="hidden" name="phone_code" value="91">
                        {{ Form::number('phone', $value = $phone, $attributes = array('class'=>'form-control',

                        'ng-model'=>'phone',

                        'required'=> 'true',

                        'id'=> 'phone',

                        'placeholder' => getPhrase("mobile_number_with_country_code_you_will_receive_OTP"),

                        'ng-class'=>'{"has-error": loginForm.phone.$touched && loginForm.phone.$invalid}',
                        $disabled

                       )) }}

                        <div class="validation-error" ng-messages="loginForm.phone.$error" >

                          {!! getValidationMessage()!!}

                          {!! getValidationMessage('phone')!!}

                        </div>

                        </div>
                      </div>

                      @if( 'yes' === $sent_otp )
                        <div class="col-12">
                        <div class="form-group">
                            <label for="pwd">OTP:</label>

                           {{ Form::password('otp', $attributes = array('class'=>'form-control instruction-call',

                        'placeholder' => getPhrase("otp"),

                        'ng-model'=>'registration.otp',

                        'required'=> 'true',
                        'id'=> 'otp',

                        'ng-class'=>'{"has-error": loginForm.otp.$touched && loginForm.otp.$invalid}',

                          )) }}

                        <div class="validation-error" ng-messages="loginForm.otp.$error" >

                          {!! getValidationMessage()!!}

                        </div>

                        </div>
                      </div>
                        @endif

                      <div class="col-12">
                      <div class="form-group">

                             @if($rechaptcha_status == 'yes')




                  <div class="  form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">



                                    {!! app('captcha')->display() !!}



                               </div>


                             @endif


                        </div>
                      </div>


  <br>


            </div>
            <div class="form-group mt-3">
              <input type="hidden" name="sent_otp" value="{{$sent_otp}}">
              <input type="hidden" name="user_id" value="{{$user_id}}">

              <button type="submit" class="btn_1" >{{getPhrase('login')}}</button>
              &nbsp;|&nbsp;<a href="{{route('user.login')}}" class="">{{getPhrase('login_with_email')}}</a>
            </div>
            <div class="col-md-9 text-left">
               <a href="{{URL_USERS_REGISTER}}" class="textColor1">{{getPhrase('register')}}</a>&nbsp;|&nbsp;
               <a href="javascript:void(0);" class="textColor1" data-toggle="modal" data-target="#forgot_password_modal" ><i class="icon icon-question"></i> {{getPhrase('forgot_password')}}</a>
            </div>
          </form>
        </div>

      </div>
    </div>
  </section>
  <!-- ================ contact section end ================= -->

  @include('site.login-register-modals')
@stop

@section('footer_scripts')

  @include('common.validations')

  <script src='https://www.google.com/recaptcha/api.js'></script>
@stop