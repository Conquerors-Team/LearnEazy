@extends('layouts.sitelayout')

@section('content')
   <!-- breadcrumb start-->
    <section class="breadcrumb breadcrumb_bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb_iner text-center">
                        <div class="breadcrumb_iner_item">
                            <h2>Contact us</h2>
                            <p>Home<span>/<span>Contact us</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb start-->

  <!-- ================ contact section start ================= -->
  <section class="contact-section section_padding">
    <div class="container">
      <?php /*?>
      <div class="d-none d-sm-block mb-5 pb-4">

        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15232.399572314374!2d78.54124493576558!3d17.35892560952974!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb98aeadc10291%3A0x6e2e7faa27222ade!2sL.%20B.%20Nagar%2C%20Hyderabad%2C%20Telangana!5e0!3m2!1sen!2sin!4v1590557880271!5m2!1sen!2sin" width="1200" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>


        <div id="map" style="height: 480px;"></div>
        <script>
          function initMap() {
            var uluru = {lat: -25.363, lng: 131.044};
            var grayStyles = [
              {
                featureType: "all",
                stylers: [
                  { saturation: -90 },
                  { lightness: 50 }
                ]
              },
              {elementType: 'labels.text.fill', stylers: [{color: '#ccdee9'}]}
            ];
            var map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: -31.197, lng: 150.744},
              zoom: 9,
              styles: grayStyles,
              scrollwheel:  false
            });
          }

        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCiqiV3wRFIWQnwZkBb95XbwNYC1o4yXDQ&callback=initMap"></script>


      </div>
<?php */ ?>

      <div class="row">
        <div class="col-12">
          <h2 class="contact-title">Get in Touch</h2>
        </div>
        <div class="col-lg-8">

          <!-- <form class="form-contact contact_form" action="contact_process.php" method="post" id="contactForm" novalidate="novalidate"> -->
            {!! Form::open(array('url'=>URL_SEND_CONTACTUS, 'name'=>'user-contact' ,'id'=>'contactForm', 'class' => 'form-contact contact_form'))  !!}
            <div class="row">
              <div class="col-12">
                <div class="form-group">

                    <textarea class="form-control w-100" name="message" id="message" cols="30" rows="9" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Message'" placeholder = 'Enter Message'></textarea>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" name="name" id="name" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your name'" placeholder = 'Enter your name'>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" name="phone" id="phone" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your mobile number'" placeholder = 'Enter your mobile number'>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" name="email" id="email" type="email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter email address'" placeholder = 'Enter email address'>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <input class="form-control" name="subject" id="subject" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Subject'" placeholder = 'Enter Subject'>
                </div>
              </div>
            </div>
            <div class="form-group mt-3">
              <button type="submit" class="button button-contactForm btn_1">Send Message</button>
            </div>
          </form>
        </div>
        <div class="col-lg-4">
          <!-- <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-home"></i></span>
            <div class="media-body">
              <h3>LB Nagar, Hyderabad.</h3>
              <p>India</p>
            </div>
          </div> -->
          <!-- <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-tablet"></i></span>
            <div class="media-body">
              <h3>98663 97147</h3>
              <p>Mon to Fri 9am to 6pm</p>
            </div>
          </div> -->
          <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-email"></i></span>
            <div class="media-body">
              <h3>info@learneazy.org</h3>
              <p>Send us your query anytime!</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ================ contact section end ================= -->

  @include('site.login-register-modals')
@stop

@section('footer_scripts')

  <script src='https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js'></script>
        <script>

                $(function() {

                  $("form[name='user-contact']").validate({

                    rules: {
                      name: "required",
                      //phone: "required",
                      subject: "required",
                      message: "required",
                      email: {
                        required: true,
                        email: true
                      },

                    },

                    messages: {
                      name: "{{getPhrase('Please enter your Name')}}",
                      message: "{{getPhrase('Please enter your Message')}}",
                      subject: "{{getPhrase('Please enter your Subject')}}",
                      phone: "{{getPhrase('Please enter your Phone Number')}}",


                      email: {
                        required: "{{getPhrase('Please provide a valid email')}}",
                        email: "{{getPhrase('Please enter a valid email address')}}"
                      }
                    },

                    submitHandler: function(form) {
                      form.submit();
                    }
                  });
                });


                function ContactUsConfirmation(){


           $(function(){
                    PNotify.removeAll();
                    new PNotify({
                        title: "{{getPhrase('congratulations')}}",
                        text: "{{getPhrase('your_message_was_sent_our_team_will_contact_you_soon')}}",
                        type: "success",
                        delay: 4000,
                        shadow: true,
                        width: "300px",

                        animate: {
                                    animate: true,
                                    in_class: 'fadeInLeft',
                                    out_class: 'fadeOutRight'
                                }
                        });
                });

                 }



        </script>

@stop