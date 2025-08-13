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

// app.directive("ckeditor", ["$timeout", function($timeout) {
//     return {
//         require: '?ngModel',
//         link: function ($scope, element, attr, ngModelCtrl) {
//             var editor = CKEDITOR.replace(element[0], {
//             height: 320
//             });


//             editor.on("change", function() {
//                 $timeout(function() {
//                     ngModelCtrl.$setViewValue(editor.getData());
//                 });
//             });

//             ngModelCtrl.$render = function (value) {
//                 editor.setData(ngModelCtrl.$modelValue);
//             };
//         }
//     };
// }]);
app.directive("ckeditor", ["$timeout", function($timeout) {
    return {
        require: '?ngModel',
        link: function ($scope, element, attr, ngModelCtrl) {
            if (window.editorType === 'quilljs') {
                // Check if QuillJS is already initialized on this element
                if (element[0].quillInitialized) {
                    return; // Already initialized, skip
                }
                
                // Mark this element as initialized
                element[0].quillInitialized = true;
                
                // QuillJS editor setup
                element.css('display', 'none'); // hide original textarea
                
                var quillDiv = document.createElement('div');
                quillDiv.style.height = '320px';
                quillDiv.classList.add('quill-editor-container');
                element[0].parentNode.insertBefore(quillDiv, element[0].nextSibling);
                
                var quill = new Quill(quillDiv, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ font: [] }],
                            [{ size: ['small', false, 'large', 'huge'] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ color: [] }, { background: [] }],
                            [{ script: 'sub' }, { script: 'super' }],
                            [{ header: '1' }, { header: '2' }, 'blockquote', 'code-block'],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            [{ indent: '-1' }, { indent: '+1' }],
                            ['link', 'image', 'video', 'formula'],
                            ['clean']
                        ],
                        formula: true
                    }
                });
                
                quill.root.innerHTML = element.val();
                
                quill.on('text-change', function() {
                    $timeout(function() {
                        var html = quill.root.innerHTML;
                        ngModelCtrl.$setViewValue(html);
                        element.val(html); // update textarea for form submit
                    });
                });
                
                ngModelCtrl.$render = function () {
                    var safeHtml = ngModelCtrl.$viewValue || '';
                    if (quill.root.innerHTML !== safeHtml) {
                        quill.root.innerHTML = safeHtml;
                    }
                };
                
                if (element[0].form) {
                    element[0].form.addEventListener('submit', function() {
                        element.val(quill.root.innerHTML);
                    });
                }
            } else {
                // CKEditor editor setup
                var editor = CKEDITOR.replace(element[0], {
                    height: 320
                });
                
                editor.on("change", function() {
                    $timeout(function() {
                        ngModelCtrl.$setViewValue(editor.getData());
                    });
                });
                
                ngModelCtrl.$render = function () {
                    editor.setData(ngModelCtrl.$modelValue || '');
                };
            }
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