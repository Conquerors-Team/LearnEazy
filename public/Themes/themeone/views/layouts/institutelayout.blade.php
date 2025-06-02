<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lern Easy</title>
    <link rel="icon" href="{{IMAGE_PATH_SETTINGS.getSetting('site_favicon', 'site_settings')}}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{themes('site/css/bootstrap.min.css')}}">
    <!-- animate CSS -->
    <link rel="stylesheet" href="{{themes('site/css/animate.css')}}">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="{{themes('site/css/owl.carousel.min.css')}}">
    <!-- themify CSS -->
    <link rel="stylesheet" href="{{themes('site/css/themify-icons.css')}}">
    <!-- flaticon CSS -->
    <link rel="stylesheet" href="{{themes('site/css/flaticon.css')}}">
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="{{themes('site/css/magnific-popup.css')}}">
    <!-- swiper CSS -->
    <link rel="stylesheet" href="{{themes('site/css/slick.css')}}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>


<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/css/intlTelInput.css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.min.js"></script>

<link href="{{themes('css/sweetalert.css')}}" rel="stylesheet">

    <!-- style CSS -->
    <link rel="stylesheet" href="{{themes('site/css/style.css')}}">


</head>

<body ng-app="academia">
    <!-- Navigation -->

     @if(Route::is('site.home'))
        @include('site.header_home')
     @elseif(Route::is('site.institute'))
        @include('site.header_institute')
     @else
        @include('site.header')
     @endif


      @yield('content')


    <!-- footer part start-->
    <footer class="footer-area">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="single-footer-widget footer_1">
                        <a href="{{URL_HOME}}"> <img src="{{themes('site/img/logo.png')}}" alt=""> </a>
                        <p>We as a team belive every child deserves quality education enriched with experimental learning guided by best teaching methodolgy and teachers. </p>
                        <p>This platform provides the best learning solutions for the students in the pursuit of realizing their dreams.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-4">
                    <div class="single-footer-widget footer_2">
                        <h4>Quick Links</h4>
                       <!--  <p>Stay updated with our latest trends Seed heaven so said place winged over given forth fruit.
                        </p>
                        <form action="#">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder='Enter email address'
                                        onfocus="this.placeholder = ''"
                                        onblur="this.placeholder = 'Enter email address'">
                                    <div class="input-group-append">
                                        <button class="btn btn_1" type="button"><i class="ti-angle-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form> -->
                        <ul>
                            <li><a href="{{route('site.about')}}"> About</a></li>
                            <li><a href="{{route('site.courses')}}"> Courses</a></li>
                            <li><a href="{{route('site.blog')}}"> Blog</a></li>
                            <li><a href="{{route('site.pricing')}}"> Pricing</a></li>
                            <li><a href="{{route('site.practice')}}"> Practice</a></li>
                            <li><a href="{{route('site.contact')}}"> Contact</a></li>
                        </ul>

                        <div class="social_icon">
                            <a href="#"> <i class="ti-facebook"></i> </a>
                            <a href="#"> <i class="ti-twitter-alt"></i> </a>
                            <a href="#"> <i class="ti-instagram"></i> </a>
                            <a href="#"> <i class="ti-skype"></i> </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-md-4">
                    <div class="single-footer-widget footer_2">
                        <h4>Contact us</h4>
                        <div class="contact_info">
                            <!-- <p><span> Address :</span> Hi-tech City, Hyderabad. </p>
                            <p><span> Phone :</span> +2 34 567 (8060)</p> -->
                            <p><span> Email : </span>info@learneazy.org </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright_part_text text-center">
                        <div class="row">
                            <div class="col-lg-12">
                                <p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Powered by <a href="https://conquerorstech.net/" target="_blank">CSTPL</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- footer part end-->

    <!-- jQuery -->


    <!-- jquery plugins here-->
    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{themes('site/js/jquery-1.12.1.min.js')}}"></script>
    <!-- popper js -->
    <script src="{{themes('site/js/popper.min.js')}}"></script>
    <!-- bootstrap js -->
    <script src="{{themes('site/js/bootstrap.min.js')}}"></script>
    <!-- easing js -->
    <script src="{{themes('site/js/jquery.magnific-popup.js')}}"></script>
    <!-- swiper js -->
    <script src="{{themes('site/js/swiper.min.js')}}"></script>
    <!-- swiper js -->
    <script src="{{themes('site/js/masonry.pkgd.js')}}"></script>
    <!-- particles js -->
    <script src="{{themes('site/js/owl.carousel.min.js')}}"></script>
    <!-- <script src="js/jquery.nice-select.min.js"></script> -->
    <!-- swiper js -->
    <script src="{{themes('site/js/slick.min.js')}}"></script>
    <script src="{{themes('site/js/jquery.counterup.min.js')}}"></script>
    <script src="{{themes('site/js/waypoints.min.js')}}"></script>

    <script src="{{themes('site/js/notify.js')}}"></script>
     <script src="{{themes('site/js/sweetalert-dev.js')}}"></script>
    <!-- custom js -->
    <script src="{{themes('site/js/custom.js')}}"></script>

    <script>
    $(document).ready(function() {
  $('#media').carousel({
    pause: true,
    interval: false,
  });
});
</script>



@include('errors.formMessages')

@include('common.validations')



   @yield('footer_scripts')

    {!!getSetting('google_analytics', 'seo_settings')!!}


@include('site.login-register-modals-scripts')

</body>

</html>