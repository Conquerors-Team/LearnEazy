@include('common.angular-factory')
<script>

 app.controller('batchesController', function ($scope, $http, httpPreConfig)
  {
      $scope.target_items = [];

      $scope.setPreSelectedData  = function(user_id, institute_id, batch_id) {
         
         
         $scope.doCall(user_id,institute_id,batch_id);
      }
         // console.log($scope.target_items);

      $scope.doCall     = function (user_id,institute_id,batch_id) {

        route   = '{{URL_GET_STUDENTS}}';  
        data    = { _method     : 'post', 
                  '_token'      : httpPreConfig.getToken(), 
                  'user_id'     : user_id, 
                  'institute_id': institute_id, 
                  'batch_id'    : batch_id, 
                 
               };
               
        httpPreConfig.webServiceCallPost(route, data).then(function(result){
          console.log(result.data);
          
         users          = [];

        angular.forEach(result.data, function(value, key) {
            users.push(value);
          })

        $scope.result_data = users;

        

       });
    }


     $scope.printIt = function(){
     
      $('#htmlform').submit();
     }

     $scope.toggleSelect = function(){
      
        angular.forEach($scope.result_data, function(item){
        
        item.mycheck = event.target.checked;

      });
  }


 
 
 
});
 
  
</script>