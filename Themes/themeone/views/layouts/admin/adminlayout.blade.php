<!DOCTYPE html>

<html lang="en" dir="{{ (App\Language::isDefaultLanuageRtl()) ? 'rtl' : 'ltr' }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="{{getSetting('meta_description', 'seo_settings')}}">
	<meta name="keywords" content="{{getSetting('meta_keywords', 'seo_settings')}}">
	<meta name="csrf_token" content="{{ csrf_token() }}">

	<link rel="icon" href="{{IMAGE_PATH_SETTINGS.getSetting('site_favicon', 'site_settings')}}" type="image/x-icon" />
	<title>@yield('title') {{ isset($title) ? $title : getSetting('site_title','site_settings') }}</title>
	<!-- Bootstrap Core CSS -->
	 @yield('header_scripts')

	   <link href="{{themes('css/bootstrap.min.css')}}" rel="stylesheet">
	   <link href="{{themes('css/sweetalert.css')}}" rel="stylesheet">

	   <link href="{{themes('css/metisMenu.min.css')}}" rel="stylesheet">
	   <link href="{{themes('css/custom-fonts.css')}}" rel="stylesheet">
	   <link href="{{themes('css/materialdesignicons.css')}}" rel="stylesheet">
	   <link href="{{themes('font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">

	   <link href="{{themes('css/bootstrap-datepicker.min.css')}}" rel="stylesheet">

	<!-- Morris Charts CSS -->
	{{-- <link href="{{CSS}}plugins/morris.css" rel="stylesheet"> --}}
	   <link href="{{themes('css/plugins/morris.css')}}" rel="stylesheet">
	 <link href="{{themes('css/sb-admin.css')}}" rel="stylesheet">
	 {{-- <link href="{{themes('css/themeone-blue.css')}}" rel="stylesheet"> --}}

    <?php
    $theme_color  = getThemeColor();
    // dd($theme_color);
    ?>
    @if($theme_color == 'blueheader')
	 <link href="{{themes('css/theme-colors/header-blue.css')}}" rel="stylesheet">
    @elseif($theme_color == 'bluenavbar')
	 <link href="{{themes('css/theme-colors/blue-sidebar.css')}}" rel="stylesheet">
    @elseif($theme_color == 'darkheader')
	 <link href="{{themes('css/theme-colors/dark-header.css')}}" rel="stylesheet">
    @elseif($theme_color == 'darktheme')
	 <link href="{{themes('css/theme-colors/dark-theme.css')}}" rel="stylesheet">
    @elseif($theme_color == 'whitecolor')
	 <link href="{{themes('css/theme-colors/white-theme.css')}}" rel="stylesheet">]
	@endif



</head>

