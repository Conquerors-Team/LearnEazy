<!DOCTYPE html>

<html>
 <head>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
</head>
 
 <body onload="printMe()" id="printableArea">

<h4 align="center">{{ucwords($institute->institute_name)}} - {{$title}}</h4>
 	<div class="row vertical-scroll">
  
    <table class="data-table-student" style="border-collapse: collapse;">

    <thead>
        <th style="border:1px solid #000;">{{getPhrase('sno')}}</th>
        <th style="border:1px solid #000;" >{{getPhrase('name')}}</th>
        <th style="border:1px solid #000;">{{getPhrase('amount')}}</th>
        <th style="border:1px solid #000;">{{getPhrase('paid_amount')}}</th>
        <th style="border:1px solid #000;">{{getPhrase('discount')}}</th>
        <th style="border:1px solid #000;">{{getPhrase('balance')}}</th>
       
       
        
    </thead>
    <tbody>
    <?php $sno =1;?>

     @foreach($records as $record)
    <tr>
        
        <td style="border:1px solid #000;">{{$sno++}}</td>
        <td style="border:1px solid #000;">{{ucfirst($record['name'])}}</td>
        <td style="border:1px solid #000;">{{$currency}}{{$record['amount']}}</td>
        <td style="border:1px solid #000;">{{$currency}}{{$record['paid_amount']}}</td>
        <td style="border:1px solid #000;">{{$currency}}{{$record['discount']}}</td>
        <td style="border:1px solid #000;">{{$currency}}{{$record['balance']}}</td>
        
        
       
    </tr> 
    @endforeach
    </tbody>
    </table>
</div>
  <style>
  	
  	.data-table-student{    width: 71%;
    margin: 0 auto;}
    .data-table-student th,.data-table-student td{padding: 14px;}
  </style>
  
  
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
</script>

<script>
	function printMe(){
		var printContents = document.getElementById('printableArea').innerHTML;
		var originalContents = document.body.innerHTML;

		document.body.innerHTML = 
              "<html><head><title></title></head><body>" + 
              printContents + "</body>";;

     window.print();

     document.body.innerHTML = originalContents;
	};
</script> 


 </body>
</html>