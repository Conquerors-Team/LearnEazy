 <?php $__env->startSection('header_scripts'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(CSS); ?>select2.css">
 <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="/"><i class="mdi mdi-home"></i></a> </li>
							<li class="active"><?php echo e(isset($title) ? $title : ''); ?></li>
						</ol>
					</div>
				</div>
					<?php echo $__env->make('errors.errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<!-- /.row -->

				<div class="panel panel-custom col-lg-12" >
					<div class="panel-heading">
					<h1><?php echo e($title); ?>  </h1>
					</div>
					<div class="panel-body" >
					<?php $button_name = getPhrase('update'); ?>
					<?php echo e(Form::model($record,
						array('url' => url()->current(),
						'method'=>'post', 'files' => true, 'name'=>'frmBatches', 'novalidate'=>''))); ?>

						<div class="row">
							<?php
							if(canDo('email_alerts') && canDo('sms_alerts')) {
								$alerts = \App\Alert::where('status', 'active')->orderBy('name')->get();
							} elseif(canDo('sms_alerts')) {
								$alerts = \App\Alert::where('status', 'active')->where('type', 'SMS')->orderBy('name')->get();
							} else {
								$alerts = \App\Alert::where('status', 'active')->where('type', 'email')->orderBy('name')->get();
							}
							?>
							<?php $__empty_1 = true; $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<fieldset class="form-group col-lg-6">
								<?php echo e(Form::label('name', $alert->name . ' ('.$alert->type.')')); ?>

							</fieldset>
							<?php if( $alert->select_type == 'batch'): ?>
							<fieldset class="form-group col-lg-6">
								<?php echo e(Form::label('batches', getphrase('batches'))); ?>

								<button type="button" class="btn btn-primary btn-xs" id="selectbtn-batches_<?php echo e($alert->id); ?>">
							        <?php echo e(getPhrase('select_all')); ?>

							    </button>
							    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-batches_<?php echo e($alert->id); ?>">
							        <?php echo e(getPhrase('deselect_all')); ?>

							    </button>
								<span class="text-red">*</span>
								<?php
								$institute_id   = adminInstituteId();
								$batches = \App\Batch::where('status', 'active')->where('institute_id', $institute_id)->get()->pluck('name', 'id')->toArray();
								$selected_batches = \App\AlertEnabled::where('alert_id', $alert->id)->get()->pluck('batch_id')->toArray();
								//print_r($alert->alerts_enabled);
								?>
								<?php echo e(Form::select('batches['.$alert->id.'][]', $batches, $selected_batches, ['class'=>'form-control select2', 'name'=>'batches['.$alert->id.'][]', 'multiple'=>'true', 'id' => 'batches_' .$alert->id, 'required' => 'true'])); ?>

								<div class="validation-error" ng-messages="frmBatches.batches.$error" >
			    					<?php echo getValidationMessage(); ?>

								</div>
							</fieldset>
							<?php elseif( $alert->select_type == 'text'): ?>
							<fieldset class="form-group col-lg-6">
								<?php echo e(Form::label('enter', getphrase('enter'))); ?> in minutes
								<span class="text-red">*</span>
								<?php
								$selected_value = \App\AlertEnabled::where('alert_id', $alert->id)->where('institute_id', $institute_id)->first();
								if ( $selected_value ) {
									$selected_value = $selected_value->batch_id;
								}
								//print_r($alert->alerts_enabled);
								?>
								<?php echo e(Form::text('batches['.$alert->id.'][]', $selected_value, ['class'=>'form-control', 'name'=>'batches['.$alert->id.'][]', 'id' => 'batches_' .$alert->id, 'required' => 'true'])); ?>

								<div class="validation-error" ng-messages="frmBatches.batches.$error" >
			    					<?php echo getValidationMessage(); ?>

								</div>
							</fieldset>
							<?php else: ?>
							<fieldset class="form-group col-lg-6">
								<?php echo e(Form::label('select', getphrase('select'))); ?>

								<span class="text-red">*</span>
								<?php
								$options = [
									'no' => 'No',
									'yes' => 'Yes',
								];
								$selected_options = \App\AlertEnabled::where('alert_id', $alert->id)->where('institute_id', $institute_id)->get()->pluck('batch_id')->toArray();
								//print_r($alert->alerts_enabled);
								?>
								<?php echo e(Form::select('batches['.$alert->id.'][]', $options, $selected_options, ['class'=>'form-control select2', 'name'=>'batches['.$alert->id.'][]', 'id' => 'batches_' .$alert->id, 'required' => 'true'])); ?>

								<div class="validation-error" ng-messages="frmBatches.batches.$error" >
			    					<?php echo getValidationMessage(); ?>

								</div>
							</fieldset>
							<?php endif; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<p>No Alerts</p>
							<?php endif; ?>
					</div>

					<div class="buttons text-center">

							<button class="btn btn-lg btn-success button"

							ng-disabled='!frmBatches.$valid'><?php echo e($button_name); ?></button>

						</div>

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
<script src="<?php echo e(JS); ?>select2.js"></script>

 <script>
 	  $('.select2').select2({
       placeholder: "Please select",
    });

 	<?php $__empty_1 = true; $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
 	$("#selectbtn-batches_<?php echo e($alert->id); ?>").click(function(){
        $("#batches_<?php echo e($alert->id); ?> > option").prop("selected","selected");
        $("#batches_<?php echo e($alert->id); ?>").trigger("change");
    });
    $("#deselectbtn-batches_<?php echo e($alert->id); ?>").click(function(){
        $("#batches_<?php echo e($alert->id); ?> > option").prop("selected","");
        $("#batches_<?php echo e($alert->id); ?>").trigger("change");
    });
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <?php endif; ?>

 </script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>