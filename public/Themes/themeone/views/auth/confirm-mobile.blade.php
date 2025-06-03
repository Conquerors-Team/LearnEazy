@extends('layouts.sitelayout')

@section('content')

<style type="text/css">
  /*Demo Credentials */

.login-content {
    position: relative;
}
.login-user-details {
    display: none;
}
@media(min-width:768px) {
    .login-user-details {

        background-color: #d9edf7;
        padding: 15px 15px;
        display: block;
    }
    .login-user-details li {
        text-align: center;
        font-size: 18px;
    }
    .login-user-details li.title {
        color: #44a1ef;
        margin-bottom: 10px;
        font-weight: bold;
    }
    .login-user-details li + li {
        margin-top: 5px;
    }
    .login-user-details li a {
        padding: 5px 10px;
        display: block;
        text-decoration: none;
        border-radius: 3px;
        cursor: pointer;
    }
    .login-user-details li a.positive {
        border: 1px solid #44a1ef;
        background: #44a1ef;
        color: #fff;
    }
    .login-user-details li a:hover {
        color: #44a1ef;
        background: #FFF;
        border-color: #44a1ef;
    }
}
@media(min-width:1367px) {
    .login-user-details {
        top: 280px;
    }
</style>


       <!-- Login Section -->
       <div  style="background-image: url({{IMAGES}}login-bg.png);background-repeat: no-repeat;background-color: #f8fafb">
    <div class="container">
        <div class="row cs-row" style="margin-top: 180px">


            <div class="col-md-12">



                <div class="cs-box-resize  login-box row" style="max-width: 700px; padding:30px; ">

                  <?php
                    $env_demo = env('DEMO_MODE');
                  ?>

                  @if($env_demo == FALSE)
                    <div class="col-sm-6" style="margin-left:150px;">
                      @else
                    <div class="col-sm-6">
                  @endif



                 <h4 class="text-center login-head">{{getPhrase('confirm_mobile')}}</h4>

                    <!-- Form Login/Register -->
                      {!! Form::open(array( 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"loginform", 'name'=>"loginForm")) !!}

                        @include('errors.errors')

                        <div class="form-group">

                          <label for="email">{{getPhrase('email_address')}}:</label>
                          <?php
                          $disabled = '';
                          if ( 'yes' === $sent_otp ) {
                            $disabled = ' readonly';
                          }
                          if ( old('email') ) {
                            $email = old('email');
                          }
                          ?>
                            {{ Form::text('email', $value = $email, $attributes = array('class'=>'form-control',

                        'ng-model'=>'email',

                        'required'=> 'true',

                        'id'=> 'email',

                        'placeholder' => getPhrase('email'),

                        'ng-class'=>'{"has-error": loginForm.email.$touched && loginForm.email.$invalid}',
                        $disabled

                       )) }}

                        <div class="validation-error" ng-messages="loginForm.email.$error" >

                          {!! getValidationMessage()!!}

                          {!! getValidationMessage('email')!!}

                        </div>

                        </div>

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
                        <?php
                        $otp_status = session("otp_status");
                        ?>

                        @if( 'yes' === $otp_status )
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
                        @endif


                        <input type="hidden" name="sent_otp" value="{{$sent_otp}}">
                        <input type="hidden" name="otp_status" value="{{$otp_status}}">
                        @if( 'yes' === $otp_status)
                        <button type="submit" class="btn button btn-primary btn-lg" style="margin-left: 85px;">{{getPhrase('validate')}}</button>&nbsp;|&nbsp;
                        <a href="{{route('login.reset_otp_session')}}">{{getPhrase('reset')}}</a>
                        @else
                        <button type="submit" class="btn button btn-primary btn-lg" style="margin-left: 85px;">{{getPhrase('send_otp')}}</button>
                        @endif

                    </form>

                    <!-- Form Login/Register -->
               </div>

  <div class="col-sm-1" >
  </div>





            </div>
        </div>
    </div>
    <!-- Login Section -->


  <!-- Modal -->

<div id="myModal" class="modal fade" role="dialog">

  <div class="modal-dialog">

  {!! Form::open(array('url' => URL_USERS_FORGOT_PASSWORD, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"loginform", 'name'=>"passwordForm")) !!}

    <!-- Modal content-->

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">{{getPhrase('forgot_password')}}</h4>

      </div>

      <div class="modal-body">

        <div class="form-group">
          <label>Email Address</label>





          {{ Form::email('email', $value = null , $attributes = array('class'=>'form-control',

      'ng-model'=>'email',

      'required'=> 'true',

      'placeholder' => getPhrase('email'),

      'ng-class'=>'{"has-error": passwordForm.email.$touched && passwordForm.email.$invalid}',

    )) }}

  <div class="validation-error" ng-messages="passwordForm.email.$error" >

    {!! getValidationMessage()!!}

    {!! getValidationMessage('email')!!}

  </div>



      </div>

      </div>

      <div class="modal-footer">

      <div class="pull-right">

        <button type="button" class="btn btn-default" data-dismiss="modal">{{getPhrase('close')}}</button>

        <button type="submit" class="btn btn-primary" ng-disabled='!passwordForm.$valid'>{{getPhrase('submit')}}</button>

        </div>

      </div>

    </div>

  {!! Form::close() !!}

  </div>

</div>
</div>

@stop



@section('footer_scripts')

  @include('common.validations')

  <script src='https://www.google.com/recaptcha/api.js'></script>


@stop