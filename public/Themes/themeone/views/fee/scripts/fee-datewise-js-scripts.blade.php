@include('common.angular-factory')
<script>

 app.controller('feeReportsController', function ($scope, $http, httpPreConfig)
  {   

      $scope.getInstituteReports  = function(institute_id, from_date, to_date){
     
        $scope.selected_institute   = institute_id;
          
           route   = '{{URL_GET_DAILY_FEE_REPORTS}}';  
            data    = {   
                         _method: 'post', 
                         '_token':httpPreConfig.getToken(), 
                         'institute_id': institute_id, 
                         'from_date'   : from_date, 
                         'to_date'     : to_date, 
                      };

        httpPreConfig.webServiceCallPost(route, data).then(function(result){
           // console.log(result.data);
           $scope.result_data  = result.data.fee_payments;
           $scope.date_from    = result.data.date1;
           $scope.date_to      = result.data.date2;
           $scope.total_paid   = result.data.total_paid;


       });

      }
      
       $scope.getDailyReports  = function(from_date){
            
            route   = '{{URL_GET_DAILY_FEE_REPORTS}}';  
            data    = {   
                   _method: 'post', 
                  '_token':httpPreConfig.getToken(), 
                  'from_date': from_date, 
               };

        httpPreConfig.webServiceCallPost(route, data).then(function(result){
           console.log(result.data);
           $scope.result_data  = result.data.fee_payments;
           $scope.date_from    = result.data.date1;
           $scope.date_to      = result.data.date2;
           $scope.total_paid   = result.data.total_paid;


       });
           
      }

      $scope.getReports  = function(from_date, to_date){
            
            route   = '{{URL_GET_DAILY_FEE_REPORTS}}';  
            data    = {   
                   _method: 'post', 
                  '_token':httpPreConfig.getToken(), 
                  'from_date': from_date, 
                  'to_date': to_date, 
               };

            httpPreConfig.webServiceCallPost(route, data).then(function(result){
           console.log(result.data);
           $scope.result_data  = result.data.fee_payments;
           $scope.date_from    = result.data.date1;
           $scope.date_to      = result.data.date2;
           $scope.total_paid   = result.data.total_paid;

         });
          
      }



 $scope.printIt  = function(){

    $('#printReports').submit();
    
 }
  

});
 
 
  
</script>