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


						<?php if(canDo('institute_batch_create')): ?>
						<div class="pull-right messages-buttons">
							<a href="<?php echo e(URL_BATCHS_ADD); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('create')); ?></a>
						</div>
						<?php endif; ?>

						<h1><?php echo e($title); ?></h1>

					</div>

					<div class="panel-body packages">
						<div >
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">

							<thead>
								<tr>

									<!-- <?php if(checkRole(getUserGrade(3)) || shareData('share_batches')): ?>
                                        <th><?php echo e(getPhrase('institute')); ?></th>
                                    <?php endif; ?> -->

									<th><?php echo e(getPhrase('name')); ?></th>

									<th>Assign LMS</th>

									<th>Assign Notes</th>

									<th>Exam Report</th>

									<!-- <th><?php echo e(getPhrase('total_seats')); ?></th>

									<th><?php echo e(getPhrase('booked_seats')); ?></th>

									<th><?php echo e(getPhrase('available_seats')); ?></th> -->

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



 <?php echo $__env->make('common.datatables', array('route'=>URL_BATCHS_GETLIST, 'route_as_url' => TRUE), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

 <?php echo $__env->make('common.deletescript', array('route'=>URL_BATCHS_DELETE), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>



<?php $__env->stopSection(); ?>


<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>