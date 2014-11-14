<?php

class SurveysController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /surveys
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /surveys/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}
	public function contact(){
		$input=Input::all();
		$validator=Validator::make($input,
		array( 
			'email'=>'required|email',
			'subject'=>'required',
			'text'=>'required'
			)
		);
		if($validator->fails()){
			return Response::json(array('status'=>'invalid','message'=>$validator->messages()->toArray()));
		}
		Mail::queue('emails.contact.contact',array('text'=>$input['text']), function($message) use($input)
		{
			$message->from($input['email'], 'website guest');
		    $message->to('info@x-presscards.com')->subject($input['subject']);
		});
		Return Response::json(array('status'=>'success'));
	}
	/**
	 * Store a newly created resource in storage.
	 * POST /surveys
	 *
	 * @return Response
	 */
	public function store()
	{
		// $validator=Validator::make(Input::all(),array(
			// 'comments'=>'required',
			// 'rating'=>'required',
			// 'price'=>'required',
		// ));
		$survey=Survey::create(Input::all());
		if(isset($suvey)){
			return Response::json(array('status'=>'success','survey'=>$survey,'input'=>Input::all()));
		}
	}

	/**
	 * Display the specified resource.
	 * GET /surveys/{id}
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
	 * GET /surveys/{id}/edit
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
	 * PUT /surveys/{id}
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
	 * DELETE /surveys/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}