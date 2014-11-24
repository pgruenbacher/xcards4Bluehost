<?php

class UsersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /users
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /users/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /users
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /users/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user=User::where('id','=',$id)->where('active','=',1)->select('name')->first();
		return Response::json(array('status'=>'success','user'=>$user));
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /users/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /users/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user=User::find(ResourceServer::getOwnerId());
		if($user->id!==$id){
			return Response::json(array('status'=>'401','message'=>'cant edit that user'));
		}
		$validator=Validator::make(Input::all(),array(
			'name'=>'required',
			'number'=>'sometimes|numeric'
		));
		$validator->sometimes(array(
			'stateAbbreviation',
			'cityName',
			'zipCode',
			'deliveryLine1',
			'lastLine',
			), 'required', function($input){
		    	return Input::has('address');
		});
		if($validator->fails()){
			return Response::json(array('status'=>'invalid','message'=>$validator->messages()->toArray()));
		}
		$user->name=Input::get('name');
		if(Input::has('number')){
			$user->phone_number=Input::get('number');
		}
		if(Input::has('address')){
			$address=Input::get('address');
			if(isset($user->homeAddress->id)){
				$newAddress=$user->homeAddress()->first();
				if(!isset($newAddress->id)){
					return Response::json(array('status'=>'error','message','could not find that address'));
				}
			}else{
				$newAddress=new Addresses;
			}
			$newAddress->address=Input::get('address');
			if(Input::has('plus4Code')){
				$newAddress->plus4_code=Input::get('plus4Code');
			}
			$newAddress->zip_code=Input::get('zipCode');
			$newAddress->city_name=Input::get('cityName');
			$newAddress->delivery_line_1=Input::get('deliveryLine1');
			$newAddress->last_line=Input::get('lastLine');
			$newAddress->state_abbreviation=Input::get('stateAbbreviation');
			$newAddress->id=$user->id;
			$newAddress->users_home=1;
			if(Input::has('number')){
				$newAddress->number=Input::get('number');
			}
			$newAddress->save();
			$user->address_id=$newAddress->id;
		}
		if(!$user->save()){
			return Response::json(array('status'=>'error','message','couldnt save user'));
		}
		return Response::json(array('status'=>'success','user'=>$user));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /users/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}