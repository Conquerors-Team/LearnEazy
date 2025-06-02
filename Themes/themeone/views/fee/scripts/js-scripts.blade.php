@include('common.angular-factory')
<script>

 app.controller('feePayController', function ($scope, $http, httpPreConfig)
  {

    $scope.user_details      = null;
    $scope.payment_details   = null;
    $scope.total_fee         = 0;
    $scope.total_amount_paid = 0;
    $scope.show_pay_button   = 0;    
    $scope.paid_percentage   = 0;
    $scope.net_amount_to_pay = 0;
    $scope.discount_amount   = 0;

    $scope.getStudents = function(batch_id){

            $scope.selected_batch = batch_id;

            route   = '{{URL_GET_FEE_CATEGORY_STUDENTS}}';  
            data    = {   
                   _method: 'post', 
                  '_token':httpPreConfig.getToken(), 
                  'batch_id': batch_id, 
               };

        httpPreConfig.webServiceCallPost(route, data).then(function(result){
           $scope.students = result.data;

       });
   }

   $scope.getStuduntFeeDetails = function(batch_id, batch_student_id){
        
        $scope.selected_studentid    = batch_student_id;
       
       route   = '{{URL_BATCH_STUDNET_DETAILS}}'; 
        data   = {

                   _method: 'post', 
                  '_token':httpPreConfig.getToken(), 
                  'batch_student_id': batch_student_id,
        };

         httpPreConfig.webServiceCallPost(route, data).then(function(result){
             // console.log(result.data);
             $scope.student_data      = result.data.student_record;
             $scope.feerecords_data   = result.data.fee_payments;
             // $scope.total_amount_pay  = parseInt($scope.student_data.amount) - (parseInt($scope.student_data.paid_amount) + parseInt($scope.student_data.discount));

               $scope.total_amount_pay  = parseInt($scope.student_data.amount) - parseInt($scope.student_data.paid_amount);
             
        });
    }

   /**
   This method is calculate the final amount after discount
   **/
    $scope.afterDiscount  = function(discount_value,net_amount){
       
       $scope.discount_amount = parseInt(discount_value);
       $scope.amount_to_pay   = parseInt(net_amount);
       // $scope.final_pay       = $scope.amount_to_pay - $scope.discount_amount;
       $scope.final_pay       = $scope.amount_to_pay;


    }

    /**
     * This method will check the paid amount against minimum amount need to pay
     * which is set by admin in settings
     * If the user not reached the minimum amount we will not show the pay now button
     * @param  {[type]} total_amount       [description]
     * @param  {[type]} minimum_percentage [description]
     * @return {[type]}                    [description]
     */
    $scope.validateAmount = function(total_amount, paid_amount, minimum_percentage) {
      $scope.paid_percentage = ((paid_amount/total_amount)*100).toFixed(2);
      minimum_percentage=100;
      $scope.show_pay_button = 0;
      /**
       * Here the user need to pay the exact amount or minimum amount as 
       * specified by the admin, other than that, the paynow button will be disabled
       * @type {[type]}
       */
      if(minimum_percentage==100)
      {
        if($scope.paid_percentage==100)
          $scope.show_pay_button = 1;
        else
          $scope.show_pay_button = 0;
      }
      else if($scope.paid_percentage<=100)
      {
        if($scope.paid_percentage>=minimum_percentage)
          $scope.show_pay_button = 1;
        else
          $scope.show_pay_button = 0;
      }

    }

  



});
 
 function myfunction() {
       
       var x = $("#amount").val();
       var y =$("#paid_amount").val();
       var z = x-y;
       $("#balance").val(z);

    }

  
  
</script>