
 					 <fieldset class="form-group">

						{{ Form::label('title', getphrase('title')) }}
						<span class="text-red">*</span>
						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('enter_title'),
							'ng-model'=>'title',
							'ng-pattern' => getRegexPattern('name'),
							'ng-minlength' => '2',
							'ng-maxlength' => '60',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formCategories.title.$touched && formCategories.title.$invalid}',

							)) }}
							<div class="validation-error" ng-messages="formCategories.title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
	    					{!! getValidationMessage('pattern')!!}
						</div>
					</fieldset>

					<fieldset class="form-group">
                        {{ Form::label('status', getphrase('status')) }}
						<span class="text-red">*</span>
						<?php
						$status = [
							1 => 'active',
							0 => 'inactive',
						];
						?>
						{{Form::select('status', $status, null, ['class'=>'form-control',
							'ng-model'=>'status',
							'required'=> 'ture',
							'ng-class'=>'{"has-error": formCategories.status.$touched && formCategories.status.$invalid}'

						 ])}}
						  <div class="validation-error" ng-messages="formCategories.status.$error" >
	    					{!! getValidationMessage()!!}
	    				</div>
					</fieldset>

					<fieldset class="form-group">
						{{ Form::label('board', getphrase('board')) }}

						<span class="text-red">*</span>
						<?php

						$boards =\App\Board::get()->pluck('title', 'id')->toArray();
						?>
						{{Form::select('board_id', $boards, $value = null, ['class'=>'form-control',
							'ng-model'=>'board_id',
							'ng-class'=>'{"has-error": formCategories.board_id.$touched && formCategories.board_id.$invalid}'

						 ])}}
						<div class="validation-error" ng-messages="formCategories.subjects.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

					<fieldset class="form-group">
						{{ Form::label('class', getphrase('class')) }}

						<span class="text-red">*</span>
						<?php

						$classes =\App\BoardClass::get()->pluck('title', 'id')->toArray();
						?>
						{{Form::select('board_class_id', $classes, $value = null, ['class'=>'form-control',
							'ng-model'=>'board_class_id',
							'ng-class'=>'{"has-error": formCategories.board_class_id.$touched && formCategories.board_class_id.$invalid}'

						 ])}}
						<div class="validation-error" ng-messages="formCategories.subjects.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

                    	<fieldset class="form-group">
						{{ Form::label('subjects', getphrase('subjects')) }}

						<span class="text-red">*</span>
						<?php

						$subjects =\App\BoardSubject::get()->pluck('title', 'id')->toArray();
						?>
						{{Form::select('subject_id', $subjects, $value = null, ['class'=>'form-control',
							'ng-model'=>'subject_id',
							'ng-class'=>'{"has-error": formCategories.subject_id.$touched && formCategories.subject_id.$invalid}'

						 ])}}
						<div class="validation-error" ng-messages="formCategories.subjects.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

                    <fieldset class="form-group" >
				   {{ Form::label('fileinput', getphrase('attachments')) }}
				         <input type="file" class="form-control" name="fileinput"
				         accept=".png,.jpg,.jpeg,.pdf,.docx" id="image_input">


				    </fieldset>
				     <fieldset class="form-group" >
					@if($record)
				   		@if($record->
				   		file_input)
				         <?php $examSettings = getExamSettings(); ?>

                          <a href="{{route('site.media-file-download', ['model' => 'BoardChapter', 'field' => 'file_input', 'record_id' => $record->id])}}" target="_blank">{{$record->file_input}}</a>
				         @endif
				     @endif


				    </fieldset>
						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button"
							ng-disabled='!formCategories.title.$valid'>{{ $button_name }}</button>
						</div>
