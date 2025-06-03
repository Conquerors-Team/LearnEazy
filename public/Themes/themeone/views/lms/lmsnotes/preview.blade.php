 @extends($layout)
@section('content')

<div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
               <div class="row">
          <div class="col-lg-12">
            <ol class="breadcrumb">
              <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
              @if(canDo('lms_notes_access'))
              <li><a href="{{URL_LMS_NOTES}}">LMS {{ getPhrase('notes')}}</a></li>
              @endif
              <li class="active"> {{ $title }} </li>
            </ol>
          </div>
        </div>
                <div class="panel panel-custom">

                    <div class="panel-body">
<?php
$content_type = $content->content_type;
$is_pop_exam = 'no';

?>
<div id="accordion" class="accordion">

  <h3>{{$content->title}}</h3>
  <div>
    @if( ! empty( $content->quiz->id ) && 'yes' === $is_pop_exam )
    <a class="btn btn-lg btn-success pull-right" href="javascript:void(0);" onClick="showInstructions('{{URL_STUDENT_TAKE_EXAM.$content->quiz->slug . '/' . $content->slug}}')" @if(!checkRole(['student'])) disabled @endif>Take Quiz</a>
    @endif
    <p>
        <?php
        $url = url('public/uploads/lms/notes/' . $content->file_path, [], true);
        if ( in_array( $content->content_type, ['url', 'video_url'] ) ) {
            $url = $content->file_path;
            echo $url;
        }
        ?>
        @if($content->description && $content_type != 'text')
        <p>{!! $content->description !!}</p>
        @endif
        @if( in_array( $content_type, ['video', 'audio'] ) )
            <?php
                $video_src = $content->file_path;
                if($content->content_type=='video') {
                    $video_src = IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->file_path;
                }
                // echo $video_src;
            ?>
            <video id="my-video" class="video-js vjs-big-play-centered" autoplay controls preload="auto" width="300" height="264"
              poster="" data-setup='{"aspectRatio":"640:267", "playbackRates": [1, 1.5, 2] }'>
                <source src="{{$video_src}}" type='video/mp4'>
                <p class="vjs-no-js">
                  To view this video please enable JavaScript, and consider upgrading to a web browser that
                  <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                </p>
            </video>
        @elseif( $content_type == 'iframe' && preg_match('/iframe/',$content->file_path) )
            {!! $content->file_path !!}
        @elseif( $content_type == 'text' )
            {!! $content->description !!}
        @else
            <?php
            $ext = pathinfo($content->file_path, PATHINFO_EXTENSION);
            ?>
            @if ( in_array( $ext, ['xlsx', 'docx', 'pptx']) )
            <iframe src='https://view.officeapps.live.com/op/embed.aspx?src={{$url}}' width='100%' height='565px' frameborder='0' toolbar=0> </iframe>
             @elseif (in_array( $content->content_type, ['video_url'] ) )
             <iframe width="100%" height="560" src="{{$url}}#toolbar=0" width='100%' height='565px' frameborder='0' toolbar=0> </iframe>
            @else
            <iframe width="100%" height="560" src="{{$url}}#toolbar=0" frameborder="0" toolbar=0 allowfullscreen></iframe>
            @endif
        @endif
    </p>
  </div>
</div>

</div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>

    <!-- /#page-wrapper -->

@stop

@section('footer_scripts')

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
$( function() {
  $( "#accordion" ).accordion();
} );
</script>

@stop