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
							<li><a href="<?php echo e(PREFIX); ?>"><i class="mdi mdi-home"></i></a> </li>
							<li><?php echo e($title); ?></li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<?php if(checkRole(getUserGrade(9))): ?>
						<div class="pull-right messages-buttons">
							<a href="<?php echo e(URL_QUESTIONBAMK_IMPORT); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('import_questions')); ?></a>
							<a href="<?php echo e(URL_SUBJECTS_ADD); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('add_subject')); ?></a>
						</div>
						<?php endif; ?>
						<h1><?php echo e($title); ?></h1>
					</div>
					<?php if(checkRole(getUserGrade(9))): ?>
						<?php echo $__env->make('common.search-form', ['url' => url()->current(), 'show_content_types' => 'FALSE', 'show_chapters' => 'FALSE', 'show_topics' => 'FALSE', 'show_sub_topics' => 'FALSE','show_question_category' =>'FALSE','show_question_actegory'=>'FALSE','show_difficulty_level'=>'FALSES'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
					<?php endif; ?>

					<div class="panel-body packages">
						<div>
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>

								    <?php if(checkRole(getUserGrade(3)) || shareData('share_subjects')): ?>
                                        <th><?php echo e(getPhrase('institute')); ?></th>
                                    <?php endif; ?>

									<th><?php echo e(getPhrase('subject')); ?></th>
									<th><?php echo e(getPhrase('code')); ?></th>
									 <th><?php echo e(getPhrase('action')); ?></th>

								</tr>
							</thead>

						</table>
						</div>

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('footer_scripts'); ?>
	
 <?php echo $__env->make('common.filter-scripts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>	

 <?php echo $__env->make('common.datatables', array('route'=> 'exams.questionbank.getList', 'search_columns' => ['subject' => request('subject_id'),'chapter' => request('chapter_id'),'topic' => request('topic_id'),'sub_topic' => request('sub_topic_id'), 'institute' => request('institute_id')]), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
 <?php echo $__env->make('common.deletescript', array('route'=> URL_QUESTIONBANK_DELETE), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>