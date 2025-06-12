<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta content="width=device-width, initial-scale=1.0" name="viewport">

		<title>Home | LearnEazy</title>
		<meta content="" name="descriptison">
		<meta content="" name="keywords">

		<!-- Favicons -->
		<link href="{{themes('site/img/favicon.png')}}" rel="icon">
		<link href="{{themes('site/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

		<!-- Google Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

		<!-- Vendor CSS Files -->
		<link href="{{themes('site/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
		<link href="{{themes('site/vendor/icofont/icofont.min.css')}}" rel="stylesheet">

		<!-- Template Main CSS File -->
		<link href="{{themes('site/css/home.css')}}" rel="stylesheet">

		@if(Request::routeIs('site.pricing') || Request::routeIs('site.institute') )
		<link rel="stylesheet" href="{{themes('site/css/style.css')}}">
		@endif

		<link rel="manifest" href="public/manifest.json" />

		@if(Request::routeIs('user.login') || Request::routeIs('user.register') || Request::routeIs('user-otp.register'))
		<link href="{{themes('css/angular-validation.css')}}" rel="stylesheet">
		@endif
		<style>

		#registerModal {
    overflow-x: hidden;
    overflow-y: auto;
		}
		body{
			overflow: auto;
		}
		/* Hide scrollbar for Chrome, Safari and Opera */
			#registerModal::-webkit-scrollbar {
			  display: none;
			}

			/* Hide scrollbar for IE, Edge and Firefox */
			#registerModal {
			  -ms-overflow-style: none;  /* IE and Edge */
			  scrollbar-width: none;  /* Firefox */
			}
	</style>
	</head>

	<body>

		<!-- ======= Header ======= -->
		<header id="header" class="fixed-top ">
			<div class="container d-flex align-items-center">
				<img src="{{themes('site/img/logo.gif')}}" style="height:40px; width: auto;">
				<h1 class="logo pl-2 mr-auto">
					@if(Request::routeIs('site.home'))
					<a href="#hero">Learn<span>Eazy</span></a>
					@else
					<a href="{{route('site.home')}}">Learn<span>Eazy</span></a>
					@endif

				</h1>
				<nav class="nav-menu d-none d-lg-block">
					<ul>
						@if(Request::routeIs('site.home'))
						<li><a href="#features">Features</a></li>
						@endif
						<li class="drop-down"><a href="#">Study Materials</a>
							<ul>
								<?php
								$boards = \App\Board::where('status', 'active')->get();
								foreach ($boards as $board) {
								?>
								<li><a href="{{route('site.board', ['board_id' => $board->slug])}}">{{$board->title}}</a></li>
								<?php } ?>
								<li><a href="{{route('site.ref_books')}}">Reference Books</a></li>
							</ul>
						</li>
						<!-- <li><a href="{{route('site.pricing')}}">Pricing</a></li> -->
						<li><a href="{{route('site.contact')}}">Contact</a></li>
						@if(Request::routeIs('site.institute'))
						<li class="btn btn-le-outline-dark" data-toggle="modal" data-target="#registerModal"><a href="#">Register</a></li>
						@else
						<li class="btn btn-le-outline-dark"><a href="{{route('site.institute')}}">Institute</a></li>
						@endif
						<li class="btn btn-le-orange" data-toggle="modal" data-target="#loginModal"><a href="#">Login</a></li>
					</ul>
				</nav><!-- .nav-menu -->

			</div>
		</header><!-- End Header -->

		@yield('content')


		<!-- ======= Footer ======= -->
		<footer id="footer">

			<div class="footer-top">
				<div class="container">
					<div class="row">

						<div class="col-lg-4 col-md-6 footer-contact">
							<h3>LearnEazy</h3>
							<p>We as a team belive every child deserves quality education enriched with experimental learning guided by best teaching methodolgy and teachers.</p>
							<p>This platform provides the best learning solutions for the students in the pursuit of realizing their dreams.</p>
						</div>

						<div class="col-lg-4 col-md-6 footer-links">
							<div class="row">
								<div class="col-6">
									<h4>Quick Links</h4>
									<ul>
										<!-- <li><i class="icofont-rounded-right"></i> <a href="{{route('site.pricing')}}">Pricing</a></li> -->
										<li><i class="icofont-rounded-right"></i> <a href="{{route('site.contact')}}">Contact Us</a></li>
										<li><i class="icofont-rounded-right"></i> <a href="{{route('site.terms_of_service')}}">Terms of service</a></li>
										<li><i class="icofont-rounded-right"></i> <a href="{{route('site.privacy_policy')}}">Privacy policy</a></li>
									</ul>
								</div>
								<div class="col-6">
									<h4>Study Materials</h4>
									<ul>
										<?php
										$boards = \App\Board::where('status', 'active')->get();
										foreach ($boards as $board) {
										?>

										<li><i class="icofont-rounded-right"></i> <a href="{{route('site.board', ['board_id' => $board->slug])}}">{{$board->title}}</a></li>
										<?php } ?>
										<li><i class="icofont-rounded-right"></i> <a href="{{route('site.ref_books')}}">Reference Books</a></li>
									</ul>
								</div>
							</div>
						</div>

						<div class="col-lg-4 col-md-12 footer-newsletter">
							<h4>Connect with Us</h4>
							<p>
								<strong>Phone:</strong> +91 97018 91039<br>
								<strong>Email:</strong> info@learneazy.org<br>
							</p>
							<div class="social-links text-center text-lg-left pt-3">
								<a href="#" class="facebook"><i class="icofont-facebook"></i></a>
								<a href="#" class="twitter"><i class="icofont-twitter"></i></a>
								<a href="#" class="instagram"><i class="icofont-instagram"></i></a>
								<a href="#" class="skype"><i class="icofont-skype"></i></a>
								<a href="#" class="linkedin"><i class="icofont-linkedin"></i></a>
							</div>
						</div>

					</div>
				</div>
			</div>

			<div class="container py-4">
				<div class="mr-md-auto text-center">
					<div class="copyright">
						&copy; 2020 <strong><span>LearnEazy</span></strong>. All Rights Reserved
					</div>
					<div class="credits">
						<!-- All the links in the footer should remain intact. -->
						Powered by <a href="https://conquerorstech.net/" target="_blank"><strong>CSTPL</strong></a>
					</div>
				</div>
			</div>
		</footer><!-- End Footer -->

		<a href="#" class="back-to-top"><i class="icofont-arrow-up"></i></a>

		<!-- Vendor JS Files -->
		<script src="{{themes('site/vendor/jquery/jquery.min.js')}}"></script>
		<script src="{{themes('site/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
		<script src="{{themes('site/vendor/jquery.easing/jquery.easing.min.js')}}"></script>
		<script src="{{themes('site/vendor/php-email-form/validate.js')}}"></script>
		<script src="{{themes('site/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>

		<!-- Template Main JS File -->
		<script src="{{themes('site/js/home.js')}}"></script>

		@yield('footer_scripts')



		<!-- LOGIN MODAL -->
		<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header border-bottom-0">
						<h4 class="modal-title text-navy text-uppercase" style="font-weight:700" id="loginModalLabel">Log in</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					 <div class="alert alert-danger print-error-msg-login" style="display:none">
                            <ul></ul>
                            </div>
					<div class="modal-body">
						{!! Form::open(array('url' => URL_USERS_LOGIN, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"form-horizontal", 'name'=>"loginForm", 'role' => 'form', 'id' => 'loginForm')) !!}
						<!-- <form> -->
							<div class="form-group" id="input-group-email">
								<label class="text-royal" for="loginUserName"><strong>Username</strong></label>
								{{ Form::text('email', $value = null , $attributes = array('class'=>'form-control',
							        'ng-model'=>'email',
							        'required'=> 'true',
							        'id'=> 'login-email',
							        'placeholder' => getPhrase('username').'/'.getPhrase('email'),
							        'ng-class'=>'{"has-error": loginForm.email.$touched && loginForm.email.$invalid}',
							        )) }}
								<!-- <input type="email" class="form-control" id="loginUserName" placeholder="Enter email"> -->
							</div>
							<div class="form-group" id="input-group-password">
								<label class="text-royal" for="loginPassword"><strong>Password</strong></label>
								{{ Form::password('password', $attributes = array('class'=>'form-control instruction-call',
							        'placeholder' => getPhrase("password"),
							        'ng-model'=>'registration.password',
							        'required'=> 'true',
							        'id'=> 'login-password',
							        'ng-class'=>'{"has-error": loginForm.password.$touched && loginForm.password.$invalid}',
							        
							          )) }}
								<!-- <input type="password" class="form-control" id="loginPassword" placeholder="Password"> -->
							</div>
							@if( Request::routeIs('site.institute') )
							<a href="javascript:void(0);" class="text-orange" onclick="openModal('registerModal')">SIGN UP FOR A NEW ACCOUNT</a>
							@else
							<a class="text-orange" href="{{route('user-otp.register')}}">SIGN UP FOR A NEW ACCOUNT</a>
							@endif
							<input type="hidden" name="redirect_url" id="redirect_url" value="{{URL_USERS_DASHBOARD}}">
							<button type="submit" class="btn btn-le-dark float-right">Submit</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- REGISTRATION MODAL -->
		<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header border-bottom-0">
						<h4 class="modal-title text-navy text-uppercase" style="font-weight:700" id="registerModalLabel">Registration</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="alert alert-danger print-error-msg-signup" style="display:none">
                                    <ul></ul>
                                    </div>
					<div class="modal-body">
						{!! Form::open(array('url' => URL_USERS_REGISTER, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"loginform", 'id'=>"registrationForm")) !!}
						<!-- <form> -->
							<!-- <div class="input_field select_option">
                                            <?php
                                            $register_types = [
                                                '' => 'Please select register as',
                                                'student' => 'Student',
                                                'institute' => 'Institute',
                                            ];
                                            ?>
                                            {{Form::select('register_type', $register_types, null, [
                                            'ng-model'=>'register_type',
                                            'id' => 'register_type',
                                            'required'=> 'true',
                                            'ng-class'=>'{"has-error": registrationForm.register_type.$touched && registrationForm.register_type.$invalid}',
                                            ])}}
                                            <div class="select_arrow">
                                        </div>
                                        </div> -->
							@if(Request::routeIs('site.institute') )
                            	<input type="hidden" name="register_type" id="register_type" value="institute">
                            @else
                            	<input type="hidden" name="register_type" id="register_type" value="student">
                            @endif
							<div class="form-group row">
								 <input type="hidden" name="register_type" id="register_type" ng-model="register_type" value="institute">
								<div class="col-lg-6">
									<label class="text-royal" for="firstName"><strong>First Name</strong></label>
									<input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control"/>
									<!-- <input type="email" class="form-control" id="firstName"> -->
								</div>
								<div class="col-lg-6">
									<label class="text-royal" for="lastName"><strong>Last Name</strong></label>
									<input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control" required />
									<!-- <input type="email" class="form-control" id="lastName"> -->
								</div>
							</div>
							@if(Request::routeIs('site.institute') )
							<div class="form-group">
								<label class="text-royal" for="instituteName"><strong>Institute Name</strong></label>
								<input type="text" name="institute_name" id="institute_name" placeholder="Institute Name" class="form-control" required />
								<!-- <input type="email" class="form-control" id="instituteName"> -->
							</div>
							<div class="form-group">
								<label class="text-royal" for="institute_address"><strong>Institute Address</strong></label>
								<textarea name="institute_address" id="institute_address" placeholder="Institute Address" class="form-control" required></textarea>
								<!-- <input type="email" class="form-control" id="instituteName"> -->
							</div>
							@else

							<div class="form-group" style="margin-bottom: 1rem;">
                                <label for="board_id" class="text-royal"><strong>Board</label></strong><span style="color: red;">*</span>
                                <?php
                                $boards = \App\Board::where('status', 'active')->get()->pluck('title', 'id')->prepend('Please select board', '');
                                ?>
                                {{Form::select('board_id', $boards, null, [
                                'ng-model'=>'board_id',
                                'id' => 'board_id',
                                'class' => 'form-control',
                                'required'=> 'true',
                                'ng-class'=>'{"has-error": registrationForm.board_id.$touched && registrationForm.board_id.$invalid}',
                                ])}}
                            </div>

							 <div class="form-group" style="margin-bottom: 1rem;">
                                <label for="student_class_id" class="text-royal"><strong>Class</label></strong>
                                <?php
                                $classes = \App\StudentClass::where('institute_id', OWNER_INSTITUTE_ID)->get()->pluck('name', 'id')->prepend('Please select class', '');
                                ?>
                                {{Form::select('student_class_id', $classes, null, [
                                'ng-model'=>'student_class_id',
                                'id' => 'student_class_id',
                                'class' => 'form-control',
                                'ng-class'=>'{"has-error": registrationForm.student_class_id.$touched && registrationForm.student_class_id.$invalid}',
                                'onChange' => 'getCourses()'
                                ])}}
                            </div>
							<div class="form-group" style="margin-bottom: 1rem;">
	                            <label for="course_id" class="text-royal"><strong>Course</label></strong>
	                            <?php
	                            $courses = \App\Course::where('institute_id', OWNER_INSTITUTE_ID)->get()->pluck('title', 'id')->prepend('Please select course', '0');
	                            ?>
	                            {{Form::select('course_id', $courses, null, [
	                            'ng-model'=>'course_id',
	                            'id' => 'course_id',
	                            'class' => 'form-control',
	                            'ng-class'=>'{"has-error": registrationForm.course_id.$touched && registrationForm.course_id.$invalid}',
	                            ])}}
	                        </div>
	                        

                           
							@endif

							<div class="form-group">
								<label class="text-royal" for="userName"><strong>Username</strong></label>
								<input type="text" name="username" id="username" placeholder="Username" class="form-control" required />
								<!-- <input type="email" class="form-control" id="userName"> -->
							</div>
							<div class="form-group">
								<label class="text-royal" for="registerPhone"><strong>Phone</strong></label>
								<input type="tel" name="phone" id="phone-register" placeholder="Phone" class="form-control" required />
								<!-- <input type="email" class="form-control" id="registerPhone"> -->
							</div>
							<div class="form-group">
								<label class="text-royal" for="registeremail"><strong>Email</strong></label>
								<input type="email" name="email" id="signup-email" placeholder="Email" class="form-control" required />
								<!-- <input type="email" class="form-control" id="registeremail"> -->
							</div>
							<div class="form-group">
								<label class="text-royal" for="registerPassword"><strong>Password</strong></label>
								<input type="password" name="password" id="signup-password" placeholder="Password" class="form-control" required />
                                            <i class="fa fa-eye togglepassword-signup togglepassword_register_model" aria-hidden="true"></i>
								<!-- <input type="password" class="form-control" id="registerPassword"> -->
							</div>
							<div class="form-group">
								<label class="text-royal" for="confirmPassword"><strong>Confirm Password</strong></label>
								<input type="password" name="password_confirmation" id="password_confirmation" placeholder="Re-type Password" class="form-control" required />
								<!-- <input type="password" class="form-control" id="confirmPassword"> -->
							</div>
							<a href="javascript:void(0);" class="text-orange" onclick="openModal('loginModal')">Have Account?</a>
							<!-- <a class="text-orange" href="#">Have Account?</a> -->
							<button type="submit" class="btn btn-le-dark float-right">Submit</button>
							<div id="loading"></div>
						</form>
					</div>
				</div>
			</div>
		</div>

