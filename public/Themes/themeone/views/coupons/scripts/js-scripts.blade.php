<script src="{{JS}}angular.js"></script>

 <script src="{{JS}}angular-messages.js"></script>


<?php
$online_classes_price = getSetting('online_classes_price', 'site-settings');
//$online_classes_price = 90;
if ( empty( $online_classes_price ) ) {
  $online_classes_price = 0;
}
$cost = $item->cost + $online_classes_price;
?>
<script>

var app = angular.module('academia', ['ngMessages']);

app.controller('couponsController', function( $scope, $http) {



    $scope.intilizeData = function(data){


        $scope.ngdiscount = 0;

        $scope.ngtotal = {{$cost}};
        $scope.cost = {{$item->cost}};
        $scope.cart_subtotal = {{$cost}};
        $scope.online_classes_price = {{$online_classes_price}};

        $scope.isAppliedOnlineClass = true;
        //$('#subscribe_onlineclasses').prop('checked', true);

        $scope.isApplied = false;

        return;

    }

    $scope.subscribeOnlineclass = function() {
      var online_classes_price = $('#subscribe_onlineclasses').val();
      if ( $('#subscribe_onlineclasses').prop('checked') ) {
        $scope.isAppliedOnlineClass = true;
        $('#is_onlineclass_applied').val('1')
        $scope.cart_subtotal = $scope.cost + $scope.online_classes_price
        $scope.ngtotal = $scope.cost + $scope.online_classes_price - $scope.ngdiscount
      } else {
        $('#is_onlineclass_applied').val('0')
        $scope.isAppliedOnlineClass = false;
        $scope.cart_subtotal = $scope.cost
        $scope.ngtotal = $scope.cost - $scope.ngdiscount

      }
    }

     /**
      * This method will validate the coupon code
      * @param  {[type]} item_name  Name of the item purchasing
      * @param  {[type]} item_type  Item type like lms,combo,quiz
      * @param  {[type]} cost       Cost of the item
      * @param  {[type]} student_id if parent is purchasing, the student_id is non-zero
      * @return {[type]}            [description]
      */
     $scope.validateCoupon = function(item_name, item_type, cost, student_id) {



        coupon_code = $scope.coupon_code;



        if(coupon_code === undefined || coupon_code=='')

            return;
          updated_student_id = student_id;
          //Update the student id i.e., the parent may change his selection
        if(student_id!=0)
            updated_student_id =  $('#selected_child_id').val();



        route = '{{URL_COUPONS_VALIDATE}}';

        data= {

                '_method': 'post',

                '_token':$scope.getToken(),

                'coupon_code': coupon_code,

                'item_name': item_name,

                'item_type': item_type,

                'cost'     : cost,
                'student_id'     : updated_student_id

               };



        $http.post(route, data).success(function(result, status) {

           if(result.status==0) {



               alertify.error(result.message);

               return;

            }

            else{

              if(updated_student_id!=0) {
                $('#childrens_list_div').fadeOut(100);
              }

              $scope.test_amount  = result.amount_to_pay + $scope.online_classes_price;

                $scope.isApplied        = true;

                $scope.ngdiscount       = result.discount;

                $scope.discount_availed = result.discount;

                $scope.ngtotal          = result.amount_to_pay + $scope.online_classes_price;

                $('#is_coupon_applied').val('1');

                $('#discount_availed').val(result.discount);

                $('#after_discount').val(result.amount_to_pay + $scope.online_classes_price);

                $('#coupon_id').val(result.coupon_id);

                alertify.success(result.message);

                return;

            }



        });

        }





     /**

     * Returns the token by fetching if from from form

     */

    $scope.getToken = function(){

      return  $('[name="_token"]').val();

    }



 });


$('#subscribe_onlineclasses').click(function() {
    if ( $(this).prop('checked') ) {
      $('#is_onlineclass_applied').val('1')
    } else {
      $('#is_onlineclass_applied').val('0')
    }
  });

</script>



{{-- <script>

$(document).ready(function(){

    $('[data-toggle="tooltip"]').tooltip();

});

</script> --}}