<body ng-app="academia" @if(env('DISABLE_RIGHTCLICK')) oncontextmenu="return false;" @endif>
 @yield('custom_div')
 <?php
 $class = '';
 if(!isset($right_bar))
 	$class = 'no-right-sidebar';

 ?>
	<div id="wrapper" class="{{$class}}">
		<!-- Navigation -->
		<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
				<a class="navbar-brand" href="{{ URL_HOME }}" target="_blank"><img src="{{IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')}}" alt="{{getSetting('site_title','site_settings')}}"></a>
			</div>

			<!-- Top Menu Items -->
			<?php $newUsers = (new App\User())->getLatestUsers(); ?>
			<ul class="nav navbar-right top-nav">
				<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<i class="icon-topbar-event"></i> {{ getPhrase('latest_users') }}  </a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-notif" aria-labelledby="dd-notification">
					<div class="dropdown-menu-notif-list" id="latestUsers">
					@foreach($newUsers as $user)
							<div class="dropdown-menu-notif-item">
								<div class="photo">
									<img src="{{ getProfilePath($user->image)}}" alt="">
								</div>
								 <a href="{{URL_USER_DETAILS.$user->slug}}">{{ucfirst($user->name)}}</a>  {{ getPhrase('was_joined_as').' '. getRoleData($user->role_id)}} {{getPhrase('in')}} {{$user->studentInstitute()}}
								<div class="color-blue-grey-lighter">{{$user->updated_at->diffForHumans()}}</div>
							</div>
					@endforeach
						</div>

						<div class="dropdown-menu-notif-more">
							<a href="{{URL_USERS}}">{{ getPhrase('see_more') }}</a>
						</div>
					</div>
				</li>


				<li class="dropdown profile-menu">
					<div class="dropdown-toggle top-profile-menu" data-toggle="dropdown">
						@if(Auth::check())
						<div class="username">
							<h2>{{Auth::user()->name}}</h2>

						</div>
						@endif

						<div class="profile-img"> <img src="{{ getProfilePath(Auth::user()->image, 'thumb') }}" alt=""> </div>
						<div class="mdi mdi-menu-down"></div>
					</div>
					<ul class="dropdown-menu">
						<li>
							<a href="{{URL_USERS_EDIT}}{{Auth::user()->slug}}">
								<sapn>{{ getPhrase('my_profile') }}</sapn>
							</a>
						</li>

						<li>
							<a href="{{URL_USERS_CHANGE_PASSWORD}}{{Auth::user()->slug}}">
								<sapn>{{ getPhrase('change_password') }}</sapn>
								</a>
						</li>

						<li>
							<a href="{{URL_USERS_LOGOUT}}">
								<sapn>{{ getPhrase('logout') }}</sapn>
							</a>
						</li>
					</ul>
				</li>
			</ul>
			<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
			<!-- /.navbar-collapse -->
		</nav>
		 @if(env('DEMO_MODE'))
		<div class="alert alert-info demo-alert">
		&nbsp;&nbsp;&nbsp;<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  			<strong>{{getPhrase('info')}}!</strong> CRUD {{getPhrase('operations_are_disabled_in_demo_version')}}
		</div>
		@endif

		@if(env('APP_DEBUG'))
		<div class="alert alert-info demo-alert">
		&nbsp;&nbsp;&nbsp;<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  			{{print_r(getController())}}
		</div>
		@endif

		<aside class="left-sidebar">			<div class="collapse navbar-collapse navbar-ex1-collapse">				<ul class="nav navbar-nav side-nav">					<li {{ isActive($active_class, 'dashboard') }}>
						<a href="{{PREFIX}}">
							<i class="fa fa-fw fa-window-maximize"></i> {{ getPhrase('dashboard') }}
						</a>
					</li>



					<li {{ isActive($active_class, 'users') }}> <a href="{{URL_USERS}}"><i class="fa fa-fw fa-user-circle"></i> {{ getPhrase('users') }} </a> </li>

					<li {{ isActive($active_class, 'classes') }}> <a href="{{URL_INSTITUTE_CLASSES}}"><i class="fa fa-meetup"></i> {{ getPhrase('classes') }} </a> </li>
					<li {{ isActive($active_class, 'chapters') }}> <a  href="{{route('mastersettings.chapters_index')}}"><i class="fa fa-meetup"></i> {{ getPhrase('chapters') }} </a> </li>

					<li {{ isActive($active_class, 'courses') }}>
						<a href="{{URL_INSTITUTE_COURSE}}"><i class="fa fa-graduation-cap"></i> {{ getPhrase('courses') }} </a>
					</li>

					<li {{ isActive($active_class, 'institutes') }}>
						<a data-toggle="collapse" data-target="#institute" href="{{URL_VIEW_INSTITUES}}"><i class="fa fa-bank"></i> {{ getPhrase('institutes') }} </a>
						<ul id="institute" class="collapse sidemenu-dropdown">
							<li><a href="{{URL_VIEW_INSTITUES}}"> <i class="fa fa-bank"></i>{{ getPhrase('all_institutes') }}</a></li>
							<li><a href="{{URL_VIEW_INSTITUES}}/registered"> <i class="fa fa-building" aria-hidden="true"></i>
							{{ getPhrase('activation_pending_institues') }}</a></li>

						</ul>
					</li>

					<li {{ isActive($active_class, 'batches') }}> <a href="{{URL_BATCHS}}"><i class="fa fa-sitemap"></i> {{ getPhrase('institute_batches') }} </a> </li>

				  <li {{ isActive($active_class, 'fee') }}>

						<a data-toggle="collapse" data-target="#fee" href="{{URL_PAY_FEE}}"><i class="fa fa-money" ></i>
					       {{ getPhrase('pay_fee') }} </a>

					    <ul id="fee" class="collapse sidemenu-dropdown">

							<li><a href="{{URL_PAY_FEE}}"> <i class="fa fa-money"></i>{{ getPhrase('pay_fee') }}</a></li>

							<li><a href="{{URL_GET_FEE_REPORTS_INSTITUTE_WISE}}"> <i class="fa fa-university"></i>{{ getPhrase('institutes_fee_reports') }}</a></li>

							<li><a href="{{URL_GET_FEE_REPORTS_BATCH_WISE}}"> <i class="fa fa-list"></i>{{ getPhrase('batches_fee_reports') }}</a></li>

							<li><a href="{{URL_GET_FEE_DATE_WISE_REPORTS}}"> <i class="fa fa-calendar"></i>{{ getPhrase('fee_reports') }}</a></li>

						</ul>



				 </li>




					<li {{ isActive($active_class, 'exams') }} >

					<a data-toggle="collapse" data-target="#exams"><i class="fa fa-fw fa-desktop" ></i>
					{{ getPhrase('exams') }} </a>

					<ul id="exams" class="collapse sidemenu-dropdown">
							<li><a href="{{route('exams.dashboard')}}"> <i class="fa fa-fw fa-fw fa-random"></i>Dashboard</a></li>

							<li><a href="{{URL_QUESTION_BANK_MANAGEMENT}}"> <i class="fa fa-fw fa-fw fa-random"></i>{{ getPhrase('question_bank_management') }}</a></li>

							<li><a href="{{URL_TOPICS_DIRECTORY}}"> <i class="fa fa-fw fa-fw fa-random"></i>{{ getPhrase('directory') }}</a></li>
							{{--
							<li><a href="{{URL_QUIZ_CATEGORIES}}"> <i class="fa fa-fw fa-fw fa-random"></i>{{ getPhrase('categories') }}</a></li>
							--}}

							<li><a href="{{URL_QUIZ_QUESTIONBANK}}"> <i class="fa fa-fw fa-fw fa-question"></i>{{ getPhrase('question_bank') }}</a></li>

							<li><a href="{{URL_QUIZZES}}"> <i class="icon-total-time"></i> {{ getPhrase('exams')}}</a></li>

							<li><a href="{{URL_EXAM_TYPES}}"> <i class="fa fa-fw fa-list"></i> {{ getPhrase('exam_types')}}</a></li>


							<li><a href="{{URL_EXAM_SERIES}}"> <i class="fa fa-fw fa-list-ol"></i> {{ getPhrase('exam_series')}}</a></li>

							<li><a href="{{URL_INSTRUCTIONS}}"> <i class="fa fa-fw fa-hand-o-right"></i> {{ getPhrase('instructions')}}</a></li>
							<li><a href="{{URL_MASTERSETTINGS_SUBJECTS}}"> <i class="icon-books"></i> {{ getPhrase('subjects_master')}}</a></li>

							<li><a href="{{route('mastersettings.chapters_index')}}"> <i class="icon-books"></i> {{ getPhrase('subject_chapters')}}</a></li>
							<li><a href="{{URL_MASTERSETTINGS_TOPICS}}"> <i class="fa fa-fw fa-database"></i> {{ getPhrase('subject_topics')}}</a></li>

							<li><a href="{{ URL_COMPETITIVE_EXAM_TYPES }}"> <i class="fa fa-compress"></i>Competitive Types</a></li>
							<li><a href="{{ URL_QUESTION_BANK_TYPES }}"> <i class="fa fa-question-circle-o"></i>Question Bank Types</a></li>
							<li><a href="{{ URL_QUESTIONBANK_CATEGORIES }}"> <i class="fa fa-question-circle-o"></i>Question Categories</a></li>



							<li><a href="{{ URL_SUBJECTLOGOS }}"> <i class="fa fa-question-circle-o"></i>Subject Logos</a></li>
							<li><a href="reported_issues/index"> <i class="fa fa-reply" aria-hidden="true"></i>Reported issues</a></li>
					</ul>

					</li>

					<li {{ isActive($active_class, 'live_quizzes') }} >
						<a href="{{route('exams.live_quizzes')}}" ><i class="fa fa-bolt" aria-hidden="true"></i>Live Quizzes</a>
					</li>

					<li {{ isActive($active_class, 'test_series') }} >
						<a href="{{route('exams.test_series')}}" ><i class="fa fa-cloud" aria-hidden="true"></i>
					{{ getPhrase('test_series') }} </a>
					</li>



					<li {{ isActive($active_class, 'coupons') }} >

					<a data-toggle="collapse" data-target="#coupons"><i class="fa fa-fw fa-tags"></i>
					{{ getPhrase('coupons') }} </a>

					<ul id="coupons" class="collapse sidemenu-dropdown">
							<li><a href="{{URL_COUPONS}}"> <i class="fa fa-fw fa-list"></i>{{ getPhrase('list') }}</a></li>
							<li><a href="{{URL_COUPONS_ADD}}"> <i class="fa fa-fw fa-plus"></i>{{ getPhrase('add') }}</a></li>

					</ul>

					</li>



					<li {{ isActive($active_class, 'lms') }} >

					<a data-toggle="collapse" data-target="#lms"><i class="fa fa-fw fa-tv" ></i>
					LMS </a>

					<ul id="lms" class="collapse sidemenu-dropdown">
							<!-- <li><a href="{{ URL_LMS_CATEGORIES }}"> <i class="fa fa-fw fa-random"></i>{{ getPhrase('categories') }}</a></li> -->
							<li><a href="{{ URL_LMS_CONTENT }}"> <i class="icon-books"></i>{{ getPhrase('contents') }}</a></li>
							<li><a href="{{route('lms.series.index')}}"> <i class="fa fa-fw fa-list-ol"></i>{{ getPhrase('series') }}</a></li>
							<li><a href="{{route('lmsseries.directory')}}"> <i class="fa fa-fw fa-fw fa-random"></i>{{ getPhrase('directory') }}</a></li>
							<li><a href="{{URL_LMS_GROUPS}}"> <i class="fa fa-fw fa-list-ol"></i>{{ getPhrase('groups') }}</a></li>
							<li><a href="{{ URL_LMS_NOTES }}"> <i class="fa fa-sticky-note"></i>{{ getPhrase('notes') }}</a></li>
					</ul>
					</li>





					<li {{ isActive($active_class, 'reports') }} >

					<a data-toggle="collapse" data-target="#reports"><i class="fa fa-fw fa-credit-card" ></i>
					{{ getPhrase('payment_reports') }} </a>

					<ul id="reports" class="collapse sidemenu-dropdown">
						  	<li><a href="{{URL_ONLINE_PAYMENT_REPORTS}}"> <i class="fa fa-fw fa-link"></i>{{ getPhrase('online_payments') }}</a></li>
							<li><a href="{{URL_OFFLINE_PAYMENT_REPORTS}}"> <i class="fa fa-fw fa-chain-broken"></i>{{ getPhrase('offline_payments') }}</a></li>
							<li><a href="{{URL_PAYMENT_REPORT_EXPORT}}"> <i class="fa fa-fw fa-file-excel-o"></i>{{ getPhrase('export') }}</a></li>


					</ul>

					</li>


					<li {{ isActive($active_class, 'onlineclasses') }} >
						<a href="{{URL_ADMIN_ONLINECLASSES}}" ><i class="fa fa-cloud" aria-hidden="true"></i>
					{{ getPhrase('online_classes') }} </a>
					</li>

					<li {{ isActive($active_class, 'whiteboard') }} >
						<a href="{{route('onlineclasses.whiteboard')}}" ><i class="fa fa-cloud" aria-hidden="true"></i>
					{{ getPhrase('white_board') }} </a>
					</li>


					<li {{ isActive($active_class, 'packages') }} >
					<a data-toggle="collapse" data-target="#reports"><i class="fa fa-fw fa-bell" ></i>{{ getPhrase('packages') }} </a>
					<ul id="reports" class="collapse sidemenu-dropdown">
					  	<li><a href="{{route('packages.list')}}"> <i class="fa fa-fw fa-bell"></i>{{ getPhrase('list') }}</a></li>
						<!-- <li><a href="{{route('packages.get_renewal_requests')}}"> <i class="fa fa-fw fa-chain-broken"></i>{{ getPhrase('renewal_requests') }}</a></li> -->
						<li><a href="{{route('packages.renewal_requests')}}"> <i class="fa fa-fw fa-chain-broken"></i>{{ getPhrase('renewal_requests') }}</a></li>
					</ul>
					</li>

					<li {{ isActive($active_class, 'syllabus_contents') }}>
					<a data-toggle="collapse" data-target="#syllabus"><i class="fa fa-book" aria-hidden="true"></i>{{getPhrase('syllabus_contents')}}</a>
					<ul id="syllabus" class="collapse sidemenu-dropdown">
						<li><a href="{{URL_BOARDS_CLASSES}}"> <i class="fa fa-fw fa-bell"></i>{{ getPhrase('classes') }}</a></li>
						<li><a href="{{URL_BOARDS_SUBJECTS}}"> <i class="fa fa-fw fa-bell"></i>{{ getPhrase('subjects') }}</a></li>
						<li><a href="{{URL_BOARDS_CHAPTERS}}"> <i class="fa fa-fw fa-bell"></i>{{ getPhrase('chapters') }}</a></li>
						<li><a href="{{URL_REF_BOOKS}}"> <i class="fa fa-fw fa-bell"></i>{{ getPhrase('reference_books') }}</a></li>
						<li><a href="{{ URL_BOARDS }}"> <i class="fa fa-question-circle-o"></i>Boards</a></li>
					</ul>
					</li>

					<li {{ isActive($active_class, 'studentpaidcontent') }} >
						<a href="{{URL_PAID_CONTENT}}" ><i class="fa fa-fw fa-bell" aria-hidden="true"></i>Student Packages</a>
					</li>



					<li {{ isActive($active_class, 'notifications') }} >
						<a href="{{URL_ADMIN_NOTIFICATIONS}}" ><i class="fa fa-fw fa-bell" aria-hidden="true"></i>
					{{ getPhrase('notifications') }} </a>
					</li>

					{{--
					<li {{ isActive($active_class, 'sms') }} >
						<a href="{{URL_SEND_SMS}}" ><i class="fa fa-fw fa-envelope" ></i>
					SMS </a>
					</li>
					--}}

					<li {{ isActive($active_class, 'messages') }} >

					<a  href="{{URL_MESSAGES}}"> <i class="fa fa-fw fa-comments" aria-hidden="true"> </i>
					{{ getPhrase('messages')}} <small class="msg">{{$count = Auth::user()->newThreadsCount()}} </small></a>

					</li>
					<!-- <li {{ isActive($active_class, 'feedback') }} >
						<a href="{{URL_FEEDBACKS}}" ><i class="fa fa-fw fa-commenting" ></i>
					{{ getPhrase('feedback') }} </a>

					</li> -->

					<li {{ isActive($active_class, 'login_history') }} >
						<a href="{{route('user.login_history')}}" ><i class="fa fa-fw fa-sign-in" ></i>Login History</a>
					</li>

					<li {{ isActive($active_class, 'user_actions') }} >
						<a href="{{route('user.actions')}}" ><i class="fa fa-fw fa-history" ></i>User Actions</a>
					</li>

					<li {{ isActive($active_class, 'master_settings') }} >

					<a data-toggle="collapse" data-target="#master_settings" href="{{URL_MASTERSETTINGS_SETTINGS}}"><i class="fa fa-fw fa-cog" ></i>
					{{ getPhrase('master_settings') }} </a>

					{{-- <ul id="master_settings" class="collapse sidemenu-dropdown"> --}}


							{{-- <li><a href="{{URL_MASTERSETTINGS_EMAIL_TEMPLATES}}"> <i class="icon-history"></i> {{ getPhrase('email_templates') }}</a></li> --}}
							{{-- @if(checkRole(getUserGrade(1)))
							<li><a href="{{URL_MASTERSETTINGS_SETTINGS}}"> <i class="icon-settings"></i> {{ getPhrase('settings') }}</a></li>
							@endif --}}

					{{-- </ul> --}}
					</li>

					{{--
					<li {{ isActive($active_class, 'themes') }} >
						<a href="{{URL_THEMES_LIST}}" ><i class="fa fa-fw fa-th-large" ></i>
					{{ getPhrase('themes') }} </a>

					</li>
					--}}

				</ul>
			</div>
		</aside>
		@if(isset($right_bar))

		<aside class="right-sidebar" id="rightSidebar">
			<button class="sidebat-toggle" id="sidebarToggle" href='javascript:'><i class="mdi mdi-menu"></i></button>
			<div class="panel panel-right-sidebar">
				<?php $data = '';
			if(isset($right_bar_data))
				$data = $right_bar_data;
			?>
				@include($right_bar_path, array('data' => $data))
			</div>
		</aside>

	@endif

		@yield('content')
	</div>
	<!-- /#wrapper -->
	<!-- jQuery -->

	{{-- <script>
            var csrfToken = $('[name="csrf_token"]').attr('content');

            setInterval(refreshToken, 600000); // 1 hour

            function refreshToken(){
                $.get('refresh-csrf').done(function(data){
                    csrfToken = data; // the new token
                });
            }

            setInterval(refreshToken, 600000); // 1 hour

        </script> --}}

	<!-- Bootstrap Core JavaScript -->
	<script src="{{themes('js/jquery-1.12.1.min.js')}}"></script>
	<script src="{{themes('js/bootstrap.min.js')}}"></script>
	<script src="{{themes('js/main.js')}}"></script>
	<script src="{{themes('js/metisMenu.min.js')}}"></script>
	<script src="{{themes('js/sweetalert-dev.js')}}"></script>

	<script >
		 /*Sidebar Menu*/
    $("#ag-menu").metisMenu();
	</script>


	 @yield('footer_scripts')

	@include('errors.formMessages')



 	@yield('custom_div_end')
	{!!getSetting('google_analytics', 'seo_settings')!!}
	<div class="ajax-loader" style="display:none;" id="ajax_loader"><img src="{{AJAXLOADER}}"> {{getPhrase('please_wait')}}...</div>
</body>

</html>