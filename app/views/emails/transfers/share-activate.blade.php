<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>X-Cards Share</title>
    <style type="text/css">
      <style type="text/css">
		<!--
		body {
		  font-family:Tahoma;
		}
		
		img {
		  border:0;
		  width:20%;
		}
		
		#page {
		  max-width:600px;
		  margin:0 auto;
		  padding:15px;
		
		}
		
		#logo {
		  float:left;
		  margin:0;
		}
		
		#address {
		  height:181px;
		  margin-left:250px; 
		}
		
		table {
		  width:100%;
		}
		
		td {
		padding:5px;
		}
		
		tr.odd {
		  background:#e1ffe1;
		}
		-->
    </style>
  </head>
  <body>
	<div id="page">
  	<div id="logo">
    	<a href="{{URL::route('home')}}"><img src="{{URL::asset('assets/images/mrbunny-white.gif')}}"></a>
 	</div><!--end logo-->
  	<p>Hello {{$user->name}}, </p>
  	<br><br>
  	<p>{{$auth->name}} has shared {{isset($credits)? $credits.' credits,':''}} {{isset($addresses)? count($addresses).' addresses':''}} with you</p>
  	@if(isset($addresses))
  	<table>
  		<thead>
  			<tr>
  				<th style="padding:4px;">Name</th>
  				<th style="padding:4px;">Addresss</th>
  			</tr>
  		</thead>
  		<tbody>
  			<?php $i=0; ?>
  			@foreach($addresses as $address)
  				<tr class="{{$i%2==0?'even':'odd'}}">
  					<td style="padding:4px;">{{$address->name}}</td>
  					<td style="padding:4px;">{{$address->address}}</td>
  				</tr>
  				<?php $i++; ?>
  			@endforeach
  		</tbody>
  	</table>
  	@endif
	@if($user->active == 0)
	<p>Because you do not have an account with us, {{$user->name}} went ahead and made one for you.</p>
	<p>Any credits transferred to your account can be used to send custom cards free of charge.</p>
	<p>You can activate your account using the following link.</p>
	<br><br>
	
	--<br>
	<a href="{{$link}}">{{$link}}</a><br>
	--
	<p>You can then use this <strong>temporary password</strong> to login: <span style="color:##000066;">{{$password}}</span></p>
	<p>after which you may change your password</p>
	@endif
	</div>
  </body>
</html>