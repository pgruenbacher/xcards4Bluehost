<?php

class TransfersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /transfers
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /transfers/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /transfers
	 *
	 * @return Response
	 */
	public function store()
	{
		$author=User::find(ResourceServer::getOwnerId());
		$max=$author->credits;
		$input=Input::all();
		$validator=Validator::make($input,array(
			'recipient'=>'required',
			'recipient.name'=>'required',
			'credits'=>'required|integer|between:0,'.$max,
		));
		$validator->sometimes('recipient.email','required|email', function($input){
		    return !Input::has('recipient.number');
		});
		$validator->sometimes('recipient.number','required|numeric',function($input){
			return !Input::has('recipient.email');
		});
		if($validator->fails()){
			return Response::json(array('error',$input,$validator->messages()->toArray()));
		}
		$email=(Input::has('recipient.email') ? $input['recipient']['email']: '');
		$phoneNumber=(Input::has('recipient.number')? $input['recipient']['number']:'');
		$name=$input['recipient']['name'];
		$addresses=Input::has('addresses')?$input['addresses']:null;
		$credits=$input['credits'];
		$user=User::where('email','=',$email)->where('active','=','1')->first();
		if(!isset($user->id)){
			$user=new User;
			$user->email=$email;
			$user->phone_number=$phoneNumber;
			$user->name=$name;
			$password=str_random(6);
			$user->password=Hash::make($password);
			$code=str_random(10);
			$user->code=$code;
			$user->active=0;
			$user->save();
			$user_id=$user->id;
		}
		else{
			$user_id=$user->id;
			$password=null;
			$code=null;
		}
		if($addresses){
			$address_ids=Input::get('addresses');
			$addresses=Addresses::find($address_ids);
			foreach($addresses as $address){
				$address->copyTo($user_id);
				}
		}
		if($credits>0){
			$transfer=new Transfers;
			$transfer->recipient_id=$user_id;
			$transfer->sender_id=$author->id;
			$transfer->credits=$credits;
			$transfer->save();
			$author->credits -= $credits;
			$author->save();
		}else{
			$credits=null;
			$transfer=null;
		}
		$link=URL::route('activate',array('activate'=>$code));
		$data=array(
			'link'=>$link,
			'user'=>$user,
			'credits' =>$credits,
	        'addresses'=>$addresses,
	        'password'=>$password,
	        'auth'=>$author
		);
		if($email){
			Mail::send('emails.transfers.share-activate',$data,function($message) use($author)
			{
				$message->from('info@x-presscards.com', 'paul gruenbacher');
			    $message->to($author->email,$author->name)->subject('Items have been shared with you!');
			});
		}else{
			$email=false;
		}
		if($phoneNumber){
			if($user->active){
				$message=$author->name.' has shared credits with you: login in to x-presscards.com to learn more';
			}else{
				$message=$author->name.' has shared and created an account for you: go to paulgruenbacher.com/xcards2/activate?activate='.$code.' to learn more';
			}
			Twilio::message($phoneNumber,$message);
			$text=true;
		}else{
			$text=false;
		}
		return Response::json(array('status'=>'success','transfer'=>$transfer,'user'=>$user->toArray(),'email'=>$email,'text'=>$text));
	}

	/**
	 * Display the specified resource.
	 * GET /transfers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(Input::has('accept')){
			$transfer=Transfers::find($id);
			if(Input::get('accept')=='true'){
				if($credits=$transfer->accept()){
					return Response::json(array('status'=>'success','message'=>'accepted','credits'=>$credits));
				}
			}else{
				if($transfer->revert()){
					return Response::json(array('status'=>'success','message'=>'reverted'));
				}
			}
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /transfers/{id}/edit
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
	 * PUT /transfers/{id}
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
	 * DELETE /transfers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}