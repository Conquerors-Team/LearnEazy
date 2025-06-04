@extends($layout)
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">

@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							@if(canDo('institute_view'))
						<li><a href="{{URL_VIEW_INSTITUES}}">{{ getPhrase('institutes')}}</a> </li>
						    @endif
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
				@include('errors.errors')
				<div class="panel panel-custom col-lg-6 col-lg-offset-3">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							@if(canDo('permission_access'))
							<a href="{{URL_PERMISSIONS}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
							@endif
						</div>
					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body  form-auth-style" >
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record,
						array('url' => URL_INSTITUTE_SET_PERMISSION.$record->id,
						'method'=>'patch',  'novalidate'=>'','name'=>'registrationForm','name'=>'formLanguage ')) }}
					<!-- @else
						{!! Form::open(array('url' => URL_ADD_INSTITUTE_REGISTER, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'',  'name'=>"registrationForm")) !!} -->
					@endif
             

 					 <fieldset class="form-group">

                        	<label for="institute_name">{{getPhrase('institute_name')}}</label>
                        	<span style="color: red;">*</span>

						   {{ Form::text('institute_name', $value = $ins_name , $attributes = array('class'=>'form-control',

									'placeholder' => getPhrase("institute_name"),

									'ng-model'=>'institute_name',

									'ng-pattern' => getRegexPattern('name'),

									'disabled'=> 'true',

									'ng-class'=>'{"has-error": registrationForm.institute_name.$touched && registrationForm.institute_name.$invalid}',

									'ng-minlength' => '4',

								)) }}



                        </fieldset>


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

					</fieldset>
						<div class="buttons text-center">
							<button class="btn btn-lg btn-success button"
							>{{ $button_name }}</button>
						</div>

					{!! Form::close() !!}
					</div>

				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
@stop
@section('footer_scripts')
  @include('common.validations');
  @include('common.alertify')

  <script src="{{JS}}select2.js"></script>
  <script>
      $('.select2').select2({
       placeholder: "Please select",
    });

    $("#selectbtn-permissions").click(function(){
        $("#permissions > option").prop("selected","selected");
        $("#permissions").trigger("change");
    });
    $("#deselectbtn-permissions").click(function(){
        $("#permissions > option").prop("selected","");
        $("#permissions").trigger("change");
    });

    </script>

<!--  <script>
 	var file = document.getElementById('image_input');

file.onchange = function(e){
    var ext = this.value.match(/\.([^\.]+)$/)[1];
    switch(ext)
    {
        case 'jpg':
        case 'jpeg':
        case 'png':


            break;
        default:
           alertify.error("{{getPhrase('file_type_not_allowed')}}");
            this.value='';
    }
};
 </script> -->
@stop
