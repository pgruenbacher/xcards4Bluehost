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
Route::get('login',array(
	'as'=>'login',
	'uses'=>'AccountController@login'
));
Route::post('login',array(
	'before'=>'csrf',
	'as'=>'post-login',
	'uses'=>'AccountController@postLogin'
));
Route::get('test',function(){
	if(Auth::check()){
			return Auth::user();
			foreach(Auth::user()->roles()->get() as $role){
				Log::info($role);
				if($role->type==='admin'){
					return 'true';
				}
			}
		}
		return 'false';
	$cards=Cards::with('addresses','croppedImage','frontDrawing','backDrawing','cardSetting')
	->where('id','=',128)->get();
	$data=array(
		'cards'=>$cards
	);
	//$snappy = App::make('snappy.pdf');
	//To file
	//$snappy->generateFromHtml('<h1>Bill</h1><p>You owe me money, dude.</p>', '/tmp/bill-125.pdf');
// 	
	// return new Response(
	    // $snappy->getOutputFromHtml($html),
	    // 200,
	    // array(
	        // 'Content-Type'          => 'application/pdf',
	        // 'Content-Disposition'   => 'attachment; filename="file.pdf"'
	    // )
	// );
	//return View::make('pdf/html2pdf')->with($data);
	$pdf = PDF::loadView('pdf/html2pdf', $data)
	//->setOption('dpi',100)
	->setOption('page-width','1368pt')
	->setOption('page-height','936pt')
	->setOption('margin-top',0)
	->setOption('margin-bottom',0)
	->setOption('margin-left',0)
	->setOption('margin-right',0);
	return $pdf->download('asdfasdfasdf.pdf');
});
Route::get('/',array(
	'as'=>'home', 
	function()
	{
		return 'hello';
	}
));
/*******Queues*******/
Route::post('queue/receive', function()
{
    return Queue::marshal();
});
/*****End Queues ******/

Route::get('addressRequest/{token}',array(
	'as'=>'addressRequest',
	'uses'=>'RequestsController@create'
));
Route::get('resetPassword/{code}',array(
	'as'=>'retrievePassword',
	'uses'=>'AccountController@resetPassword'
));
Route::post('requests/{id}',array(
	'as'=>'addressRequestPost',
	'uses'=>'RequestsController@update'
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
	Route::post('contact',array(
		'uses'=>'SurveysController@contact'
	));
	Route::post('forgotPassword',array(
		'uses'=>'AccountController@forgotPassword'
	));
	Route::get('check',function(){
		try{$bool=ResourceServer::isValid();}catch(Exception $e){$bool=false;}
		if($bool){
			$user=User::with('roles','homeAddress','orders','incomingTransfers','outgoingTransfers')->find(ResourceServer::getOwnerId());
			//$user['roles']=$user->roles()->get();
			return Response::json(array('valid'=>$bool,'user'=>$user));
		}else{
			$guest=new User;
			$guest->active=1;
			$guest->guest=1;
			$guest->email=str_random(10);
			$password=str_random(10);
			$guest->password=Hash::make($password);
			$guest->save();
			$guest->assignRole('guest');
			return Response::json(array('valid'=>$bool,'user'=>$guest,'password'=>$password));
		}
		
	});
	Route::post('user','AccountController@store');
	Route::post('imageUploadGuest/{id}',array(
			'uses'=>'ImagesController@upload'
		));
	/****Secure OUATH*******/
	Route::group(array('before'=>'oauth'),function(){
		Route::post('change',array(
			'uses'=>'AccountController@change'
		));
		Route::post('account/copy',array(
			'uses'=>'AccountController@copyAssets'
		));
		Route::resource('users','UsersController');
		Route::get('user/find',array(
			'uses'=>'AccountController@find'
		));
		Route::get('shared',array(
			'uses'=>'AccountController@shared'
		));
		Route::resource('surveys','SurveysController');
		Route::resource('requests','RequestsController');
		Route::post('requests/check',array(
			'uses'=>'RequestsController@check'
		));
		Route::resource('transfers','TransfersController');
		Route::resource('cards.images','CardsImagesController');
		Route::resource('cards','CardsController');
		Route::post('cards/{cardId}/images/{imageId}/message',array(
			'uses'=>'CardsImagesController@message',
		));
		Route::post('cards/{id}/message',array(
			'uses'=>'CardsController@message'
		));
		Route::post('cards/{cardId}/addresses',array(
			'uses'=>'CardsController@addresses',
		));
		Route::resource('orders','OrdersController');
		Route::post('creditOrder',array(
			'uses'=>'OrdersController@credit'
		));
		Route::post('orders/products',array(
			'uses'=>'OrdersController@product'
		));
		Route::resource('pricings','PricingsController');
		Route::resource('addresses','AddressesController');
		Route::post('imageUpload',array(
			'uses'=>'ImagesController@upload'
		));
		// Route::get('user/auth',function(){
			// $user=User::find(ResourceServer::getOwnerId());
			// Auth::login($user);
			// return Response::json(array($user->toArray()));
		// });
	});
});

