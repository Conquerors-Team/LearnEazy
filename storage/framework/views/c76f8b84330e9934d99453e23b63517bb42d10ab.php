<link rel="stylesheet" type="text/css" href="<?php echo e(CSS); ?>select2.css">
<?php $__env->startSection('content'); ?>

<div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
               <div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="<?php echo e(PREFIX); ?>"><i class="mdi mdi-home"></i></a> </li>
              <li><a href="<?php echo e(URL_STUDENT_SUBJECTS); ?>"><i class="icon-books"></i>&nbsp;Subjects</a> </li>
							<li> <a href="<?php echo e(URL_STUDENT_LMS_SERIES); ?>"><?php echo e(getPhrase('learning_management_series')); ?> </a> </li>
							<li class="active"> <?php echo e($title); ?> </li>
						</ol>
					</div>
				</div>
                <div class="panel panel-custom">

                    <div class="panel-body">

                        <?php if(!$content_record): ?>



                        <?php elseif($content_record->content_type == 'video' || $content_record->content_type == 'iframe' || $content_record->content_type == 'video_url' || $content_record->content_type == 'animation'): ?>

                            <?php echo $__env->make('student.lms.series-video-player', array('series'=>$item, 'content' => $content_record), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                        <?php elseif($content_record->content_type == 'audio' || $content_record->content_type == 'audio_url'): ?>

                            <?php echo $__env->make('student.lms.series-audio-player', array('series'=>$item, 'content' => $content_record), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        <?php endif; ?>
                        <hr>

                       <?php echo $__env->make('student.lms.series-items', array('series'=>$item, 'content'=>$content_record), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>

		<!-- /#page-wrapper -->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer_scripts'); ?>

<?php if($content_record): ?>
    <?php if($content_record->content_type == 'video' || $content_record->content_type == 'video_url'): ?>
        <?php echo $__env->make('common.video-scripts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>

<?php endif; ?>
<?php echo $__env->make('common.custom-message-alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('common.alertify', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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

function changePopQuiz(series_id, content_id) {
  var route = '<?php echo e(url("get-pop-quiz-info")); ?>/'+series_id + '/' + content_id;
  var token = $('[name="_token"]').val();

  data= $('#frmContent_' + content_id).serialize();
  $.ajax({
    type: "POST",
    url:route,
    dataType: 'json',
    data: data,
    success:function(result){
      $('#batchModal_' + content_id).modal('toggle')
      $('#popquiz_a_' + content_id).attr('disabled', 'disabled');
      alertify.success('Quiz updated');
    }
  });
}

</script>

<script src="<?php echo e(JS); ?>select2.js"></script>

<script>
$( function() {
  $( "#accordion" ).accordion();
} );

$('.select2').select2({
       placeholder: "Please select",
    });

$('.batchform').submit(function(e) {
  e.preventDefault();
  alert('ffff');
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>