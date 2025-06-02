@include('emails.template_header')


  
   
   <div class="row">
    <div class="col-lg-12">
    	<p style="font-size:20px;margin:11px 0;">Dear {{$user_name}}, </p>
      <p style="font-size:20px;margin:11px 0;">Greetings,</p>
	<p style="font-size:20px;margin:11px 0;">Your institute {{$ins_name}} was {{$status_message}}. Please contact admin for further details.</p>
  {{-- <p style="font-size:20px;margin:11px 0;"><strong>Email:</strong> {{$email}}</p>
  <p style="font-size:20px;margin:11px 0;"><strong>Password:</strong> {{$password}}</p> --}}


   {{--  <br>
    <p style="font-size:20px;margin:11px 0;"><a href="{{URL_USERS_LOGIN}}"> Click here to Login</a></p> --}}
  <br><br>


  
<p style="font-size:20px;margin:11px 0;">Sincerely, </p>
<p style="font-size:20px;margin:11px 0;">Customer Support Services</p>

	</div>
   </div>



@include('emails.disclaimer')


@include('emails.template_footer')