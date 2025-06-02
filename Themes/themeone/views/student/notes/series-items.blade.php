 <?php
 // $contents = $series->getContents();
 $contents = \DB::table('lms_notes')->select(['lms_notes.*'])
 ->join('batch_lmsnotes', 'batch_lmsnotes.lms_note_id', '=', 'lms_notes.id')
 ->where('lms_note_id', $series->id)
  ->whereNotNull('lms_notes.content_type')
  ->where('lms_notes.subject_id', $subject->id)
 ->get();
 // dd( $contents );
 $active_class = '';
 $active_class_id = 0;
 $content_image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
 if(isset($content) && $content)
 {
    if(isset($content->id))
        $active_class_id = $content->id;
    if($content->image)
    $content_image_path = IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->image;
 }
 ?>
<div id="accordion" class="accordion">
  @foreach($contents as $content)
  <?php
  $content_type = $content->content_type;
  if ( empty( $content_type ) ) {
    $content_type = 'text';
  }
  ?>
  @if( ! empty( $content_type ) )
  <h3>{{$content->title}}</h3>
  <div>
    <p>
        <?php
        $url = url('public/uploads/lms/notes/' . $content->file_path, [], true);
        if (  in_array( $content->content_type, ['url','video_url'] )) {
            $url = $content->file_path;
        }
        ?>
        @if($content->description && $content_type != 'text')
        <p>{!! $content->description !!}</p>
        @endif
        @if( in_array( $content_type, ['video','audio'] ) )
            <?php
                $video_src = $content->file_path;
                if($content->content_type=='video') {
                    $video_src = IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->file_path;
                }
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
  @endif
  @endforeach