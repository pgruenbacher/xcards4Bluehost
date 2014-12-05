<?php

class JobsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /jobs
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /jobs/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /jobs
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /jobs/{id}
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
	 * GET /jobs/{id}/edit
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
	 * PUT /jobs/{id}
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
	 * DELETE /jobs/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	public function tempUser($data){
		$user=User::where('email','=',$data['email'])
		->orwhere('phone_number','=',$data['number'])
		->where('active','=',0)
		->first();
		if(isset($user->id)){
			return $user;
		}
		$user=new User;
		$user->name=$data['name'];
		$user->email=isset($data['email'])?$data['email']:str_random(6);
		$user->phone_number=isset($data['number'])?$data['number']:0;
		$user->active=0;
		if($user->save()){
			return $user;
		}
	}
	public function sendRequestText($job,$data){
		$job_id = $job->getJobId();
		$ejob = Jobs::where('job_id',$job_id)->first();
		$ejob->status = 'running';
		$ejob->save();
		$tempUser=$this->tempUser($data);
		$sender=User::find($data['user']);
		$request=new AddressRequests;
		$request->sender_id=$sender->id;
		$request->responder_id=$tempUser->id;
		$request->method='text';
		$request->token=str_random(10);
		$request->save();
		$message=$sender->name.'would like your address to send a card of gift. Please go to paulgruenbacher.com/xcards2/addressRequest/'.$request->token;
		$twilio=Twilio::message($tempUser->phone_number,$message);
		Log::info($twilio);
		if(true){
			$ejob->status = 'finished';
    		$ejob->save();
			return true;
		}else{
			$ejob->status='failure';
			$ejob->save();
			return false;
		}
	}
	public function sendSocialEmail($job,$data){
		$job_id = $job->getJobId();
		$ejob = Jobs::where('job_id',$job_id)->first();
		$ejob->status = 'running';
		$ejob->save();
		Mail::send('emails.auth.social',array('name'=>$data['name'],'password'=>$data['password']), function($message) use($data)
		{
			$message->from('info@x-presscards.com', 'paul gruenbacher');
		    $message->to($data['email'],$data['name'])->subject('Welcome!');
		});
		$ejob->status = 'finished';
		$ejob->save();
		return true;
	}
	public function sendRequestEmail($job,$data){
		$job_id = $job->getJobId();
		$ejob = Jobs::where('job_id',$job_id)->first();
		$ejob->status = 'running';
		$ejob->save();
		
		$tempUser=$this->tempUser($data);
		$sender=User::find($data['user']);
		$request=new AddressRequests;
		$request->sender_id=$sender->id;
		$request->responder_id=$tempUser->id;
		$request->method='email';
		$request->token=str_random(10);
		$request->save();
		
		Mail::send('emails.address_request',array('name'=>$data['name'],'token'=>$request->token,'sender'=>$sender), function($message) use($data,$sender)
		{
			$message->from('info@x-presscards.com','paul gruenbacher');
		    $message->to($data['email'],$data['name'])->subject($sender->name.' has requested your address');
		});
		if(true){
			$ejob->status = 'finished';
    		$ejob->save();
			return true;
		}else{
			$ejob->status='failure';
			$ejob->save();
			return false;
		}
	}
	public function crop($job,$data){
		$image=Images::find($data['imageId']);
		$image->crop($data['x'],$data['y'],$data['w'],$data['h']);
	}
	public function thumbnail($job,$data){
		$job_id = $job->getJobId();
		$ejob = Jobs::where('job_id',$job_id)->first();
		$ejob->status = 'running';
		$ejob->save();
		$thumbnail=new Images;
		if($thumbnail->saveThumbnail($data['id'])){
			$ejob->status = 'finished';
    		$ejob->save();
			return true;
		}else{
			$ejob->status='failure';
			$ejob->save();
			return false;
		}
		
	}
}