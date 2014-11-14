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
	  	<p>Hello {{$name}},</p></br>
		</br>
		<p>Have you forgotten your password? If so, follow this link and use the temporary password below to login in.</p>
		<p>Otherwise, you can safely ignore this email</p>
		<p>Temporary Password: <strong>{{$password}}</strong></p>
		<span>------------------</span><br>
		<p>Follow this link: {{$link}}</p>
		<span>--------------</span>
		</br>
		<p>Best,</p>
		<p>The X-Press Cards Team</p>
	</body>
</html>
