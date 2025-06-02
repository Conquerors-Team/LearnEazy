<!DOCTYPE html>

<html lang="en" dir="<?php echo e((App\Language::isDefaultLanuageRtl()) ? 'rtl' : 'ltr'); ?>">



<head>

	<meta charset="utf-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="description" content="<?php echo e(getSetting('meta_description', 'seo_settings')); ?>">

	<meta name="keywords" content="<?php echo e(getSetting('meta_keywords', 'seo_settings')); ?>">

	 	<meta name="csrf_token" content="<?php echo e(csrf_token()); ?>">


	<link rel="icon" href="<?php echo e(IMAGE_PATH_SETTINGS.getSetting('site_favicon', 'site_settings')); ?>" type="image/x-icon" />

	<title><?php echo $__env->yieldContent('title'); ?> <?php echo e(isset($title) ? $title : getSetting('site_title','site_settings')); ?></title>

	<!-- Bootstrap Core CSS -->

 <?php echo $__env->yieldContent('header_scripts'); ?>

	   <link href="<?php echo e(themes('css/bootstrap.min.css')); ?>" rel="stylesheet">
	   <link href="<?php echo e(themes('css/sweetalert.css')); ?>" rel="stylesheet">
	   <link href="<?php echo e(themes('css/sb-admin.css')); ?>" rel="stylesheet">
	   <link href="<?php echo e(themes('css/custom-fonts.css')); ?>" rel="stylesheet">
	   <link href="<?php echo e(themes('css/materialdesignicons.css')); ?>" rel="stylesheet">
	   <link href="<?php echo e(themes('font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet">




	<	<link href="<?php echo e(themes('css/bootstrap-datepicker.min.css')); ?>" rel="stylesheet">

	<!-- Morris Charts CSS -->
	<link href="<?php echo e(themes('css/plugins/morris.css')); ?>" rel="stylesheet">



	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

	<!--[if lt IE 9]>

        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->

 <?php
    $theme_color  = getThemeColor();
    ?>
    <?php if($theme_color == 'blueheader'): ?>
	 <link href="<?php echo e(themes('css/theme-colors/header-blue.css')); ?>" rel="stylesheet">
    <?php elseif($theme_color == 'bluenavbar'): ?>
	 <link href="<?php echo e(themes('css/theme-colors/blue-sidebar.css')); ?>" rel="stylesheet">
    <?php elseif($theme_color == 'darkheader'): ?>
	 <link href="<?php echo e(themes('css/theme-colors/dark-header.css')); ?>" rel="stylesheet">
    <?php elseif($theme_color == 'darktheme'): ?>
	 <link href="<?php echo e(themes('css/theme-colors/dark-theme.css')); ?>" rel="stylesheet">
    <?php elseif($theme_color == 'whitecolor'): ?>
	 <link href="<?php echo e(themes('css/theme-colors/white-theme.css')); ?>" rel="stylesheet">]
	<?php endif; ?>



</head>



<body ng-app="academia" <?php if(env('DISABLE_RIGHTCLICK')): ?> oncontextmenu="return false;" <?php endif; ?>>

 <?php echo $__env->yieldContent('custom_div'); ?>

 <?php

 $class = '';

 if(!isset($right_bar))

 	$class = 'no-right-sidebar';

$block_class = '';

