
<script src="{{JS}}angular.js"></script>
<script src="{{JS}}angular-messages.js"></script>

<script>
var app = angular.module('academia', ['ngMessages']);
app.controller('angLmsController', function($scope, $http) {


    $scope.initAngData = function(data) {
        if(data=='')
        {
            $scope.series = '';
            $scope.content_type = '';
            return;
        }
         data = JSON.parse(data);
         $scope.content_type    = data.content_type;
    }
});

function getSubjectChapters()
    {
      subject_id = $('#subject_id').val();
      route = '{{url("mastersettings/chapters/get-parents-chapters")}}/'+subject_id;

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
      subject_id = $('#subject_id').val();
      chapter_id = $('#chapter_id').val();
      route = '{{url("mastersettings/topics/get-parents-topics-exam")}}/'+subject_id + '/' + chapter_id;

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

</script>