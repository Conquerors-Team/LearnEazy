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
              if( student_class_id == '' ) {
                 $('#student_class_id').after('<div class="error input-group col-sm-12" style="padding:0px;">Please select your class.</div>');
                  error++;
              }
              if( course_id == '0' ) {
                 $('#course_id').after('<div class="error input-group col-sm-12" style="padding:0px;">Please select your course.</div>');
                  error++;
              }
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
</script>