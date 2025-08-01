 

					 <fieldset class="form-group">

						

						{{ Form::label('old_password', getphrase('old_password')) }}

						<span class="text-red">*</span>

	{{ Form::password('old_password', $attributes = array(
		'class'=>'form-control pr-5', // Add right padding for the icon
		'placeholder' => getphrase('old_password'),
		'ng-model'=>'old_password',
		'required'=> 'true',
		'ng-class'=>'{"has-error": changePassword.old_password.$touched && changePassword.old_password.$invalid}',
		'ng-minlength' => 5,
		'id' => 'old_password' // Add ID to target it in JS
	)) }}
	<span onclick="togglePassword('old_password', this)" style="position:absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor:pointer;">
		<i class="fa fa-eye"></i>
	</span>



	<div class="validation-error" ng-messages="changePassword.old_password.$error" >

		{!! getValidationMessage()!!}

		{!! getValidationMessage('password')!!}

	</div>

					</fieldset>

					 

					 <fieldset class="form-group">

						

						{{ Form::label('password', getphrase('new_password')) }}

						<span class="text-red">*</span>

						{{ Form::password('password', $attributes = array(
		'class'=>'form-control pr-5', // padding-right for eye icon
		'placeholder' => getphrase('new_password'),
		'ng-model'=>'password',
		'required'=> 'true',
		'ng-class'=>'{"has-error": changePassword.password.$touched && changePassword.password.$invalid}',
		'ng-minlength' => 5,
		'id' => 'password' // required for JS toggle
	)) }}
	<span onclick="togglePassword('password', this)" style="position:absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor:pointer;">
		<i class="fa fa-eye"></i>
	</span>

	<div class="validation-error" ng-messages="changePassword.password.$error" >

		{!! getValidationMessage()!!}

		{!! getValidationMessage('password')!!}

	</div>

					</fieldset>

					 <fieldset class="form-group">

						

						{{ Form::label('password_confirmation', getphrase('retype_password')) }}

						<span class="text-red">*</span>

						{{ Form::password('password_confirmation', $attributes = array(
		'class'=>'form-control pr-5',
		'placeholder' => getphrase('retype_password'),
		'ng-model'=>'password_confirmation',
		'required'=> 'true',
		'ng-class'=>'{"has-error": changePassword.password_confirmation.$touched && changePassword.password_confirmation.$invalid}',
		'compare-to' =>"password",
		'ng-minlength' => 5,
		'id' => 'password_confirmation'
	)) }}
	<span onclick="togglePassword('password_confirmation', this)" style="position:absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor:pointer;">
		<i class="fa fa-eye"></i>
	</span>

	<div class="validation-error" ng-messages="changePassword.password_confirmation.$error" >

		{!! getValidationMessage()!!}

		{!! getValidationMessage('password')!!}

		{!! getValidationMessage('confirmPassword')!!}

	</div>

					</fieldset>											

					 

					

						<div class="buttons text-center">

							<button class="btn btn-lg btn-success button"

							ng-disabled='!changePassword.$valid' >{{ $button_name }}</button>

						</div>
                        <script>
	function togglePassword(fieldId, iconElement) {
		const input = document.getElementById(fieldId);
		const icon = iconElement.querySelector('i');
		if (input.type === "password") {
			input.type = "text";
			icon.classList.remove('fa-eye');
			icon.classList.add('fa-eye-slash');
		} else {
			input.type = "password";
			icon.classList.remove('fa-eye-slash');
			icon.classList.add('fa-eye');
		}
	}
</script>
