<?php
$institute_id   = adminInstituteId();
if ( request()->filled('institute_id') ) {
        $institute_id = request('institute_id');
}

$institutes = \App\Institute::where('status', 1);
if(checkRole(getUserGrade(3))) {
    $institutes->get();
} elseif ( shareData('share_subjects') ) {
    $institutes->whereIn('id', [$institute_id, OWNER_INSTITUTE_ID])->get();
} else {
    $institutes->where('id', $institute_id)->get();
}
$institutes = $institutes->pluck('institute_name', 'id')->prepend('Please select', '');

// Subjects.
/*
$subjects = \App\Subject::where('status', 'Active');

if(checkRole(getUserGrade(3))) {
        $subjects->get();
} elseif ( shareData('share_subjects') ) {
     $subjects->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID]);
} else {
    $subjects->where('institute_id', $institute_id);
}
*/
$subjects = \App\Subject::where('status', 'Active')->where('institute_id', $institute_id);
if(checkRole(getUserGrade(10))) { // Faculty
  $faculty_subjects = \App\User::find( \Auth::id() )->subjects()->pluck('subject_id')->toArray();
  $subjects->whereIn('id', $faculty_subjects);
}

if ( request()->filled('institute_id') ) {
    $subjects->where('institute_id', request('institute_id'));
}
// echo getEloquentSqlWithBindings( $subjects );
$subjects = $subjects->get()->pluck('subject_title', 'id')->prepend('Please select', '');

$chapters = $topics = $sub_topics = [];

$str_subjects = $str_chapters = $str_topics = $str_sub_topics = $str_type = '';

$subject = request('subject_id');
$chapter = request('chapter_id');
$topic = request('topic_id');

if( !empty($subject_id)){
    $subject = $subject_id;
}
// dd($subject_id);

if ( ! empty( $subject ) ) {
$chapters = \App\Chapter::where('subject_id', $subject);
$topics = \App\Topic::where('subject_id', $subject)->where('parent_id', 0);
$sub_topics = \App\Topic::where('subject_id', $subject)->where('parent_id', '>', 0);
if(checkRole(getUserGrade(3))) {
$chapters->get();

// Topics.
if ( ! empty( $chapter ) ) {
	$topics->where('chapter_id', $chapter);
}
$topics = $topics->get();

// Sub Topics.
if ( ! empty( $chapter ) ) {
	$sub_topics->where('chapter_id', $chapter);
}
if ( ! empty( $topic ) ) {
	$sub_topics->where('parent_id', $topic);
}
$sub_topics = $sub_topics->get();
} elseif ( shareData('share_subjects') ) {
    $chapters->whereIn('institute_id', [$institute_id, OWNER_INSTITUTE_ID])->get();

    // Topics.
    if ( ! empty( $chapter ) ) {
    	$topics->where('chapter_id', $chapter);
    }
    $topics = $topics->get();


    // Sub Topics.
    if ( ! empty( $chapter ) ) {
    	$sub_topics->where('chapter_id', $chapter);
    }
    if ( ! empty( $topic ) ) {
    	$sub_topics->where('parent_id', $topic);
    }
    $sub_topics = $sub_topics->get();
} else {
    $chapters->where('institute_id', $institute_id)->get();

    // Topics.
    if ( ! empty( $chapter ) ) {
    	$topics->where('chapter_id', $chapter);
    }
    $topics = $topics->get();

    // Sub Topics.
    if ( ! empty( $chapter ) ) {
    	$sub_topics->where('chapter_id', $chapter);
    }
    if ( ! empty( $topic ) ) {
    	$sub_topics->where('parent_id', $topic);
    }
    $sub_topics = $sub_topics->get();
    }

    $chapters = $chapters->pluck('chapter_name', 'id')->prepend('Please select', '');
    $topics = $topics->pluck('topic_name', 'id')->prepend('Please select', '');
    $sub_topics = $sub_topics->pluck('topic_name', 'id')->prepend('Please select', '');
}