if(isset($block_navigation))

	$block_class = 'non-clickable';

 ?>

	<div id="wrapper" class="<?php echo e($class); ?>">

		<!-- Navigation -->

		<nav class="navbar navbar-custom navbar-fixed-top <?php echo e($block_class); ?>" role="navigation">

		<?php
		if(isset($block_navigation)) { ?>
			<div class="alert alert-danger alert-norefresh">
			  <strong><?php echo e(getPhrase('warning')); ?> !</strong> <?php echo e(getPhrase('do_not_press_back/refresh_button')); ?>

			</div>

		<?php } ?>

			<!-- Brand and toggle get grouped for better mobile display -->

			<div class="navbar-header">

				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>

				<a class="navbar-brand" target="_blank" href="<?php echo e(URL_HOME); ?>"><img src="<?php echo e(IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')); ?>" alt="<?php echo e(getSetting('site_title','site_settings')); ?>"></a>

			</div>

			<!-- Top Menu Items -->

			<ul class="nav navbar-right top-nav">

				<?php
                  $institute_id  = adminInstituteId();
                  $institute     = getInstitute($institute_id);

			 	 ?>
			 	 <li style="color: white;"><font size="4px;"><?php echo e($institute->institute_name); ?></font>, <?php echo e($institute->institute_address); ?></li>





				<li class="dropdown profile-menu">

					<div class="dropdown-toggle top-profile-menu" data-toggle="dropdown">

						<?php if(Auth::check()): ?>

						<div class="username">

							<h2><?php echo e(Auth::user()->name); ?></h2>



						</div>

						<?php endif; ?>



						<div class="profile-img"> <img src="<?php echo e(getProfilePath(Auth::user()->image, 'thumb')); ?>" alt=""> </div>

						<div class="mdi mdi-menu-down"></div>

					</div>

					<ul class="dropdown-menu">



						<li>

							<a href="<?php echo e(URL_BOOKMARKS.Auth::user()->slug); ?>">

								<sapn><?php echo e(getPhrase('my_bookmarks')); ?></sapn>

							</a>

						</li>

						<li>

							<a href="<?php echo e(URL_USERS_EDIT.Auth::user()->slug); ?>">

								<sapn><?php echo e(getPhrase('my_profile')); ?></sapn>

							</a>

						</li>

						 <?php if(isOnlinestudent()): ?>
						 <li>

							<a href="<?php echo e(URL_USERS_CHANGE_PASSWORD.Auth::user()->slug); ?>">

								<sapn><?php echo e(getPhrase('change_password')); ?></sapn>

								</a>

						</li>
						<?php endif; ?>

						<!-- <li>

							<a href="<?php echo e(URL_FEEDBACK_SEND); ?>">

								<sapn><?php echo e(getPhrase('feedback')); ?></sapn>

							</a>

						</li> -->



						<li>

							<a href="<?php echo e(URL_USERS_LOGOUT); ?>">

								<sapn><?php echo e(getPhrase('logout')); ?></sapn>

							</a>

						</li>

					</ul>

				</li>

			</ul>

			<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->

			<!-- /.navbar-collapse -->

		</nav>

		<?php if(env('DEMO_MODE')): ?>
		<div class="alert alert-info demo-alert">
		&nbsp;&nbsp;&nbsp;<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  			<strong><?php echo e(getPhrase('info')); ?>!</strong> CRUD <?php echo e(getPhrase('operations_are_disabled_in_demo_version')); ?>

		</div>
		<?php endif; ?>

		<?php if(env('APP_DEBUG')): ?>
		<div class="alert alert-info demo-alert">
		&nbsp;&nbsp;&nbsp;<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  			<?php echo e(print_r(getController())); ?>

		</div>
		<?php endif; ?>

		<aside class="left-sidebar">

			<div class="collapse navbar-collapse navbar-ex1-collapse">

				<ul class="nav navbar-nav side-nav <?php echo e($block_class); ?>">

					<li <?php echo e(isActive($active_class, 'dashboard')); ?>>
						<a href="<?php echo e(PREFIX); ?>">
							<i class="fa fa-fw fa-window-maximize"></i>  <?php echo e(getPhrase('dashboard')); ?>

						</a>
					</li>
                    
						
					
					





					


					<li <?php echo e(isActive($active_class, 'analysis')); ?> >



					<a data-toggle="collapse" data-target="#analysis">
					<i class="fa fa-fw fa-bar-chart" aria-hidden="true"></i>

					<?php echo e(getPhrase('analysis')); ?> </a>



					<ul id="analysis" class="collapse sidemenu-dropdown">

						<li><a href="<?php echo e(URL_STUDENT_ANALYSIS_SUBJECT.Auth::user()->slug); ?>"> <i class="fa fa-key"></i><?php echo e(getPhrase('by_subject')); ?></a></li>

						<li><a href="<?php echo e(URL_STUDENT_ANALYSIS_BY_EXAM.Auth::user()->slug); ?>"> <i class="fa fa-suitcase"></i><?php echo e(getPhrase('by_exam')); ?></a></li>

						<li><a href="<?php echo e(URL_STUDENT_EXAM_ATTEMPTS.Auth::user()->slug); ?>"> <i class="fa fa-history"></i><?php echo e(getPhrase('history')); ?> </a></li>

					</ul>



					</li>



					<?php if(getSetting('messaging', 'module')): ?>

					<li <?php echo e(isActive($active_class, 'messages')); ?> >

