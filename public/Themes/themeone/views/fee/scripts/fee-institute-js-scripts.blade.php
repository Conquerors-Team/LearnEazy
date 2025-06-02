@include('common.angular-factory')
<script>

 app.controller('feeReportsController', function ($scope, $http, httpPreConfig)
  {
      
      $scope.balance = 0;


      $scope.getBatches = function(institute_id){

            $scope.selected_institute = institute_id;

            route   = '{{URL_GET_INSTITUTE_BATCHES}}';  
            data    = {   
                   _method: 'post', 
                  '_token':httpPreConfig.getToken(), 
                  'institute_id': institute_id, 
               };

        httpPreConfig.webServiceCallPost(route, data).then(function(result){
           // console.log(result.data);
           $scope.batches = result.data;

       });
   }

    $scope.getStudents = function(batch_id){

            $scope.selected_batch = batch_id;

            route   = '{{URL_GET_FEE_PAID_STUDENTS_BATCH}}';  
            data    = {   
                   _method: 'post', 
                  '_token':httpPreConfig.getToken(), 
                  'batch_id': batch_id, 
               };

        httpPreConfig.webServiceCallPost(route, data).then(function(result){
           $scope.result_data = result.data;
           // console.log($scope.students);

       });
   }

 $scope.printIt  = function(){

    $('#printReports').submit();
    
 }
  

});
 
 
  
</script>