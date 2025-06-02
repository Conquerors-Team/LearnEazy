@extends('layouts.student.studentlayout')
@section('content')


<style>
/*subject slider styles*/
	/* Slider */

.slick-slide {
    margin: 0px 20px;
}

.slick-slide img {
    width: 100%;
}

.slick-slider
{
    position: relative;
    display: block;
    box-sizing: border-box;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
            user-select: none;
    -webkit-touch-callout: none;
    -khtml-user-select: none;
    -ms-touch-action: pan-y;
        touch-action: pan-y;
    -webkit-tap-highlight-color: transparent;
}

.slick-list
{
    position: relative;
    display: block;
    overflow: hidden;
    margin: 0;
    padding: 0;
}
.slick-list:focus
{
    outline: none;
}
.slick-list.dragging
{
    cursor: pointer;
    cursor: hand;
}

.slick-slider .slick-track,
.slick-slider .slick-list
{
    -webkit-transform: translate3d(0, 0, 0);
       -moz-transform: translate3d(0, 0, 0);
        -ms-transform: translate3d(0, 0, 0);
         -o-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
}

.slick-track
{
    position: relative;
    top: 0;
    left: 0;
    display: block;
}
.slick-track:before,
.slick-track:after
{
    display: table;
    content: '';
}
.slick-track:after
{
    clear: both;
}
.slick-loading .slick-track
{
    visibility: hidden;
}

.slick-slide
{
    display: none;
    float: left;
    height: 100%;
    min-height: 1px;
}
[dir='rtl'] .slick-slide
{
    float: right;
}
.slick-slide img
{
    display: block;
}
.slick-slide.slick-loading img
{
    display: none;
}
.slick-slide.dragging img
{
    pointer-events: none;
}
.slick-initialized .slick-slide
{
    display: block;
}
.slick-loading .slick-slide
{
    visibility: hidden;
}
.slick-vertical .slick-slide
{
    display: block;
    height: auto;
    border: 1px solid transparent;
}
.slick-arrow.slick-hidden {
    display: none;
}

/*subject slider styles end*/

	.tabbed {
  width: 700px;
 /* margin: 50px auto;*/
}

.tabbed > input {
  display: none;
}

.tabbed > label {
  display: block;
  float: left;
  padding: 12px 20px;
  margin-right: 5px;
  cursor: pointer;
  transition: background-color .3s;
}

.tabbed > label:hover,
.tabbed > input:checked + label {
  background: #7691ab;
  color:#fff;
}

.tabs {
  clear: both;
  perspective: 600px;
}

.tabs > div {
  width: 700px;
  position: absolute;
  border: 2px solid #7691ab;
 /* padding: 10px 30px 40px;*/
  line-height: 1.4em;
  opacity: 0;
  transform: rotateX(-20deg);
  transform-origin: top center;
  transition: opacity .3s, transform 1s;
  z-index: 0;
}

#tab-nav-1:checked ~ .tabs > div:nth-of-type(1),
#tab-nav-2:checked ~ .tabs > div:nth-of-type(2),
#tab-nav-3:checked ~ .tabs > div:nth-of-type(3),
#tab-nav-4:checked ~ .tabs > div:nth-of-type(4) {
  transform: rotateX(0);
  opacity: 1;
  z-index: 1;
}

@media screen and (max-width: 700px) {
  .tabbed {
    width: 400px;
  }

  .tabbed > label {
    display: none;
  }

  .tabs > div {
    width: 400px;
    border: none;
    padding: 0;
    opacity: 1;
    position: relative;
    transform: none;
    margin-bottom: 60px;
  }

  .tabs > div h2 {
    border-bottom: 2px solid #7691ab;
    padding-bottom: .5em;
  }
}



/*subjects*/

.container .icon {
  position: relative;
  margin: 10px;
  display: inline-block;
  float: left;
  width: 75px;
  height: 75px;
  font-size: 40px;
  border-radius: 15px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, .2),
              -1px 0px 2px rgba(0, 0, 0, .2);
   overflow: hidden;
}
.container .icon .fab {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) scale(1) rotate(0deg);
  transition: all .5s ease-in-out;
}
.container .icon:hover .fab {
  transform: translate(-50%, -50%)scale(1.4) rotate(360deg);
  color: #fff;
}

