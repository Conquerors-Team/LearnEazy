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

	   <link href="<?php echo e(themes('css/metisMenu.min.css')); ?>" rel="stylesheet">
	   <link href="<?php echo e(themes('css/custom-fonts.css')); ?>" rel="stylesheet">
	   <link href="<?php echo e(themes('css/materialdesignicons.css')); ?>" rel="stylesheet">
	   <link href="<?php echo e(themes('font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet">

	   <link href="<?php echo e(themes('css/bootstrap-datepicker.min.css')); ?>" rel="stylesheet">

	<!-- Morris Charts CSS -->
	
	   <link href="<?php echo e(themes('css/plugins/morris.css')); ?>" rel="stylesheet">
	 <link href="<?php echo e(themes('css/sb-admin.css')); ?>" rel="stylesheet">
	 

    <?php
    $theme_color  = getThemeColor();
    // dd($theme_color);
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

 ?>
	<div id="wrapper" class="<?php echo e($class); ?>">
		<!-- Navigation -->
		<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
				<a class="navbar-brand" href="<?php echo e(URL_HOME); ?>" target="_blank"><img src="<?php echo e(IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')); ?>" alt="<?php echo e(getSetting('site_title','site_settings')); ?>"></a>


			</div>

			<!-- Top Menu Items -->
			<?php $newUsers = (new App\User())->getLatestUsers(); ?>

			<ul class="nav navbar-right top-nav">
				<?php
                      $institute_id  = adminInstituteId();
                      $institute     = getInstitute($institute_id);

				 	 ?>
				 	 <li style="color: white;"><font size="4px;"><?php echo e($institute->institute_name); ?></font>, <?php echo e($institute->institute_address); ?></li>

				<?php if(checkRole(getUserGrade(10), 'user_access')): ?>
				<li class="dropdown">

					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<i class="icon-topbar-event"></i> <?php echo e(getPhrase('latest_users')); ?>

				 </a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-notif" aria-labelledby="dd-notification">
					<div class="dropdown-menu-notif-list" id="latestUsers">
					<?php $__currentLoopData = $newUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<div class="dropdown-menu-notif-item">
								<div class="photo">
									<img src="<?php echo e(getProfilePath($user->image)); ?>" alt="">
								</div>
								 <a href="<?php echo e(URL_USER_DETAILS.$user->slug); ?>"><?php echo e(ucfirst($user->name)); ?></a>  <?php echo e(getPhrase('was_joined_as').' '. getRoleData($user->role_id)); ?> <?php echo e(getPhrase('in')); ?> <?php echo e($user->studentInstitute()); ?>

								<div class="color-blue-grey-lighter"><?php echo e($user->updated_at->diffForHumans()); ?></div>
							</div>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</div>

						<div class="dropdown-menu-notif-more">
							<a href="<?php echo e(URL_USERS); ?>"><?php echo e(getPhrase('see_more')); ?></a>
						</div>
					</div>
				</li>
				<?php endif; ?>


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
							<a href="<?php echo e(URL_USERS_EDIT); ?><?php echo e(Auth::user()->slug); ?>">
								<sapn><?php echo e(getPhrase('my_profile')); ?></sapn>
							</a>
						</li>
						<li>
							<a href="<?php echo e(URL_USERS_CHANGE_PASSWORD); ?><?php echo e(Auth::user()->slug); ?>">
								<sapn><?php echo e(getPhrase('change_password')); ?></sapn>
								</a>
						</li>

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

		<aside class="left-sidebar">			<div class="collapse navbar-collapse navbar-ex1-collapse">				<ul class="nav navbar-nav side-nav">					<li <?php echo e(isActive($active_class, 'dashboard')); ?>>
						<a href="<?php echo e(PREFIX); ?>">
							<i class="fa fa-fw fa-window-maximize"></i> <?php echo e(getPhrase('dashboard')); ?>

						</a>
					</li>

					<!-- <li <?php echo e(isActive($active_class, 'users')); ?>> <a href="<?php echo e(URL_USERS); ?>"><i class="fa fa-fw fa-user-circle"></i> <?php echo e(getPhrase('users')); ?> </a> </li>
 -->

 					<?php if( canDo('institute_batch_access') ): ?>
					<li <?php echo e(isActive($active_class, 'batches')); ?>> <a href="<?php echo e(URL_BATCHS); ?>"><i class="fa fa-sitemap"></i> <?php echo e(getPhrase('student_batches')); ?> </a> </li>
					<?php endif; ?>



					<?php if( canDo('exams_access') ): ?>
					<li <?php echo e(isActive($active_class, 'exams')); ?> >

					<a data-toggle="collapse" data-target="#exams"><i class="fa fa-fw fa-desktop" ></i>
					<?php echo e(getPhrase('exams')); ?> </a>

					<ul id="exams" class="collapse sidemenu-dropdown">
							<?php if('exam_question_access'): ?>
							<li><a href="<?php echo e(URL_QUIZ_QUESTIONBANK); ?>"> <i class="fa fa-fw fa-fw fa-question"></i><?php echo e(getPhrase('question_bank')); ?></a></li>
							<?php endif; ?>

							<li><a href="<?php echo e(URL_TOPICS_DIRECTORY); ?>"> <i class="fa fa-fw fa-fw fa-random"></i><?php echo e(getPhrase('directory')); ?></a></li>

							<?php if('exams_access'): ?>
							<li><a href="<?php echo e(URL_QUIZZES); ?>"> <i class="icon-total-time"></i> <?php echo e(getPhrase('exams')); ?></a></li>
							<?php endif; ?>

							<?php if('exam_instruction_access'): ?>
							<li><a href="<?php echo e(URL_INSTRUCTIONS); ?>"> <i class="fa fa-fw fa-hand-o-right"></i> <?php echo e(getPhrase('instructions')); ?></a></li>
							<?php endif; ?>

					</ul>

					</li>
					<?php endif; ?>

					<li <?php echo e(isActive($active_class, 'live_quizzes')); ?> >
						<a href="<?php echo e(route('exams.live_quizzes')); ?>" ><i class="fa fa-bolt" aria-hidden="true"></i>Live Quizzes</a>
					</li>


					<?php if( canDo( 'lms_access') ): ?>
					<li <?php echo e(isActive($active_class, 'lms')); ?> >
					<a data-toggle="collapse" data-target="#lms"><i class="fa fa-fw fa-tv" ></i>
					LMS </a>
					<ul id="lms" class="collapse sidemenu-dropdown">
							<?php if( canDo( 'lms_content_access') ): ?>
							<li><a href="<?php echo e(URL_LMS_CONTENT); ?>"> <i class="icon-books"></i><?php echo e(getPhrase('contents')); ?></a></li>
							<?php endif; ?>

							<?php if( canDo('lms_series_access') ): ?>
							<li><a href="<?php echo e(route('lms.series')); ?>"> <i class="fa fa-fw fa-list-ol"></i><?php echo e(getPhrase('series')); ?></a></li>
							<?php endif; ?>

							<?php if(canDo('lms_series_access')): ?>
							<li><a href="<?php echo e(URL_LMS_GROUPS); ?>"> <i class="fa fa-fw fa-list-ol"></i>Content Library</a></li>
							<?php endif; ?>

							<li><a href="<?php echo e(route('lmsseries.directory')); ?>"> <i class="fa fa-fw fa-fw fa-random"></i><?php echo e(getPhrase('directory')); ?></a></li>

							<?php if( canDo( 'lms_notes_access') ): ?>
							<li><a href="<?php echo e(URL_LMS_NOTES); ?>"> <i class="fa fa-sticky-note"></i><?php echo e(getPhrase('notes')); ?></a></li>
							<?php endif; ?>
					</ul>
					</li>
					<?php endif; ?>



					<?php if( canDo('onlineclasses_access') ): ?>
					<li <?php echo e(isActive($active_class, 'onlineclasses')); ?> >
						<a href="<?php echo e(URL_ADMIN_ONLINECLASSES); ?>" ><i class="fa fa-cloud" aria-hidden="true"></i>
					Academic Schedule (Online classes) </a>
					</li>
					<?php endif; ?>


					<li <?php echo e(isActive($active_class, 'batch_reports')); ?> >
					<a data-toggle="collapse" data-target="#lms"><i class="fa fa-fw fa-tv" ></i>
					Reports </a>
					<ul id="lms" class="collapse sidemenu-dropdown">

							<li><a href="<?php echo e(url('admin/onlineclasses/attendence')); ?>"> <i class="icon-books"></i><?php echo e(getPhrase('attendance')); ?></a></li>

							<li><a href="<?php echo e(route('batch.reports')); ?>"> <i class="icon-books"></i><?php echo e(getPhrase('batch_reports')); ?></a></li>

					</ul>
					</li>


					<?php if( canDo('white_board') ): ?>
					<li <?php echo e(isActive($active_class, 'whiteboard')); ?> >
						<a href="<?php echo e(route('onlineclasses.whiteboard')); ?>" ><i class="fa fa-cloud" aria-hidden="true"></i>
					<?php echo e(getPhrase('white_board')); ?> </a>
					</li>
					<?php endif; ?>

					<?php if( canDo('internal_notification_access') ): ?>
					<li <?php echo e(isActive($active_class, 'notifications')); ?> >
						<a href="<?php echo e(URL_NOTIFICATIONS); ?>" ><i class="fa fa-bell" aria-hidden="true"></i>
					<?php echo e(getPhrase('notifications')); ?> </a>
					</li>
					<?php endif; ?>


					<?php if( canDo('message_access') ): ?>
					<li <?php echo e(isActive($active_class, 'messages')); ?> >
					<a  href="<?php echo e(URL_MESSAGES); ?>"> <i class="fa fa-fw fa-comments" aria-hidden="true"> </i>
					<?php echo e(getPhrase('messages')); ?> <small class="msg"><?php echo e($count = Auth::user()->newThreadsCount()); ?> </small></a>
					</li>
					<?php endif; ?>


				</ul>
			</div>
		</aside>
		<?php if(isset($right_bar)): ?>

		<aside class="right-sidebar" id="rightSidebar">
			<button class="sidebat-toggle" id="sidebarToggle" href='javascript:'><i class="mdi mdi-menu"></i></button>
			<div class="panel panel-right-sidebar">
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

