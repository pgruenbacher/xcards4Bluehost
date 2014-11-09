<?php

class AddressesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /addresses
	 *
	 * @return Response
	 */
	public function index(){
		$user=User::find(ResourceServer::getOwnerId());
		$addresses=$user->addresses()->select('id','name','email','address','number')->take(10)->get()->toArray();
		return Response::json($addresses);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /addresses/create
	 *
	 * @return Response
	 */
	public function create()
	{
		
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /addresses
	 *
	 * @return Response
	 */
	public function store()
	{
		$user=User::find(ResourceServer::getOwnerId());
		$validate=Validator::make(Input::all(),
		array(
		'name'=>'required',
		'address'=> 'required',
		'email'=> 'email',
		'number'=>'numeric',
		'cityName'=>'required',
		'zipCode'=>'required',
		'deliveryLine1'=>'required',
		'lastLine'=>'required',
		'stateAbbreviation'=>'required'
		));
		if($validate->fails()){
			return Response::json(array(
				'errors'=>$validate->messages()->toJson(),
				'status'=>'validation',
			));
		}else{
			$address=new Addresses;
			$address->name=Input::get('name');
			$address->address=Input::get('address');
			if(Input::has('number')){
				$address->number=Input::get('number');
			}
			if(Input::has('email')){
				$address->email=Input::get('email');
			}
			$address->city_name=Input::get('cityName');
			$address->delivery_line_1=Input::get('deliveryLine1');
			$address->last_line=Input::get('lastLine');
			$address->state_abbreviation=Input::get('stateAbbreviation');
			$address->zip_code=Input::get('zipCode');
			if(Input::has('plus4Code')){
				$address->plus4_code=Input::get('plus4Code');
			}
			if($user->addresses()->save($address)){
				Return Response::json(array('status'=>'success','message'=>'succesfully saved'));
			}
		}
	}

	/**
	 * Display the specified resource.
	 * GET /addresses/{id}
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
	 * GET /addresses/{id}/edit
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
	 * PUT /addresses/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$validate=Validator::make(Input::all(),
		array(
		'name'=>'required',
		'address'=> 'required',
		'email'=> 'email',
		'number'=>'numeric'
		));
		if($validate->fails()){
			return Response::json(array(
				'errors'=>$validate->messages()->toJson(),
				'status'=>'validation',
			));
		}else{
			$address=Addresses::find($id);
			$address->name=Input::get('name');
			$address->email=Input::get('email');
			$address->address=Input::get('address');
			$address->number=Input::get('number');
			if($address->save()){
				Return Response::json(array('status'=>'success','message'=>'succesfully saved'));
			}
		}
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /addresses/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}