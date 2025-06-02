<div id="subjectSidebar" >
			<div class="panel panel-custom">
				<div class="panel-heading">
					<h2 class="text-uppercase subject-title"> <i class="icon-school-hub"></i> {{getPhrase('Content')}} </h2>
				</div>
				<div class="panel-body subject-list-box">
					<?php
					$content = \App\LmsContent::getRecordWithSlug( $lms_slug );

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


				</div>

			</div>

		</div>