<a  href="<?php echo e(URL_MESSAGES); ?>"> <i class="fa fa-fw fa-comments" aria-hidden="true"> </i>
					<?php echo e(getPhrase('messages')); ?> <small class="msg"><?php echo e($count = Auth::user()->newThreadsCount()); ?> </small></a>

					</li>

					<?php endif; ?>

					<?php if(isOnlineclassSubscribed() || isOnlinestudent()): ?>
					<li <?php echo e(isActive($active_class, 'onlineclasses')); ?> >
						<a href="<?php echo e(URL_ONLINECLASSES); ?>" ><i class="fa fa-cloud" aria-hidden="true"></i>
					Academic Schedule (Online classes) </a>
					</li>
					<?php endif; ?>

					<li <?php echo e(isActive($active_class, 'notifications')); ?> >
						<a href="<?php echo e(URL_NOTIFICATIONS); ?>" ><i class="fa fa-bell" aria-hidden="true"></i>
					<?php echo e(getPhrase('notifications')); ?> </a>
					</li>

					<?php if(!isOnlinestudent()): ?>
					<!-- <li><a href="<?php echo e(URL_STUDENT_EXAM_SERIES_LIST); ?>"> <i class="fa fa-list-ol"></i><?php echo e(getPhrase('exam_series')); ?></a></li> -->
					<?php endif; ?>

					<li <?php echo e(isActive($active_class, 'subscriptions')); ?> >
					<a  href="<?php echo e(URL_PAYMENTS_LIST.Auth::user()->slug); ?>"><i class="fa fa-fw fa-ticket" ></i>
					<?php echo e(getPhrase('subscriptions')); ?> </a>
					</li>

					<?php if(Auth::user()->institute_id == OWNER_INSTITUTE_ID): ?>
					<li <?php echo e(isActive($active_class, 'paid_content')); ?> >
					<a  href="<?php echo e(route('student.paid_content')); ?>"><i class="fa fa-fw fa-ticket" ></i>
					<?php echo e(getPhrase('buy_now')); ?> </a>
					</li>
					<?php endif; ?>
                   

				</ul>

			</div>

		</aside>

		<?php if(isset($right_bar)): ?>



		<aside class="right-sidebar" id="rightSidebar">

			<button class="sidebat-toggle" id="sidebarToggle" href='javascript:'><i class="mdi mdi-menu"></i></button>

			<?php $right_bar_class_value = '';

			if(isset($right_bar_class))

				$right_bar_class_value = $right_bar_class;

			?>

			<div class="panel panel-right-sidebar <?php echo e($right_bar_class_value); ?>">

			<?php $data = '';

			if(isset($right_bar_data))

				$data = $right_bar_data;

			?>

				<?php echo $__env->make($right_bar_path, array('data' => $data), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

			</div>

		</aside>



	<?php endif; ?>

		<?php echo $__env->yieldContent('content'); ?>

	</div>

	<!-- /#wrapper -->

	<!-- jQuery -->

     <script src="<?php echo e(themes('js/jquery-1.12.1.min.js')); ?>"></script>
	<script src="<?php echo e(themes('js/bootstrap.min.js')); ?>"></script>
	<script src="<?php echo e(themes('js/main.js')); ?>"></script>
	<script src="<?php echo e(themes('js/sweetalert-dev.js')); ?>"></script>


    <script>
            var csrfToken = $('[name="csrf_token"]').attr('content');

            setInterval(refreshToken, 600000); // 1 hour

            function refreshToken(){
                $.get('refresh-csrf').done(function(data){
                    csrfToken = data; // the new token
                });
            }

            setInterval(refreshToken, 600000); // 1 hour

        </script>



	<?php echo $__env->make('common.alertify', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>



	<?php echo $__env->yieldContent('footer_scripts'); ?>

	<?php echo $__env->make('errors.formMessages', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<?php echo $__env->yieldContent('custom_div_end'); ?>
	<?php echo getSetting('google_analytics', 'seo_settings'); ?>


</body>



</html>