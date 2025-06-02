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
							<?php
							 $display_types = [
								'subject' => 'Subject-wise',
								'chapter' => 'Chapter-wise test',
								'previousyear' => 'Previous Year test',
								'grand' => 'Grand test',
							]
							?>
							 @foreach( $display_types as $key_type => $title)
							 <div class="col-md-3">
							 	<a href="{{route('student.paid_content', ['display_type' => $key_type])}}" title="{{$title}}">{{$title}}</a>
							 </div>
							 @endforeach
						</div>
				</div>
			</div>

</div>
		<!-- /#page-wrapper -->

@stop