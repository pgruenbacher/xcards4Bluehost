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