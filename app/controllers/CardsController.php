<?php

class CardsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /cards
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /cards/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /cards
	 *
	 * @return Response
	 */
	public function store()
	{
		$user=User::find(ResourceServer::getOwnerId());
		$card=new Cards;
		$validate=Validator::make(Input::all(),
		array(
			'settingId'=>'required',
		));
		if($validate->fails()){
			return Response::json(array(
				'errors'=>$validate->messages()->toJson(),
				'input'=>Input::all(),
				'status'=>'validation',
			));
		}
		else{
			$card=new Cards;
			$card->cardsetting_id=Input::get('settingId');
			$saved=$user->cards()->save($card);
			if($saved){
				return Response::json(array(
					'status'=>'succes',
					'message'=>'your card has been created.',
					'card'=>$card
				));
			}else{
				return Response::json(array(
					'status'=>'error',
					'message'=>'could not save card'
				));
			}
		}
	}

	/**
	 * Display the specified resource.
	 * GET /cards/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$card=Cards::find($id);
		$card['croppedImage']=$card->croppedImage()->first();
		$card['originalImage']=$card->originalImage()->first();
		$card['cardSetting']=$card->cardSetting()->first();
		$card['frontDrawing']=$card->frontDrawing()->first();
		$card['backDrawing']=$card->backDrawing()->first();
		$card['recipients']=$card->addresses()->get();
		if(isset($card->id)){
			return Response::json(array('status'=>'success','card'=>$card));
		}else{
			return Response::json(array('status'=>'error'));
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /cards/{id}/edit
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
	 * PUT /cards/{id}
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
	 * DELETE /cards/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	public function addresses($cardId){
		$validator=Validator::make(
			array('recipients' => Input::get('recipients')),
		    array('recipients' => array('required', 'array'))
		);
		if($validator->fails()){
			return Response::json(array('status'=>'error'));
		}else{
			$card=Cards::find($cardId);
			$status=$card->addresses()->sync(Input::get('recipients'));
			if($status){
				return Response::json(array('status'=>'succes','recipients'=>$card->recipients));
			}
		}
	}
}