<!-- Class alert Modal -->
<div id="class_alert_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Online class</h4>
      </div>

      <div class="modal-body">
      	<span style="font-size:41px; color:red;">Your class will be ended in 10 mins</span>
      </div>

      <div class="modal-footer">
      <div class="pull-right">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(getPhrase('close')); ?></button>
        </div>
      </div>
    </div>
  </div>
</div>
	<!-- jQuery -->

	

	<!-- Bootstrap Core JavaScript -->
	<script src="<?php echo e(themes('js/jquery-1.12.1.min.js')); ?>"></script>
	<script src="<?php echo e(themes('js/bootstrap.min.js')); ?>"></script>
	<script src="<?php echo e(themes('js/main.js')); ?>"></script>
	<script src="<?php echo e(themes('js/metisMenu.min.js')); ?>"></script>
	<script src="<?php echo e(themes('js/sweetalert-dev.js')); ?>"></script>

	<script >
		 /*Sidebar Menu*/
    $("#ag-menu").metisMenu();
	</script>

	<script type="text/javascript">
	 <?php if(isFaculty()): ?>
		function timerFunc() {
			var route = '<?php echo e(url("onlineclasses/classend/alerts")); ?>';
			var token = $('[name="_token"]').val();
			data= {_method: 'get', '_token':token};
			$.ajax({
			url:route,
			//dataType: 'json',
			data: data,
			success:function(result){
				//console.log('result');
				if ( result != '' ) {
					//alert( result );
					$('#class_alert_modal').modal('show');
				}
			  }
			});
		}
	 	// setInterval(timerFunc, 1000); // For each second
	 	setInterval(timerFunc, 1000 * 60); // For each minute
	 	// alert('fgggg');
	 <?php endif; ?>
	 </script>


	 <?php echo $__env->yieldContent('footer_scripts'); ?>

	<?php echo $__env->make('errors.formMessages', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>



 	<?php echo $__env->yieldContent('custom_div_end'); ?>
	<?php echo getSetting('google_analytics', 'seo_settings'); ?>

	<div class="ajax-loader" style="display:none;" id="ajax_loader"><img src="<?php echo e(AJAXLOADER); ?>"> <?php echo e(getPhrase('please_wait')); ?>...</div>
</body>

</html>