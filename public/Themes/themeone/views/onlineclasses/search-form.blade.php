<div class="row">
        {!! Form::open(array('url' => url()->current(), 'method' => 'GET',
        'novalidate'=>'','name'=>'formTopics ')) !!}


        <fieldset class="form-group col-lg-3">
        {{ Form::label('class_title', getphrase('class')) }}
        <?php
        $institute_id   = adminInstituteId();

        $student_classes = \App\StudentClass::where('institute_id', $institute_id)->get()->pluck('name', 'id')->prepend('Please select', '')->toArray();

        ?>
        {{Form::select('class_title', $student_classes, null, ['class'=>'form-control', 'id'=>'class_title',
            'ng-model'=>'class_title',
            'ng-class'=>'{"has-error": formTopics.class_title.$touched && formTopics.class_title.$invalid}'
        ])}}
        </fieldset>

        @if( isInstitute() || isStudent() )
        <fieldset class="form-group col-lg-3">
        {{ Form::label('faculty_id', getphrase('faculty')) }}
        <span class="text-red">*</span>
        <?php
        $faculty = \App\User::where('institute_id',$institute_id)->where('role_id', FACULTY_ROLE_ID)->get()->pluck('name', 'id')->prepend('Please select', '')->toArray();

        ?>
        {{Form::select('faculty_id', $faculty, null, ['class'=>'form-control', 'id'=>'faculty_id',
            'ng-model'=>'faculty_id',
            'ng-class'=>'{"has-error": formTopics.faculty_id.$touched && formTopics.faculty_id.$invalid}'
        ])}}
         <div class="validation-error" ng-messages="formTopics.faculty_id.$error" >
            {!! getValidationMessage()!!}
        </div>
        </fieldset>
        @endif

        @if( isStudent() )
        <fieldset class="form-group col-lg-3">
        {{ Form::label('subject_id', getphrase('subject')) }}
        <span class="text-red">*</span>
        <?php
        //$subjects = \App\User::where('institute_id',$institute_id)->where('role_id', FACULTY_ROLE_ID)->get()->pluck('name', 'id')->prepend('Please select', '')->toArray();
        $subject_ids = \App\User::getUserSeleted('exam_subjects');
        $subjects = \App\Subject::whereIn('id',$subject_ids)->get()->pluck('subject_title', 'id')->prepend('Please select', '')->toArray();
        ?>
        {{Form::select('subject_id', $subjects, null, ['class'=>'form-control', 'id'=>'subject_id',
            'ng-model'=>'subject_id',
            'ng-class'=>'{"has-error": formTopics.subject_id.$touched && formTopics.subject_id.$invalid}'
        ])}}
         <div class="validation-error" ng-messages="formTopics.subject_id.$error" >
            {!! getValidationMessage()!!}
        </div>
        </fieldset>
        @endif

        <fieldset class="form-group col-lg-3">
        {{ Form::label('batch_id', getphrase('batch')) }}
        <span class="text-red">*</span>
        <?php
        $institute_id   = adminInstituteId();
        if ( isStudent() ) {
            $student_batches = getStudentBatches();
            $batches = \App\Batch::whereIn('id',$student_batches)->get()->pluck('name', 'id')->prepend('Please select', '')->toArray();
        } elseif ( isFaculty() ) {
        $faculty_batches = Auth::user()->faculty_batches()->get()->pluck('id')->toArray();
        $batches = \App\Batch::whereIn('id',$faculty_batches)->get()->pluck('name', 'id')->prepend('Please select', '')->toArray();
        } else {
        $batches = \App\Batch::where('institute_id',$institute_id)->get()->pluck('name', 'id')->prepend('Please select', '')->toArray();
        }
        ?>
        {{Form::select('batch_id', $batches, null, ['class'=>'form-control', 'id'=>'batch_id',
        	'ng-model'=>'batch_id',
        	'required'=> 'true',
        	'ng-class'=>'{"has-error": formTopics.batch_id.$touched && formTopics.batch_id.$invalid}'
        ])}}
         <div class="validation-error" ng-messages="formTopics.batch_id.$error" >
        	{!! getValidationMessage()!!}
        </div>
        </fieldset>

        <fieldset class="form-group col-lg-3">
        {{ Form::label('from_date', getphrase('from_date')) }}
        <span class="text-red">*</span>
        {{Form::text('from_date', null, ['class'=>'form-control datepicker1', 'id'=>'from_date'
        ])}}
        </fieldset>

        <fieldset class="form-group col-lg-3">
        {{ Form::label('to_date', getphrase('to_date')) }}
        <span class="text-red">*</span>
        {{Form::text('to_date', null, ['class'=>'form-control datepicker1', 'id'=>'to_date'
        ])}}
        </fieldset>


        <fieldset class="form-group col-lg-4" style="padding-top: 15px;">
        <div class="buttons text-center">
        	<button class="btn btn-lg btn-success button"
        	>Search</button>&nbsp;
        	<a href="{{url()->current()}}" class="btn btn-lg btn-error button"
        	>Reset</a>
        </div>
        </fieldset>
        {!! Form::close() !!}
</div>