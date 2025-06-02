<?php $__env->startSection('header_scripts'); ?>
<link href="<?php echo e(CSS); ?>ajax-datatables.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div id="page-wrapper" ng-controller="batchesController">

			<div class="container-fluid">

				<!-- Page Heading -->

				<div class="row">

					<div class="col-lg-12">

						<ol class="breadcrumb">

							<li><a href="<?php echo e(PREFIX); ?>"><i class="mdi mdi-home"></i></a> </li>

							<?php if(canDo('institute_batch_access')): ?>
							<li><a href="<?php echo e(URL_BATCHS); ?>"><?php echo e(getPhrase('batches')); ?></a></li>
							<?php endif; ?>

							<li><?php echo e($title); ?></li>

						</ol>

					</div>

				</div>

				<?php echo $__env->make('common.search-form', ['url' => URL_BATCHS_ADD_LMS . $record->id, 'show_content_types' => 'FALSE'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

				<!-- /.row -->

				<div class="panel panel-custom">

					<div class="panel-heading">
                        <div class="pull-right messages-buttons">
							 <?php if(canDo('lms_series_create')): ?>
							<a href="<?php echo e(URL_LMS_SERIES_ADD); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('create lms series')); ?></a>
							 <?php endif; ?>
						</div>

						<h1><?php echo e($title); ?></h1>

					</div>


	<?php echo Form::open(array('url' => URL_BATCHS_ADD_LMS . $record->id, 'method' => 'POST', 'name'=>'htmlform ','id'=>'htmlform', 'novalidate'=>'')); ?>




        <div class="panel-body packages">

			<div >

		<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
			<thead>
				<tr>
					<?php if(checkRole(getUserGrade(3)) || shareData('share_lms_series')): ?>
		                <th><?php echo e(getPhrase('institute')); ?></th>
		            <?php endif; ?>
					<th><?php echo e(getPhrase('title')); ?></th>
					<th><?php echo e(getPhrase('image')); ?></th>
					<th><?php echo e(getPhrase('total_items')); ?></th>
					<th><?php echo e(getPhrase('type')); ?></th>
					<th><?php echo e(getPhrase('action')); ?></th>
				</tr>
			</thead>

		</table>
	</div>
</div>

 </div>

<!-- <submit type="submit" class="btn btn-primary pull-right" ><?php echo e(getPhrase('add')); ?></submit> -->
<button class="btn btn-lg btn-success button" type="submit">Add</button>
<br>
  </div>



					 </div>



					</div>

			<input type="hidden" name="batch_id" value="<?php echo e($record->id); ?>">

					<?php echo Form::close(); ?>



				</div>

			</div>

			<!-- /.container-fluid -->

		</div>

<?php $__env->stopSection(); ?>





<?php $__env->startSection('footer_scripts'); ?>
   <?php echo $__env->make('common.filter-scripts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

   <?php echo $__env->make('common.datatables', array('route'=>'lmsseries.dataTable', 'search_columns' => ['callfrom' => 'batch', 'batch_id' => $record->id, 'subject' => request('subject_id'),'chapter' => request('chapter_id'),'topic' => request('topic_id'),'sub_topic' => request('sub_topic_id'),'content_type' => request('content_type'), 'institute' => request('institute_id')]), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>