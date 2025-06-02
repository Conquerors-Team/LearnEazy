
@include('common.angular-factory')
<script >
 
     app.controller('users_controller', function ($scope, $http, httpPreConfig) {
      
      $scope.showSearch = false;
      $scope.userDetails = false;


      $scope.accountAvailable = function (availability)
      {
        
        if(!availability)
        {
          $scope.userDetails = true;
          $scope.showSearch = false;
        }
        else {
          $scope.showSearch = true;
          $scope.userDetails = false;
        }
        // URL_SEARCH_PARENT_RECORDS
      }

      
    });

</script>