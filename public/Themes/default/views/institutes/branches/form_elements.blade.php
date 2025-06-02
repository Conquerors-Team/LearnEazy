 					<fieldset class="form-group">

						{{ Form::label('institute_name', getphrase('institute_name')) }}

						<span class="text-red">*</span>

						{{ Form::text('institute_name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('institute_name'),

							'ng-model'=>'institute_name',

                             'required'=> 'true', 

							'ng-class'=>'{"has-error": instituteBranch.institute_name.$touched && instituteBranch.institute_name.$invalid}',

						 ))}}

						  <div class="validation-error" ng-messages="instituteBranch.institute_name.$error" >

	    					{!! getValidationMessage()!!}

                         </div>

					</fieldset>

					<fieldset class="form-group">

							<label for="institute_address">{{getPhrase('institute_address')}}</label>
							<span style="color: red;">*</span>

						   {{ Form::textarea('institute_address', $value = null , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("institute_address"),

									'ng-model'=>'institute_address',

									'rows'=>'3', 

									'cols'=>'15',

                                    'required'=> 'true', 

									'ng-class'=>'{"has-error": instituteBranch.institute_address.$touched && instituteBranch.institute_address.$invalid}',


								)) }}

									<div class="validation-error" ng-messages="instituteBranch.institute_address.$error" >

										{!! getValidationMessage()!!}

									</div>


					</fieldset>

					<fieldset class="form-group">
						
							<label for="phone">{{getPhrase('phone')}}</label>
							<span style="color: red;">*</span>

						   {{ Form::number('phone', $value = null , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("phone"),

									'ng-model'=>'phone',

                                    'required'=> 'true', 

									'ng-class'=>'{"has-error": instituteBranch.phone.$touched && instituteBranch.phone.$invalid}',
                               )) }}

									<div class="validation-error" ng-messages="instituteBranch.phone.$error" >

										{!! getValidationMessage()!!}

                                 </div>

					</fieldset>

					<fieldset class="form-group">
						
					   <label for="fax">{{getPhrase('fax')}}</label>

						   {{ Form::text('fax', $value = null , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("fax"),

                               )) }}

					</fieldset>


					<fieldset class="form-group">
						
					   <label for="web_site">{{getPhrase('web_site')}}</label>

						   {{ Form::text('web_site', $value = null , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("web_site"),

                               )) }}


					</fieldset>

   
                    <fieldset class='form-group'>

						{{ Form::label('logo', getphrase('logo')) }}

						<div class="form-group row">

							<div class="col-md-6">

						

					{!! Form::file('logo', null, array('class'=>'form-control')) !!}

							</div>

							<?php if(isset($record) && $record) { 

								  if($record->logo!='') {

								?>

							<div class="col-md-6">

								<img src="{{ IMAGE_PATH_UPLOAD_BRANCH.$record->logo }}" height="50" width="50" />



							</div>

							<?php } } ?>

						</div>

					</fieldset>


                         <div class="buttons text-center">

							<button class="btn btn-lg btn-success button" ng-disabled='!instituteBranch.$valid'

							>{{ $button_name }}</button>

						</div>

		 