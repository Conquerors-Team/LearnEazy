@extends('layouts.student.studentlayout')

@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a> </li>
							<li><a href="/exams/student/categories"> {{getPhrase('quiz_categories')}} </a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div class="table-responsive">
							<div style="width:600px;height:300px;border: 1px solid black;"><div style="position:relative;z-index:10;height:40px;padding-left:4px;width:150px;"><a style="text-decoration:none;color:#CCC;font-size:20px;font-family:Dosis;" href="https://ziteboard.com" target="_blank">Zoom & Move</a></div><iframe seamless="seamless" style="position:relative;width: 100%; height: 100%;top:-40px;" src="https://view.ziteboard.com/shared/61181981309515" frameborder="0" allowfullscreen></iframe></div>
						</div>

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
@endsection


@section('footer_scripts')


@stop
