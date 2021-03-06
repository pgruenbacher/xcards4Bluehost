<?php

class RequestsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /requests
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /requests/create
	 *
	 * @return Response
	 */
	public function create($token)
	{
		$request=AddressRequests::where('token','=',$token)->first();
		$sender=$request->sender()->first();
		$responder=$request->responder()->first();
		$agent=Agent::is('IE');
		return View::make('AddressRequestForm',array(
			'sender'=>$sender,
			'responder'=>$responder,
			'request'=>$request,
			'agent'=>$agent,
		));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /requests
	 *
	 * @return Response
	 */
	public function check(){
		$contacts=Input::all();
		$emails=array();
		$numbers=array();
		foreach($contacts as $request){
			$validator=Validator::make($request,array(
				'number'=>'sometimes|integer',
				'email'=>'sometimes|email',
			));
			if($validator->fails()){
				return Response::json(array('status'=>'invalid','messages'=>$validator->messages()->toArray()));
			}
		}
		foreach($contacts as $array){
			if(isset($array['email'])){
				$emails[]=$array['email'];
			}
			if(isset($array['number'])){
				$numbers[]=$array['number'];
			}
		};
		$existing=array();
		$query=DB::table('addresses');
		if(!empty($emails)){
          $query->whereIn('email', $emails);
		}
		if(!empty($numbers)&&!empty($emails)){
			$query->orWhereIn('number', $numbers);
		}else if(!empty($numbers)&& empty($emails)){
			$query->whereIn('number',$numbers);
		}
		if(!empty($emails)||!empty($numbers)){
			$existing=$query->get();
		}
		function duplicates($array,$address){
			foreach($array as $record){
				if($address==$record['address']){
					return true;
				}
			}
			return false;
		}
		$response=array();
		if(!empty($existing)){
			foreach($existing as $record){
				foreach($contacts as $contact){
					$number=isset($contact['number'])? $contact['number']:'';	
					$email=isset($contact['email'])? $contact['email']:'';
					if(($record->email==$email && $email!='')||($record->number==$number && $number!='')){
						$array=array(
						'email'=>$email,
						'number'=>$number,
						'address'=>$record->address,
						'id'=>$record->id
						);
						if(!duplicates($response,$record->address)){
							$response[]=$array;
						}
					}
				}
			}
		}
		return Response::json(array('status'=>'success','existing'=>$response));
	}
	public function store()
	{
		$input=Input::all();
		$user=User::find(ResourceServer::getOwnerId());
		//Validation
		foreach($input as $request){
			$validator=Validator::make($request,array(
				'name'=>'required',
				'number'=>'sometimes|integer',
				'email'=>'sometimes|email',
				'addressId'=>'sometimes|integer',
			));
			if($validator->fails()){
				return Response::json(array('status'=>'invalid','messages'=>$validator->messages()->toArray()));
			}
		}
		foreach($input as $request){
			if(isset($request['addressId'])){
				$address=Addresses::find($request['addressId']);
				$address=$address->toArray();
				unset($address['id'],$address['updated_at'],$address['deleted_at']);
				$address['user_id']=$user->id;
				$address['updated_at']=date('Y-m-d H:i:s');
				$status=Addresses::insert($address);
			}
		}
		foreach($input as $request){
			if(isset($request['addressId'])||!isset($request['email'])){continue;}
			if(isset($request['number'])){$number=$request['number'];}else{$number=null;}
			$job_id=Queue::push('JobsController@sendRequestEmail', array(
				'email' => $request['email'],
				'name'  => $request['name'],
				'number'=> $number,
				'user'	=> $user->id
			));
			Jobs::create(array('job_id' => $job_id));
		}
		foreach($input as $request){
			if(isset($request['addressId'])||!isset($request['number'])){continue;}
			if(isset($request['email'])){$email=$request['email'];}else{$email=null;}			
			$job_id=Queue::push('JobsController@sendRequestText', array(
				'email' => $email,
				'name'  => $request['name'],
				'number' => $request['number'],
				'user'	=> $user->id
			));
			Jobs::create(array('job_id' => $job_id));
			
		}
		return Response::json(array('status'=>'success','input'=>Input::all()));
	}

	/**
	 * Display the specified resource.
	 * GET /requests/{id}
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
	 * GET /requests/{id}/edit
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
	 * PUT /requests/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		
		$request=AddressRequests::find($id);
		$responder=$request->responder()->first();
		$sender=$request->sender()->first();
		$input=Input::all();
		$validator=Validator::make($input,array(
			'cityName'=>'required',
			'stateAbbreviation'=>'required',
			'zipCode'=>'required',
			'deliveryLine1'=>'required',
			'name'=>'required'
		));
		if($validator->fails()){
			return Redirect::refresh()->withErrors($validator);
		}
		if(!Input::has('lastLine')){
			$input['lastLine']=$input['cityName'].', '.$input['stateAbbreviation'].' '.$input['zipCode'];
		}
		if(!Input::has('address')){
			$input['address']=$input['deliveryLine1'].' '.$input['lastLine'];
		}
		$address=new Addresses;
		$address->address=$input['address'];
		$address->city_name=$input['cityName'];
		$address->state_abbreviation=$input['stateAbbreviation'];
		$address->delivery_line_1=$input['deliveryLine1'];
		$address->last_line=$input['lastLine'];
		$address->zip_code=$input['zipCode'];
		if(Input::has('plus4Code')){
			$address->plus4_code=$input['plus4Code'];
		}
		if(isset($responder->email)){
			$address->email=$responder->email;
		}
		if(isset($responder->number)){
			$address->number=$responder->number;
		}
		$date=new DateTime();
		$request->finished_at=$date->format('Y-m-d h:m:s');
		$request->save();
		$status=$sender->addresses()->save($address);
		$status2=$responder->addresses()->save($address);
		return Redirect::to('/');
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /requests/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}