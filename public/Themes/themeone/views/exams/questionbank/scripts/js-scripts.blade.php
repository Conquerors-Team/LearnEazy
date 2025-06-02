<script src="{{JS}}angular.js"></script>
<script src="{{JS}}angular-messages.js"></script>

<script>
var app = angular.module('academia', ['ngMessages']);
app.controller('questionsController', function($scope, $http) {

    $scope.initAngData = function(data) {

         if(data=='')
        {

            return;
        }


        data=JSON.parse(data);


        $scope.question_type = data.question_type;
        $scope.correct_answers = data.correct_answers;
        $scope.total_correct_answers = data.total_correct_answers;

        if(data.question_type=='blanks')
        {
            console.log('hhhhhh');
        }

        if(data.question_type=='radio')
        {
            $scope.answers = data.answers;
        }

        if(data.question_type=='checkbox')
        {
            $scope.answers = data.answers;
            $scope.correct_answers = data.correct_answers;
        }
        if(data.question_type=='match')
        {

            $scope.answers = data.answers;
            $scope.correct_answers = data.correct_answers;
        }

        if(data.question_type=='para' || data.question_type=='video' || data.question_type=='audio' )
        {

            $scope.answers = data.answers;
            $scope.correct_answers = data.correct_answers;
        }


    }

     $scope.range = function(count) {
        var range = [];
        for (var i = 0; i < count; i++) {
          range.push(i)
        }
        return range;
    }

    $scope.answersChanged = function(selected_number) {
        $scope.total_answers = selected_number;

    }
    $scope.correctAnswersChanged = function(selected_number) {
        $scope.total_correct_answers = selected_number;

    }
    $scope.paraOptionsChanged = function(selected_number) {
        $scope.total_para_options = selected_number;

    }

    $scope.getToken = function(){
      return  $('[name="_token"]').val();
    }

});

app.directive("ckeditor", ["$timeout", function($timeout) {
    return {
        require: '?ngModel',
        link: function ($scope, element, attr, ngModelCtrl) {
            var editor = CKEDITOR.replace(element[0], {
            height: 320
            });


            editor.on("change", function() {
                $timeout(function() {
                    ngModelCtrl.$setViewValue(editor.getData());
                });
            });

            ngModelCtrl.$render = function (value) {
                editor.setData(ngModelCtrl.$modelValue);
            };
        }
    };
}]);

function getChaptersTopics()
{
  var subject_id = $('#subject_id').val();
  var chapter_id = $('#chapter_id').val();
  var route = '{{url("mastersettings/topics/get-parents-topics")}}/'+subject_id + '/' + chapter_id;
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

</script>