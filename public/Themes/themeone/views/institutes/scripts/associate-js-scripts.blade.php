@include('common.angular-factory')

<script>

app.controller('associates', function( $scope, $http) {


	$scope.initAngData = function(data,associates) {
        
        if(data === undefined || data=='')
            return;

        institutes    = [];
        associated    = [];
        is_twoway     = [];

        angular.forEach(data, function(value, key) {
           
           if(associates && associates[key] != undefined){

           	  associated.push( associates[key].assosiated_id);
           	  is_twoway.push( associates[key].is_twoway);
           	  // console.log(associated);
           }
          institutes.push(value);

     })

        $scope.result_data   = institutes;
        $scope.is_associates = associated;
        $scope.twoway        = is_twoway;
           	  console.log($scope.is_associates);
           	  console.log($scope.twoway);

       
     }

    

     // $scope.PreData  = function(mydata, pre_data){

     //         angular.forEach(pre_data, function(value, key) {
              
     //           if(parseInt(value.assosiated_id) == parseInt(mydata.id)){
                 
     //             $scope.ispredata  = 1;

     //          }else{
                   
     //               $scope.ispredata  = 2;
     //          }

     //     })
        
     // }
});

</script>
