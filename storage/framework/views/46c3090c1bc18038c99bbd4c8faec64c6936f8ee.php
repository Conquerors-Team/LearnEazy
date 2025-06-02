<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf_token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" href="<?php echo e(IMAGE_PATH_SETTINGS.getSetting('site_favicon', 'site_settings')); ?>" type="image/x-icon" />
    <title>
    <?php echo $__env->yieldContent('title'); ?> <?php echo e(isset($title) ? $title : getSetting('site_title','site_settings')); ?>

    </title>


    <?php echo $__env->yieldContent('header_scripts'); ?>


      <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo e(themes('site/css/bootstrap.min.css')); ?>">
    <!-- animate CSS -->
    <link rel="stylesheet" href="<?php echo e(themes('site/css/animate.css')); ?>">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="<?php echo e(themes('site/css/owl.carousel.min.css')); ?>">
    <!-- themify CSS -->
    <link rel="stylesheet" href="<?php echo e(themes('site/css/themify-icons.css')); ?>">
    <!-- flaticon CSS -->
    <link rel="stylesheet" href="<?php echo e(themes('site/css/flaticon.css')); ?>">
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="<?php echo e(themes('site/css/magnific-popup.css')); ?>">
    <!-- swiper CSS -->
    <link rel="stylesheet" href="<?php echo e(themes('site/css/slick.css')); ?>">

    <link href="<?php echo e(themes('css/angular-validation.css')); ?>" rel="stylesheet">

    <link href="<?php echo e(themes('font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet">

    <link href="<?php echo e(themes('css/notify.css')); ?>" rel="stylesheet">
     <link href="<?php echo e(themes('css/angular-validation.css')); ?>" rel="stylesheet">
      <link href="<?php echo e(themes('css/sweetalert.css')); ?>" rel="stylesheet">

    <!-- <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/css/intlTelInput.css'> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.min.js"></script> -->
    <!-- style CSS -->
    <link rel="stylesheet" href="<?php echo e(themes('site/css/style.css')); ?>">

<link rel="manifest" href="public/manifest.json" />
</head>

<body ng-app="academia" class="sb-chat sb-conversation">
    <!-- Navigation -->

     <?php if(Route::is('site.home')): ?>
        <?php echo $__env->make('site.header_home', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
     <?php elseif(Route::is('site.institute')): ?>
        <?php echo $__env->make('site.header_institute', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
     <?php else: ?>
        <?php echo $__env->make('site.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
     <?php endif; ?>



      <?php echo $__env->yieldContent('content'); ?>


    <!-- footer part start-->
    <footer class="footer-area">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="single-footer-widget footer_1">
                        <a href="<?php echo e(URL_HOME); ?>"> <img src="<?php echo e(themes('site/img/logo.png')); ?>" alt=""> </a>
                        <p>We as a team belive every child deserves quality education enriched with experimental learning guided by best teaching methodolgy and teachers. </p>
                        <p>This platform provides the best learning solutions for the students in the pursuit of realizing their dreams.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-4">
                    <div class="single-footer-widget footer_2">
                        <h4>Quick Links</h4>
                         <ul>
                            <li><a href="<?php echo e(route('site.about')); ?>"> About</a></li>
                            <li><a href="<?php echo e(route('site.courses')); ?>"> Courses</a></li>
                            <li><a href="<?php echo e(route('site.blog')); ?>"> Blog</a></li>
                            <li><a href="<?php echo e(route('site.pricing')); ?>"> Pricing</a></li>
                            <li><a href="<?php echo e(route('site.practice')); ?>"> Practice</a></li>
                            <li><a href="<?php echo e(route('site.contact')); ?>"> Contact</a></li>
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
    <script src="<?php echo e(themes('site/js/jquery-1.12.1.min.js')); ?>"></script>
    <!-- popper js -->
    <script src="<?php echo e(themes('site/js/popper.min.js')); ?>"></script>
    <!-- bootstrap js -->
    <script src="<?php echo e(themes('site/js/bootstrap.min.js')); ?>"></script>
    <!-- easing js -->
    <script src="<?php echo e(themes('site/js/jquery.magnific-popup.js')); ?>"></script>
    <!-- swiper js -->
    <script src="<?php echo e(themes('site/js/swiper.min.js')); ?>"></script>
    <!-- swiper js -->
    <script src="<?php echo e(themes('site/js/masonry.pkgd.js')); ?>"></script>
    <!-- particles js -->
    <script src="<?php echo e(themes('site/js/owl.carousel.min.js')); ?>"></script>
    <!--<script src="<?php echo e(themes('site/js/jquery.nice-select.min.js')); ?>"></script>-->
    <!-- swiper js -->
    <script src="<?php echo e(themes('site/js/slick.min.js')); ?>"></script>
    <script src="<?php echo e(themes('site/js/jquery.counterup.min.js')); ?>"></script>
    <script src="<?php echo e(themes('site/js/waypoints.min.js')); ?>"></script>

     <script src="<?php echo e(themes('site/js/notify.js')); ?>"></script>
     <script src="<?php echo e(themes('site/js/sweetalert-dev.js')); ?>"></script>

    <!-- custom js -->
    <script src="<?php echo e(themes('site/js/custom.js')); ?>"></script>


<?php echo $__env->make('errors.formMessages', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php echo $__env->make('common.validations', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>



   <?php echo $__env->yieldContent('footer_scripts'); ?>

    <?php echo getSetting('google_analytics', 'seo_settings'); ?>



<?php echo $__env->make('site.login-register-modals-scripts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<!-- <script src="<?php echo e(url('/supportboard/js/min/jquery.min.js')); ?>"></script> -->
<?php if(! env('DISABLE_BOT') ): ?>
<script src="<?php echo e(url('/supportboard/js/init.js')); ?>"></script>
<?php endif; ?>

</body>

</html>

<script type="text/javascript">
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