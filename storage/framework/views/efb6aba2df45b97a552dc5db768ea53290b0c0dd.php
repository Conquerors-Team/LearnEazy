<?php $__env->startSection('header_scripts'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div id="page-wrapper" ng-controller="batchesController">

			<div class="container-fluid">

				<!-- Page Heading -->

				<div class="row">

					<div class="col-lg-12">

						<ol class="breadcrumb">

							<li><a href="<?php echo e(PREFIX); ?>"><i class="mdi mdi-home"></i></a> </li>
						  <?php if(canDo('institute_batch_access')): ?>
              <li><a href="<?php echo e(URL_BATCHS); ?>"><?php echo e(getPhrase('batches')); ?></a></li>
              <?php endif; ?>

							<li><?php echo e($title); ?></li>

						</ol>

					</div>

				</div>

				<!-- /.row -->

				<div class="panel panel-custom" ng-init="setPreSelectedData('<?php echo e($record->user_id); ?>','<?php echo e($record->institute_id); ?>','<?php echo e($record->id); ?>')">

					<div class="panel-heading">

						<h1><?php echo e($title); ?></h1>

					</div>


	<?php echo Form::open(array('url' => URL_UPDATE_STUDENT_TO_BATCH, 'method' => 'POST', 'name'=>'htmlform ','id'=>'htmlform', 'novalidate'=>'')); ?>


					<div class="panel-body instruction">

                     <div>


                   <div ng-show="result_data.length>0" class="row">

					   <div class="col-sm-4 col-sm-offset-8">
					            <div class="input-group">
					                    <input type="text" ng-model="search" class="form-control input-lg" placeholder="<?php echo e(getPhrase('search')); ?>" name="search" />
					                    <span class="input-group-btn">
					                        <button class="btn btn-primary btn-lg" type="button">
					                            <i class="glyphicon glyphicon-search"></i>
					                        </button>
					                    </span>
					                </div>
					        </div>
					 </div>
					 <br>

   <div ng-if="result_data.length!=0">
   <div>

    <div class="row vertical-scroll">



    <table class="table table-bordered" style="border-collapse: collapse;">
    <thead>

        <th style="border:1px solid #000;text-align: center;"><b><?php echo e(getPhrase('sno')); ?></b></th>
        <th style="border:1px solid #000;text-align: center;"><b><?php echo e(getPhrase('name')); ?></b></th>
        <th style="border:1px solid #000;text-align: center;"><b><?php echo e(getPhrase('email')); ?></b></th>
        <?php if(checkRole(getUserGrade(9))): ?>
        <th style="border:1px solid #000;text-align: center;"><b><?php echo e(getPhrase('Remove_all')); ?></b>
        	<input type="checkbox" name="add_all" value="<?php echo e($record->id); ?>" style="display: block;" ng-click="toggleSelect()">
        </th>
        <?php endif; ?>


    </thead>
    <tbody>

    <tr ng-repeat="user in result_data | filter:search track by $index">


             <td style="border:1px solid #000;text-align: center;" >{{$index+1}}</td>
            <td style="border:1px solid #000;text-align: center;"><a target="_blank" href="<?php echo e(URL_USER_DETAILS); ?>{{user.slug}}">{{user.name}}</a></td>

        <td style="border:1px solid #000;text-align: center;">{{user.email}}</td>
        <?php if(checkRole(getUserGrade(9))): ?>
        <td style="border:1px solid #000;text-align: center;">
        	 <input id="{{user.id}}" value="{{user.id}}" name="user_ids[]" type="checkbox" style="display: block;"
        	 ng-model="user.mycheck" >
        </td>
        <?php endif; ?>






    </tr>

    </tbody>
    </table>
</div>
 </div>
 <?php if(checkRole(getUserGrade(9))): ?>
	<button ng-if="result_data.length!= 0" class="btn btn-primary pull-right" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"><?php echo e(getPhrase('remove_students')); ?></button>
  <?php endif; ?>

<br>
  </div>
<div ng-if="result_data.length == 0 " class="text-center" ><?php echo e(getPhrase('no_data_available')); ?></div>


					 </div>



					</div>

			<input type="hidden" name="batch_id" value="<?php echo e($record->id); ?>">

					<?php echo Form::close(); ?>



				</div>

			</div>

			<!-- /.container-fluid -->




<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm" style="width: 600px;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" align="center"><?php echo e(getPhrase('remove_students_from_batch')); ?></h4>
      </div>
      <div class="modal-body">
        <h4 align="center"><?php echo e(getPhrase('are_you_sure_to_remove_students_from_this_batch')); ?></h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-right" ng-click="printIt()"><?php echo e(getPhrase('yes')); ?></button>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?php echo e(getPhrase('no')); ?></button>
      </div>
    </div>

  </div>
</div>

		</div>

<?php $__env->stopSection(); ?>





<?php $__env->startSection('footer_scripts'); ?>

   <?php echo $__env->make('batches.scripts.js-scripts-1', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>