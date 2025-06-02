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

							<a href="<?php echo e(URL_QUESTIONBANK_ADD_QUESTION.$subject->slug); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('create')); ?></a>

						</div>
						<h1><?php echo e($title); ?></h1>
					</div>
					<?php if(checkRole(getUserGrade(9))): ?>
						<?php echo $__env->make('common.search-form', ['url' => url()->current(), 'show_content_types' => 'FALSE','show_subjects' => 'FALSE','show_sub_topics' => 'FALSE','show_batch_assigned' => 'FALSE', 'subject_id' => $subject->id], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
					<?php endif; ?>
					<div class="panel-body packages">
						<div class="table-responsive">
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
								    <!-- <?php if(checkRole(getUserGrade(3)) || shareData()): ?>
                                        <th><?php echo e(getPhrase('institute')); ?></th>
                                    <?php endif; ?> -->

									<th width="120px"><?php echo e(getPhrase('question_code')); ?></th>
									<th width="620px"><?php echo e(getPhrase('question')); ?></th>
									<th><?php echo e(getPhrase('category')); ?></th>
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
  

 <?php echo $__env->make('common.datatables', array('route'=>URL_QUESTIONBANK_GETQUESTION_LIST.$subject->slug, 'route_as_url' => 'TRUE', 'search_columns' => ['subject' => request('subject_id'),'chapter' => request('chapter_id'),'topic' => request('topic_id'),'sub_topic' => request('sub_topic_id'), 'institute' => request('institute_id'),'question_category_id' => request('question_category_id'), 'difficulty_level' => request('difficulty_level')]), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
 <?php echo $__env->make('common.deletescript', array('route'=>URL_QUESTIONBANK_DELETE), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('common.filter-scripts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>