.react {
  color: #15AABF;
}
.python {
  color:  #306998;
}
.js {
  color: #F0DB4F;
}
.html5 {
  color: #F16529 ;
}
.css3 {
  color: #264de4;
}
.react:before,
.python:before, .js:before, .html5:before, .css3:before {
  content: "";
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: -100%;
  transition: all .5s ease-in-out;
  border-radius: 15px;
}
.react:hover:before,
.python:hover:before, .js:hover:before, .html5:hover:before, .css3:hover:before {
  left: 0;
}
.react:hover:before {
  background: #15AABF;
}
.python:hover:before {
  background:  #306998;
}
.js:hover:before {
  background: #F0DB4F;
}
.html5:hover:before {
  background: #F16529;
}
.css3:hover:before {
  background: #264de4;
}

/*table*/

table {
  border-collapse: collapse;
  background-color: white;
  overflow: hidden;
  border-radius: 0px;
}

th, td {
  font-family:'Motnserrat',sans-serif;
  text-align: left;
  font-size: 12px;
  padding: 10px;
}

th {
  background-color: #7691ab;
  color: white;
}

.ps-btn{
	border: 1px solid #7691ab;
	color:#7691ab;
	padding: 6px 12px;
	background: #fff;
	font-weight: 600;
}
.ps-btn:hover{
	border: 1px solid #7691ab;
	color: #fff;
	padding: 6px 12px;
	background: #7691ab;
	font-weight: 600;
}
.pp-color{
	color: #7691ab;
	font-weight: 600;
}
.pp-redcolor{
	color: #ff0000;
	font-weight: 600;
}


/*progress bars*/

progress[value]::-webkit-progress-bar {
	/*background-color: whiteSmoke;*/
	border-radius: 3px;
	box-shadow: 0 2px 3px rgba(0,0,0,.5) inset;
}

progress[value]::-webkit-progress-value {
	position: relative;

	background-size: 35px 20px, 100% 100%, 100% 100%;
	border-radius:3px;

	/* Let's animate this */
	animation: animate-stripes 5s linear infinite;
}

@keyframes animate-stripes { 100% { background-position: -100px 0; } }

/* Let's spice up things little bit by using pseudo elements. */

progress[value]::-webkit-progress-value:after {
	/* Only webkit/blink browsers understand pseudo elements on pseudo classes. A rare phenomenon! */
	content: '';
	position: absolute;

	width:5px; height:5px;
	top:7px; right:7px;

	background-color: white;
	border-radius: 100%;
}

/* Firefox provides a single pseudo class to style the progress element value and not for container. -moz-progress-bar */

