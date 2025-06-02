
 <?php $settings = getSettings('lms');?>


					<div class="row">

 					 <fieldset class="form-group col-md-12">
						{{ Form::label('title', getphrase('title')) }}
						<span class="text-red">*</span>
						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('series_title'),

							'ng-pattern'=>getRegexPattern('name'),
							'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.title.$touched && formLms.title.$invalid}',
							'ng-minlength' => '2',
							)) }}
						<div class="validation-error" ng-messages="formLms.title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('pattern')!!}
	    					{!! getValidationMessage('minlength')!!}
						</div>
					</fieldset>



					<fieldset class="form-group col-md-4" >
						{{ Form::label('subject_id', 'Subject') }}
						<span class="text-red">*</span>

						{{Form::select('subject_id', $subjects, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'id'=>'subject_id',
						'onChange'=>'getSubjectChapters()',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.subject_id.$touched && formLms.subject_id.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.subject_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

					<fieldset class="form-group col-md-4" >
						{{ Form::label('chapter_id', 'Chapter') }}
						<span class="text-red">*</span>

						{{Form::select('chapter_id', $chapters, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'id'=>'chapter_id',
						'onChange' => 'getChaptersTopics()',
						'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.chapter_id.$touched && formLms.chapter_id.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.chapter_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

					<fieldset class="form-group col-md-4" >
						{{ Form::label('topic_id', 'Topic') }}
						<span class="text-red">*</span>

						{{Form::select('topic_id', $topics, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'id'=>'topic_id',
						'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.topic_id.$touched && formLms.topic_id.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.topic_id.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>


					 <?php
					 $content_type = '';
					 if($record) {
					 	$content_type = $record->content_type;
					 }
					 ?>
					 <fieldset class="form-group col-md-6" >
						{{ Form::label('content_type', getphrase('content_type')) }}
						<span class="text-red">*</span>
						{{Form::select('content_type', $settings->content_types, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
						'ng-model'=>'content_type',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formLms.content_type.$touched && formLms.content_type.$invalid}',
						]) }}
						<div class="validation-error" ng-messages="formLms.content_type.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>


					<fieldset ng-if="content_type=='url' || content_type=='iframe' || content_type=='video_url'|| content_type=='audio_url'" class="form-group col-md-6">
							{{ Form::label('file_path', getphrase('resource_link')) }}
							<span class="text-red">*</span>
							{{ Form::text('file_path', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Resource URL',
								'ng-model'=>'file_path',
								'required'=> 'true',
								'ng-class'=>'{"has-error": formLms.file_path.$touched && formLms.file_path.$invalid}',
						)) }}
						<div class="validation-error" ng-messages="formLms.file_path.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>




					<fieldset ng-if="content_type=='file' || content_type=='video' || content_type=='audio' || content_type == 'animation'" class="form-group col-md-6">
							{{ Form::label('lms_file', getphrase('lms_file')) }}
							<span class="text-red">*</span>
							 <input type="file"
							 class="form-control"
							 name="lms_file"  >
					</fieldset>


					@if($record)
						@if($record->resource_link!='')
						<fieldset class="form-group col-md-6">
							<label>&nbsp;</label>
						 {{link_to_asset(IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->resource_link, getPhrase('download'))}}
						 </fieldset>
						@endif
					@endif

				    </div>

				    <div class="row">
						<fieldset class="form-group col-md-12" >
						{{ Form::label('image', getphrase('image')) }}
						<input type="file" class="form-control" name="image"
						accept=".png,.jpg,.jpeg" id="image_input">
						<div class="validation-error" ng-messages="formCategories.image.$error" >
						{!! getValidationMessage('image')!!}
						</div>
						</fieldset>

				    	<fieldset class="form-group  col-md-12">
						{{ Form::label('description', getphrase('description')) }}
						{{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'5', 'placeholder' => getPhrase('description'))) }}
						</fieldset>
				    </div>





						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button"
							ng-disabled='!formLms.$valid'>{{ $button_name }}</button>
						</div>


