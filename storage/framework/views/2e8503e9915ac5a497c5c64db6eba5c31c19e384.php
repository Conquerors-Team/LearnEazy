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
							<a href="<?php echo e(URL_INSTITUTE_REGISTER); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('add_institute')); ?></a>
						</div>

						<h1><?php echo e($title); ?></h1>
					</div>
					<div class="panel-body packages">
						<div >
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>

									<th><?php echo e(getPhrase('name')); ?></th>
									<th><?php echo e(getPhrase('institute_name')); ?></th>
									<th><?php echo e(getPhrase('address')); ?></th>
									<th><?php echo e(getPhrase('status')); ?></th>
								 	<th><?php echo e(getPhrase('action')); ?></th>

								</tr>
							</thead>

						</table>
						</div>

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->


	<div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center"><?php echo e(getPhrase('approve_institute')); ?></h4>
      </div>
      <div class="modal-body">

      <?php echo Form::open(array('url'=> URL_UPDATE_INSTITUTE_STATUS,'method'=>'POST','name'=>'userstatus')); ?>


      <h4 class="text-center"><?php echo e(getPhrase('are_you_sure_to_approve_this_institute')); ?></h4>

        <input type="hidden" name="institute_id" id="institute_id" >
        <input type="hidden" name="status" value="approve" >

          <fieldset class="form-group col-sm-12">

             <?php echo e(Form::label('comments', getphrase('comments'))); ?>


             <?php echo e(Form::textarea('comments', $value = null , $attributes = array('class'=>'form-control','rows'=>3, 'cols'=>'15', 'placeholder' => getPhrase('please_enter_your_address')
             ))); ?>


      </fieldset>


      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-right" >Yes</button>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">No</button>
      </div>
      <?php echo Form::close(); ?>


    </div>

  </div>
</div>

		</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('footer_scripts'); ?>

 <?php echo $__env->make('common.datatables', array('route'=>URL_INSTITUTES_GETDATATABLE ,'route_as_url'=>TRUE,  'search_columns' => ['type' => $type] ), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

 <script>

 		function approveInstitute(institute_id){


           $('#institute_id').val(institute_id);

           $('#myModal').modal('show');
 		}

 </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.adminlayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>