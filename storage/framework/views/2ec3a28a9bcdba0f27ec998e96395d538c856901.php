<link href="<?php echo e(CSS); ?>bootstrap-datepicker.css" rel="stylesheet">
<?php $__env->startSection('content'); ?>
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="<?php echo e(PREFIX); ?>"><i class="mdi mdi-home"></i></a> </li>

							<?php if(canDo('institute_view')): ?>
						<li><a href="<?php echo e(URL_VIEW_INSTITUES); ?>"><?php echo e(getPhrase('institutes')); ?></a> </li>
						     <?php endif; ?>
							<li class="active"><?php echo e(isset($title) ? $title : ''); ?></li>
						</ol>
					</div>
				</div>
				<?php echo $__env->make('errors.errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<div class="panel panel-custom col-lg-8 col-lg-offset-2">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							<?php if(canDo('institute_view')): ?>
							<a href="<?php echo e(URL_VIEW_INSTITUES); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('list')); ?></a>
							<?php endif; ?>
						</div>
					<h1><?php echo e($title); ?>  </h1>
					</div>
					<div class="panel-body  form-auth-style" >
					<?php $button_name = getPhrase('create'); ?>
					<?php if($record): ?>
					 <?php $button_name = getPhrase('update'); ?>

						<?php echo e(Form::model($record,
						array('url' => URL_EDIT_INSTITUTE_DETAILS.$record->institute_id,
						'method'=>'patch',  'novalidate'=>'','name'=>'registrationForm','name'=>'formLanguage '))); ?>


					<?php else: ?>


						<?php echo Form::open(array('url' => URL_ADD_INSTITUTE_REGISTER, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'',  'name'=>"registrationForm")); ?>

					<?php endif; ?>

					 <?php echo $__env->make('institutes.registration',
					 array(['button_name'=> $button_name,'record'=>$record,'ins_name'=>$ins_name,'ins_address'=>$ins_address]), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
					<?php echo Form::close(); ?>

					</div>

				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer_scripts'); ?>
  <?php echo $__env->make('common.validations', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <script src="<?php echo e(JS); ?>datepicker.min.js"></script>

   <script>

 	  $('.datepicker1').datepicker({
        autoclose: true,
        startDate: "0d",
         format: '<?php echo e(getDateFormat()); ?>',
    });
 </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.adminlayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>