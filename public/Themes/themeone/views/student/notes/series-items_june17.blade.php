 <?php
 // $contents = $series->getContents();
 $contents = \App\LmsSeriesData::
  join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
 ->where('lmsseries_id', $series->id)->get();
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
<div id="accordion">
  @foreach($contents as $content)
  <h3>{{$content->title}}</h3>
  <div>
    @if( $content->quiz )
    <a class="btn btn-lg btn-success pull-right" href="javascript:void(0);" onClick="showInstructions('{{URL_STUDENT_TAKE_EXAM.$content->quiz->slug . '/' . $content->slug}}')">Take Exam</a>
    @endif
    <p>
        <?php
        $url = url('public/uploads/lms/content/' . $content->file_path);
        if ( $content->content_type == 'url' ) {
            $url = $content->file_path;
        }
        ?>
        @if( in_array( $content->content_type, ['video', 'video_url', 'audio'] ) )
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
        @elseif( $content->content_type == 'iframe' && preg_match('/iframe/',$content->file_path) )
            {!! $content->file_path !!}
        @else
            <iframe width="100%" height="560" src="{{$url}}" frameborder="0" allowfullscreen></iframe>
        @endif
    </p>
  </div>
  @endforeach
</div>