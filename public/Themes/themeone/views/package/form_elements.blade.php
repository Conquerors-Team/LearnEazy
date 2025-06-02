



					<div class="row">

 					 <fieldset class="form-group col-md-6">

						{{ Form::label('title', getphrase('title')) }}

						<span class="text-red">*</span>

						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('title'),

							'ng-model'=>'title',

							'ng-pattern'=>getRegexPattern('name'),

							'required'=> 'true',

							'ng-class'=>'{"has-error": formQuiz.title.$touched && formQuiz.title.$invalid}',

							'ng-minlength' => '4',

							'ng-maxlength' => '60',

							)) }}

						<div class="validation-error" ng-messages="formQuiz.title.$error" >

	    					{!! getValidationMessage()!!}

	    					{!! getValidationMessage('pattern')!!}

	    					{!! getValidationMessage('minlength')!!}

	    					{!! getValidationMessage('maxlength')!!}

						</div>

					</fieldset>

					<fieldset class="form-group col-md-6">
							{{ Form::label('cost', getphrase('cost')) }}
							<span class="text-red">*</span>
							{{ Form::number('cost', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('cost'),
							'ng-model'=>'cost',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formQuiz.cost.$touched && formQuiz.cost.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formQuiz.number_of_logins.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>
					</fieldset>

					<fieldset class="form-group col-md-6">
							{{ Form::label('number_of_logins', getphrase('number_of_logins')) }}
							<span class="text-red">*</span>
							{{ Form::number('number_of_logins', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('enter_number_of_logins'),								'min'=>1,
							'ng-model'=>'number_of_logins',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formQuiz.number_of_logins.$touched && formQuiz.number_of_logins.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formQuiz.number_of_logins.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>
					</fieldset>

				 </div>

				 <div class="row">

					<fieldset class="form-group col-md-6">

						<?php $package_for = array('institute' => getPhrase('institute'), 'student' => getPhrase('student'), );?>

						{{ Form::label('package_for', getphrase('package_for')) }}

						<span class="text-red">*</span>

						{{Form::select('package_for', $package_for, null, ['class'=>'form-control'])}}



					</fieldset>

					 <fieldset class="form-group col-md-6">


                        <?php $trail_available = array('yes' => getPhrase('yes'), 'no' => getPhrase('no'), );?>

						{{ Form::label('trail_available', getphrase('trail_available')) }}

						<span class="text-red">*</span>

						{{Form::select('trail_available', $trail_available, null, ['class'=>'form-control'])}}

					</fieldset>

					</div>


				<div class="row">

					 <fieldset class="form-group col-md-6">



							{{ Form::label('trail_period_days', getphrase('trail_period_days')) }}

							<!-- <span class="text-red">*</span> -->

							{{ Form::number('trail_period_days', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('enter_trail_period_days'),
							'ng-model'=>'trail_period_days',
							'ng-class'=>'{"has-error": formQuiz.trail_period_days.$touched && formQuiz.trail_period_days.$invalid}',



							)) }}

						<div class="validation-error" ng-messages="formQuiz.trail_period_days.$error" >

	    					{!! getValidationMessage()!!}

	    					{!! getValidationMessage('number')!!}

						</div>

					</fieldset>



				<fieldset class="form-group col-md-6">

						<?php $is_default = array('yes' =>'yes', 'no' => 'no', );?>

						{{ Form::label('is_default', getphrase('is_default')) }}

						<span class="text-red">*</span>

						{{Form::select('is_default', $is_default, null, ['class'=>'form-control'])}}



					</fieldset>

				</div>

				<div class="row">

					 <fieldset class="form-group col-md-6">



							{{ Form::label('duration', getphrase('duration')) }}

							<span class="text-red">*</span>

							{{ Form::number('duration', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('enter_duration'),
								'min'=>1,
							'ng-model'=>'duration',

							'required'=> 'true',

							'ng-class'=>'{"has-error": formQuiz.duration.$touched && formQuiz.duration.$invalid}',



							)) }}

						<div class="validation-error" ng-messages="formQuiz.duration_type.$error" >

	    					{!! getValidationMessage()!!}

	    					{!! getValidationMessage('number')!!}

						</div>

					</fieldset>



				<fieldset class="form-group col-md-6">

						<?php $duration_type = array('Day' =>'Day', 'Week' => 'Week','Month' => 'Month','Year' => 'Year' );?>

						{{ Form::label('duration_type', getphrase('duration_type')) }}

						<span class="text-red">*</span>

						{{Form::select('duration_type', $duration_type, null, ['class'=>'form-control'])}}



					</fieldset>

					<fieldset class="form-group col-md-6">


                        <?php $status = array('active' => getPhrase('active'), 'inactive' => getPhrase('inactive'), );?>

						{{ Form::label('status', getphrase('status')) }}

						<span class="text-red">*</span>

						{{Form::select('status', $status, null, ['class'=>'form-control'])}}

					</fieldset>

					<fieldset class="form-group col-md-4" >
				    {{ Form::label('packimage', getphrase('image')) }}
				         <input type="file" class="form-control" name="packimage"
				         accept=".png,.jpg,.jpeg" id="image_input">
				    </fieldset>

				     <fieldset class="form-group col-md-2" >
				       @if($record)
				   		@if($record->image)
				         <?php $examSettings = getExamSettings(); ?>
				         <img src="{{ PREFIX.$examSettings->courseImagepath.$record->image }}" height="100" width="100" >

				         @endif
				     @endif
				    </fieldset>

				</div>

				 <div class="row">

					<fieldset class="form-group">
						{{ Form::label('permissions', getphrase('permissions')) }}
						<button type="button" class="btn btn-primary btn-xs" id="selectbtn-permissions">
					        {{ getPhrase('select_all') }}
					    </button>
					    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-permissions">
					        {{ getPhrase('deselect_all') }}
					    </button>
						<span class="text-red">*</span>
						<?php
						$permissions = \App\Permission::get()->pluck('title', 'id')->toArray();
						?>
						{{Form::select('permissions[]', $permissions, null, ['class'=>'form-control select2', 'name'=>'permissions[]', 'multiple'=>'true', 'id' => 'permissions', 'required' => 'true'])}}
						<div class="validation-error" ng-messages="formCategories.permissions.$error" >
	    					{!! getValidationMessage()!!}
						</div>
					</fieldset>

                    </div>



						<div class="buttons text-center">

							<button class="btn btn-lg btn-success button"

							ng-disabled='!formQuiz.$valid'>{{ $button_name }}</button>

						</div>

