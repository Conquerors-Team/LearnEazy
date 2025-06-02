<script type="text/javascript">
  function getSubjectParents()
    {
      subject_id = $('#subject').val();
      route = '<?php echo e(URL_TOPICS_GET_PARENT_TOPICS); ?>'+subject_id;
      var token = $('[name="_token"]').val();
      data= {_method: 'get', '_token':token, 'subject_id': subject_id};
      $.ajax({
        url:route,
        dataType: 'json',
        data: data,
        success:function(result){
           $('#parent').empty();
        for(i=0; i<result.length; i++)
         $('#parent').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
        }
      });
    }

    function getInstituteSubjects()
    {
      institute_id = $('#institute_id').val();
      route = '<?php echo e(url("mastersettings/subjects/get-institute-subjects")); ?>/'+institute_id;
      var token = $('[name="_token"]').val();
      data= {_method: 'get', '_token':token, 'institute_id': institute_id};
      $.ajax({
        url:route,
        dataType: 'json',
        data: data,
        success:function(result){
          $('#subject').empty();
          for(i=0; i<result.length; i++)
            $('#subject').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
          }
      });
    }

    function getSubjectChapters()
    {
      subject_id = $('#subject').val();
      route = '<?php echo e(url("mastersettings/chapters/get-parents-chapters")); ?>/'+subject_id;
      var token = $('[name="_token"]').val();
      data= {_method: 'get', '_token':token, 'subject_id': subject_id};
      $.ajax({
        url:route,
        dataType: 'json',
        data: data,
        success:function(result){
          $('#chapter_id').empty();
          for(i=0; i<result.length; i++)
            $('#chapter_id').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
          }
      });
    }

    function getChaptersTopics()
    {
      subject_id = $('#subject').val();
      chapter_id = $('#chapter_id').val();
      route = '<?php echo e(url("mastersettings/topics/get-parents-topics")); ?>/'+subject_id + '/' + chapter_id;
      var token = $('[name="_token"]').val();
      data= {_method: 'get', '_token':token};
      $.ajax({
        url:route,
        dataType: 'json',
        data: data,
        success:function(result){
          $('#topic_id').empty();
          for(i=0; i<result.length; i++)
            $('#topic_id').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
          }
      });
      // console.log(ssssresult);
    }

    function getChaptersTopicsSubtopics()
    {
      subject_id = $('#subject').val();
      chapter_id = $('#chapter_id').val();
      topic_id = $('#topic_id').val();
      route = '<?php echo e(url("student/lms/get-sub-topics")); ?>/'+subject_id + '/' + chapter_id + '/' + topic_id ;
      var token = $('[name="_token"]').val();
      data= {_method: 'get', '_token':token};
      $.ajax({
        url:route,
        dataType: 'json',
        data: data,
        success:function(result){
          $('#sub_topic_id').empty();
          for(i=0; i<result.length; i++)
            $('#sub_topic_id').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
          }
      });
    }
</script>