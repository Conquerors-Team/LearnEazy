<?php if( $subject ): ?>
<?php $__env->startSection('header_scripts'); ?>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
<!-- <link rel="stylesheet" type="text/css" href="https://kenwheeler.github.io/slick/slick/slick-theme.css"/> -->

<style type="text/css">
  <?php if( ! empty( $subject->color_code ) ): ?>
  .nav-tabs {
    border-bottom: 1px solid <?php echo e($subject->color_code); ?> !important;
  }
  .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    border-top:    1px solid <?php echo e($subject->color_code); ?> !important;
    border-right:  1px solid <?php echo e($subject->color_code); ?> !important;
    border-left: 1px solid <?php echo e($subject->color_code); ?> !important;
  }
  .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
    background: <?php echo e($subject->color_code); ?> !important;
  }
  <?php endif; ?>
  .package-details{
    padding: 10px;
    border: 1px solid;
    border-radius: 5px;
  }
</style>

<?php $__env->stopSection(); ?>
<?php endif; ?>

<?php $__env->startSection('content'); ?>

<?php if( $subject ): ?>
<style>

  h2{
  text-align:center;
  padding: 20px;
}
/* Slider */





    .slick-slide {
      margin: 0px 20px;
    }

    .slick-slide img {
      width: 100%;
    }

    .slick-prev:before,
    .slick-next:before {
      color: black;
    }


    .slick-slide {
      transition: all ease-in-out .3s;
      opacity: .2;
      border: 3px solid <?php echo e($subject->color_code); ?>;
      border-radius: 15px;
      padding: 10px;
      text-align:center;
    }

    .slick-active {
      opacity: .6;
    }

    .slick-current {
      opacity: 1;
    }
