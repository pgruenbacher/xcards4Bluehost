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
	public function create()
	{
		//
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
		foreach($input as $request){
			if(!isset($request['name'])){
				return Response::json(array('status'=>'invalid','message'=>'name required'));
			}
			if(isset($request['addressId'])){
				$address=Addresses::find($request['addressId']);
				$address=$address->toArray();
				unset($address['id'],$address['updated_at'],$address['deleted_at']);
				$address['user_id']=$user->id;
				$address['updated_at']=date('Y-m-d h:m:s');
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
		//
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