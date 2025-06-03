 					<fieldset class="form-group">

						{{ Form::label('name', getphrase('name')) }}

						<span class="text-red">*</span>

						{{ Form::text('name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('name'),

							'ng-model'=>'name',

                             'required'=> 'true',

							'ng-class'=>'{"has-error": instituteBranch.name.$touched && instituteBranch.name.$invalid}',

						 ))}}

						  <div class="validation-error" ng-messages="instituteBranch.name.$error" >

	    					{!! getValidationMessage()!!}

                         </div>

					</fieldset>



					<fieldset class="form-group">

							<label for="description">{{getPhrase('description')}}</label>

						   {{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("description"),

									'ng-model'=>'description',

									'rows'=>'3',

									'cols'=>'15',


									'ng-class'=>'{"has-error": instituteBranch.description.$touched && instituteBranch.description.$invalid}',


								)) }}

									<div class="validation-error" ng-messages="instituteBranch.description.$error" >

										{!! getValidationMessage()!!}

									</div>
					</fieldset>


                     <div class="buttons text-center">

						<button class="btn btn-lg btn-success button" ng-disabled='!instituteBranch.$valid'

						>{{ $button_name }}</button>

					</div>

