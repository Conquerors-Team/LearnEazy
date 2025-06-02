<?php $__env->startSection('content'); ?>
<!-- ======= Hero Section ======= -->
	  <section id="hero">
		<div class="container">
			<div class="text-center pt-3 pt-lg-5">
				<h3 class="d-none">Welcome to the THE BEST LEARNING PLATFORM FOR CLASSES 8TH – 12TH</h3>
				<h1 data-aos="fade-up">Experiential <span>Learning</span></h1>
				<h2 data-aos="fade-up" data-aos-delay="400" class="px-5">Guided by the best teaching methodolgy and teachers</h2>
			</div>
		</div>
	  </section> <!-- End Hero -->

	   <!-- ======= Hero Section Part II ======= -->
	  <section id="half-n-half">
		<div class="container">
			<div id="laptop" class="position-relative">
				<img src="<?php echo e(themes('site/img/laptop.png')); ?>" class="img-fluid">
				<video autoplay muted loop>
					<source src="<?php echo e(themes('site/vid/laptop.mp4')); ?>" type="video/mp4">
					Your browser does not support the video tag.
				</video>
			</div>
		</div>
	  </section> <!-- End Hero Part II -->

	<section id="" class="container pb-0">
		<div class="row">
			<div class="col-12 offset-md-3 col-md-6">
				<h3 style="font-weight:600; color:var(--accent-1);">Let's Get Started</h3>
				<?php echo Form::open(array('route' => ['user-otp.register'], 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"loginform", 'name'=>"registrationForm")); ?>

                <?php echo $__env->make('errors.errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<div class="input-group">
					<input type="text" id="phone" name="phone" class="form-control" placeholder="Phone number" aria-label="Your phone number" aria-describedby="btn-enrol" value="<?php echo e(old('phone')); ?>">
					<div class="input-group-append">
						<button type="submit" class="btn btn-le-dark" type="button" id="btn-enrol">Enrol</button>
					</div>
				</div>
				</form>
			</div>
		</div>
	</section>


	  <main id="main">

		<!-- ======= Features Section ======= -->
		<section id="features" class="features pb-0">
		  <div class="container">

			<div class="section-title">
			  <h3>Our Awesome <span>Tools & Methods</span></h3>
			  <p>We as a team belive every child deserves quality education enriched with experiential learning guided by best teaching methodolgy and teachers. This platform provides the best learning solutions for the students in the pursuit of realizing their dreams.</p>
			  <img src="<?php echo e(themes('site/img/reading.png')); ?>" class="img-fluid mt-4">
			</div>

			<div class="row">
				<div class="col-12 focus text-center">
					<h3>Enrol with us</h3>
				</div>
				<div class="col-6 timeline-left"></div>
				<div class="col-6 timeline-right"></div>
			</div>

			<div class="row">
				<div class="col-12 focus d-none d-md-block text-center">
					<h1><i class="icofont-verification-check"></i></h1>
				</div>
				<div class="col-6 d-none d-md-block timeline-left">
					<img src="<?php echo e(themes('site/img/content.png')); ?>" class="img-fluid" style="margin-top:-50px; max-height:340px;">
				</div>
				<div class="col-6 d-none d-md-block timeline-right">
					<h2>Simplified Learning Content</h2>
					<p>Theory behind various concepts is presented in synopsis with a great simplification for easy understanding and memory. The flow of concepts helps form the mind map in easier way for the learner.</p>
				</div>
				<div class="col-12 text-center d-md-none">
					<img src="<?php echo e(themes('site/img/content.png')); ?>" class="img-fluid my-3" style="max-height:200px;">
					<h4>Simplified Learning Content</h4>
					<p>Theory behind various concepts is presented in synopsis with a great simplification for easy understanding and memory. The flow of concepts helps form the mind map in easier way for the learner.</p>
				</div>
				<div class="col-6 d-md-none timeline-left"></div>
				<div class="col-6 d-md-none timeline-right"></div>
			</div>

			<div class="row">
				<div class="col-12 focus d-none d-md-block text-center">
					<h1><i class="icofont-verification-check"></i></h1>
				</div>
				<div class="col-6 d-none d-md-block timeline-left">
					<h2>Interactive Animations</h2>
					<p>In scientific learning activity precedes theoretical conclusions. The interactive animations help learner to play around to understand complex concepts which are hard to visualize otherwise.</p>
				</div>
				<div class="col-6 d-none d-md-block timeline-right">
					<img src="<?php echo e(themes('site/img/interactions.png')); ?>" class="img-fluid" style="margin-top:-50px; max-height:360px;">
				</div>
				<div class="col-12 text-center d-md-none">
					<img src="<?php echo e(themes('site/img/interactions.png')); ?>" class="img-fluid my-3" style="max-height:200px;">
					<h4>Interactive Animations</h4>
					<p>In scientific learning activity precedes theoretical conclusions. The interactive animations help learner to play around to understand complex concepts which are hard to visualize otherwise.</p>
				</div>
				<div class="col-6 d-md-none timeline-left"></div>
				<div class="col-6 d-md-none timeline-right"></div>
			</div>

			<div class="row">
				<div class="col-12 focus d-none d-md-block text-center">
					<h1><i class="icofont-verification-check"></i></h1>
				</div>
				<div class="col-6 d-none d-md-block timeline-left">
					<img src="<?php echo e(themes('site/img/exam.png')); ?>" class="img-fluid" style="margin-top:-50px; max-height:340px;">
				</div>
				<div class="col-6 d-none d-md-block timeline-right">
					<h2>Engaging Tests</h2>
					<p>In these tests learner typically must answer the question by experimenting with animations. This test gives a chance to understand the concepts by experimenting with the animations as these aren’t time bound.</p>
				</div>
				<div class="col-12 text-center d-md-none">
					<img src="<?php echo e(themes('site/img/exam.png')); ?>" class="img-fluid my-3" style="max-height:200px;">
					<h4>Engaging Tests</h4>
					<p>In these tests learner typically must answer the question by experimenting with animations. This test gives a chance to understand the concepts by experimenting with the animations as these aren’t time bound.</p>
				</div>
				<div class="col-6 d-md-none timeline-left"></div>
				<div class="col-6 d-md-none timeline-right"></div>
			</div>

			<div class="row">
				<div class="col-12 focus d-none d-md-block text-center">
					<h1><i class="icofont-verification-check"></i></h1>
				</div>
				<div class="col-6 d-none d-md-block timeline-left">
					<h2>Self Assessment</h2>
					<p>The questions of the bank with proper tagging can assess the student’s logical and analytical skills to let him know where he stands in the journey of learning.The tests offer best assessment of the students learning gaps.</p>
				</div>
				<div class="col-6 d-none d-md-block timeline-right">
					<img src="<?php echo e(themes('site/img/assess.png')); ?>" class="img-fluid" style="margin-top:-50px; max-height:340px;">
				</div>
				<div class="col-12 text-center d-md-none">
					<img src="<?php echo e(themes('site/img/assess.png')); ?>" class="img-fluid my-3" style="max-height:200px;">
					<h4>Self Assessment</h4>
					<p>The questions of the bank with proper tagging can assess the student’s logical and analytical skills to let him know where he stands in the journey of learning.The tests offer best assessment of the students learning gaps.</p>
				</div>
				<div class="col-6 d-md-none timeline-left"></div>
				<div class="col-6 d-md-none timeline-right"></div>
			</div>

			<div class="row">
				<div class="col-12 focus text-center">
					<h3>Achieve Unbelievable results</h3>
					<img src="<?php echo e(themes('site/img/win.png')); ?>" class="img-fluid d-block mx-auto mt-3" style="max-height:250px;">
				</div>
			</div>

		  </div>
		</section><!-- End Features Section -->


		<!-- ======= Screenshots Section ======= -->
		<section id="screenshots" class="screenshots bg-navy">
			<div class="container">
				<div class="section-title">
					<img src="<?php echo e(themes('site/img/en_badge_web_generic.png')); ?>" style="height:60px; width:auto;">
					<h3>Now Available in <span style="color:var(--body-light);">Play Store</span></h3>
					<p style="color:var(--body-light)">The app is a breeze to navigate, and packed with all the tools you need for your success.</p>
				</div>

				<div class="carousel slide carousel-fade mx-auto" style="max-width:300px;" data-ride="carousel">
					<div class="carousel-inner">
						<div class="carousel-item active">
							<img src="<?php echo e(themes('site/img/sc_home.png')); ?>" class="d-block w-100">
						</div>
						<div class="carousel-item">
							<img src="<?php echo e(themes('site/img/sc_learn.png')); ?>" class="d-block w-100">
						</div>
						<div class="carousel-item">
							<img src="<?php echo e(themes('site/img/sc_anim1.png')); ?>" class="d-block w-100">
						</div>
					</div>
				</div>
			</div>

		</section><!-- End Screenshots Section -->

	  </main><!-- End #main -->


<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.sitelayoutnew', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>