<link href="<?php echo e(CSS); ?>bootstrap-datepicker.css" rel="stylesheet">

<?php $__env->startSection('content'); ?>
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="/"><i class="mdi mdi-home"></i></a> </li>
							<?php if(canDo('onlineclasses_access')): ?>
							<li><a href="<?php echo e(URL_ADMIN_ONLINECLASSES); ?>"><?php echo e(getPhrase('notifications')); ?></a></li>
							 <?php endif; ?>
							<li class="active"><?php echo e(isset($title) ? $title : ''); ?></li>
						</ol>
					</div>
				</div>
					<?php echo $__env->make('errors.errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<!-- /.row -->

		 <div class="panel panel-custom col-lg-8 col-lg-offset-2">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							<?php if(canDo('onlineclasses_access')): ?>
							<a href="<?php echo e(URL_ADMIN_ONLINECLASSES); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('list')); ?></a>
							<?php endif; ?>
						</div>

					<h1><?php echo e($title); ?>  </h1>
					</div>
					<div class="panel-body" >
					<?php $button_name = getPhrase('create'); ?>
					<?php if($record): ?>
					 <?php $button_name = getPhrase('update'); ?>
						<?php echo e(Form::model($record,
						array('url' => URL_ADMIN_ONLINECLASSES_EDIT.$record->slug,
						'method'=>'patch', 'name'=>'formNotifications ', 'novalidate'=>''))); ?>

					<?php else: ?>
						<?php echo Form::open(array('url' => URL_ADMIN_ONLINECLASSES_ADD, 'method' => 'POST', 'name'=>'formNotifications', 'novalidate'=>'')); ?>

					<?php endif; ?>


					 <?php echo $__env->make('onlineclasses.form_elements',
					 array('button_name'=> $button_name),
					 array('record' 		=> $record), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

					<?php echo Form::close(); ?>

					</div>

				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
<?php echo $__env->make('common.validations', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>;
<?php echo $__env->make('onlineclasses.scripts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>;
   
<?php echo $__env->make('common.editor', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>;
<script src="<?php echo e(JS); ?>datepicker.min.js"></script>
 <script>
 	  $('.input-daterange').datepicker({
        autoclose: true,
        // startDate: "0d",
        format: '<?php echo e(getDateFormat()); ?>',
    });
 </script>
<!--  <script src="<?php echo e(JS); ?>select2.js"></script>
  <script>
      $('.select2').select2({
       placeholder: "Please select",
    });

    $("#selectbtn-packages").click(function(){
        $("#packages > option").prop("selected","selected");
        $("#packages").trigger("change");
    });
    $("#deselectbtn-packages").click(function(){
        $("#packages > option").prop("selected","");
        $("#packages").trigger("change");
    });

    </script> -->

<?php $__env->stopSection(); ?>


<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>