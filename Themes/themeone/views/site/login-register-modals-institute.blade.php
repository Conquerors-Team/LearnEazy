 <!-- ALL MODALS Start -->
        <div id="forgot_password_modal" class="modal fade" role="dialog">

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

        <button type="submit" class="btn_1" ng-disabled='!passwordForm.$valid'>{{getPhrase('submit')}}</button>

        </div>

      </div>

    </div>

  {!! Form::close() !!}

  </div>

</div>

        <!-- Login Modal start -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" style="margin-top: 150px;">
        <div class="modal-content">

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×</button>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="Login">
                             <h3 class="login_label">LOGIN</h3>

                            <div class="alert alert-danger print-error-msg-login" style="display:none">
                            <ul></ul>
                            </div>

                                {!! Form::open(array('url' => URL_USERS_LOGIN, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"form-horizontal", 'name'=>"loginForm", 'role' => 'form', 'id' => 'loginForm')) !!}


    <div class="input-group col-sm-12" id="input-group-email">
        <label for="email" class="col-sm-4 control-label" style="padding-top: 10px;">Username:</label>
        {{ Form::text('email', $value = null , $attributes = array('class'=>'form-control',
        'ng-model'=>'email',
        'required'=> 'true',
        'id'=> 'login-email',
        'placeholder' => getPhrase('username').'/'.getPhrase('email'),
        'ng-class'=>'{"has-error": loginForm.email.$touched && loginForm.email.$invalid}',
        )) }}
    </div>

    <div class="input-group col-sm-12" id="input-group-password">
        <label for="password" class="col-sm-4 control-label" style="padding-top: 10px;">Password:</label>
        {{ Form::password('password', $attributes = array('class'=>'form-control instruction-call',
        'placeholder' => getPhrase("password"),
        'ng-model'=>'registration.password',
        'required'=> 'true',
        'id'=> 'login-password',
        'ng-class'=>'{"has-error": loginForm.password.$touched && loginForm.password.$invalid}',
        'ng-minlength' => 5
          )) }}
          <i class="fa fa-eye togglepassword-institute-login togglepassword_login_model" aria-hidden="true"></i>
    </div>

                                <div class="row">
                                    <div class="col-sm-12 btn-center1">
                                        <input type="hidden" name="redirect_url" id="redirect_url" value="{{URL_USERS_DASHBOARD}}">
                                        <button type="submit" class="btn_1">
                                            Submit</button>
                                    </div>
                                </div>
                                </form>
                                <ul class="btn-center2">
                                <li>
                                    <!-- <a href="#" data-target="#register" data-toggle="modal">SIGN UP FOR A NEW ACCOUNT</a> -->
                                    <!-- <a href="javascript:void(0);" onclick="openModal('registerModal')">SIGN UP FOR A NEW ACCOUNT</a> -->
                                    <a href="#" data-target="#registerModal" data-toggle="modal" data-dismiss='modal'>SIGN UP FOR A NEW ACCOUNT</a></li></ul>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
        <!-- login modal End -->
     <!--    <script>
            $("input").intlTelInput({
  utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/js/utils.js"
});
        </script> -->

<!-- Register Modal start -->
<div id="registerModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="margin-top: 150px;">
        <div class="modal-content">
            <div class="modal-body" >
                <div class="form_wrapper">
                    <div class="form_container">
                        <div class="title_container">
                            <button data-dismiss="modal" class="close">
                                &times;
                            </button>
                            <h2>
                                REGISTRATION FORM
                            </h2>

                            <div class="row clearfix">
                                <div class="">
                                    <div class="alert alert-danger print-error-msg-signup" style="display:none">
                                    <ul></ul>
                                    </div>
                                    {!! Form::open(array('url' => URL_USERS_REGISTER, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"loginform", 'id'=>"registrationForm")) !!}
                                        <input type="hidden" name="register_type" id="register_type" ng-model="register_type" value="institute">



                                        <div class="row clearfix">
                                            <div class="col_half">
                                                <div class="input_field">
                                                    <span>
                                                        <i aria-hidden="true" class="fa fa-user">
                                                        </i>
                                                    </span>
                                                    <input type="text" name="first_name" id="first_name" placeholder="First Name" />
                                                </div>
                                            </div>
                                            <div class="col_half">
                                                <div class="input_field">
                                                    <span>
                                                        <i aria-hidden="true" class="fa fa-user">
                                                        </i>
                                                    </span>
                                                    <input type="text" name="last_name" id="last_name" placeholder="Last Name" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input_field">
                                            <span>
                                                <i aria-hidden="true" class="fa fa-building">
                                                </i>
                                            </span>
                                            <input type="text" name="institute_name" id="institute_name" placeholder="Institute Name" required />
                                        </div>
                                        <div class="input_field">
                                            <span>
                                                <i aria-hidden="true" class="fa fa-user">
                                                </i>
                                            </span>
                                            <input type="text" name="username" id="username" placeholder="Username" required />
                                        </div>

                                         <div class="input_field">
                                            <span>
                                                <i aria-hidden="true" class="fa fa-phone">
                                                </i>
                                            </span>
                                            <input type="tel" name="phone" id="phone-register" placeholder="Phone" required />
                                        </div>
                                        <div class="input_field">
                                            <span>
                                                <i aria-hidden="true" class="fa fa-envelope">
                                                </i>
                                            </span>
                                            <input type="email" name="email" id="signup-email" placeholder="Email" required />
                                        </div>
                                        <div class="input_field">
                                            <span>
                                                <i aria-hidden="true" class="fa fa-lock">
                                                </i>
                                            </span>
                                            <input type="password" name="password" id="signup-password" placeholder="Password" required />
                                            <i class="fa fa-eye togglepassword-institute-signup togglepassword_signup_model" aria-hidden="true"></i>
                                        </div>
                                        <div class="input_field">
                                            <span>
                                                <i aria-hidden="true" class="fa fa-lock">
                                                </i>
                                            </span>
                                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Re-type Password" required />
                                        </div>

                                            <!-- <a href="" class="btn_1" type="submit">Register</a> -->
                                            <button type="submit" class="btn_1" id="registrationFormBtn">{{getPhrase('register_now')}}</button>
                                            <div id="loading"></div>
                                            <!-- <a href="#" onclick="openModal('loginModal')">Have Account?</a> -->
                                            <a href="#" data-toggle="modal" data-target="#loginModal" data-dismiss='modal'>Have Account?</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Registermodal End -->
    <!-- ALL MODALS End-->