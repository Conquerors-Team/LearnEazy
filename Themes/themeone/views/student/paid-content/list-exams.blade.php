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

              <!-- <li><a href="{{URL_STUDENT_SUBJECTS}}"><i class="icon-books"></i>&nbsp;Subjects</a> </li> -->

              <li>{{ $title }}</li>
            </ol>
          </div>
        </div>

        <!-- /.row -->
        <div class="panel panel-custom">
          <div class="panel-heading">
            <?php
             $types = [
              'subject-exams' => 'Subject tests',
              'grand-exams' => 'Grand tests',
              'previousyear-exams' => 'Previous year tests',
             ];
             ?>
            <ul class="nav nav-tabs">
              @foreach( $types as $item_type => $title)
              <li @if($item_type == $type) class="active" @endif><a href="{{route('student.paid_exams', ['type' => $item_type])}}">{{$title}}</span></a></li>
              @endforeach
            </ul>
          </div>

          <div class="panel-body packages">
            @if( in_array( $type, ['subject-exams', 'previousyear-exams']) )
              <?php
              $student_courses = getStudentClasses('courses');
              $date = date('Y-m-d');
              $user_id = Auth::user()->id;

              $subjects = \App\Subject::select(['subjects.*'])
                      ->join('quizzes_subjects as qs', 'qs.subject_id', '=', 'subjects.id')
                      ->join('quizzes as q', 'q.id', '=', 'qs.quiz_id')
                      ->join('student_paid_contents_data as spcd', 'spcd.item_id', '=', 'q.id')
                      ->join('student_paid_contents_courses as spcc', 'spcc.student_paid_contents_id', '=', 'spcd.student_paid_contents_id')
                      ->join('student_paid_contents', 'student_paid_contents.id', '=', 'spcc.student_paid_contents_id')
                      ->join('payments as p', 'p.item_id', '=', 'spcd.student_paid_contents_id')

                      ->whereIn('spcc.course_id', $student_courses)
                      ->where('student_paid_contents.total_items', '>', 0)
                      ->where('student_paid_contents.status', 'active')
                      ->where('spcd.item_type', 'subject-exams')
                      ->where('p.end_date','>=',$date)
                      ->where('p.user_id','=',$user_id)
                      ->where('p.plan_type','=','paidcontent')

                      ->groupBy('spcd.item_id')
                      ->groupBy('qs.subject_id')
                      ->get()->pluck('subject_title', 'id')->prepend(getPhrase('select'), '');

              $years = \App\Quiz::select(['quizzes.*'])
                      ->join('quizzes_subjects as qs', 'qs.quiz_id', '=', 'quizzes.id')
                      // ->join('quizzes as q', 'quizzes.id', '=', 'qs.quiz_id')
                      ->join('student_paid_contents_data as spcd', 'spcd.item_id', '=', 'quizzes.id')
                      ->join('student_paid_contents_courses as spcc', 'spcc.student_paid_contents_id', '=', 'spcd.student_paid_contents_id')
                      ->join('student_paid_contents', 'student_paid_contents.id', '=', 'spcc.student_paid_contents_id')
                      ->join('payments as p', 'p.item_id', '=', 'spcd.student_paid_contents_id')

                      ->whereIn('spcc.course_id', $student_courses)
                      ->where('student_paid_contents.total_items', '>', 0)
                      ->where('student_paid_contents.status', 'active')
                      ->where('spcd.item_type', 'previousyear-exams')
                      ->where('p.end_date','>=',$date)
                      ->where('p.user_id','=',$user_id)
                      ->where('p.plan_type','=','paidcontent')

                      ->groupBy('spcd.item_id')
                      ->groupBy('quizzes.year')
                      ->orderBy('quizzes.year', 'desc')
                      ->get()->pluck('year', 'year')->prepend(getPhrase('select'), '');
              ?>
              @include('student.paid-content.search-form', ['url' => url()->current(), 'subjects' => $subjects, 'type' => $type, 'years' => $years])
            @endif
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

@include('common.datatables', array('route'=>URL_STUDENT_QUIZ_PAID . $type, 'route_as_url' => TRUE, 'search_columns' => ['subject' => request('subject_id'),'previousyear' => request('previousyear')]))

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
