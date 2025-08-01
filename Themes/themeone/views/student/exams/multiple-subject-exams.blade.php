@extends($layout)
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
<style type="text/css">
  @if( ! empty( $category->color_code ) )
  .nav-tabs {
    border-bottom: 1px solid {{$category->color_code}} !important;
  }
  .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    border-top:    1px solid {{$category->color_code}} !important;
    border-right:  1px solid {{$category->color_code}} !important;
    border-left: 1px solid {{$category->color_code}} !important;
  }
  .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
    background: {{$category->color_code}} !important;
  }
  @endif
</style>
@stop
@section('content')

<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>

							<!-- <li><a href="{{URL_STUDENT_SUBJECTS}}"><i class="icon-books"></i>&nbsp;Subjects</a> </li> -->

							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<?php
            $examSettings = getExamSettings();
            $student_batches = getStudentBatches();
            $image = $examSettings->defaultCategoryImage;
            ?>
            <div>
            <img src="{{ PREFIX.$examSettings->subjectsImagepath.$image }}" height="100" width="100" >
            <span>{{ $title }}</span>
            </div>



					</div>

          <div class="panel-body packages">
            <div>
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
              <thead style="display: none;">
                <tr>
                  <th>{{ getPhrase('title')}}</th>
                  <th>{{ getPhrase('marks')}}</th>
                  <th>{{ getPhrase('duration')}}</th>
                  <th>{{ getPhrase('action')}}</th>
                </tr>
              </thead>

            </table>

            </div>

          </div>

				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
@endsection


@section('footer_scripts')

@include('common.datatables', array('route'=> 'student.multisubject.examsgetlist','table_columns' => ['title', 'total_questions', 'dueration', 'action']))

<script>
function showInstructions(url) {
  width = screen.availWidth;
  height = screen.availHeight;
  window.open(url,'_blank',"height="+height+",width="+width+", toolbar=no, top=0,left=0,location=no,menubar=no, directories=no, status=no, menubar=no, scrollbars=yes,resizable=no");

	runner();
}

function runner()
{
	url = localStorage.getItem('redirect_url');
    if(url) {
      localStorage.clear();
       window.location = url;
    }
    setTimeout(function() {
          runner();
    }, 500);

}

</script>
@stop
