@extends($layout)
@section('content')

<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li class="active"> {{ $title }} </li>
						</ol>
					</div>
				</div>
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<h1>{{$title}}</h1>
					</div>
					<div class="panel-body packages">
						<div class="row library-items">
					<?php $settings = getExamSettings(); ?>
					@if(count($subjects))
						@foreach($subjects as $c)
							<div class="col-md-3">
								<div class="library-item mouseover-box-shadow">

									<div class="item-image">
									<?php $image = $settings->defaultCategoryImage;
									if(isset($c->image) && $c->image!='')
										$image = $c->image;
									?>
									<img src="{{ PREFIX.$settings->categoryImagepath.$image}}" alt="">
									</div>

									<div class="item-details">
										<h3>{{ $c->subject_title }}</h3>
										<ul>
											<li><a href="{{URL_STUDENT_EXAMS.$c->slug . '/subject'}}"><i class="icon-bookmark"></i> {{ count($c->subject_quizzes()).' '.getPhrase('exams')}}</a></li>

											<li><a href="{{route('student.lms_notes', $c->slug)}}"><i class="fa fa-sticky-note-o"></i> {{ $c->subject_notes()->count().' '.getPhrase('notes')}}</a></li>

											<li><a href="{{route('studentlms.subjectitems', ['slug' => $c->slug])}}"><i class="icon-eye"></i>LMS</a></li>
										</ul>
									</div>

								</div>
							</div>
							 @endforeach
							@else
						Ooops...! {{getPhrase('No_Subjects_available')}}
						@endif
						</div>
						@if(count($subjects))
						{!! $subjects->links() !!}
						@endif
					</div>
				</div>
			</div>

</div>
		<!-- /#page-wrapper -->

@stop