progress[value]::-moz-progress-bar {
	/* Gradient background with Stripes */
	background-image:
	-moz-linear-gradient( 135deg,
													 transparent,
													 transparent 33%,
													 rgba(0,0,0,.1) 33%,
													 rgba(0,0,0,.1) 66%,
													 transparent 66%),
    -moz-linear-gradient( top,
														rgba(255, 255, 255, .25),
														rgba(0,0,0,.2)),
     -moz-linear-gradient( left, #09c, #f44);

	background-size: 35px 20px, 100% 100%, 100% 100%;
	border-radius:3px;

	/* Firefox doesn't support CSS3 keyframe animations on progress element. Hence, we did not include animate-stripes in this code block */
}

/* Fallback technique styles */
.progress-bar {
	/*background-color: whiteSmoke;*/
	border-radius: 3px;
	box-shadow: 0 2px 3px rgba(0,0,0,.5) inset;

	/* Dimensions should be similar to the parent progress element. */
	width: 100%; height:20px;
}

.progress-bar span {
	background-color: royalblue;
	border-radius: 3px;

	display: block;
	text-indent: -9999px;
}

p[data-value] {

  position: relative;
}

/* The percentage will automatically fall in place as soon as we make the width fluid. Now making widths fluid. */

p[data-value]:after {
	content: attr(data-value) '%';
	position: absolute; right:0;
}





.test3::-webkit-progress-value  {
	/* Gradient background with Stripes */
	background-image:
	-webkit-linear-gradient( 135deg,
													 transparent,
													 transparent 33%,
													 rgba(0,0,0,.1) 33%,
													 rgba(0,0,0,.1) 66%,
													 transparent 66%),
    -webkit-linear-gradient( top,
														rgba(255, 255, 255, .25),
														rgba(0,0,0,.2)),
     -webkit-linear-gradient( left, #09c, #f44);
}

.css3::-webkit-progress-value
{
	/* Gradient background with Stripes */
	background-image:
	-webkit-linear-gradient( 135deg,
													 transparent,
													 transparent 33%,
													 rgba(0,0,0,.1) 33%,
													 rgba(0,0,0,.1) 66%,
													 transparent 66%),
    -webkit-linear-gradient( top,
														rgba(255, 255, 255, .25),
														rgba(0,0,0,.2)),
     -webkit-linear-gradient( left, #09c, #ff0);
}



.test3::-moz-progress-bar {
	/* Gradient background with Stripes */
	background-image:
	-moz-linear-gradient( 135deg,
													 transparent,
													 transparent 33%,
													 rgba(0,0,0,.1) 33%,
													 rgba(0,0,0,.1) 66%,
													 transparent 66%),
    -moz-linear-gradient( top,
														rgba(255, 255, 255, .25),
														rgba(0,0,0,.2)),
     -moz-linear-gradient( left, #09c, #f44);
}

.css3::-moz-progress-bar {
{
	/* Gradient background with Stripes */
	background-image:
	-moz-linear-gradient( 135deg,
													 transparent,
													 transparent 33%,
													 rgba(0,0,0,.1) 33%,
													 rgba(0,0,0,.1) 66%,
													 transparent 66%),
    -moz-linear-gradient( top,
														rgba(255, 255, 255, .25),
														rgba(0,0,0,.2)),
     -moz-linear-gradient( left, #09c, #ff0);
}


     ul.userNews.media{
      list-style-type: none;
      font-weight: bold;
     }
     ul.userNews li .fa{
      display: table-cell;
    padding-right: 6px;
    color: red;
    height: 30px;
    float: left;
      position: relative;
    top: 7px;
     }
     ul.userNews .media p{
      padding-left: 18px;
      color: rgba(0,0,0,.2);
     }
 .userNews{
    height: 200px;
    overflow-y: auto;
    margin-top: 20px;
  }
</style>
<div id="page-wrapper">
<div class="container-fluid">
<div class="row" style="display: none;">
<div class="col-lg-12">
<ol class="breadcrumb">

<li>{{ $title}}</li>
</ol>
</div>
</div>
<?php
// $user = Auth::user();
$user = \App\User::with(['student_class'])->find( Auth::id() );
// dd( $user );
?>
<!-- new design starts -->
<div class="row dashboardContent">
	<div class="col-md-9">
		<div class="leftSideContent">
			<div class="dashboardC">
				<div class="col-md-12">
					<h5>Dashboard</h5>
				</div>
			</div>

			<div class="col-md-12 welcomeText">
				<div class="">
					<div class="col-md-9">
						<h4> Welcome back ! {{$user->name}}</h4>
						<p>
							You have completed {{$user->quiz_count_week}} tests for this week! <br>
							Start a new goal and improve your result
						</p>
						@if( isOnlinestudent() )
						<?php
						$payments = \App\Payment::where('user_id', \Auth::id())->where('payment_status', 'success')->where('notification_closed', '0')->get();

						$under_trail = '';
						if( $payments->count() > 0 ) {
							foreach( $payments as $payment ) {
								$days = dateDiffInDays(date('Y-m-d'), $payment->end_date);
								//$days = 0;
								//echo $payment->end_date;
//dd($days);

                                // dd($days);
                                $package = \App\StudentPaidContent::find( $payment->item_id );
								if ( $days < 30 ) {

									if( $payment->notes == 'Trail period' ) {
										if ( $days >= 0 ) {
											$under_trail = '<h3 style="color:red;">Your trail period for <i>'.$package->title.'</i> ends in ' . $days . ' Day(s).';

											if ( $days == 0 ) {
											$under_trail = '<h3 style="color:red;">Your trail period for <i>'.$package->title.'</i> ends today <a href="'.route('payments.checkout', ['type' => 'paidcontent', 'slug' => $package->slug]).'">Click</a> here to buy it.';
											} elseif ( $days < 5 ) {
												$under_trail .= ' <a href="'.route('payments.checkout', ['type' => 'paidcontent', 'slug' => $package->slug]).'">Click</a> here to buy it.';
											}
											$under_trail .= '</h3>';
										} else {
											$under_trail = '<h3 style="color:red;">Your trail expired for <i>'.$package->title.'</i>.';

											$under_trail .= ' <a href="'.route('payments.checkout', ['type' => 'paidcontent', 'slug' => $package->slug]).'">Click</a> here to buy it.';
											$under_trail .= '</h3>';
										}

									} else {
										if ( $days >= 0 ) {
											$under_trail = '<h3 style="color:red;">Your package validity <i>'.$package->title.'</i> ends in ' . $days . ' Day(s).';
											if ( $days == 0 ) {
												$under_trail = '<h3 style="color:red;">Your package validity <i>'.$package->title.'</i> ends today. <a href="'.route('payments.checkout', ['type' => 'paidcontent', 'slug' => $package->slug]).'">Click</a> here to renew it.';
											} elseif ( $days < 5 ) {
												$under_trail .= ' <a href="'.route('payments.checkout', ['type' => 'paidcontent', 'slug' => $package->slug]).'">Click</a> here to renew it.';
											}
											$under_trail .= '</h3>';
										} else {
											$under_trail = '<h3 style="color:red;">Your package <i>'.$package->title.'</i> has been expired.';

											$under_trail .= ' <a href="'.route('payments.checkout', ['type' => 'paidcontent', 'slug' => $package->slug]).'">Click</a> here to renew it.';
											$under_trail .= '</h3>';
										}
									}
									echo $under_trail;
								} else {
									echo '<h3 style="color:green;">Your Package <i>'.$package->title.'</i></h3>';
								}
							}
						}
						?>
						@endif
					</div>
					<div class="col-md-3">
						<img src="http://phpstack-152693-1271537.cloudwaysapps.com/public/uploads/users/thumbnail/dashboard.png" width="150" />

					</div>
				</div>

			</div>

			<?php

	  $notifications = \App\Notification::whereNotNull('valid_from')->whereNotNull('valid_to')
	  	//->whereRaw('NOW() BETWEEN valid_from AND valid_to')
	  ->whereRaw('date(valid_from) >= date(NOW())')
	  ->whereRaw('NOW() <= valid_to')
	  	->where(function($query) {
			$batches = getStudentBatches();
			if ( count( $batches ) ) {
				foreach ($batches as $batch_id) {
					$query->orWhere('batch_id', $batch_id);
				}
			}
			$query->orWhere('notification_for', 'allstudents' );
			$classes = getStudentClasses();
			if ( count( $classes ) ) {
				foreach ($classes as $class_id) {
					$query->orWhere('student_class_id', $class_id);
				}
			}
             })
	  	->where(function($query) {
	  		$query->orWhere('notification_for', 'class');
	  		$query->orWhere('notification_for', 'batch');
	  		$query->orWhere('notification_for', 'allstudents' );
	  	});
	  	;
	  	//print_r($notifications->getBindings());
	  	//dd( $notifications->toSql() );

	  	$notifications = $notifications->get();
	  ?>
	  @if ( $notifications->count() > 0 )
			<marquee behavior="scroll" direction="left">
			@forelse( $notifications as $row)
				<a href="{{$row->url}}" target="_blank" title="{{$row->title}}">{{$row->title}}</a>&nbsp;|&nbsp;
			@empty
			<p style="color: red;">There are no classes</p>
			@endforelse
			</marquee>
		@endif
			<div class="layer2">
			<div class="col-md-6">
				<div class="white_bgcurve coursesList">
					<h5>Results</h5><a class="moreBtn" href="{{URL_STUDENT_EXAM_ATTEMPTS . $user->slug}}">more</a>

					<?php
					$marks = App\QuizResult::where('user_id', '=', $user->id)->orderBy('updated_at','desc')->take(5)->get();

					if ( $marks->count() > 0 ) {
					?>
					<br>
					<ul class="coursesNames">
						@foreach( $marks as $mark )
						<li>
							<div class="col-md-7 coursesText">
									{{$mark->quizName->title}}<p class="greyText">{{date('d/m/Y', strtotime($mark->created_at))}}</p>
								</div>
								<div class="col-md-5 pull-right">
									<div class="progress">
									  <?php
										$result = ucfirst($mark->exam_status);
										$class = ($result=='Pass') ? 'label-success' : 'label-danger';

										if ( ! empty( $mark->marks_obtained ) ) {
											$percent = (Int) $mark->total_marks / $mark->marks_obtained;
										}
										$percent = 100;
									  ?>
									  <div class="progress-bar {{$class}}" role="progressbar" aria-valuenow="10"
									  aria-valuemin="0" aria-valuemax="100" style="width:{{$percent}}%">{{$mark->marks_obtained.' / '.$mark->total_marks}}
									  </div>

									</div>
						</li>
						@endforeach
					</ul>
				<?php } ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="white_bgcurve coursesList">
					<h5>Live Quizzes</h5>
					<div class="white_bgcurve" style="clear:both; ">
						 <?php
						 /*
						 $student_batches = getStudentBatches();
						 $live_quizzes = \App\Quiz::select(['quizzes.*'])->join('batch_quizzes', 'batch_quizzes.quiz_id', '=', 'quizzes.id')
						 ->where('batch_quizzes.is_popquiz', 'yes')
						 ->whereIn('batch_quizzes.batch_id', $student_batches)
						 ->whereDate('batch_quizzes.date_time', '=', \Carbon\Carbon::today()->toDateString())
						 ->where('batch_quizzes.category_id', QUIZTYPE_LIVEQUIZ)
						 ->groupBy('quiz_id')
						 ->get();
						 */
						 $batches = getStudentBatches();
						  $onlineclasses = \App\Onlineclass::select(['quizzes.*', 'onlineclasses.live_quiz_popstatus'])
						  ->join('quizzes', 'quizzes.id', 'onlineclasses.live_quiz_id')
						  ->whereNotNull('class_time')->whereNotNull('valid_from')->whereNotNull('valid_to')
						  ->whereIn('batch_id', $batches)
						  ->whereDate('valid_from', '>=', date('Y-m-d'))
						  ->whereRaw(date('Y-m-d') . ' <= DATE(valid_to)')
						  ->where('live_quiz_popstatus', 'yes')
						  ;
						  //echo getEloquentSqlWithBindings( $onlineclasses );
						  $live_quizzes = $onlineclasses->orderBy('class_time')->get();
						 ?>
						 <table class="table ">
							<tr>
								<th>Exam</th>
								<th>Subjects</th>
								<th>Action</th>
							</tr>
							@forelse( $live_quizzes as $live_quizze)
							<tr>
								<td>
									{{$live_quizze->title}}

								</td>
								<td>
									<?php
									$quiz = \App\Quiz::find( $live_quizze->id );
									if(count($quiz->subjects) > 0) {
							            echo $subjects = '<p><span class="label label-info label-many">' . implode('</span><span class="label label-info label-many"> ',
							                        $quiz->subjects->pluck('subject_title')->toArray()) . '</span></p>';
							          }
									?>
								</td>
								<td>
									<?php
									echo '<a onClick="showInstructions(\''.URL_STUDENT_TAKE_EXAM.$live_quizze->slug.'\')" href="javascript:void(0);" class="btn btn-primary">'.getPhrase("take_exam").'</a>';
									?>
								</td>
							</tr>
							@empty
							<tr><td colspan="3" align="center">No Liquizzes today!</td></tr>
							@endforelse
						</table>
						</div>
					</div>
			</div>
			</div>
<br>
			<div class="row">
				<div class="col-md-12">
				<div class="white_bgcurve coursesList">
					<h5>Subjects</h5>
					<div class="white_bgcurve" style="clear:both; ">
						 <?php
						 $subjects = (Object)[];
						 $userSubjects = \App\User::getUserSeleted('exam_subjects');
				        if($userSubjects) {
				            $subjects  = \App\Subject::whereIn('id',$userSubjects)->paginate(getRecordsPerPage());
				        }

						$settings = getExamSettings();
						?>
						 @forelse( $subjects as $subject)
						 <div class="col-md-3">
						 	<div class="item-image">

							<?php $image = $settings->defaultCategoryImage;

							if(isset($subject->image) && $subject->image!='')
								$image = $subject->image;
							?>
							<img src="{{ PREFIX.$settings->subjectsImagepath.$image}}" alt="{{$subject->subject_title}}" width="150">
							</div>
						 	<a href="{{URL_STUDENT_EXAMS.$subject->slug . '/subject'}}" title="{{$subject->subject_title}}">{{$subject->subject_title}}</a>
						 </div>
						 @empty
						 	<p>You have not yet enrolled in any batch OR No course selected.</p>
						 @endforelse

						 @if( ! isOnlinestudent() )
						 <div class="col-md-3" style="padding-top: 20px;">
						 	<div class="item-image">
							<?php $image = $settings->defaultCategoryImage;
							?>
							<img src="{{themes('images/exam3.png')}}" alt="Exams" width="150">
							</div>
						 	<a href="{{url('multiple-student/exams')}}" title="Multiple Subjects">Exams</a>
						 </div>
						 @endif

						</div>
					</div>
				</div>
			</div>

			@if( isOnlinestudent() )
			<div class="row">
				<div class="col-md-12">
				<div class="white_bgcurve coursesList">
					<h5>Test Series</h5>
					<div class="white_bgcurve" style="clear:both; ">
						 <?php
						 $types = [
						 	'subject-exams' => 'Subject tests',
						 	'grand-exams' => 'Grand tests',
						 	'previousyear-exams' => 'Previous year tests',
						 ];
						 ?>
						 @foreach( $types as $item_type => $title)
						 <div class="col-md-3">
					 		<a href="{{route('student.paid_exams', ['type' => $item_type])}}" title="{{$title}}">{{$title}}</a>
						 </div>
						 @endforeach
						</div>
					</div>
				</div>
			</div>
			@endif
			<br>
		</div>
	</div>

	<div class="col-md-3">
		<div class="rightSideContent">
			<div class="rightSideInnerContent userProfileContent">
				<div class="col-md-12 userProfile text-center">

					<img src="{{ getProfilePath(Auth::user()->image, 'thumb') }}" alt="">
					<dl>
						<dt>{{$user->name}}</dt>
						<dd>{{$user->student_class->name}}</dd>
						@if(count($user->batches) > 0)
						<dd><span class="label label-info label-many"><?php echo implode('</span><span class="label label-info label-many"> ', $user->batches->pluck('name')->toArray()); ?></span></dd>
						@endif
					</dl>
				</div>
<!-- <?php
// $batches = getStudentBatches();
$users = \App\User::with(['student_class'])->find( Auth::id() );
$student_id = $users->student_class_id;
// $batches  = \App\Batch::where('user_id',OWNER_INSTITUTE_ID)->where('student_class_id',$student_id)->pluck('id')->toArray();
// dd($batches);
$show_online_classes = true;
if ( isOnlinestudent() ) {
 $batches  = \App\Batch::where('user_id',OWNER_INSTITUTE_ID)->where('student_class_id',$student_id)->get();
 $batches_count =   $batches->count();
}
?> -->
@if(isOnlineclassSubscribed()|| isOnlinestudent() )
				<div class="col-md-12">
					<h5>Online Classes</h5>
					<div class="userNews" style="height: 400px;
    overflow-y: auto;
    margin-top: 20px;">
					 <?php
	  $batches = getStudentBatches();
	  //print_r($batches);die();
// echo date('Y-m-d H:i:s');
	  $newtimestamp_after_mins = strtotime(date('Y-m-d H:i:s') . ' + 30 minute');
	  $newtimestamp_before_mins = strtotime(date('H:i:s') . ' - 5 minute');
	  $newtimestamp = strtotime(date('Y-m-d H:i:s'));
	  //$newtimestamp = strtotime(date('Y-m-d H:i:s'));
	  //echo date('Y-m-d H:i:s', $newtimestamp);

	  //$onlineclasses = \App\Onlineclass::whereIn('batch_id', $batches)->whereNotNull('valid_from')->whereNotNull('valid_to')->whereRaw('valid_from >= CONCAT(DATE(NOW()), " ", class_time)')->whereRaw('DATE(valid_to) <="' . date('Y-m-d').'"')->get();

	  $onlineclasses = \App\Onlineclass::whereNotNull('class_time')->whereNotNull('valid_from')->whereNotNull('valid_to')
	  ->whereIn('batch_id', $batches)
	  ->whereDate('valid_from', '>=', date('Y-m-d'))
	  ->whereRaw(date('Y-m-d') . ' <= DATE(valid_to)')
	  ;

	  // echo date('Y-m-d H:i:s', $newtimestamp_after_mins);
	  //echo $onlineclasses->toSql();


	  $onlineclasses = $onlineclasses->orderBy('class_time')->get();
// echo date('Y-m-d H:i:s', $newtimestamp_before_mins) . '@@' . date('Y-m-d H:i:s', $newtimestamp_after_mins);
//echo '@@:' . date('H:i:s', $newtimestamp_after_mins);
	  $classes = 0;
	  ?>
	  @forelse( $onlineclasses as $row)
		<?php
// $row->class_time = '20:30:00';
		if ( $row->class_time < date('H:i:s', strtotime(date('Y-m-d H:i:s') . ' - 30 minute')) ) {
			continue;
		}
		$classes++;
		?>
		<div class="media">
		<div class="media-left">
		<i class="fa fa-codepen" aria-hidden="true"></i>
		</div>

		<div class="media-body">
		<h4 class="media-heading">
			<p>{{$row->title}}</p>
			<p>Time: {{$row->class_time}}</p>
			<?php

			$valid_from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d') . ' ' . $row->class_time);
			$minutes = now()->diffInMinutes($valid_from, false);
			//echo '#' . TIMER_ENABLE_BEFORE;
			//$minutes = 10;
			// echo $row->valid_from . '#' . date('Y-m-d H:i:s') . '@' . $row->valid_to;
//var_dump($newtimestamp_before_mins >= $row->class_time);
			?>
			@if( $newtimestamp_before_mins >= $row->class_time && $newtimestamp_before_mins <= $row->class_time )
				<p><a class="btn btn-lg btn-success button" href="/student/onlineclasses/attendence/{{$row->id}}" target="_blank" id="join_button_final_{{$row->id}}">Join Now</a></p>
			@elseif( $minutes <= TIMER_ENABLE_BEFORE )
				<p id="timer_{{$row->id}}"><i class="fa fa-spin fa-refresh" style="color: red;"></i>&nbsp;Starts in: <span id="minutes_{{$row->id}}">{{$minutes}} mins</span></p>
				<p><a class="btn btn-lg btn-success button" href="javascript:void(0);" id="join_button_{{$row->id}}" onclick="alert('Class will start in {{$minutes}} mins')" disabled>Join Now</a></p>
			@else
				<?php /* ?>
				<p><i class="fa fa-clock-o" style="color: green;">&nbsp;Starts: {{date('d/m/Y H:i:s', strtotime(date('Y-m-d') . ' ' . $row->class_time))}}</i></p>
				<?php */ ?>
				<p id="timer_{{$row->id}}"><i class="fa fa-spin fa-refresh" style="color: red;" style="display: none;"></i>&nbsp;Starts in: <span id="minutes_{{$row->id}}">{{$minutes}} mins</span></p>
				<p><a class="btn btn-lg btn-success button" href="javascript:void(0);" id="join_button_{{$row->id}}" onclick="alert('Class will start in {{$minutes}} mins')" disabled>Join Now</a></p>
			@endif

			@if($row->lmsseries_id || $row->lmsnotes_id)
			<p>
				@if($row->lmsseries_id)
				<a href="{{URL_STUDENT_LMS_SERIES_VIEW.$row->lmsseries->slug}}" target="_blank">LMS</a>
				@endif
				@if($row->lmsnotes_id)
				&nbsp;|&nbsp;<a href="{{route('lms.preview_notes', ['slug' => $row->lmsnotes->slug])}}" target="_blank">Notes</a>
				@endif
			</p>
			@endif
		</h4>
		<p class="greyText">Faculty: {{$row->createdby->name}}</p>
		@if($row->subject)
		<p class="greyText">Subject: {{$row->subject->subject_title}}</p>
		@endif
		@if($row->topic)
		<p class="greyText">Topic: {{$row->topic}}</p>
		@endif
		</div>
		</div>
	@empty
		<!-- <p style="color: red;">There are no classes</p> -->
	@endforelse

	@if( $classes == 0 )
		<p style="color: red;">There are no classes</p>
	@endif
			</div>
				</div>

@endif
				<div class="col-md-12">
					<h5>Latest LMS</h5>
					<?php
	  $quizzes = \App\LmsSeries::select(['lmsseries.*', 'subjects.slug AS subject_slug','subjects.subject_title AS subject_title'])->join('subjects', 'subjects.id', '=', 'lmsseries.subject_id')->where('lmsseries.institute_id',$user->institute_id)->orderBy('lmsseries.updated_at')->take(5)->get();
	  ?>
	  @forelse( $quizzes as $quizz)
		<div class="media">
		<div class="media-left">
		<i class="fa fa-codepen" aria-hidden="true"></i>
		</div>

		<div class="media-body">
		<h3 class="media-heading"><a href="{{route('studentlms.subjectitems', ['slug' => $quizz->subject_slug, 'series_slug' => $quizz->slug])}}">{{$quizz->subject_title}}</a></h3>
		<h4 class="media-heading">{{$quizz->title}}</h4>
		<p class="greyText">{{$quizz->end_date}}</p>
		</div>
		</div>
	@empty
	@endforelse
				</div>


			</div>
		</div>
	</div>
</div>
<!-- new design ends -->


<br><br>
<div class="clearfix"></div>

<div class="row" ><?php $ids=[];?>
@for($i=0; $i<count($chart_data); $i++)
<?php
$newid = 'myChart'.$i;
$ids[] = $newid;
?>
<div class="col-md-6">
	<div class="panel panel-primary dsPanel">
		<div class="panel-body" >
			<canvas id="{{$newid}}" width="100" height="60"></canvas>
		</div>
	</div>
</div>
@endfor
</div>

</div>
<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

@stop

@section('footer_scripts')

<script>
function showInstructions(url) {
  width = screen.availWidth;
  height = screen.availHeight;
  window.open(url,'_blank',"height="+height+",width="+width+", toolbar=no, top=0,left=0,location=no,menubar=no, directories=no, status=no, menubar=no, scrollbars=yes,resizable=no");

	runner();
}
</script>

@if(isOnlineclassSubscribed() || isOnlinestudent() )
<script type="text/javascript">
	@if( $onlineclasses->count() > 0 )
	setInterval(timerFunc, 1000);

	var counter = 0;
	function timerFunc() {
		@foreach($onlineclasses as $row)
			<?php
			$valid_from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d') . ' ' . $row->class_time);
			$minutes = now()->diffInMinutes($valid_from, false);
			$minutes_seconds = $minutes * 60;
			?>
			@if( $minutes <= TIMER_ENABLE_BEFORE )
				var minutes_seconds = '{{$minutes_seconds}}';
				minutes_seconds = minutes_seconds - counter;

				var str = Math.floor(minutes_seconds / 60);
				if ( $('#minutes_{{$row->id}}').length > 0 ) {
					if ( str <= 0 ) {
						$('#join_button_{{$row->id}}').attr('href', '/student/onlineclasses/attendence/{{$row->id}}');
						$('#join_button_{{$row->id}}').attr('target', '_blank');
						$('#join_button_{{$row->id}}').removeAttr('disabled');
						$('#join_button_{{$row->id}}').removeAttr('onclick');
						$('#timer_{{$row->id}}').remove();
					}
					// console.log(str);
					if ( minutes_seconds <= 60 ) {
						str = ' less than a min';
					} else {
						str = str + ' mins';
					}
					$('#minutes_{{$row->id}}').html(str);
				}
			@endif
		@endforeach
		counter++;
	}

	@endif
</script>
@endif

@include('common.chart', array($chart_data,'ids' =>$ids));
@stop