$categories = App\QuestionbankCategory::all()->pluck('category','id')->prepend('Please select','');
$difficulty_levels = ['' => 'All','easy'=>'Easy','medium'=>'Medium','hard'=>'Hard'];
?>
<div class="row">
        <?php echo Form::open(array('url' => $url, 'method' => 'GET',
        'novalidate'=>'','name'=>'formTopics ')); ?>


        <?php if(checkRole(getUserGrade(3)) || shareData('share_subjects')): ?>
        <fieldset class="form-group col-lg-3">
        <?php echo e(Form::label('institute_id', getphrase('institute'))); ?>

        <span class="text-red">*</span>

        <?php echo e(Form::select('institute_id', $institutes, null, ['class'=>'form-control', 'id'=>'institute_id',
        'onChange'=>'getInstituteSubjects()'
        ])); ?>

        </fieldset>
        <?php endif; ?>

        <?php if(checkRole(getUserGrade(1)) && empty($show_batch_assigned)): ?>
        <fieldset class="form-group col-lg-3">
        <?php echo e(Form::label('batch_assigned', getphrase('assigned'))); ?>?
        <span class="text-red">*</span>
        <?php
        $batch_assigned_opts = [
            '' => 'All',
            'yes' => 'Yes',
            'no' => 'No',
        ];
        ?>
        <?php echo e(Form::select('batch_assigned', $batch_assigned_opts, null, ['class'=>'form-control', 'id'=>'batch_assigned'
        ])); ?>

        </fieldset>
        <?php endif; ?>

        <?php if( empty( $show_content_types ) ): ?>
        <?php $settings = getSettings('lms');?>
        <fieldset class="form-group col-lg-3">
        <?php echo e(Form::label('content_type', getphrase('content_type'))); ?>

        <span class="text-red">*</span>
        <?php
        $content_types = (array)$settings->content_types;
        array_unshift($content_types, 'Please select');
        ?>
        <?php echo e(Form::select('content_type', $content_types, null, ['class'=>'form-control', 'id'=>'content_type',
                'ng-model'=>'content_type',
                'ng-class'=>'{"has-error": formTopics.content_type.$touched && formTopics.content_type.$invalid}'
        ])); ?>

        </fieldset>
        <?php endif; ?>

        <?php if( empty( $show_subjects ) ): ?>
        <fieldset class="form-group col-lg-3">
        <?php echo e(Form::label('subject_id', getphrase('subject'))); ?>

        <span class="text-red">*</span>
        <?php echo e(Form::select('subject_id', $subjects, null, ['class'=>'form-control','onChange'=>'getSubjectChapters()', 'id'=>'subject',
        	'ng-model'=>'subject_id',
        	'required'=> 'true',
        	'ng-class'=>'{"has-error": formTopics.subject_id.$touched && formTopics.subject_id.$invalid}'
        ])); ?>

         <div class="validation-error" ng-messages="formTopics.subject_id.$error" >
        	<?php echo getValidationMessage(); ?>

        </div>
        </fieldset>
        <?php endif; ?>

        <?php if( empty( $show_chapters ) ): ?>
        <fieldset class="form-group col-lg-3">
        <?php echo e(Form::label('chapter_id', getphrase('chapter'))); ?>

        <?php echo e(Form::select('chapter_id', $chapters, null, ['class'=>'form-control', 'id'=>'chapter_id', 'onChange' => 'getChaptersTopics()' ])); ?>

        </fieldset>
        <?php endif; ?>

        <?php if( empty( $show_topics ) ): ?>
        <fieldset class="form-group col-lg-3">
        <?php echo e(Form::label('topics', 'Topic')); ?>

        <?php echo e(Form::select('topic_id', $topics, null, ['class'=>'form-control', 'id'=>'topic_id', 'onChange' => 'getChaptersTopicsSubtopics()' ])); ?>

        </fieldset>
        <?php endif; ?>

        <?php if( empty( $show_sub_topics ) ): ?>
        <fieldset class="form-group col-lg-3">
        <?php echo e(Form::label('sub_topic', 'Subtopic')); ?>

        <?php echo e(Form::select('sub_topic_id', $sub_topics, null, ['class'=>'form-control', 'id'=>'sub_topic_id' ])); ?>

        </fieldset>
        <?php endif; ?>

        <?php if( empty( $show_question_actegory ) ): ?>
        <fieldset class="form-group col-lg-3">
        <?php echo e(Form::label('question_category_id', 'Question category')); ?>

        <?php echo e(Form::select('question_category_id', $categories, null, ['class'=>'form-control', 'id'=>'question_category_id' ])); ?>

        </fieldset>
        <?php endif; ?>

        <?php if( empty( $show_difficulty_level ) ): ?>
        <fieldset class="form-group col-lg-3">
        <?php echo e(Form::label('difficulty_level', 'Difficulty level')); ?>

        <?php echo e(Form::select('difficulty_level', $difficulty_levels, null, ['class'=>'form-control', 'id'=>'difficulty_level' ])); ?>

        </fieldset>
        <?php endif; ?>

        <fieldset class="form-group col-lg-4" style="padding-top: 15px;">
        <div class="buttons text-center">
        	<button class="btn btn-lg btn-success button"
        	>Search</button>&nbsp;
        	<a href="<?php echo e($url); ?>" class="btn btn-lg btn-error button"
        	>Reset</a>
        </div>
        </fieldset>
        <?php echo Form::close(); ?>

</div>