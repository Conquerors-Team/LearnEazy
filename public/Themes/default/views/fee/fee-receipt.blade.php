<!DOCTYPE html>

<html>
<head>
 <title>{{$payment_record->feecategory_title}}</title>
</head>

<body>
<div style="border:1px solid #111;margin:5px;border-radius:5px;margin-bottom:50px;">
<div style="border:1px solid #222;margin:10px;padding:10px;border-radius:5px;">
<table width="100%" cellspacing="0px" cellpadding="0px">
  <tbody>
  
   <tr>
     <td rowspan="3"><img src="{{IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')}}" alt="logo" style="width:180px;display:block;margin:0px auto;padding:0px 0px 50px;"></td>
	 <td colspan="4" rowspan="3" style="text-align:center;text-decoration:underline;padding:0px 0px 50px;"><h2>RECEIPT</h2></td>
     <td colspan="3" style="color:#1c66b1;text-align:left;font-size:15px;"><strong>{{getSetting('site_title', 'site_settings')}}</strong>
     </td>
   </tr>
   
   <tr>
     <td colspan="3" style="color:#bcbdb2;text-align:left;font-size:15px;"><strong>{{getSetting('site_address', 'site_settings')}}</strong></td>
   </tr>
   
   <tr>
     <td colspan="3" style="text-align:left;font-size:15px;padding:0px 0px 50px;"><strong>Phone NO:{{getSetting('site_phone', 'site_settings')}}</strong></td>
   </tr>

    <tr>
     <td colspan="3" style="padding:5px 0px;text-align:left;">Reciept No. 
     <strong style="border-bottom: 1px dashed #111;padding-bottom: 3px;">{{makeNumber($payment_record->id,5)}}</strong></td>
     <td colspan="2">&nbsp;</td>
     <td colspan="3" style="padding:5px 0px;text-align:left;">Date: <strong style="border-bottom: 1px dashed #111;padding-bottom: 3px;">{{$payment_record->created_at}}</strong></td>
   </tr>


   
   <tr>
     <td colspan="5" style="padding:5px 0px;text-align:left;">Recieved with thank's form <strong style="border-bottom: 1px dashed #111;    padding-bottom: 3px;">{{$student_record->getName()}}</strong></td>
     <td colspan="3" style="padding:5px 0px;text-align:left;"> </td>
   </tr>
   
   <tr>
     <td colspan="5" style="padding:5px 0px;text-align:left;">S/O:<strong style="border-bottom: 1px dashed #111;padding-bottom:3px;"> {{$student_record->getFatherName()}} </strong></td>
     <td colspan="3" style="padding:5px 0px;text-align:left;">Phone no: <strong style="border-bottom: 1px dashed #111;padding-bottom: 3px;">{{$student_record->getPhoneNo()}}</strong></td>
   </tr>
   
   
    <tr>
     <td colspan="5" style="padding:5px 0px 20px;text-align:left;">Fee Details <strong style="border-bottom: 1px dashed #111;padding-bottom:3px;">{{$payment_record->feecategory_title}}</strong></td>
     <td colspan="3" style="padding:5px 0px;text-align:left;">Branch: <strong style="border-bottom: 1px dashed #111;padding-bottom: 3px;">{{$branch_name}} ({{ $branch_addr }})</strong></td>
   </tr>
     <tr>
     <td colspan="2" style="padding:5px 0px;text-align:left;">Rupees <strong style="border-bottom: 1px dashed #111;padding-bottom:3px;">{{$payment_record->paid_amount}}</strong></td>
     <td style="padding:5px 0px;text-align:left;">By <strong style="border-bottom: 1px dashed #111;padding-bottom:3px;">{{$payment_record->payment_mode}}</strong></td>
     
   </tr>
   
 <!--   <tr>
     <td colspan="8" style="padding:5px 0px 20px;text-align:left;">RS. <strong style="border-bottom: 1px dashed #111;padding-bottom:3px;">10,700.00</strong></td>
   </tr> -->
   
   <tr>
     <td colspan="3" style="padding:5px 0px;text-align:left;">* Check/Draft Subject to Relization</td>
     <td colspan="3" style="padding:5px 0px;text-align:left;">* Fee is not Refundable</td>
     <td colspan="2" style="padding:5px 0px;text-align:left;">* Authorized Signatory</td>
   </tr>
  </tbody>
</table>
</div>
</div>
 
</body>
</html>