<?php $__env->startSection('header_scripts'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>


<div id="page-wrapper">
	<div class="container-fluid">

		<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="<?php echo e(PREFIX); ?>"><i class="mdi mdi-home"></i></a> </li>
							<li><?php echo e($title); ?></li>
						</ol>
					</div>
				</div>

					<?php if(checkRole(getUserGrade(2))): ?>
						<?php echo $__env->make('common.search-form', ['url' => url()->current(), 'show_content_types' => 'FALSE', 'show_chapters' => 'FALSE', 'show_topics' => 'FALSE', 'show_sub_topics' => 'FALSE','show_batch_assigned'=> 'FALSE','show_question_actegory'=>'FALSE','show_difficulty_level'=>'FALSE'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				    <?php endif; ?>


			<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<h1><?php echo e($title); ?></h1>
					</div>
					<div class="panel-body packages">
						<div>
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><?php echo e(getPhrase('chapters_and_topics')); ?></th>
								</tr>
							</thead>

							<tbody>


								<?php $__currentLoopData = $chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<?php
											$topics = App\Topic::where('chapter_id',$chapter->id)->get();
										?>

								<tr>


									<td>
										<p>Subject: <?php echo e($chapter->subject->subject_title); ?>(<?php echo e($chapter->subject_id); ?>)</p>
										<p>Chapter: <?php echo e($chapter->chapter_name); ?>(<?php echo e($chapter->id); ?>)</p>
										<p>Topics:</p>
										<ol>
										<?php $__currentLoopData = $topics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<li><?php echo e($topic->topic_name); ?>(<?php echo e($topic->id); ?>)</li>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</ol>
									</td>

								</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</tbody>



						</table>
						</div>

					</div>
				</div>

		</div>
	</div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('footer_scripts'); ?>
	<?php echo $__env->make('common.filter-scripts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>