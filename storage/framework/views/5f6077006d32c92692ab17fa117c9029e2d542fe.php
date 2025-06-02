 <?php $__env->startSection('custom_div'); ?>

 <div ng-controller="prepareQuestions">

 <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div id="page-wrapper">

			<div class="container-fluid">

				<!-- Page Heading -->

				<div class="row">

					<div class="col-lg-12">

						<ol class="breadcrumb">

							<li><a href="<?php echo e(PREFIX); ?>"><i class="mdi mdi-home"></i></a> </li>

							<li><a href="<?php echo e(URL_LMS_SERIES); ?>"><?php echo e(getPhrase('lms_series')); ?></a></li>

							<li class="active"><?php echo e(isset($title) ? $title : ''); ?></li>

						</ol>

					</div>

				</div>

					<?php echo $__env->make('errors.errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

				<?php $settings = ($record) ? $settings : ''; ?>

				<div class="panel panel-custom" ng-init="initAngData(<?php echo e($settings); ?>);" >

					<div class="panel-heading">

						<div class="pull-right messages-buttons">

							<a href="<?php echo e(URL_LMS_SERIES); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('list')); ?></a>

						</div>

					<h1><?php echo e($title); ?>  </h1>

					</div>

					<div class="panel-body" >

					<?php $button_name = getPhrase('create'); ?>

					 		<div class="row">









<?php echo Form::open(array('url' => URL_LMS_SERIES_UPDATE_SERIES_EXAMS . $record->slug, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'',  'name'=>"registrationForm")); ?>

								<div class="col-md-12">

							<div class="vertical-scroll" >

								<table

								  class="table table-hover">

								  <thead>
								  <tr>
									<th><?php echo e(getPhrase('title')); ?></th>

									<th><?php echo e(getPhrase('code')); ?></th>

									<th><?php echo e(getPhrase('type')); ?></th>

									<th><?php echo e(getPhrase('quiz')); ?></th>
								</tr>
								</thead>

								<tbody>

									<?php
									if(checkRole(getUserGrade(3))){
										$quizzes = \App\Quiz::select('quizzes.*')
										->join('quizzes_subjects AS qs', 'qs.quiz_id', '=', 'quizzes.id')
										->where('qs.subject_id', $record->subject_id)
										->where('quizzes.category_id', QUIZTYPE_LMS)
										->get()->pluck('title', 'id')->prepend('Select quiz', '')->toArray();
									} elseif(shareData('share_exams')){
										$quizzes = \App\Quiz::select('quizzes.*')
										->join('quizzes_subjects AS qs', 'qs.quiz_id', '=', 'quizzes.id')
										->where('qs.subject_id', $record->subject_id)
										->where('quizzes.category_id', QUIZTYPE_LMS)
										->whereIn('institute_id', [adminInstituteId(), OWNER_INSTITUTE_ID])->get()->pluck('title', 'id')->prepend('Select quiz', '')->toArray();
									}else {
										$quizzes = \App\Quiz::select('quizzes.*')
										->join('quizzes_subjects AS qs', 'qs.quiz_id', '=', 'quizzes.id')
										->where('qs.subject_id', $record->subject_id)
										->where('quizzes.category_id', QUIZTYPE_LMS)
										->where('institute_id', adminInstituteId())
										->get()->pluck('title', 'id')->prepend('Select quiz', '')->toArray();
									}

									?>

									<?php $__empty_1 = true; $__currentLoopData = $series; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
										<tr>
											<td><?php echo e($item->title); ?></td>
											<td><?php echo e($item->code); ?></td>
											<td><?php echo e($item->content_type); ?></td>
											<td>
												<?php
												$quiz_id = null;
												if ( $item->quiz_id ) {
													$quiz_id = $item->quiz_id;
												}
												?>
												<?php echo e(Form::select('quizzes['.$item->lmscontent_id.']', $quizzes, $quiz_id, ['class'=>'form-control'
												])); ?>

												<input type="hidden" name="sort_order[]" value="<?php echo e($item->lmscontent_id); ?>">
											</td>
										</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

									<?php endif; ?>

								</tbody>
								</table>

								</div>


								<div class="buttons text-center">

							<button class="btn btn-lg btn-success button" type="submit">Update</button>

						</div>


					 			</div>


<?php echo Form::close(); ?>



					 		</div>



					</div>



				</div>

			</div>

			<!-- /.container-fluid -->

		</div>

		<!-- /#page-wrapper -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
	$('tbody').sortable();
</script>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('custom_div_end'); ?>

 </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>