@if(! env('DISABLE_BOT') )
<script src="{{url('/supportboard/js/init.js')}}"></script>
@include('common.editor');
@endif

	</body>

	<script>
		if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/service-worker.js')
    .then(function(registration) {
      // Registration was successful
      console.log('ServiceWorker registration successful with scope: ', registration.scope);
    }).catch(function(err) {
      // registration failed :(
      console.log('ServiceWorker registration failed: ', err);
    });
}
	</script>

</html>
<script type="text/javascript">
   $('#loginForm').submit(function( e ) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var _token   = $("input[name='_token']").val();
            var email    = $("#login-email").val();
            var password = $("#login-password").val();

            var error = 0;

            $('.error').remove();
            if ( email == '' ) {
              $('#input-group-email').after('<div class="error input-group col-sm-12" style="padding-left:29px;">Please enter email address.</div>');
              error++;
            }

            if( password == '' ) {
               $('#input-group-password').after('<div class="error input-group col-sm-12" style="padding-left:29px;">Please enter password.</div>');
                error++;
            }

            if (error > 0) {
              e.preventDefault();
            } else {

              //var target = $('#loginForm').attr('action');
              //console.log(target)
              $.ajax({
                  url: '{{URL_USERS_LOGIN}}',
                  type:'POST',
                  data: {_token:_token, email:email, password:password, isajax:1},
                  success: function(data) {
                      // console.log(data);

                      if($.isEmptyObject(data.error)){
                          //console.log(data.success);
                           if ( $('#redirect_url').val() != '') {
                            window.location = $('#redirect_url').val();
                          }else {
                             //window.location = data.redirect;
                             window.location = '{{URL_HOME}}';
                          }

                      }else{
                          printErrorMsg(data.error, 'print-error-msg-login');
                      }

                  }
              });

              return false;
            }
        });

   $('#registrationForm').submit(function( e ) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var _token   = $("input[name='_token']").val();
            var register_type    = $("#register_type").val();
            var first_name    = $("#first_name").val();
            var last_name    = $("#last_name").val();
            var institute_name    = $("#institute_name").val();
            var username    = $("#username").val();
            var phone    = $("#phone-register").val();
            var email    = $("#signup-email").val();
            var password = $("#signup-password").val();
            var password_confirmation    = $("#password_confirmation").val();

            var board_id = $("#board_id").val();
            var student_class_id = $("#student_class_id").val();
            var course_id = $("#course_id").val();


            var image = "{{themes('images/loader.svg')}}";
            $('#loading').html("<img src='"+image+"' />");


            var error = 0;

            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var reg = /^\d+$/; // Only numbers.
            $('.error').remove();

            if( register_type == '' ) {
               $('#register_type').after('<div class="error input-group col-sm-12" style="padding:0px;">Please select register as.</div>');
                error++;
            }
            if( first_name == '' ) {
               $('#first_name').after('<div class="error input-group col-sm-12" style="padding:0px;">Please enter first name.</div>');
                error++;
            }

            if( register_type == 'institute' && institute_name == '' ) {
              $('#institute_name').after('<div class="error input-group col-sm-12" style="padding:0px;">Please enter institute name.</div>');
                error++;
            }

            if ( email == '' ) {
              $('#email').after('<div class="error input-group col-sm-12" style="padding:0px;">Please enter email address.</div>');
              error++;
            }  else if( !re.test(email) ) {
              $( '#email' ).after( '<div class="error input-group col-sm-12" style="padding:0px;">Please enter valid email address</div>' );
              error++;
            }

            if( phone == '' ) {
               $('#phone-register').after('<div class="error input-group col-sm-12" style="padding:0px;">Please enter mobile number.</div>');
                error++;
            } else if( ! reg.test(phone) || phone.length < 10 ) {
              $('#phone-register').after('<div class="error input-group col-sm-12" style="padding:0px;">Please enter valid mobile number.</div>');
                error++;
            }

            if( password == '' ) {
               $('#signup-password').after('<div class="error input-group col-sm-12" style="padding:0px;">Please enter password.</div>');
                error++;
            }

            if( password != '' && password_confirmation == '' ) {
                 $('#password_confirmation').after('<div class="error input-group col-sm-12" style="padding:0px;">Please enter password.</div>');
                  error++;
              }

            if( password != '' && password_confirmation != '' ) {
              if( password != password_confirmation ) {
                $('#password_confirmation').after('<div class="error input-group col-sm-12" style="padding:0px;">Password and confirm password should be same.</div>');
                  error++;
              }
            }

            if ( register_type == 'student' ) {
              if( board_id == '' ) {
                 $('#board_id').after('<div class="error input-group col-sm-12" style="padding:0px;">Please select board.</div>');
                  error++;
              }
              /*
              if( student_class_id == '' ) {
                 $('#student_class_id').after('<div class="error input-group col-sm-12" style="padding:0px;">Please select your class.</div>');
                  error++;
              }
              */
              // if( course_id == '0' ) {
              //    $('#course_id').after('<div class="error input-group col-sm-12" style="padding:0px;">Please select your course.</div>');
              //     error++;
              // }
          }


            if (error > 0) {
              e.preventDefault();
               $('#loading').html("").hide();
            } else {


              $('#loading').html("<img src='"+image+"' />").show();

              $.ajax({
                  url: "{{URL_USERS_REGISTER}}",
                  type:'POST',
                  data: {_token:_token, register_type:register_type, first_name:first_name, last_name:last_name, institute_name:institute_name, username:username, phone:phone, email:email, password:password, password_confirmation:password_confirmation, board_id:board_id, student_class_id:student_class_id, course_id:course_id, isajax:'yes'},
                  success: function(data) {
                      if($.isEmptyObject(data.error)){
                        // $('#registerModal').modal('hide');
                        //$("#registerModal .close").click();
                        //$('#registrationForm').trigger('reset');
                        //displayMessage();
                        window.location = '{{URL_USERS_DASHBOARD}}';
                      }else{
                          $('#loading').html("").hide();
                          printErrorMsg(data.error, 'print-error-msg-signup');
                      }
                  }
              });
            }
        });

