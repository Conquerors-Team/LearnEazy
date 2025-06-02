@extends('layouts.sitelayoutnew')

@section('content')
  <main id="main">

      <!-- ======= Book List ======= -->
      <section>
        <div class="container">

          <div class="section-title">
            <h3>{{$board->title}} <span>Syllabus</span></h3>
            <p>{!!$board->description!!}</p>
          </div>

          <!-- List of Classes -->
          <ul class="nav nav-tabs nav-fill">
            <?php
            $classes = \App\BoardClass::select(['board_classes.title', 'board_classes.slug', 'boards_board_classes.*'])->join('boards_board_classes','boards_board_classes.board_class_id','board_classes.id')
              ->where('boards_board_classes.board_id', $id)
              ->get();
              //echo getEloquentSqlWithBindings(\App\BoardClass::select(['board_classes.title', 'board_classes.slug', 'boards_board_classes.*'])->join('boards_board_classes','boards_board_classes.board_class_id','board_classes.id')              ->where('boards_board_classes.board_id', $id));
            ?>
            @foreach ($classes as $class)
              <?php
                if ( empty( $class_id ) && $loop->first ) {
                  $class_id = $class->board_class_id;
                  $class_slug = $class->slug;
                }
                $active = '';
                if ( $class_id == $class->board_class_id ) {
                  $active = ' active';
                }
              ?>
              <li class="nav-item">
                <a class="nav-link {{$active}}" href="{{route('site.board', ['board_id' => $board_slug, 'class' => $class->slug])}}">{{$class->title}}</a>
              </li>
          @endforeach
          </ul>

          <!-- List of Subjects based on Class selected -->
          <ul id="subjects-pills" class="nav nav-pills">
            <?php
            $subjects = \App\BoardSubject::join('board_classes_subjects','board_classes_subjects.board_subject_id', '=', 'board_subjects.id')
              ->where('board_classes_subjects.board_class_id', $class_id)
              ->get();
             ?>
            @foreach ($subjects as $subject)
              <?php
              if ( empty( $subject_id ) && $loop->first ) {
                $subject_id = $subject->id;
                $subject_slug = $subject->slug;
              }

              $active = '';
              if ( $subject_id == $subject->id ) {
                $active = ' active';
              }
            ?>
            <li class="nav-item">
              <a class="nav-link {{$active}}" href="{{route('site.board', ['board_id' => $board_slug, 'class' => $class_slug, 'subject' => $subject->slug])}}">{{$subject->title}}</a>
            </li>
          @endforeach
          </ul>


          <table class="table">
            <thead>
              <tr>
                <th scope="col" width="1%">#</th>
                <th scope="col">Chapter</th>
                <th scope="col" width="1%">Download</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $chapters = \App\BoardChapter::select(['board_chapters.*'])
              ->where('board_chapters.subject_id', $subject_id)
              ->where('board_chapters.board_class_id', $class_id)
              ->where('board_chapters.board_id', $id)
              ->where('board_chapters.status', 'Active')->get();
              ?>
              @forelse($chapters as $chapter)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td><strong>{{$chapter->title}}</strong></td>
                <td class="text-center">
                  <a href="{{route('site.media-file-download', ['model' => 'BoardChapter', 'field' => 'file_input', 'record_id' => $chapter->id])}}" target="_blank"><i class="icofont-download"></i></a>
                  <!-- <a href="{{url('uploads/board-downloads/' . $chapter->file_input)}}"><i class="icofont-download"></i></a> -->
                </td>
              </tr>
              @empty
              <tr><td colspan="3" align="center">No Records Found</td></tr>
              @endforelse
            </tbody>
          </table>

        </div>
      </section> <!-- End About Section -->

    </main><!-- End #main -->
@stop
