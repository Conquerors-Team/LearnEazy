<link rel="stylesheet" type="text/css" href="<?php echo e(CSS); ?>select2.css">

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
				<div class="panel panel-custom col-lg-6 col-lg-offset-3">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							<?php if(canDo('permission_access')): ?>
							<a href="<?php echo e(URL_PERMISSIONS); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('list')); ?></a>
							<?php endif; ?>
						</div>
					<h1><?php echo e($title); ?>  </h1>
					</div>
					<div class="panel-body  form-auth-style" >
					<?php $button_name = getPhrase('create'); ?>
					<?php if($record): ?>
					 <?php $button_name = getPhrase('update'); ?>
						<?php echo e(Form::model($record,
						array('url' => URL_INSTITUTE_SET_PERMISSION.$record->id,
						'method'=>'patch',  'novalidate'=>'','name'=>'registrationForm','name'=>'formLanguage '))); ?>

					<!-- <?php else: ?>
						<?php echo Form::open(array('url' => URL_ADD_INSTITUTE_REGISTER, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'',  'name'=>"registrationForm")); ?> -->
					<?php endif; ?>
                 <!--    @dd(record); -->

 					 <fieldset class="form-group">

                        	<label for="institute_name"><?php echo e(getPhrase('institute_name')); ?></label>
                        	<span style="color: red;">*</span>

						   <?php echo e(Form::text('institute_name', $value = $ins_name , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("institute_name"),

									'ng-model'=>'institute_name',

									'ng-pattern' => getRegexPattern('name'),

									'disabled'=> 'true',

									'ng-class'=>'{"has-error": registrationForm.institute_name.$touched && registrationForm.institute_name.$invalid}',

									'ng-minlength' => '4',

								))); ?>




                        </fieldset>


					<fieldset class="form-group">
						<?php echo e(Form::label('permissions', getphrase('permissions'))); ?>

						<button type="button" class="btn btn-primary btn-xs" id="selectbtn-permissions">
					        <?php echo e(getPhrase('select_all')); ?>

					    </button>
					    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-permissions">
					        <?php echo e(getPhrase('deselect_all')); ?>

					    </button>
						<span class="text-red">*</span>
						<?php
						$permissions = \App\Permission::get()->pluck('title', 'id')->toArray();
						?>
						<?php echo e(Form::select('permissions[]', $permissions, null, ['class'=>'form-control select2', 'name'=>'permissions[]', 'multiple'=>'true', 'id' => 'permissions', 'required' => 'true'])); ?>

						<div class="validation-error" ng-messages="formCategories.permissions.$error" >
	    					<?php echo getValidationMessage(); ?>

						</div>
					</fieldset>

					</fieldset>
						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button"
							><?php echo e($button_name); ?></button>
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
  <?php echo $__env->make('common.validations', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>;
  <?php echo $__env->make('common.alertify', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <script src="<?php echo e(JS); ?>select2.js"></script>
  <script>
      $('.select2').select2({
       placeholder: "Please select",
    });

    $("#selectbtn-permissions").click(function(){
        $("#permissions > option").prop("selected","selected");
        $("#permissions").trigger("change");
    });
    $("#deselectbtn-permissions").click(function(){
        $("#permissions > option").prop("selected","");
        $("#permissions").trigger("change");
    });

    </script>

<!--  <script>
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
 </script> -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>