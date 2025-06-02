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
              <?php if(canDo('institute_view')): ?>
							<li><a href="<?php echo e(URL_VIEW_INSTITUES); ?>"><?php echo e(getPhrase('institutes')); ?></a> </li>
              <?php endif; ?>
							<li><?php echo e($title); ?></li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">


						<h1><?php echo e($title); ?> <?php echo e(getPhrase('details')); ?></h1>
					</div>
					<div class="panel-body packages">
						<div >
						<table class="table">
							<tbody>
								<tr>
									<td><b><?php echo e(getPhrase('institute_name')); ?></b></td>
									<td><?php echo e($title); ?></td>

									<td><b><?php echo e(getPhrase('institute_address')); ?></b></td>
									<td><?php echo e($record->institute_address); ?></td>
								</tr>

								<tr>
									<td><b><?php echo e(getPhrase('admin_name')); ?></b></td>
									<td><?php echo e(ucwords($user->name)); ?></td>

									<td><b><?php echo e(getPhrase('email')); ?></b></td>
									<td><?php echo e($user->email); ?></td>
								</tr>

								<tr>
									<td><b><?php echo e(getPhrase('phone_number')); ?></b></td>
									<td><?php echo e($user->phone); ?></td>
									<td><b><?php echo e(getPhrase('address')); ?></b></td>
									<td><?php echo e($user->address); ?></td>
								</tr>
							</tbody>

						</table>

            <div class="row">

              <span class="label label-info label-many">
                <?php
                echo implode('</span>&nbsp;|&nbsp;<span class="label label-info label-many"> ', $record->permissions->pluck('title')->toArray());
                ?>
              </span>
            </div>

            <div class="row">Change Status To:</div>
						</div class="row">

                     <?php if( checkRole(getUserGrade(1))): ?>

                       <?php if(!$is_superadmin): ?>

                       


                            <?php if( $record->status == 0 || $record->status == 2 ): ?>

                                <?php if( $record->status != BLOCK ): ?>

                            <a href="javascript:void(0)" class="btn btn-sm btn-success button" onclick="updateInstitute('<?php echo e($record->id); ?>','approve')"><?php echo e(getPhrase('approve')); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;

                                <?php endif; ?>

                            <?php endif; ?>

                           

                            <?php if( $record->status == 0 || $record->status == APPROVE): ?>

                            <a href="javascript:void(0)" class="btn btn-sm btn-danger button" onclick="updateInstitute('<?php echo e($record->id); ?>','block')"><?php echo e(getPhrase('block')); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;

                            <?php elseif( $record->status == BLOCK ): ?>

                           <a href="javascript:void(0)" class="btn btn-sm btn-info button" onclick="updateInstitute('<?php echo e($record->id); ?>','unblock')"><?php echo e(getPhrase('un_block')); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;

                           <?php endif; ?>

                       

                         <?php endif; ?>

                       <?php endif; ?>

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
        <h4 class="modal-title text-center"><span id='msg1'></span> <?php echo e(getPhrase('institute')); ?></h4>
      </div>
      <div class="modal-body">

      <?php echo Form::open(array('url'=> URL_UPDATE_INSTITUTE_STATUS,'method'=>'POST','name'=>'userstatus')); ?>


      <h4 class="text-center" id="msg2"></h4>

        <input type="hidden" name="institute_id" id="institute_id" >
        <input type="hidden" name="status" id="status" >

        <fieldset class="form-group col-sm-12">

             <?php echo e(Form::label('comments', getphrase('comments'))); ?>


             <?php echo e(Form::textarea('comments', $value = null , $attributes = array('class'=>'form-control','rows'=>3, 'cols'=>'15', 'placeholder' => getPhrase('please_enter_your_comments')
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


<script>

 		function updateInstitute(institute_id,status){


           $('#institute_id').val(institute_id);

           $('#status').val(status);

           if(status == 'approve'){

           	 $('#msg1').html('Approve');
           	 $('#msg2').html('Are You Sure To Approve This Institute');
           }
           if(status == 'reject'){

           	 $('#msg1').html('Reject');
           	 $('#msg2').html('Are You Sure To Reject This Institute');
           }
           if(status == 'block'){

           	 $('#msg1').html('Block');
           	 $('#msg2').html('Are You Sure To Block This Institute');
           }
           if(status == 'unblock'){

           	 $('#msg1').html('Unblock');
           	 $('#msg2').html('Are You Sure To Unblock This Institute');
           }

           $('#myModal').modal('show');
 		}




 </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>