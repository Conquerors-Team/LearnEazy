<?php $__env->startSection('header_scripts'); ?>
<link href="<?php echo e(CSS); ?>ajax-datatables.css" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>


<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="<?php echo e(url('/')); ?>"><i class="mdi mdi-home"></i></a> </li>
							<li><a href="<?php echo e(URL_QUIZ_QUESTIONBANK); ?>"><?php echo e(getPhrase('question_subjects')); ?></a></li>
							<li><a href="<?php echo e(URL_QUESTIONBAMK_IMPORT); ?>"><?php echo e(getPhrase('import_questions')); ?></a></li>
							<li><?php echo e($title); ?></li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">

						<div class="pull-right messages-buttons">

							<a href="<?php echo e(URL_QUESTIONBANK_VIEW.$subject->slug); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('back')); ?></a>

						</div>
						<h1><?php echo e($title); ?></h1>
					</div>
					<div class="panel-body packages">
						<div class="questions">

                                    <span class="language_l1"><?php echo $question->question; ?>   </span>
                                    <?php if($question->question_l2): ?>
                                     <?php if($question->question_type == 'radio' || $question->question_type == 'checkbox' || $question->question_type == 'blanks' || $question->question_type == 'match'): ?>
                                   <span class="language_l2" style="display: none;"> <?php echo $question->question_l2; ?>   </span>
                                   <?php else: ?>
                                   <span class="language_l2" style="display: none;"> <?php echo $question->question; ?>   </span>
                                     <?php endif; ?>
                                   <?php else: ?>
                                   <span class="language_l2" style="display: none;"> <?php echo $question->question; ?>   </span>
                                   <?php endif; ?>

                                    <div class="row">
  <div class="col-md-8 text-center">
  <?php if($question->question_type!='audio' && $question->question_type !='video'): ?>
  <?php if($question->question_file): ?>
  <img class="image " src="<?php echo e($image_path.$question->question_file); ?>" style="max-height:200px;">
  <?php endif; ?>
  <?php endif; ?>
  </div>
  <div class="col-md-4">
   <span class="pull-right">






                                   <?php echo e($question->marks); ?> Mark(s)</span>
  </div>
  </div>



                                    <?php if($question->hint): ?>
                                    <div class="option-hints pull-right default" data-placement="left" data-toggle="tooltip" ng-show="hints" title="<?php echo e($question->hint); ?>">

                                        <i class="mdi mdi-help-circle">

                                        </i>

                                    </div>
                                    <?php endif; ?>

                                </div>

                                <hr>

                                    <?php

                                    $image_path = PREFIX.(new App\ImageSettings())->

                                    getExamImagePath();



                                    ?>



								<?php echo $__env->make('student.exams.question_'.$question->question_type, array('question', $question, 'image_path' => $image_path,'previous_answers'=>[]  ), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


                                </hr>

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('footer_scripts'); ?>
  
 <?php echo $__env->make('common.datatables', array('route'=>URL_QUESTIONBANK_GETQUESTION_LIST.$subject->slug, 'route_as_url' => 'TRUE'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
 <?php echo $__env->make('common.deletescript', array('route'=>URL_QUESTIONBANK_DELETE), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>



<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>