<?php $__env->startSection('header_scripts'); ?>
<link href="<?php echo e(CSS); ?>ajax-datatables.css" rel="stylesheet">
<link href="<?php echo e(CSS); ?>bootstrap-datepicker.css" rel="stylesheet">
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
							<?php if(canDo('onlineclasses_create') && ! isStudent()): ?>
							<a href="<?php echo e(URL_ADMIN_ONLINECLASSES_ADD); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('create')); ?></a>&nbsp;|&nbsp;
							<a href="<?php echo e(route('onlineclasses.import')); ?>" class="btn  btn-primary button" ><?php echo e(getPhrase('import')); ?></a>
							<?php endif; ?>
						</div>

						<h1><?php echo e($title); ?></h1>
					</div>

					<div class="panel-body packages">
						<?php echo $__env->make('onlineclasses.search-form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
						<div>
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>

									<th><?php echo e(getPhrase('date')); ?></th>
									<th><?php echo e(getPhrase('time')); ?></th>
									<th><?php echo e(getPhrase('class')); ?></th>
									<th><?php echo e(getPhrase('batch')); ?></th>
									<th><?php echo e(getPhrase('subject')); ?></th>

									<th><?php echo e(getPhrase('topic')); ?></th>
									<?php if(isInstitute() || isStudent() ): ?>
									<th><?php echo e(getPhrase('faculty')); ?></th>
									<?php endif; ?>
									<th><?php echo e(getPhrase('url')); ?></th>
									<?php if(checkRole(getUserGrade(2))): ?>
									<th><?php echo e(getPhrase('action')); ?></th>
									<?php endif; ?>
								</tr>
							</thead>

						</table>
						</div>

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('footer_scripts'); ?>

 <?php echo $__env->make('common.datatables', array('route'=>URL_ADMIN_ONLINECLASSES_GETLIST, 'route_as_url' => TRUE, 'search_columns' => ['class_title' => request('class_title'), 'batch_id' => request('batch_id'), 'from_date' => request('from_date'), 'to_date' => request('to_date'), 'faculty_id' => request('faculty_id'), 'subject_id' => request('subject_id')]), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
 <?php echo $__env->make('common.deletescript', array('route'=>URL_ADMIN_ONLINECLASSES_DELETE), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<script src="<?php echo e(JS); ?>datepicker.min.js"></script>
<script type="text/javascript">
	$('.datepicker1').datepicker({
        autoclose: true,
        /*startDate: "0d",*/
        format: '<?php echo e(getDateFormat()); ?>',
    });

    function showInstructions(url) {
	  width = screen.availWidth;
	  height = screen.availHeight;
	  window.open(url,'_blank',"height="+height+",width="+width+", toolbar=no, top=0,left=0,location=no,menubar=no, directories=no, status=no, menubar=no, scrollbars=yes,resizable=no");

		runner();
	}

	function runner()
	{
		url = localStorage.getItem('redirect_url');
	    if(url) {
	      localStorage.clear();
	       window.location = url;
	    }
	    setTimeout(function() {
	          runner();
	    }, 500);

	}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>