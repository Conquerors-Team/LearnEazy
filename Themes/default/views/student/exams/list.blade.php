@extends($layout)
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')


<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							
							<li><a href="{{URL_STUDENT_EXAM_CATEGORIES}}"> {{getPhrase('exam_categories')}} </a> </li>

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
						<div> 
						  <?php   
						  		$user = Auth::user(); 
						  		 $interested_categories      = null;
						        if($user->settings)
						        {
						          $interested_categories =  json_decode($user->settings)->user_preferences;
						        }

						  ?>
						  @if($interested_categories->quiz_categories)
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>{{ getPhrase('title')}}</th>
									<th>{{ getPhrase('batch_name')}}</th>
									<th>{{ getPhrase('start_date')}}</th>
									<th>{{ getPhrase('end_date')}}</th>
									<th>{{ getPhrase('type')}}</th>
									<th>{{ getPhrase('total_marks')}}</th>
									<th>{{ getPhrase('action')}}</th>
								  
								</tr>
							</thead>
							 
						</table>
						@else
							Ooops...! {{getPhrase('no_exams_available')}}
						
						<a href="{{URL_USERS_SETTINGS.Auth::user()->slug}}" >{{getPhrase('click_here_to_change_your_preferences')}}</a>
						@endif
						</div>

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->

			<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm" style="width: 600px;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">{{getPhrase('exam_timings')}}</h4>
      </div>
      <div class="modal-body">
      <h4 class="text-center" id="message"></h4>
     </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-right" data-dismiss="modal">Ok</button>
      </div>
    </div>

  </div>
</div>
		</div>
@endsection
 

@section('footer_scripts')
@if($interested_categories)
  @if($category)
	 @include('common.datatables', array('route'=>URL_STUDENT_QUIZ_GETLIST.$category->slug, 'route_as_url' => TRUE))
  @else
	 @include('common.datatables', array('route'=>URL_STUDENT_QUIZ_GETLIST_ALL, 'route_as_url' => TRUE))
  @endif
	 @include('common.deletescript', array('route'=>URL_QUIZ_DELETE))
 @endif
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


function showMessage(time){

    $('#myModal').modal('show');
     message  = '<h4>Exam will start at ' + time +'</h4>';
     $('#message').html(message);
}

</script>
@stop
