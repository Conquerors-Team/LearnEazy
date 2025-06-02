



					<div class="row">

 					 <fieldset class="form-group col-md-6">
						<?php echo e(Form::label('title', getphrase('title'))); ?>

						<span class="text-red">*</span>
						<?php echo e(Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('series_title'),
							'ng-model'=>'title',
							'ng-pattern'=>getRegexPattern('name'),
							'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.title.$touched && formLms.title.$invalid}',
							'ng-minlength' => '2',
							))); ?>

						<div class="validation-error" ng-messages="formLms.title.$error" >
	    					<?php echo getValidationMessage(); ?>

	    					<?php echo getValidationMessage('pattern'); ?>

	    					<?php echo getValidationMessage('minlength'); ?>

	    					<?php echo getValidationMessage('maxlength'); ?>

						</div>
					</fieldset>




					<fieldset class="form-group col-md-3" >
						<?php echo e(Form::label('subject_id', 'Subject')); ?>

						<span class="text-red">*</span>

						<?php
						$institute_id   = adminInstituteId();
						$subjects = \App\Subject::query();
						if(shareData('share_subjects')){
							$subjects->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();
						} else {
							$subjects->where('institute_id', $institute_id)->get();
						}
						$subjects = $subjects->pluck('subject_title', 'id')->prepend('Please select', '')->toArray();
						?>
						<?php echo e(Form::select('subject_id', $subjects, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'ng-model'=>'subject_id',
						'onChange' => 'getSubjectChapters()',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.subject_id.$touched && formLms.subject_id.$invalid}',
						])); ?>

						<div class="validation-error" ng-messages="formLms.subject_id.$error" >
	    					<?php echo getValidationMessage(); ?>

						</div>
					</fieldset>

					<fieldset class="form-group col-md-4" >
						<?php echo e(Form::label('chapter_id', 'Chapter')); ?>

						<span class="text-red">*</span>
						<?php echo e(Form::select('chapter_id', $chapters, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'id'=>'chapter_id',
						'onChange' => 'getChaptersTopics()',
						'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.chapter_id.$touched && formLms.chapter_id.$invalid}',
						])); ?>

						<div class="validation-error" ng-messages="formLms.chapter_id.$error" >
	    					<?php echo getValidationMessage(); ?>

						</div>
					</fieldset>

					<fieldset class="form-group col-md-4" >
						<?php echo e(Form::label('topic_id', 'Topic/Subtopic')); ?>

						<?php echo e(Form::select('topic_id', $topics, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'id'=>'topic_id',
						'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.topic_id.$touched && formLms.topic_id.$invalid}',
						])); ?>

						<div class="validation-error" ng-messages="formLms.topic_id.$error" >
	    					<?php echo getValidationMessage(); ?>

						</div>
					</fieldset>

					<fieldset class="form-group col-md-3" >
						<?php echo e(Form::label('quiz_id', 'Quiz')); ?>

						<span class="text-red">*</span>
						<?php
						$quizzes = \App\Quiz::where('institute_id', adminInstituteId())->get()->pluck('title', 'id')->toArray();
						?>
						<?php echo e(Form::select('quiz_id', $quizzes, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'ng-model'=>'quiz_id',
							'ng-class'=>'{"has-error": formLms.quiz_id.$touched && formLms.quiz_id.$invalid}',
						])); ?>

						<div class="validation-error" ng-messages="formLms.quiz_id.$error" >
	    					<?php echo getValidationMessage(); ?>

						</div>
					</fieldset>

				    </div>



				<div  class="row">



					<input type="hidden" name="is_paid" value="0" ng-model="is_paid">





					<div ng-if="is_paid==1">

	  				 <fieldset class="form-group col-md-3">



							<?php echo e(Form::label('validity', getphrase('validity'))); ?>


							<span class="text-red">*</span>

							<?php echo e(Form::number('validity', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('validity_in_days'),

							'ng-model'=>'validity',
							'string-to-number'=>'true',

							'min'=>'-1',



							'required'=> 'true',
							'string-to-number'=>'true',
							'ng-class'=>'{"has-error": formLms.validity.$touched && formLms.validity.$invalid}',



							))); ?>


						<div class="validation-error" ng-messages="formLms.validity.$error" >

	    					<?php echo getValidationMessage(); ?>


	    					<?php echo getValidationMessage('number'); ?>


						</div>

					</fieldset>

	  				 <fieldset class="form-group col-md-3">



						<?php echo e(Form::label('cost', getphrase('cost'))); ?>


						<span class="text-red">*</span>

						<?php echo e(Form::number('cost', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '40',

							'min'=>'0',



						'ng-model'=>'cost',

						'required'=> 'true',
						'string-to-number'=>'true',
						'ng-class'=>'{"has-error": formLms.cost.$touched && formLms.cost.$invalid}',



							))); ?>


						<div class="validation-error" ng-messages="formLms.cost.$error" >

	    					<?php echo getValidationMessage(); ?>


	    					<?php echo getValidationMessage('number'); ?>


						</div>

				</fieldset>

				</div>

				</div>

				<div class="row">

				 <fieldset class="form-group col-md-6">



							<?php echo e(Form::label('total_items', getphrase('total_items'))); ?>


							<span class="text-red">*</span>

							<?php echo e(Form::text('total_items', $value = null , $attributes = array('class'=>'form-control','readonly'=>'true' ,'placeholder' => getPhrase('It will be updated by adding the LMS items')))); ?>


					</fieldset>



 					<fieldset class="form-group col-md-4" >

				   <?php echo e(Form::label('image', getphrase('image'))); ?>


				         <input type="file" class="form-control" name="image"
				          accept=".png,.jpg,.jpeg" id="image_input">



				         <div class="validation-error" ng-messages="formCategories.image.$error" >

	    					<?php echo getValidationMessage('image'); ?>




						</div>

				    </fieldset>



				     <fieldset class="form-group col-md-2" >

					<?php if($record): ?>

				   		<?php if($record->image): ?>

				         <?php $examSettings = getExamSettings(); ?>

				         <img src="<?php echo e(IMAGE_PATH_UPLOAD_LMS_SERIES.$record->image); ?>" height="100" width="100" >



				         <?php endif; ?>

				     <?php endif; ?>

				    </fieldset>

			    </div>



			<div class="row input-daterange" id="dp">
				<?php
				$date_from = date('Y/m/d');
				$date_to = date('Y/m/d');
				if($record)
				{
					$date_from = $record->start_date;
					$date_to = $record->end_date;
				}
				 ?>
				 <fieldset class="form-group col-md-6">
					<?php echo e(Form::label('start_date', getphrase('start_date'))); ?>

					<?php echo e(Form::text('start_date', $value = $date_from , $attributes = array('class'=>'input-sm form-control', 'placeholder' => '2015/7/17'))); ?>

				</fieldset>

				<fieldset class="form-group col-md-6">
					<?php echo e(Form::label('end_date', getphrase('end_date'))); ?>

					<?php echo e(Form::text('end_date', $value = $date_to , $attributes = array('class'=>'input-sm form-control', 'placeholder' => '2015/7/17'))); ?>

				</fieldset>
			</div>

          <div class="row">
				<?php $options = array('1'=>'Yes', '0'=>'No');?>

					 <fieldset class="form-group col-md-12" >

						<?php echo e(Form::label('show_in_front', getphrase('show_in_home_page'))); ?>


						<span class="text-red">*</span>

						<?php echo e(Form::select('show_in_front', $options, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',

						    'ng-model'=>'show_in_front',

							'required'=> 'true',

							'ng-class'=>'{"has-error": formLms.show_in_front.$touched && formLms.show_in_front.$invalid}',



						])); ?>


						<div class="validation-error" ng-messages="formLms.show_in_front.$error" >

	    					<?php echo getValidationMessage(); ?>


						</div>

                </fieldset>
            </div>

 					<div class="row">

					<fieldset class="form-group  col-md-6">



						<?php echo e(Form::label('short_description', getphrase('short_description'))); ?>




						<?php echo e(Form::textarea('short_description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'5', 'placeholder' => getPhrase('short_description')))); ?>


					</fieldset>

					<fieldset class="form-group  col-md-6">



						<?php echo e(Form::label('description', getphrase('description'))); ?>




						<?php echo e(Form::textarea('description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'5', 'placeholder' => getPhrase('description')))); ?>


					</fieldset>



					</div>

						<div class="buttons text-center">

							<button class="btn btn-lg btn-success button"

							ng-disabled='!formLms.$valid'><?php echo e($button_name); ?></button>

						</div>


