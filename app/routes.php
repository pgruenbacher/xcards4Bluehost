<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/',array(
	'as'=>'home', 
	function()
	{
		return 'hello';
	}
));
Route::get('activate',array(
	'as'=>'activate',
	'uses'=>'AccountController@activate',
));
Route::group(array('before'=>'angularFilter','prefix'=>'api'),function(){
	/****Insecure OAUTH*******/
	Route::get('oauth/access_token',function(){
		return AuthorizationServer::performAccessTokenFlow();
	});
	Route::get('check',function(){
		try{$bool=ResourceServer::isValid();}catch(Exception $e){$bool=false;}
		if($bool){
			$user=User::find(ResourceServer::getOwnerId());
		}else{
			$user=null;
		}
		return Response::json(array('valid'=>$bool,'user'=>$user));
	});
	Route::post('user','AccountController@store');
	/****Secure OUATH*******/
	Route::group(array('before'=>'oauth'),function(){
		Route::post('change',array(
			'uses'=>'AccountController@change'
		));
		Route::resource('addresses','AddressesController');
		Route::get('user/auth',function(){
			$user=User::find(ResourceServer::getOwnerId());
			Auth::login($user);
			return Response::json(array($user->toArray()));
		});
	});
});

