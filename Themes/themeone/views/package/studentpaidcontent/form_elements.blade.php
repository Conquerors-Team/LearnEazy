


 					 <fieldset class="form-group col-md-6">

						{{ Form::label('title', getphrase('title')) }}
						<span class="text-red">*</span>
						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('series_title'),
							'ng-model'=>'title',
							'ng-pattern'=>getRegexPattern('name'),
							'required'=> 'true',
							'ng-class'=>'{"has-error": formQuiz.title.$touched && formQuiz.title.$invalid}',
							'ng-minlength' => '2',
							'ng-maxlength' => '40',
							)) }}
						<div class="validation-error" ng-messages="formQuiz.title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('pattern')!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
						</div>
					</fieldset>

					<fieldset class="form-group col-md-6">
						<?php $opts = array('active' =>'Active', 'inactive' => 'Inactive');?>
						{{ Form::label('status', getphrase('status')) }}
						<span class="text-red">*</span>
						{{Form::select('status', $opts, null, ['class'=>'form-control', 'ng-model' => 'status'])}}
					</fieldset>

					<fieldset class="form-group col-md-6">
						<?php $opts = array('1' =>'Yes', '0' => 'No');?>
						{{ Form::label('is_paid', getphrase('is_paid')) }}
						<span class="text-red">*</span>
						{{Form::select('is_paid', $opts, null, ['class'=>'form-control', 'ng-model' => 'is_paid'])}}
					</fieldset>

					<fieldset class="form-group col-md-6" ng-if="is_paid == 1">
							{{ Form::label('cost', getphrase('package_price')) }}
							<span class="text-red">*</span>
							{{ Form::number('cost', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('cost'),
							'ng-model'=>'cost',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formQuiz.cost.$touched && formQuiz.cost.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formQuiz.cost.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>
					</fieldset>

					<fieldset class="form-group col-md-6" ng-if="is_paid == 1">
							{{ Form::label('free_trail_days', getphrase('free_trail_days')) }}
							<!-- <span class="text-red">*</span> -->
							{{ Form::number('free_trail_days', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('free_trail_days'),
							'ng-model'=>'free_trail_days',
							'ng-class'=>'{"has-error": formQuiz.free_trail_days.$touched && formQuiz.free_trail_days.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formQuiz.free_trail_days.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>
					</fieldset>


					 <fieldset class="form-group col-md-6" ng-if="is_paid == 1">
							{{ Form::label('duration', getphrase('duration')) }}

							<span class="text-red">*</span>

							{{ Form::number('duration', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('enter_duration'),
								'min'=>1,
							'ng-model'=>'duration',

							'required'=> 'true',

							'ng-class'=>'{"has-error": formQuiz.duration.$touched && formQuiz.duration.$invalid}',



							)) }}

						<div class="validation-error" ng-messages="formQuiz.duration.$error" >

	    					{!! getValidationMessage()!!}

	    					{!! getValidationMessage('number')!!}

						</div>

					</fieldset>



				<fieldset class="form-group col-md-6" ng-if="is_paid == 1">

						<?php $duration_type = array('Day' =>'Day', 'Week' => 'Week','Month' => 'Month','Year' => 'Year' );?>

						{{ Form::label('duration_type', getphrase('duration_type')) }}

						<span class="text-red">*</span>

						{{Form::select('duration_type', $duration_type, null, ['class'=>'form-control'])}}



					</fieldset>

					<fieldset class="form-group col-md-6">
							{{ Form::label('short_description', getphrase('short_description')) }}
							<span class="text-red">*</span>
							{{ Form::textarea('short_description', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('short_description'),
							'ng-model'=>'short_description',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formQuiz.short_description.$touched && formQuiz.short_description.$invalid}',
							)) }}
						<div class="validation-error" ng-messages="formQuiz.short_description.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>
					</fieldset>

					<fieldset class="form-group col-md-4" >
				   {{ Form::label('image', getphrase('image')) }}
				         <input type="file" class="form-control" name="image"
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





<fieldset class="form-group col-md-12" >
						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button"
							>{{ $button_name }}</button>
						</div>
</fieldset>
