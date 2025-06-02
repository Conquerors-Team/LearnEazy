                       <?php

                                    $image_path = PREFIX.(new App\ImageSettings())->

                                    getExamImagePath();



                                    ?>

                    <div class="questions questions-withno">

                            <span class="question-numbers">Q {{$question_number}}.</span>

                               <span class="language_l1">{!! $question->question !!}   </span>
                               @if($question->question)
                                    <span class="language_l2" style="display: none;">{!! $question->question_l2 !!}   </span> <br>
                                    @else
                                    <span class="language_l2" style="display: none;">{!! $question->question !!}   </span> <br>
                                    @endif

                      <div class="col-md-8 text-center">
                      @if($question->question_type!='audio' && $question->question_type !='video')
                      @if($question->question_file)
                      <img class="image " src="{{$image_path.$question->question_file}}" style="max-height:200px;">
                      @endif
                      @endif
                      </div>

                            <small class="pull-right">

                                <strong>{{getPhrase('subject')}}:</strong>

                                {{$subject->subject_title}}

                            </small>

                        </div>

                    <?php
                    $meta = (object)$meta;
                   $question = $meta->question;
                    $time_spent = $meta->time_spent;

                    $timing_lable = 'label label-danger';

                    if ( ! empty( $question->time_to_spend ) && ! empty( $time_spent->time_spent ) ) {
                        if($question->time_to_spend > $time_spent->time_spent)
                        {
                            $timing_lable = 'label label-info';
                        }
                    }



                    ?>

                        <div class="answer-status-container">

                        <div class="row">

                            <div class="col-md-3">

                                <div class="question-status">

                                    <strong>{{getPhrase('time_limit')}}: </strong>

                                    {{gmdate("H:i:s", $question->time_to_spend)}}

                                </div>

                            </div>

                            <div class="col-md-3">

                                <div class="question-status">

                                    <strong>{{getPhrase('time_taken')}}: </strong>

                                    <span class="{{$timing_lable}}"> {{gmdate("H:i:s", $time_spent->time_spent)}} </span>



                                </div>

                            </div>
                            <div class="col-md-3">

                                <div class="question-status">

                                  <strong><a href="javascript:void(0);" onclick="openModal('reportModal')">Report issue <i class="fa fa-reply" aria-hidden="true"></i></a></strong>

                                </div>

                              </div>

                            <a ng-if="bookmarks[{{$question->id}}] >= 0"

                                title="{{getPhrase('unbookmark_this_question')}}"

                                ng-click="bookmark({{$question->id}},'delete','questions');"

                                href="javascript:void(0)" class="pull-right btn btn-link">

                                <i class="fa fa-star item-bookmark"></i></a>





                                <a ng-if="bookmarks[{{$question->id}}] == -1" title="{{getPhrase('bookmark_this_question')}}" ng-click="bookmark({{$question->id}},'add','questions');" href="javascript:void(0)" class="pull-right btn btn-link"> <i class="fa fa-star-o item-bookmark"></i></a>

                        </div>

                        </div>

                        <hr>

                        <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog" style="margin-top: 150px;">
                                <div class="modal-content">

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                            ×</button>
                                                <!-- Tab panes -->
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="Login">
                                                     <h3 class="login_label">Report an issue regarding this question</h3>

                            <div class="alert alert-danger print-error-msg-login" style="display:none">
                            <ul></ul>
                            </div>



            {{ Form::model($question,array('url' => 'Report-question/'.$question->id,'method'=>'POST', 'files' => TRUE, 'name'=>'formQuestionBank ', 'novalidate'=>'',  'class'=>'validation-align')) }}


                    <fieldset class="form-group col-md-4">
                    {{ Form::label('hint', getphrase('title')) }}
                    <span class="text-red">*</span>
                    {{ Form::text('tile', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Add a title to your issue')) }}
                    <div class="validation-error" ng-messages="formQuestionBank.hint.$error" >
                    {!! getValidationMessage()!!}
                    {!! getValidationMessage('minlength')!!}
                  </div>
                  </fieldset>

                  <?php $issue_types = ['bug' => 'bug','minor' => 'minor','others' => 'others'];

			                  // dd($issue_types);

			                  ?>


                  <fieldset class="form-group col-md-4">
					{{ Form::label('topic_id', getphrase('type')) }} <span class="text-red">*</span>
					{{Form::select('type', $issue_types, null, ['class'=>'form-control', "id"=>"type"])}}
					</fieldset>

                    <fieldset class="form-group col-md-12">
                        {{ Form::label('explanation', getphrase('explanation')) }}
                        <span class="text-red">*</span>
                        {{ Form::textarea('explanation', $value = null , $attributes = array('class'=>'form-control ckeditor', 'placeholder' => 'Your explanation', 'rows' => '5', 'id' => 'explanation')) }}
                        <div class="validation-error" ng-messages="formQuestionBank.explanation.$error" >
                      {!! getValidationMessage()!!}
                      {!! getValidationMessage('minlength')!!}
                    </div>
                    </fieldset>

                                <div class="row">
                                    <div class="col-sm-12 btn-center1">
                                        <input type="hidden" name="redirect_url" id="redirect_url" value="{{URL_USERS_DASHBOARD}}">
                                        <button type="submit" class="btn_1">
                                            Submit</button>
                                    </div>
                                </div>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>