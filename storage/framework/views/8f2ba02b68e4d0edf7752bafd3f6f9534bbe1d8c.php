<script type="text/javascript">
function classChanged()
{
  class_id = $('#student_class_id').val();
  route = '<?php echo e(url("mastersettings/class/get-batch")); ?>/' + class_id;

  var token = $('[name="_token"]').val();
  data= {_method: 'get', '_token':token};
  $.ajax({
    url:route,
    dataType: 'json',
    data: data,
    success:function(result){
      $('#batch_id').empty();
      for(i=0; i<result.length; i++)
        $('#batch_id').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
      }
  });
}


function facultyChanged()
{
  created_by_id = $('#created_by_id').val();
  route = '<?php echo e(url("mastersettings/class/get-user")); ?>/' + created_by_id;

  var token = $('[name="_token"]').val();
  data= {_method: 'get', '_token':token};
  $.ajax({
    url:route,
    dataType: 'json',
    data: data,
    success:function(result){
      $('#online_url').val(result.online_url);
    }
  });

  getFacultySubjects();
}

function getFacultySubjects()
{
  created_by_id = $('#created_by_id').val();
  route = '<?php echo e(url("mastersettings/class/get-faculty-subjects")); ?>/' + created_by_id;

  var token = $('[name="_token"]').val();
  data= {_method: 'get', '_token':token};
  $.ajax({
    url:route,
    dataType: 'json',
    data: data,
    success:function(result){
      // $('#online_url').val(result.online_url);
      $('#subject_id').empty();
      for(i=0; i<result.length; i++) {
        $('#subject_id').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
      }
    }
  });
}

function batchChanged()
{
  batch_id = $('#batch_id').val();
  route = '<?php echo e(url("mastersettings/class/get-faculty")); ?>/' + batch_id;

  var token = $('[name="_token"]').val();
  data= {_method: 'get', '_token':token};
  $.ajax({
    url:route,
    dataType: 'json',
    data: data,
    success:function(result){
      $('#created_by_id').empty();
      for(i=0; i<result.length; i++) {
        $('#created_by_id').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
      }
    }
  });
}








function getChapterLMS()
{
  subject_id = $('#subject_id').val();
  chapter_id = $('#chapter_id').val();
  route = '<?php echo e(url("online-classes/get-lms")); ?>/'+subject_id + '/' + chapter_id;
  var token = $('[name="_token"]').val();
  data= {_method: 'get', '_token':token};
  $.ajax({
    url:route,
    dataType: 'json',
    data: data,
    success:function(result){
      $('#lmsseries_id').empty();
      for(i=0; i<result.length; i++)
        $('#lmsseries_id').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
      }
  });
}

function getChapterNotes()
{
  subject_id = $('#subject_id').val();
  chapter_id = $('#chapter_id').val();
  route = '<?php echo e(url("online-classes/get-notes")); ?>/'+subject_id + '/' + chapter_id;
  var token = $('[name="_token"]').val();
  data= {_method: 'get', '_token':token};
  $.ajax({
    url:route,
    dataType: 'json',
    data: data,
    success:function(result){
      $('#lmsnotes_id').empty();
      for(i=0; i<result.length; i++)
        $('#lmsnotes_id').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
      }
  });
}
</script>