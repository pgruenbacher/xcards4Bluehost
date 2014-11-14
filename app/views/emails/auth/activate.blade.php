<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>X-Cards 4 Account</title>
    <style type="text/css">
      body {
        padding     : 25px 0;
        font-family : Helvetica;
      }
    </style>
  </head>
  <body>
  	<div id="logo">
    	<a href="{{URL::route('home')}}"></a>
 	</div><!--end logo-->
  	<p>Hello {{$name}}, </p>
  	<br><br>
  	<p>Welcome to X-Press Cards! We offer customized cards that you can have sent, simply by ordering from your computer or phone!</p>
	<p>Please activate your account using the following link.</p>
	<br><br>
	
	--<br>
	<a href="{{$link}}">{{$link}}</a><br>
	--
  </body>
</html>