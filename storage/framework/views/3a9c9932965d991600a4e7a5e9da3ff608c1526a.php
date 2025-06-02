 <?php
 // $contents = $series->getContents();
 $contents = \App\LmsSeriesData::
  join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
 ->where('lmsseries_id', $series->id)
  ->whereNotNull('content_type')
 ->orderBy('display_order')
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
  <?php $__currentLoopData = $contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php
  $content_type = $content->content_type;

  if ( empty( $content_type ) ) {
    $content_type = 'text';
  }

  $pop_quiz_batches = \DB::table('lmsseries_data_batch_popquiz')->select('batches.*')
  ->join('lmsseries', 'lmsseries.id', '=', 'lmsseries_data_batch_popquiz.lmsseries_id')
  ->join('batches', 'batches.id', '=', 'lmsseries_data_batch_popquiz.batch_id')
  ->where('lmsseries_id', $series->id)->where('lmscontent_id', $content->id)->where('pop_quiz', 'yes')->get()->pluck('name')->toArray();

  $is_pop_quiz = 'yes';
  if ( ! isOnlinestudent() ) {
    $user = \App\User::with(['student_class'])->find( Auth::id() );
    if(count($user->batches) > 0) {
      $pop_quiz_batches = \DB::table('lmsseries_data_batch_popquiz')->select('batches.*')
      ->join('lmsseries', 'lmsseries.id', '=', 'lmsseries_data_batch_popquiz.lmsseries_id')
      ->join('batches', 'batches.id', '=', 'lmsseries_data_batch_popquiz.batch_id')
      ->where('lmsseries_id', $series->id)->where('lmscontent_id', $content->id)
      ->where('pop_quiz', 'yes')
      ->whereIn('batches.id', $user->batches->pluck('id')->toArray())
      ->get()->pluck('name')->toArray();
      if(count($pop_quiz_batches) == 0) {
        $is_pop_quiz = 'no';
      }
    }
  }
  ?>

  <?php if( ! empty( $content_type ) ): ?>
  <h3><?php echo e($content->title); ?></h3>
  <div>

      <?php if( ! empty( $content->quiz->id )): ?>
        <?php if(checkRole(getUserGrade(2))): ?>
          <a class="btn btn-lg btn-success pull-right" id="popquiz_a_<?php echo e($content->id); ?>" href="#batchModal_<?php echo e($content->id); ?>" data-toggle="modal">PopQuiz</a>
          <?php if(count($pop_quiz_batches) > 0): ?>
          <span class="label label-info label-many"><?php echo implode('</span><span class="label label-info label-many"> ', $pop_quiz_batches); ?></span>
          <?php endif; ?>
        <?php endif; ?>

        <?php if( 'yes' == $is_pop_quiz ): ?>
        <a class="btn btn-lg btn-success pull-right" href="javascript:void(0);" onClick="showInstructions('<?php echo e(URL_STUDENT_TAKE_EXAM.$content->quiz->slug . '/' . $content->slug); ?>')" <?php if(!checkRole(['student'])): ?> disabled <?php endif; ?>>Take Quiz</a>
        &nbsp;|&nbsp;
        <?php endif; ?>
      <?php endif; ?>
    <p>
        <?php
        $url = url('public/uploads/lms/content/' . $content->file_path, [], true);
        if (  in_array( $content->content_type, ['url','video_url'] )) {
            $url = $content->file_path;
        }
        ?>
        <?php if($content->description && $content_type != 'text'): ?>
        <p><?php echo $content->description; ?></p>
        <?php endif; ?>
        <?php if( in_array( $content_type, ['video','audio'] ) ): ?>
            <?php
                $video_src = $content->file_path;
                if($content->content_type=='video') {
                    $video_src = IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->file_path;
                }
            ?>
            <video id="my-video" class="video-js vjs-big-play-centered" autoplay controls preload="auto" width="300" height="264"
              poster="" data-setup='{"aspectRatio":"640:267", "playbackRates": [1, 1.5, 2] }'>
                <source src="<?php echo e($video_src); ?>" type='video/mp4'>
                <p class="vjs-no-js">
                  To view this video please enable JavaScript, and consider upgrading to a web browser that
                  <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                </p>
            </video>
        <?php elseif( $content_type == 'iframe' && preg_match('/iframe/',$content->file_path) ): ?>
            <?php echo $content->file_path; ?>

        <?php elseif( $content_type == 'text' ): ?>
            <?php echo $content->description; ?>

        <?php else: ?>
            <?php
            $ext = pathinfo($content->file_path, PATHINFO_EXTENSION);
            if ( $content_type != 'url' ) {
              $url = $url . '#toolbar=0';
            }
            ?>
            <?php if( in_array( $ext, ['xlsx', 'docx', 'pptx']) ): ?>
            <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=<?php echo e($url); ?>' width='100%' height='565px' frameborder='0' toolbar=0> </iframe>
             <?php elseif(in_array( $content->content_type, ['video_url'] ) ): ?>
             <iframe width="100%" height="560" src="<?php echo e($url); ?>#toolbar=0" width='100%' height='565px' frameborder='0' toolbar=0> </iframe>
            <?php else: ?>
            <iframe width="100%" height="560" src="<?php echo e($url); ?>#toolbar=0" frameborder="0" toolbar=0 allowfullscreen></iframe>
            <?php endif; ?>
        <?php endif; ?>
    </p>
  </div>
  <?php endif; ?>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if(checkRole(getUserGrade(2))): ?>
  <?php $__currentLoopData = $contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php
  $content_type = $content->content_type;
  if ( empty( $content_type ) ) {
    $content_type = 'text';
  }
  ?>
  <?php if( ! empty( $content_type ) ): ?>

  <?php if( ! empty( $content->quiz->id )): ?>
  <div id="batchModal_<?php echo e($content->id); ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">
  <?php echo Form::open(array('url' => '', 'method' => 'POST', 'name'=>'frmContent_' . $content->id, 'id'=>'frmContent_' . $content->id,'novalidate'=>'', 'class'=>"batchform",)); ?>

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Pop Quiz for batches</h4>
      </div>
        <div class="modal-body">

        <div class="form-group">
        <label>Batches</label>
        <?php
        $institute_id   = adminInstituteId();
        $batches = \DB::table('batch_lmsseries')->select('batches.*')
        ->join('lmsseries', 'batch_lmsseries.lms_series_id', '=', 'lmsseries.id')
        ->join('batches', 'batches.id', '=', 'batch_lmsseries.batch_id')
        ->where('lms_series_id', $series->id);
        if ( isFaculty() ) {
          $faculty_batches = \Auth::user()->faculty_batches()->get()->pluck('id')->toArray();
          $batches->whereIn('batches.id', $faculty_batches);
        } else {
          $batches->where('batches.institute_id', $institute_id);
        }
        $batches = $batches->get()->pluck('name', 'id')->toArray();
        ?>
        <?php echo e(Form::select('batches[]', $batches, null, ['class'=>'form-control select2', 'name'=>'batches[]', 'multiple'=>'true', 'id' => 'batches'])); ?>

</div>

</div>
<div class="modal-footer">
<div class="pull-right">
<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(getPhrase('close')); ?></button>
<button type="button" class="btn_1" onclick="changePopQuiz('<?php echo e($series->id); ?>', '<?php echo e($content->id); ?>')"><?php echo e(getPhrase('submit')); ?></button>
</div>
</div>
</div>
<?php echo Form::close(); ?>

</div>
</div>
<!-- Model End -->
<?php endif; ?>
</div>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if( isFaculty() ): ?>
<div>
  <?php if( \Auth::user()->white_board_code != ''): ?>
    <?php echo \Auth::user()->white_board_code; ?>

  <?php else: ?>
  <div style="height:600px;border: 1px solid black;"><div style="position:relative;z-index:10;height:40px;padding-left:4px;width:150px;"><a style="text-decoration:none;color:#CCC;font-size:20px;font-family:Dosis;" href="https://ziteboard.com" target="_blank">Zoom & Move</a></div><iframe seamless="seamless" style="position:relative;width: 100%; height: 100%;top:-40px;" src="https://view.ziteboard.com/shared/48016458719512" frameborder="0" allowfullscreen></iframe></div>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php endif; ?>