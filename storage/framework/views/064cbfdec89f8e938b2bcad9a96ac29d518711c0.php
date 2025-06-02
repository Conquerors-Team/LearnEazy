<script src="<?php echo e(JS); ?>angular.js"></script>
 <script src="<?php echo e(JS); ?>ngStorage.js"></script>
<script src="<?php echo e(JS); ?>angular-messages.js"></script>

<script>
	var app = angular.module('academia',[]);
app.controller('preparePacakges', function( $scope, $http, httpPreConfig) {

	$scope.getContents = function() {
	$scope.studentPackages = [];
	route = '<?php echo e(route("pricing.student-packages")); ?>';
        data= {
                _method: 'post',
                '_token':httpPreConfig.getToken(),
                'course_id': $scope.course_id,
                'class_id': $scope.class_id,
            };
            httpPreConfig.webServiceCallPost(route, data).then(function(result){
            result = result.data;
            $scope.studentPackages = [];
            $scope.studentPackages = result.items;
            });
}

$scope.classChanged = function(selected_number) {
      console.log(selected_number)
        if(selected_number=='')
            selected_number = $scope.class_id;
        class_id = selected_number;
        if(class_id === undefined)
            return;

        $scope.class_id = class_id;
        $scope.getClassCrouses(class_id)
    }

    $scope.getClassCrouses = function( class_id ) {
        if(class_id === undefined)
            return;
        route = '<?php echo e(url("get-class-courses")); ?>/' + class_id;
        data= {_method: 'get', '_token':httpPreConfig.getToken()};
            httpPreConfig.webServiceCallPost(route, data).then(function(result){
            $scope.courses =result.data;

            // $scope.getContents();
        });
    }

    $scope.getStudentPackages = function( course_id ) {

    	 $scope.getContents();

    }
});

function classChanged()
{
  class_id = $('#class_id').val();
  route = '<?php echo e(url("front-end/get-class-courses")); ?>/'+class_id;

  var token = $('[name="_token"]').val();

  data= {_method: 'get', '_token':token};
  $.ajax({
    url:route,
    dataType: 'json',
    data: data,
    success:function(result){
      $('#course_id').empty();
      for(i=0; i<result.length; i++)
        $('#course_id').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
      }
  });
}

function courseChanged()
{
    course_id = $('#course_id').val();
    route = '<?php echo e(url("pricing/student-packages")); ?>/'+course_id;

    var token = $('[name="_token"]').val();

    data= {_method: 'get', '_token':token};
    $.ajax({
    url:route,
    dataType: 'json',
    data: data,
    success:function(result) {
        $('#pack-data').html( result.items );

    }
});

}

</script>
