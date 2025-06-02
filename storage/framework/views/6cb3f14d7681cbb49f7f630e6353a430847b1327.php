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

						<div class="pull-right messages-buttons">
							<div class="btn-group">
				              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				                <i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Role&nbsp;<span class="caret"></span>
				              </button>
				              <ul class="dropdown-menu">
				                <li><a href="<?php echo e(URL_USERS); ?>/user/student">Students</a></li>
				                <li><a href="<?php echo e(URL_USERS); ?>/user/faculty">Faculty</a></li>
				                <?php if(checkRole(getUserGrade(1))): ?>
				                <li><a href="<?php echo e(URL_USERS); ?>/user/institute">Institute</a></li>
				                <?php endif; ?>
				              </ul>
				            </div>

				            <div class="btn-group">
				            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				                <i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Class&nbsp;<span class="caret"></span>
				              </button>
				              <ul class="dropdown-menu">
				                <?php
				                $classes = \App\StudentClass::where('institute_id', adminInstituteId())->get();
				                ?>
				                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				                	<li><a href="<?php echo e(URL_USERS); ?>/class/<?php echo e($class->slug); ?>"><?php echo e($class->name); ?></a></li>
				                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				              </ul>
				            </div>

				            <div class="btn-group">
				            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				                <i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Batches&nbsp;<span class="caret"></span>
				              </button>
				              <ul class="dropdown-menu">
				                <?php
				                $batches = \App\Batch::where('institute_id', adminInstituteId())->get();
				                ?>
				                <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				                	<li><a href="<?php echo e(URL_USERS); ?>/batch/<?php echo e($batch->id); ?>"><?php echo e($batch->name); ?></a></li>
				                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				              </ul>
				            </div>


				        	<a href="<?php echo e(URL_USERS); ?>" class="btn btn-primary button" ><?php echo e(getPhrase('All')); ?></a>
							<a href="<?php echo e(URL_USERS_IMPORT); ?>" class="btn btn-primary button" ><?php echo e(getPhrase('import_excel')); ?></a>
							<a href="<?php echo e(URL_USERS_ADD); ?>" class="btn btn-primary button" ><?php echo e(getPhrase('add_user')); ?></a>
							</div>
						</div>
						<h1><?php echo e($title); ?></h1>
					</div>
					<div class="panel-body packages">
						<div>
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><?php echo e(getPhrase('image')); ?></th>
								 	<th><?php echo e(getPhrase('name')); ?></th>
								 	<th><?php echo e(getPhrase('institute')); ?></th>
									<th><?php echo e(getPhrase('email')); ?></th>
									<th><?php echo e(getPhrase('role')); ?></th>
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
 <?php echo $__env->make('common.datatables', array('route' =>URL_USERS_GETLIST . $type . '/' . $type_id, 'route_as_url' => true), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
 <?php echo $__env->make('common.deletescript', array('route'=>URL_USERS_DELETE), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>