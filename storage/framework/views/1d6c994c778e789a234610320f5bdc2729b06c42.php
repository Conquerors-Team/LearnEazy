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
							<li><?php echo e($title); ?></li>
						</ol>
					</div>
				</div>

				<?php echo $__env->make('common.search-form', ['url' => URL_LMS_CONTENT, 'show_question_actegory' => 'FALSE', 'show_difficulty_level' => 'FALSE', 'show_sub_topics' => 'FALSE'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							<?php if(canDo('lms_content_create')): ?>
				            <a href="<?php echo e(URL_LMS_CONTENT_ADD); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('create')); ?></a>
				            <?php endif; ?>
						</div>
						<h1><?php echo e($title); ?></h1>
					</div>
					<div class="panel-body packages">

						<div>
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<?php if(checkRole(getUserGrade(3)) || shareData('share_lms_contents')): ?>
                                        <th><?php echo e(getPhrase('institute')); ?></th>
                                    <?php endif; ?>
									<th><?php echo e(getPhrase('title')); ?></th>
									<th><?php echo e(getPhrase('image')); ?></th>
									<th><?php echo e(getPhrase('type')); ?></th>
									<th><?php echo e(getPhrase('subject')); ?></th>
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
 <?php echo $__env->make('common.datatables', array('route'=>'lmscontent.dataTable', 'search_columns' => ['subject' => request('subject_id'),'chapter' => request('chapter_id'),'topic' => request('topic_id'),'sub_topic' => request('sub_topic_id'), 'content_type' => request('content_type'), 'institute' => request('institute_id')]), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
 <?php echo $__env->make('common.deletescript', array('route'=>URL_LMS_CONTENT_DELETE), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>