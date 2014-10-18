<?php

class AccountController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /account
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /account/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /account
	 *
	 * @return Response
	 */
	public function store()
	{
		$validate=Validator::make(Input::all(),
		array(
		'name'=>'required',
		'email'=> 'required|max:50|email|unique:users,email,NULL,id,active,1',
		'password'=> 'required|min:6',
		'password2'=> 'required|same:password',
		));
		if($validate->fails()){
			return Response::json(array(
				'errors'=>$validate->messages()->toJson(),
				'status'=>'validation',
			));
		}else{
			$name=Input::get('name');
			$email=Input::get('email');
			$password=Input::get('password');
			$address=Input::get('address');
			//Activation Code
			$code=str_random(60);
			// if(Session::has('user')){
				// $user=User::find(Session::get('user'));
			// }
			$user=new User;
			$user->guest=0;
			$user->name=$name;
			$user->email=$email;
			$user->password=Hash::make($password);
			$user->code=$code;
			$user->active=0;
			$user->save();
			$link=URL::route('activate',array(
				'activate'=>$code,
			));
			//Save address of user
			// $address_data=array(
			// 'addressee'=>array($user->first.' '.$user->last),
			// 'address'=>array($address),
			// 'email'=>array($email),
			// );
			// $addresses=new Addresses;
			// $addresses->saveArray($user,$address_data);	
			if(isset($user->email)){
				Mail::send('emails.auth.activate',array('user'=>$user,'link'=>$link), function($message) use($user)
				{
					$message->from('info@x-presscards.com', 'paul gruenbacher');
				    $message->to($user->email,$user->first)->subject('Welcome!');
				});
				return Response::json(array(
					'status'=>'succes',
					'message'=>'your account has been created. An email has been sent for you to activate'
				));
			}else{
				return Response::json(array(
					'status'=>'error',
					'message'=>'could not save user'
				));
			}
		}
	}

	/**
	 * Display the specified resource.
	 * GET /account/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /account/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /account/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /account/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	public function activate(){
		$code=Input::get('activate');
		$user = User::where('code','=',$code)->where('active','=','0');
		if($user->count()){
			$user=$user->first();
			//Update user to active state
			$user->active =1;
			$user->code ='';
			if($user->save()){
				return Redirect::route('home')
				->with('global','Congratulations your account has been activated, you may now sign in');
			}
			return Response::json(array('status'=>'error'));
		}else{
			return Response::json(array('status'=>'error'));
		}
	}
	public function change(){
		$validator=Validator::make(Input::all(), array(
		'oldPassword'=>'required',
		'password'=>'required|min:6',
		'password2'=>'required|same:password'
		));
		if($validator->fails()){
			return Response::json(array('status'=>'invalid','message'=>$validator->messages()->toJson()));
		}else{
			$user=User::find(ResourceServer::getOwnerId());
			if(isset($user->id)){
				$old_password=Input::get('oldPassword');
				$password=Input::get('password');
				if(Hash::check($old_password,$user->getAuthPassword())){
					$user->password=Hash::make($password);
					if($user->save()){
						return Response::json(array('status'=>'success','message'=>'password has been changed'));
					}
				}else{
					return Response::json(array('status'=>'invalid','message'=>'old password doesn not match'));
				}
			}else{
				return Resonse::json(array('status'=>'failure','message'=>'user is not authenticated'));
			}
		}
	}
}
