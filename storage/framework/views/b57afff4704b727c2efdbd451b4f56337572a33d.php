<?php $__env->startSection('content'); ?>
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="<?php echo e(url('/')); ?>"><i class="mdi mdi-home"></i></a> </li>
							<li><a href="/admin/onlineclasses/list"> Online Classes </a> </li>
							<li><?php echo e($title); ?></li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<h1><?php echo e($title); ?></h1>
					</div>
					<div class="panel-body packages">
						<div class="table-responsive">
							<?php if( \Auth::user()->white_board_code != ''): ?>
								<?php echo \Auth::user()->white_board_code; ?>

							<?php else: ?>
							<!--<div style="height:600px;border: 1px solid black;"><div style="position:relative;z-index:10;height:40px;padding-left:4px;width:150px;"><a style="text-decoration:none;color:#CCC;font-size:20px;font-family:Dosis;" href="https://ziteboard.com" target="_blank">Zoom & Move</a></div><iframe seamless="seamless" style="position:relative;width: 100%; height: 100%;top:-40px;" src="https://view.ziteboard.com/shared/61181981309515" frameborder="0" allowfullscreen></iframe></div>-->

							<div style="height:600px;border: 1px solid black;"><div style="position:relative;z-index:10;height:40px;padding-left:4px;width:150px;"><a style="text-decoration:none;color:#CCC;font-size:20px;font-family:Dosis;" href="https://ziteboard.com" target="_blank">Zoom & Move</a></div><iframe seamless="seamless" style="position:relative;width: 100%; height: 100%;top:-40px;" src="https://view.ziteboard.com/shared/48016458719512" frameborder="0" allowfullscreen></iframe></div>


							</div>
							<?php endif; ?>

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('footer_scripts'); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>