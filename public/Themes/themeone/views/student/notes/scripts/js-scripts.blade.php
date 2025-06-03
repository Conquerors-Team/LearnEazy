<script type="text/javascript">
function getChaptersTopics()
{
  subject_id = $('#subject_id').val();
  chapter_id = $('#chapter_id').val();
  route = '{{url("student/lms/get-parents-topics")}}/'+subject_id + '/' + chapter_id;

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
}

function getSubTopics()
{
  subject_id = $('#subject_id').val();
  chapter_id = $('#chapter_id').val();
  topic_id = $('#topic_id').val();

  route = '{{url("student/lms/get-sub-topics")}}/'+subject_id + '/' + chapter_id + '/' + topic_id;

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