<?php
/*
 * Angular JSON Filter
 * 
 */
 Route::filter('angularFilter', function() {
    switch (Request::method()) {
        case 'POST':
            $request = Request::instance();
		    // Now we can get the content from it
		    $content = $request->getContent();
			// Now we can get the content from it
			$pairs = explode("&", $content);
			$result=array();
			//I had to use curly brackets to indicate POST arrays and objects from angular.
		    foreach ($pairs as $pair) {
		        $nv = explode("=", $pair);
		        $name = urldecode($nv[0]);
				$value = urldecode($nv[1]);
				$keyParts = preg_split('/[\{\}]+/', $name, -1, PREG_SPLIT_NO_EMPTY);
			    $ref = &$result;
			    while ($keyParts) {
			        $part = array_shift($keyParts);
			        if (!isset($ref[$part])) {
			            $ref[$part] = array();
			        }
			        $ref = &$ref[$part];
			    }
				$ref=$value;
		    }
		    $input=$result;
			Input::replace($input);
			Log::info('content',array('content'=>$content,'filter'=>$input,'input'=>Input::all()));
            break;
		case 'PUT':
            $request = Request::instance();
		    // Now we can get the content from it
		    $content = $request->getContent();
			// Now we can get the content from it
			$pairs = explode("&", $content);
			$result=array();
			//I had to use curly brackets to indicate POST arrays and objects from angular.
		    foreach ($pairs as $pair) {
		        $nv = explode("=", $pair);
		        $name = urldecode($nv[0]);
				$value = urldecode($nv[1]);
				$keyParts = preg_split('/[\{\}]+/', $name, -1, PREG_SPLIT_NO_EMPTY);
			    $ref = &$result;
			    while ($keyParts) {
			        $part = array_shift($keyParts);
			        if (!isset($ref[$part])) {
			            $ref[$part] = array();
			        }
			        $ref = &$ref[$part];
			    }
				$ref=$value;
		    }
		    $input=$result;
			Input::replace($input);
			Log::info('content',array('content'=>$content,'filter'=>$input,'input'=>Input::all()));
            break;
    }
	
            // Do stuff before every request to your application...
});
/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
