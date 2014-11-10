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
		$user=User::find(ResourceServer::getOwnerId());
		$cards=$user->cards()->get();
		if(Input::has('filter')){
			$filter=Input::get('filter');
		}		
		$cards=Cards::with('originalImage.thumbnail','croppedImage.thumbnail','cardSetting','frontDrawing.thumbnail','backDrawing.thumbnail','addresses')
		->where('user_id','=',$user->id)->get();
		$cards=$cards->toArray();
		foreach ( $cards as $k=>$v )
		{
		  Log::info($k);
		  $cards[$k] ['originalImage'] = $cards[$k] ['original_image'];
		  unset($cards[$k]['original_image']);
		  $cards[$k]['cardSetting'] = $cards[$k]['card_setting'];
		   unset($cards[$k]['card_setting']);
		  $cards[$k] ['croppedImage'] = $cards[$k] ['cropped_image'];
		  unset($cards[$k]['cropped_image']);
		  $cards[$k] ['frontDrawing'] = $cards[$k] ['front_drawing'];
		  unset($cards[$k]['front_drawing']);
		  $cards[$k] ['backDrawing'] = $cards[$k] ['back_drawing'];
		  unset($cards[$k]['back_drawing']);
		  $cards[$k] ['recipients'] = $cards[$k] ['addresses'];
		  unset($cards[$k]['addresses']);
		  
		}
		
		return $cards;
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
		if(Input::has('reuse')){
			$card=Cards::find($id);
			$id=$card->reuse();
			Log::info('reuse');
		}
		$card=Cards::with('originalImage.thumbnail','cardSetting','frontDrawing.thumbnail','backDrawing.thumbnail','addresses')
		->find($id);
		$card=$card->toArray();
		   $card['originalImage'] = $card['original_image'];
		   unset($card['original_image']);
		   $card['cardSetting'] = $card['card_setting'];
		   unset($card['card_setting']);
		   $card['croppedImage'] = $card['cropped_image'];
		   unset($card['cropped_image']);
		   $card['frontDrawing'] = $card['front_drawing'];
		   unset($card['front_drawing']);
		   $card['backDrawing'] = $card['back_drawing'];
		   unset($card['back_drawing']);
		   $card['recipients'] = $card['addresses'];
		   unset($card['addresses']);
		  
		if(isset($card['id'])){
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