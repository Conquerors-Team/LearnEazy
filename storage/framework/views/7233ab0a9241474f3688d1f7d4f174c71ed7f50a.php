<?php $__env->startSection('content'); ?>

<div id="page-wrapper">
			<div class="container-fluid">
			<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							 <li><a href="<?php echo e(PREFIX); ?>"><i class="mdi mdi-home"></i></a> </li>
							<li><?php echo e($title); ?></li>
						</ol>
					</div>
				</div>

				 <div class="row">

				<div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a
							href="<?php if($payment_mode=='online'): ?>
							<?php echo e(URL_ONLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php else: ?> <?php echo e(URL_OFFLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php endif; ?>
							all"
							><div class="state-icn bg-icon-info"><i class="fa fa-money"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title"><?php echo e($payments->all); ?></h4>
								<a
							href="<?php if($payment_mode=='online'): ?>
							<?php echo e(URL_ONLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php else: ?> <?php echo e(URL_OFFLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php endif; ?>
							all"
							>
								<?php echo e(getPhrase('payments')); ?></a>
				 			</div>
				 		</div>
				 	</div>

				 	<div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a
							href="<?php if($payment_mode=='online'): ?>
							<?php echo e(URL_ONLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php else: ?> <?php echo e(URL_OFFLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php endif; ?>
							success"
							><div class="state-icn bg-icon-success"><i class="fa fa-check"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title"><?php echo e($payments->success); ?></h4>
								<a
							href="<?php if($payment_mode=='online'): ?>
							<?php echo e(URL_ONLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php else: ?> <?php echo e(URL_OFFLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php endif; ?>
							success"
							>
								<?php echo e(getPhrase('success')); ?></a>
				 			</div>
				 		</div>
				 	</div>

				 		<div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a
							href="<?php if($payment_mode=='online'): ?>
							<?php echo e(URL_ONLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php else: ?> <?php echo e(URL_OFFLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php endif; ?>
							pending"
							><div class="state-icn bg-icon-purple"><i class="fa fa-spinner"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title"><?php echo e($payments->pending); ?></h4>
								<a
							href="<?php if($payment_mode=='online'): ?>
							<?php echo e(URL_ONLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php else: ?> <?php echo e(URL_OFFLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php endif; ?>
							pending"
							>
								<?php echo e(getPhrase('pending')); ?></a>
				 			</div>
				 		</div>
				 	</div>


	<div class="col-md-3 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a
							href="<?php if($payment_mode=='online'): ?>
							<?php echo e(URL_ONLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php else: ?> <?php echo e(URL_OFFLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php endif; ?>
							cancelled"
							><div class="state-icn bg-icon-pink"><i class="fa fa-remove"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title"><?php echo e($payments->cancelled); ?></h4>
								<a
							href="<?php if($payment_mode=='online'): ?>
							<?php echo e(URL_ONLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php else: ?> <?php echo e(URL_OFFLINE_PAYMENT_REPORT_DETAILS); ?>

							<?php endif; ?>
							cancelled"
							>
								<?php echo e(getPhrase('cancelled')); ?></a>
				 			</div>
				 		</div>
				 	</div>
					 
			</div>
			<!-- /.container-fluid -->
			<div class="row">
				<div class="col-md-6">
  				  <div class="panel panel-primary dsPanel">
				    <div class="panel-heading"><?php echo e(getPhrase('payment_statistics')); ?></div>
				    <div class="panel-body" >
				    	<canvas id="payments_chart" width="100" height="60"></canvas>
				    </div>
				  </div>
				</div>

				<div class="col-md-6">
  				  <div class="panel panel-primary dsPanel">
				    <div class="panel-heading"><?php echo e(getPhrase('payment_monthly_statistics')); ?></div>
				    <div class="panel-body" >
				    	<canvas id="payments_monthly_chart" width="100" height="60"></canvas>
				    </div>
				  </div>
				</div>

				
			</div>
 
</div>
		<!-- /#page-wrapper -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
 
 <?php echo $__env->make('common.chart', array('chart_data'=>$payments_chart_data,'ids' =>array('payments_chart'), 'scale'=>TRUE), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
 <?php echo $__env->make('common.chart', array('chart_data'=>$payments_monthly_data,'ids' =>array('payments_monthly_chart'), 'scale'=>true), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
 
 

<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>