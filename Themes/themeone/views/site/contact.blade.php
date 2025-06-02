@extends('layouts.sitelayoutnew')

@section('content')
   <!-- ================ contact section start ================= -->
  <section class="contact-section section_padding">
    <div class="container">

      <div class="row">
        <div class="col-12">
          <h2 class="contact-title">Get in Touch</h2>
        </div>
        <div class="col-lg-8">

          <!-- <form class="form-contact contact_form" action="contact_process.php" method="post" id="contactForm" novalidate="novalidate"> -->
            <form method="POST" action="https://learneazy.org/send/contact-us/details" accept-charset="UTF-8" name="user-contact" id="contactForm" class="form-contact contact_form ng-pristine ng-valid" novalidate="novalidate"><input name="_token" type="hidden" value="v7mbTvIfPclTXtYzup6XEAUJkpdsRhVWYseX6HC4">
            <div class="row">
              <div class="col-12">
                <div class="form-group">

                    <textarea class="form-control w-100" name="message" id="message" cols="30" rows="9" onfocus="this.placeholder = &#39;&#39;" onblur="this.placeholder = &#39;Enter Message&#39;" placeholder="Enter Message"></textarea>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" name="name" id="name" type="text" onfocus="this.placeholder = &#39;&#39;" onblur="this.placeholder = &#39;Enter your name&#39;" placeholder="Enter your name">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" name="phone" id="phone" type="text" onfocus="this.placeholder = &#39;&#39;" onblur="this.placeholder = &#39;Enter your mobile number&#39;" placeholder="Enter your mobile number">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" name="email" id="email" type="email" onfocus="this.placeholder = &#39;&#39;" onblur="this.placeholder = &#39;Enter email address&#39;" placeholder="Enter email address">
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <input class="form-control" name="subject" id="subject" type="text" onfocus="this.placeholder = &#39;&#39;" onblur="this.placeholder = &#39;Enter Subject&#39;" placeholder="Enter Subject">
                </div>
              </div>
            </div>
            <div class="form-group mt-3">
              <button type="submit" class="button button-contactForm btn_1 bg-royal">Send Message</button>
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