<form action="{{URL::route('post-login')}}" method="POST">
	<input name="email" placeholder="email"/>
	<input name="password" type="password"/>
	<button type="submit">Submit</button>\
	{{Form::token()}}
</form>
