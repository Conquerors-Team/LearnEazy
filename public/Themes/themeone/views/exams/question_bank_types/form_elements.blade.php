 					<fieldset class="form-group">

						{{ Form::label('title', getphrase('title')) }}

						<span class="text-red">*</span>

						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('title'),

							'ng-model'=>'title',

                             'required'=> 'true',

							'ng-class'=>'{"has-error": instituteBranch.name.$touched && instituteBranch.name.$invalid}',

						 ))}}

						  <div class="validation-error" ng-messages="instituteBranch.name.$error" >

	    					{!! getValidationMessage()!!}

                         </div>

					</fieldset>

					<fieldset class="form-group">

							<label for="description">{{getPhrase('description')}}</label>
							<span style="color: red;">*</span>

						   {{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("description"),

									'ng-model'=>'description',

									'rows'=>'3',

									'cols'=>'15',

									'required'=> 'true',


									'ng-class'=>'{"has-error": instituteBranch.description.$touched && instituteBranch.description.$invalid}',


								)) }}

									<div class="validation-error" ng-messages="instituteBranch.description.$error" >

										{!! getValidationMessage()!!}

									</div>
					</fieldset>
						<fieldset class="form-group">

						{{ Form::label('status', getphrase('status')) }}

						<span class="text-red">*</span>

						{{ Form::text('status', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('status'),

							'ng-model'=>'status',

                             'required'=> 'true',

							'ng-class'=>'{"has-error": instituteBranch.status.$touched && instituteBranch.status.$invalid}',

						 ))}}

						  <div class="validation-error" ng-messages="instituteBranch.status.$error" >

	    					{!! getValidationMessage()!!}

                         </div>

					</fieldset>


                     <div class="buttons text-center">

						<button class="btn btn-lg btn-success button" ng-disabled='!instituteBranch.$valid'

						>{{ $button_name }}</button>

					</div>