</style>
<?php endif; ?>

  <div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					 <li><a href="<?php echo e(PREFIX); ?>"><i class="mdi mdi-home"></i></a> </li>
					 <li><a href="<?php echo e(route('lms-groups.index')); ?>">LMS groups</a> </li>
					 <?php if( $subject ): ?>
					 <li><a href="<?php echo e(route('lms-groups.show', ['slug' => $group->slug])); ?>"><?php echo e($group->title); ?></a> </li>
					 <?php endif; ?>
					<li><?php echo e($title); ?></li>
				</ol>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel-heading">Details of  <?php if($subject): ?> <?php echo e($subject->subject_title); ?> in <?php endif; ?> <font color="green"><?php echo e($group->title); ?> Group</font>
				</div>
				<div class="panel-body" >
					<?php
					$institute_id   = adminInstituteId();

			 		$faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
			 		$lmssubjects = \App\LmsGroup::select(['subjects.*', 'lmsgroups.slug as group_slug'])
			 			->join('lmsseries_lmsgroups', 'lmsseries_lmsgroups.lmsgroups_id', '=', 'lmsgroups.id')
			 			->join('lmsseries', 'lmsseries.id', '=', 'lmsseries_lmsgroups.lmsseries_id')
						->join('subjects', 'subjects.id', '=', 'lmsseries.subject_id')
			 			->whereIn('lmsseries.subject_id', $faculty_subjects)
			 			->where('lmsgroups.institute_id', $institute_id)
			 			->groupBy('lmsseries.subject_id')->orderBy('lmsgroups.updated_at', 'desc')->get();
			 		?>

			 	<div class="row">
					<div class="col-md-12">
						<?php if( $subject_slug ): ?>
							 <div class="row library-items">
							 <?php $settings = getSettings('lms');
	                        $chapters = $subject->chapters()->select(['chapters.*'])
	                        ->join('lmsseries', 'lmsseries.chapter_id', '=', 'chapters.id')
	                        ->join('lmsseries_lmsgroups', 'lmsseries_lmsgroups.lmsseries_id', '=', 'lmsseries.id')
	                        ->join('lmsgroups', 'lmsgroups.id', '=', 'lmsseries_lmsgroups.lmsgroups_id')
	                        ->where('lmsgroups.slug', $group->slug)
	                        ->groupBy('lmsseries.chapter_id')
	                        ->get();
	                        /*
	                        echo printSql($subject->chapters()->select(['chapters.*'])
	                        ->join('lmsseries', 'lmsseries.chapter_id', '=', 'chapters.id')
	                        ->join('lmsseries_lmsgroups', 'lmsseries_lmsgroups.lmsseries_id', '=', 'lmsseries.id')
	                        ->join('lmsgroups', 'lmsgroups.id', '=', 'lmsseries_lmsgroups.lmsgroups_id')
	                        ->where('lmsgroups.slug', $group->slug)
	                        ->groupBy('lmsseries.chapter_id'));
	                        */
	                        ?>
	                        <?php $__empty_1 = true; $__currentLoopData = $chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
	                        <?php
	                        // $topics = $chapter->topics()->get();
	                        $lmsseries = $chapter->topics()->select(['lmsseries.*'])
	                        	->join('lmsseries', 'lmsseries.topic_id', '=', 'topics.id')
	                        	->join('lmsseries_lmsgroups', 'lmsseries_lmsgroups.lmsseries_id', '=', 'lmsseries.id')
	                        	->groupBy('lmsseries_lmsgroups.lmsseries_id')
	                        	->get();
	                        // dd( $chapters );
	                        ?>
	                        <div>
	                        <li class="list-group-item"><b style="color: <?php echo e($subject->color_code); ?>"><?php echo e($chapter->chapter_name); ?></b></li>
	                        </div>
	                        <?php if( $lmsseries->count() > 0 ): ?>
	                        <section class="customer-logos slider">
	                            <?php $__empty_2 = true; $__currentLoopData = $lmsseries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $single): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
	                                <div class="slide">
	                                    <?php if( isOnlinestudent() ): ?>
	                                        <?php if($single->is_paid && !isItemPurchased($single->id, 'paidcontent') ): ?>
	                                        <p>
	                                        <a  href="javascript:void(0);" onclick="suggestPackage('<?php echo e($single->slug); ?>', 'lmsseries')">Buy now</a>
	                                        </p>
	                                        <p style="font-size: x-large;"><?php echo e($single->title); ?></p>
	                                        <?php else: ?>
	                                        <p><a href="<?php echo e(route('studentlms.subjectitems', ['slug' => $subject->slug, 'series_slug' => $single->slug])); ?>" style="color: #337ab7;">View More</a></p>
	                                        <p style="font-size: x-large;"><?php echo e($single->title); ?></p>
	                                        <?php endif; ?>
	                                    <?php else: ?>
	                                    <p><a href="<?php echo e(route('studentlms.subjectitems', ['slug' => $subject->slug, 'series_slug' => $single->slug])); ?>" style="color: #337ab7;">View More</a></p>
	                                    <p style="font-size: x-large;"><?php echo e($single->title); ?></p>
	                                    <?php endif; ?>
	                                </div>
	                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
	                                <div class="slide"><p>No Series</p></div>
	                            <?php endif; ?>
	                        </section>
	                        <?php else: ?>
	                            <section class="customer-logos slider">
	                                <div class="slide"><p>No Series</p></div>
	                            </section>
	                        <?php endif; ?>
		                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
		                        No Chapters
		                    <?php endif; ?>
		                </div>
						<?php else: ?>
						<div class="white_bgcurve coursesList">
							<h5>Subjects</h5>
							<div class="white_bgcurve" style="clear:both; ">
								<?php $__empty_1 = true; $__currentLoopData = $lmssubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
								<?php
								$settings = getExamSettings();
								$image = $settings->defaultCategoryImage;
								if(isset($subject->image) && $subject->image!='') {
									$image = $subject->image;
								}
								?>
								<div class="col-md-3">
								<div class="item-image">
								<img src="<?php echo e(PREFIX.$settings->subjectsImagepath.$image); ?>" alt="<?php echo e($subject->subject_title); ?>" width="150">
								</div>
								<a href="<?php echo e(route('lms-groups.show', ['slug' => $subject->group_slug, 'subject_slug' => $subject->slug])); ?>" title="<?php echo e($subject->subject_title); ?>"><?php echo e($subject->subject_title); ?></a>
								</div>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
									<p>No Subjects</p>
								<?php endif; ?>
							</div>
						</div>
						<?php endif; ?>
					</div>
			</div>

				</div>
			</div>
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
<?php if( ! empty( $chart_data ) ): ?>
	<?php echo $__env->make('common.chart-stack', array('chart_data'=>$chart_data,'ids' =>array('batch_report_graph'), 'scale'=>TRUE), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>

<script src="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
<script >

    $(document).ready(function(){
    $('.slider').slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        //autoplay: true,
        //autoplaySpeed: 1500,
        arrows: true,
        dots: false,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 3
            }
        }]
    });
});


  </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>