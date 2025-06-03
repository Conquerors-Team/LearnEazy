
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

						{{ Form::label('description', getphrase('description')) }}

						{{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control', 'rows'=>'5', 'placeholder' => 'Description')) }}
					</fieldset>

                    <fieldset class="form-group">
						{{ Form::label('subjects', getphrase('subjects')) }}
						<button type="button" class="btn btn-primary btn-xs" id="selectbtn-subjects">
					        {{ getPhrase('select_all') }}
					    </button>
					    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-subjects">
					        {{ getPhrase('deselect_all') }}
					    </button>
						<span class="text-red">*</span>
						<?php

						$subjects =\App\BoardSubject::get()->pluck('title', 'id')->toArray();
						?>
						{{Form::select('subjects[]', $subjects, null, ['class'=>'form-control select2', 'name'=>'subjects[]', 'multiple'=>'true', 'id' => 'subjects', 'required' => 'true'])}}
						<div class="validation-error" ng-messages="formCategories.subjects.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>


						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button"
							ng-disabled='!formCategories.title.$valid'>{{ $button_name }}</button>
						</div>