function printErrorMsg(msg, divclass) {
    $(".print-error-msg-login").css("display", 'none');
    $(".print-error-msg-signup").css("display", 'none');

    $("." + divclass).find("ul").html('');
    $("." + divclass).css('display','block');
    $.each( msg, function( key, value ) {
        $("." + divclass).find("ul").append('<li>'+value+'</li>');
    });
}

function printSuccessMsg (msg, divclass) {
    $(".print-success-msg-signup").css("display", 'none');

    $("." + divclass).find("ul").html('');
    $("." + divclass).css('display','block');
    $.each( msg, function( key, value ) {
        $("." + divclass).find("ul").append('<li>'+value+'</li>');
    });
}
/*
var app = angular.module('academia', ['ngMessages']);
app.controller('Registration', function( $scope) {
    $scope.setRegisterType = function(role) {
        $scope.register_type = role;
        $('#register_type').val(role);
    }
});

function setRegisterType(role, id) {
  $('#register_type').val(role);
  //openModal( id );
  $('#register_type').trigger('input'); // Use for Chrome/Firefox/Edge
  $('#register_type').trigger('change'); // Use for Chrome/Firefox/Edge + IE

  var scope = angular.element($("#register_type")).scope();
  scope.$apply(function(){
      scope.selectValue = newVal;
  });
}
*/
function openModal(id) {
  $('.modal').modal('hide');
  $('.modal-backdrop').remove() // removes the grey overlay.
  $('#' + id).modal('show');
}

function getCourses() {
  var student_class_id = $('#student_class_id').val();
  var _token   = $("input[name='_token']").val();

  $.ajax({
          url: "{{route('class.courses')}}",
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
    console.log(navigator);
    // Check that service workers are supported
if ('serviceWorker' in navigator) {
  // Use the window load event to keep the page load performant
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/public/service-worker.js');
  });
}


var deferredPrompt;
window.addEventListener('beforeinstallprompt', function(event) {
  event.preventDefault();
  deferredPrompt = event;
  return false;
});

function addToHomeScreen() {
  if (deferredPrompt) {
    deferredPrompt.prompt();
    deferredPrompt.userChoice.then(function (choiceResult) {
      console.log(choiceResult.outcome);
      if (choiceResult.outcome === 'dismissed') {
        console.log('User cancelled installation');
      } else {
        console.log('User added to home screen');
      }
    });
    deferredPrompt = null;
  }
}

</script>