 <?php $settings = getSettings('lms');
 ?>

				<div class="row">
 					 <fieldset class="form-group col-md-6">

						<?php echo e(Form::label('title', getphrase('title'))); ?>

						<span class="text-red">*</span>
						<?php echo e(Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'C Programming',
						'ng-model'=>'title',
							'required'=> 'true',
							'ng-pattern' => getRegexPattern("name"),
							'ng-minlength' => '2',
							'ng-class'=>'{"has-error": formLms.title.$touched && formLms.title.$invalid}',

						))); ?>

						<div class="validation-error" ng-messages="formLms.title.$error" >
	    					<?php echo getValidationMessage(); ?>

	    					<?php echo getValidationMessage('minlength'); ?>

	    					<?php echo getValidationMessage('pattern'); ?>

						</div>
					</fieldset>

					 <fieldset class="form-group col-md-6">

						<?php echo e(Form::label('code', getphrase('code'))); ?>


						<?php echo e(Form::text('code', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'CNT100',
							'ng-model'=>'code',
							'ng-pattern' => getRegexPattern("name"),
							'ng-minlength' => '2',
							'ng-maxlength' => '20',
							'ng-class'=>'{"has-error": formLms.code.$touched && formLms.code.$invalid}',

						))); ?>

						<div class="validation-error" ng-messages="formLms.code.$error" >
	    					<?php echo getValidationMessage(); ?>

	    					<?php echo getValidationMessage('minlength'); ?>

	    					<?php echo getValidationMessage('maxlength'); ?>

	    					<?php echo getValidationMessage('pattern'); ?>

						</div>
					</fieldset>


 					<fieldset class="form-group col-md-6">
						<?php echo e(Form::label('subject_id', getphrase('subject'))); ?>

						<span class="text-red">*</span>
						<?php echo e(Form::select('subject_id', $subjects, null, [
						'class'=>'form-control',
						'onChange' => 'getSubjectChapters()',
						])); ?>

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
						<?php echo e(Form::label('topic_id', 'Topic')); ?>

						<span class="text-red">*</span>
						<?php echo e(Form::select('topic_id', $topics, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'id'=>'topic_id',
						'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.topic_id.$touched && formLms.topic_id.$invalid}',
						])); ?>

						<div class="validation-error" ng-messages="formLms.topic_id.$error" >
	    					<?php echo getValidationMessage(); ?>

						</div>
					</fieldset>

					 <fieldset class="form-group  col-md-6"   >
				   <?php echo e(Form::label('image', getphrase('image'))); ?>

				         <input type="file" class="form-control" name="image"
				          accept=".png,.jpg,.jpeg" id="image_input">

				    </fieldset>

					 <fieldset class="form-group col-md-6"   >
					<?php if($record): ?>
				   		<?php if($record->image): ?>
				         <img src="<?php echo e(IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->image); ?>" height="100" width="100">
				         <?php endif; ?>
				     <?php endif; ?>
				    </fieldset>

			</div>


					<div  class="row">
				 	 <fieldset class="form-group col-md-6" >
						<?php echo e(Form::label('content_type', getphrase('content_type'))); ?>

						<span class="text-red">*</span>
						<?php echo e(Form::select('content_type', $settings->content_types, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'ng-model'=>'content_type',
							'required'=> 'true',
							'ng-pattern' => getRegexPattern("name"),
							'ng-minlength' => '2',
							'ng-maxlength' => '20',
							'ng-class'=>'{"has-error": formLms.content_type.$touched && formLms.content_type.$invalid}',

						])); ?>

						<div class="validation-error" ng-messages="formLms.content_type.$error" >
	    					<?php echo getValidationMessage(); ?>

						</div>


					</fieldset>

					 <fieldset ng-if="content_type=='url' || content_type=='iframe' || content_type=='video_url'|| content_type=='audio_url'" class="form-group col-md-6">
							<?php echo e(Form::label('file_path', getphrase('resource_link'))); ?>

							<span class="text-red">*</span>
							<?php echo e(Form::text('file_path', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Resource URL',
								'ng-model'=>'file_path',
								'required'=> 'true',
								'ng-class'=>'{"has-error": formLms.file_path.$touched && formLms.file_path.$invalid}',

						))); ?>

						<div class="validation-error" ng-messages="formLms.file_path.$error" >
	    					<?php echo getValidationMessage(); ?>

						</div>
						</fieldset>



					<fieldset ng-if="content_type=='file' || content_type=='video' || content_type=='audio' || content_type == 'animation'" class="form-group col-md-6">
							<?php echo e(Form::label('lms_file', getphrase('lms_file'))); ?>

							<span class="text-red">*</span>
							 <input type="file"
							 class="form-control"
							 name="lms_file"  >
					</fieldset>


					<?php if($record): ?>
						<?php if($record->file_path!=''): ?>
						<fieldset class="form-group col-md-6">
							<label>   &nbsp;</label>
						 <?php echo e(link_to_asset(IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->file_path, getPhrase('download'))); ?>

						 </fieldset>
						<?php endif; ?>
					<?php endif; ?>

					</div>

					<fieldset class="form-group">

						<?php echo e(Form::label('description', getphrase('description'))); ?>


						<?php echo e(Form::textarea('description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'5', 'placeholder' => 'Fine description'))); ?>

					</fieldset>




						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button"
							ng-disabled='!formLms.$valid'><?php echo e($button_name); ?></button>
						</div>
