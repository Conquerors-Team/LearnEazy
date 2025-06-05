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
	if (!isset($right_bar))
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
				<?php
				$institute_id  = adminInstituteId();
				$institute     = getInstitute($institute_id);

				?>
				<li style="color: white;">
					<font size="4px;">{{$institute->institute_name}}</font>, {{ $institute->institute_address }}
				</li>

				@if(checkRole(getUserGrade(10), 'user_access'))
				<li class="dropdown">

					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-topbar-event"></i> {{ getPhrase('latest_users') }}
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-notif" aria-labelledby="dd-notification">
						<div class="dropdown-menu-notif-list" id="latestUsers">
							@foreach($newUsers as $user)
							<div class="dropdown-menu-notif-item">
								<div class="photo">
									<img src="{{ getProfilePath($user->image)}}" alt="">
								</div>
								<a href="{{URL_USER_DETAILS.$user->slug}}">{{ucfirst($user->name)}}</a> {{ getPhrase('was_joined_as').' '. getRoleData($user->role_id)}} {{getPhrase('in')}} {{$user->studentInstitute()}}
								<div class="color-blue-grey-lighter">{{$user->updated_at->diffForHumans()}}</div>
							</div>
							@endforeach
						</div>

						<div class="dropdown-menu-notif-more">
							<a href="{{URL_USERS}}">{{ getPhrase('see_more') }}</a>
						</div>
					</div>
				</li>
				@endif


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

		<aside class="left-sidebar">
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav side-nav">
					<li {{ isActive($active_class, 'dashboard') }}>
						<a href="{{PREFIX}}">
							<i class="fa fa-fw fa-window-maximize"></i> {{ getPhrase('dashboard') }}
						</a>
					</li>

					<!-- <li {{ isActive($active_class, 'users') }}> <a href="{{URL_USERS}}"><i class="fa fa-fw fa-user-circle"></i> {{ getPhrase('users') }} </a> </li>
 -->

					@if( canDo('institute_batch_access') )
					<li {{ isActive($active_class, 'batches') }}> <a href="{{URL_BATCHS}}"><i class="fa fa-sitemap"></i> {{ getPhrase('student_batches') }} </a> </li>
					@endif



					@if( canDo('exams_access') )
					<li {{ isActive($active_class, 'exams') }}>

						<a data-toggle="collapse" data-target="#exams"><i class="fa fa-fw fa-desktop"></i>
							{{ getPhrase('exams') }} </a>

						<ul id="exams" class="collapse sidemenu-dropdown">
							@if('exam_question_access')
							<li><a href="{{URL_QUIZ_QUESTIONBANK}}"> <i class="fa fa-fw fa-fw fa-question"></i>{{ getPhrase('question_bank') }}</a></li>
							@endif

							<li><a href="{{URL_TOPICS_DIRECTORY}}"> <i class="fa fa-fw fa-fw fa-random"></i>{{ getPhrase('directory') }}</a></li>

							@if('exams_access')
							<li><a href="{{URL_QUIZZES}}"> <i class="icon-total-time"></i> {{ getPhrase('exams')}}</a></li>
							@endif

							@if('exam_instruction_access')
							<li><a href="{{URL_INSTRUCTIONS}}"> <i class="fa fa-fw fa-hand-o-right"></i> {{ getPhrase('instructions')}}</a></li>
							@endif

						</ul>

					</li>
					@endif

					<li {{ isActive($active_class, 'live_quizzes') }}>
						<a href="{{route('exams.live_quizzes')}}"><i class="fa fa-bolt" aria-hidden="true"></i>Live Quizzes</a>
					</li>


					@if( canDo( 'lms_access') )
					<li {{ isActive($active_class, 'lms') }}>
						<a data-toggle="collapse" data-target="#lms"><i class="fa fa-fw fa-tv"></i>
							LMS </a>
						<ul id="lms" class="collapse sidemenu-dropdown">
							@if( canDo( 'lms_content_access') )
							<li><a href="{{ URL_LMS_CONTENT }}"> <i class="icon-books"></i>{{ getPhrase('contents') }}</a></li>
							@endif

							@if( canDo('lms_series_access') )
							<li><a href="{{route('lms.series')}}"> <i class="fa fa-fw fa-list-ol"></i>{{ getPhrase('series') }}</a></li>
							@endif

							@if(canDo('lms_series_access'))
							<li><a href="{{URL_LMS_GROUPS}}"> <i class="fa fa-fw fa-list-ol"></i>Content Library</a></li>
							@endif

							<li><a href="{{route('lmsseries.directory')}}"> <i class="fa fa-fw fa-fw fa-random"></i>{{ getPhrase('directory') }}</a></li>

							@if( canDo( 'lms_notes_access') )
							<li><a href="{{ URL_LMS_NOTES }}"> <i class="fa fa-sticky-note"></i>{{ getPhrase('notes') }}</a></li>
							@endif
						</ul>
					</li>
					@endif



					@if( canDo('onlineclasses_access') )
					<li {{ isActive($active_class, 'onlineclasses') }}>
						<a href="{{URL_ADMIN_ONLINECLASSES}}"><i class="fa fa-cloud" aria-hidden="true"></i>
							Academic Schedule (Online classes) </a>
					</li>
					@endif


					<li {{ isActive($active_class, 'batch_reports') }}>
						<a data-toggle="collapse" data-target="#lms"><i class="fa fa-fw fa-tv"></i>
							Reports </a>
						<ul id="lms" class="collapse sidemenu-dropdown">

							<li><a href="{{ url('admin/onlineclasses/attendence') }}"> <i class="icon-books"></i>{{ getPhrase('attendance') }}</a></li>

							<li><a href="{{ route('batch.reports') }}"> <i class="icon-books"></i>{{ getPhrase('batch_reports') }}</a></li>

						</ul>
					</li>


					@if( canDo('white_board') )
					<li {{ isActive($active_class, 'whiteboard') }}>
						<a href="{{route('onlineclasses.whiteboard')}}"><i class="fa fa-cloud" aria-hidden="true"></i>
							{{ getPhrase('white_board') }} </a>
					</li>
					@endif

					@if( canDo('internal_notification_access') )
					<li {{ isActive($active_class, 'notifications') }}>
						<a href="{{URL_NOTIFICATIONS}}"><i class="fa fa-bell" aria-hidden="true"></i>
							{{ getPhrase('notifications') }} </a>
					</li>
					@endif


					@if( canDo('message_access') )
					<li {{ isActive($active_class, 'messages') }}>
						<a href="{{URL_MESSAGES}}"> <i class="fa fa-fw fa-comments" aria-hidden="true"> </i>
							{{ getPhrase('messages')}} <small class="msg">{{$count = Auth::user()->newThreadsCount()}} </small></a>
					</li>
					@endif


				</ul>
			</div>
		</aside>
		@if(isset($right_bar))

		<aside class="right-sidebar" id="rightSidebar">
			<button class="sidebat-toggle" id="sidebarToggle" href='javascript:'><i class="mdi mdi-menu"></i></button>
			<div class="panel panel-right-sidebar">
				<?php $data = '';
				if (isset($right_bar_data))
					$data = $right_bar_data;
				?>
				@include($right_bar_path, array('data' => $data))
			</div>
		</aside>

		@endif

		@yield('content')
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
						<button type="button" class="btn btn-default" data-dismiss="modal">{{getPhrase('close')}}</button>
					</div>
				</div>
			</div>
		</div>
	</div>
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

	<script>
		/*Sidebar Menu*/
		$("#ag-menu").metisMenu();
	</script>

	<script type="text/javascript">
		@if(isFaculty())

		function timerFunc() {
			var route = '{{url("onlineclasses/classend/alerts")}}';
			var token = $('[name="_token"]').val();
			data = {
				_method: 'get',
				'_token': token
			};
			$.ajax({
				url: route,
				//dataType: 'json',
				data: data,
				success: function(result) {
					//console.log('result');
					if (result != '') {
						//alert( result );
						$('#class_alert_modal').modal('show');
					}
				}
			});
		}
		// setInterval(timerFunc, 1000); // For each second
		setInterval(timerFunc, 1000 * 60); // For each minute
		// alert('fgggg');
		@endif
	</script>


	@yield('footer_scripts')

	@include('errors.formMessages')



	@yield('custom_div_end')
	{!!getSetting('google_analytics', 'seo_settings')!!}
	<div class="ajax-loader" style="display:none;" id="ajax_loader"><img src="{{AJAXLOADER}}"> {{getPhrase('please_wait')}}...</div>
</body>

</html>