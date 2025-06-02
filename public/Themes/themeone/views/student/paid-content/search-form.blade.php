<div class="row">
        {!! Form::open(array('url' => $url, 'method' => 'GET',
        'novalidate'=>'','name'=>'formTopics ')) !!}


        @if( 'subject-exams' === $type )
        <fieldset class="form-group col-lg-3">
        {{ Form::label('subject_id', getphrase('subject')) }}
        <span class="text-red">*</span>
        {{Form::select('subject_id', $subjects, null, ['class'=>'form-control', 'id'=>'subject',
        	'ng-model'=>'subject_id',
        	'required'=> 'true',
        	'ng-class'=>'{"has-error": formTopics.subject_id.$touched && formTopics.subject_id.$invalid}'
        ])}}
         <div class="validation-error" ng-messages="formTopics.subject_id.$error" >
        	{!! getValidationMessage()!!}
        </div>
        </fieldset>
        @endif

        @if( 'previousyear-exams' === $type )
        <fieldset class="form-group col-lg-3">
        {{ Form::label('previousyear', getphrase('year')) }}
        <span class="text-red">*</span>
        {{Form::select('previousyear', $years, null, ['class'=>'form-control', 'id'=>'previousyear',
            'ng-model'=>'previousyear',
            'required'=> 'true',
            'ng-class'=>'{"has-error": formTopics.previousyear.$touched && formTopics.subject_id.$invalid}'
        ])}}
         <div class="validation-error" ng-messages="formTopics.previousyear.$error" >
            {!! getValidationMessage()!!}
        </div>
        </fieldset>
        @endif

        <fieldset class="form-group col-lg-4" style="padding-top: 15px;">
        <div class="buttons text-center">
        	<button class="btn btn-lg btn-success button"
        	>Search</button>&nbsp;
        	<a href="{{$url}}" class="btn btn-lg btn-error button"
        	>Reset</a>
        </div>
        </fieldset>
        {!! Form::close() !!}
</div>