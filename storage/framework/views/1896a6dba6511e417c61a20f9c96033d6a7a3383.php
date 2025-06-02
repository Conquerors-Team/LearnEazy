<?php $__env->startSection('content'); ?>

<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="<?php echo e(PREFIX); ?>"><i class="mdi mdi-home"></i></a> </li>
							<?php if(canDo('lms_content_access')): ?>
							<li><a href="<?php echo e(URL_LMS_CONTENT); ?>">LMS <?php echo e(getPhrase('contents')); ?></a></li>
							<?php endif; ?>
							<li class="active"><?php echo e(isset($title) ? $title : ''); ?></li>
						</ol>
					</div>
				</div>
					<?php echo $__env->make('errors.errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<!-- /.row -->
				<?php
					$settings = ($record) ? $settings : '';
				?>

				<div class="panel panel-custom col-lg-6 col-lg-offset-3" ng-init="initAngData('<?php echo e($settings); ?>');" ng-controller="angLmsController">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							<?php if(canDo('lms_content_access')): ?>
							<a href="<?php echo e(URL_LMS_CONTENT); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('list')); ?></a>
							<?php endif; ?>
						</div>
					<h1><?php echo e($title); ?>  </h1>
					</div>
					<div class="panel-body" >
					<?php $button_name = getPhrase('create'); ?>
					<?php if($record): ?>
					 <?php $button_name = getPhrase('update'); ?>
						<?php echo e(Form::model($record,
						array('url' => URL_LMS_CONTENT_EDIT. $record->slug, 'novalidate'=>'','name'=>'formLms ',
						'method'=>'patch', 'files' => true))); ?>

					<?php else: ?>
						<?php echo Form::open(array('url' => URL_LMS_CONTENT_ADD,
							'novalidate'=>'','name'=>'formLms ',
						'method' => 'POST', 'files' => true)); ?>

					<?php endif; ?>
					 <?php echo $__env->make('lms.lmscontents.form_elements',
					 array('button_name'=> $button_name),
					 array('subjects'=>$subjects,
					 'chapters' => $chapters,
					 'topics' => $topics,
					 'record'=>$record), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

					<?php echo Form::close(); ?>

					</div>

				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer_scripts'); ?>
<?php echo $__env->make('lms.lmscontents.scripts.js-scripts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('common.validations', array('isLoaded'=>'1'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>;
<?php echo $__env->make('common.editor', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>;
  <?php echo $__env->make('common.alertify', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
   <script>
 	var file = document.getElementById('image_input');

file.onchange = function(e){
    var ext = this.value.match(/\.([^\.]+)$/)[1];
    switch(ext)
    {
        case 'jpg':
        case 'jpeg':
        case 'png':


            break;
        default:
               alertify.error("<?php echo e(getPhrase('file_type_not_allowed')); ?>");
            this.value='';
    }
};
 </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>