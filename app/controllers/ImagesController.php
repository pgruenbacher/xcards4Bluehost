<?php

class ImagesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /images
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /images/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /images
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /images/{id}
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
	 * GET /images/{id}/edit
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
	 * PUT /images/{id}
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
	 * DELETE /images/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	public function upload(){
		$validator = Validator::make(
		    array('image' => Input::file('image')),
		    array('image' => array('required', 'max:2000','image'))
		);
		if($validator->fails()){
			return Response::json(array('status'=>'invalid'));
		}
		$user=User::find(ResourceServer::getOwnerId()); //Logged In User
		$file = Input::file('image');
		// $data=array(
			// 'user'=>User::find(ResourceServer::getOwnerId()),
			// 'file'=>Input::file('image'),
		// );
		$filename=$file->getClientOriginalName();
		$date=time();
		$publicPath='assets/images';
		$urlPath=URL::asset($publicPath);
		$destinationPath=public_path($publicPath);
		$file_path=$destinationPath.'/'.$date.$filename;
		$url_path=$urlPath.'/'.$date.urlencode($filename);
		$image=new Images;
		$image->file_path=$file_path;
		$image->url_path=$url_path;
		$image->mimetype=$file->getMimeType();
		$image->extension=$file->getClientOriginalExtension();
		$image->filename=$filename;
		$image->size=$file->getSize();
		$image->type='original';
		$file->move($destinationPath,$date.$filename);
		$dimensions=getimagesize($file_path);
		$image->width=$dimensions[0];
		$image->height=$dimensions[1];
		if($user->images()->save($image)){
			$job_id=Queue::push('JobsController@thumbnail', array('id' => $image->id));
			Jobs::create(array('job_id' => $job_id));
			return Response::json(array('status'=>'success'));
		}
		return Response::json(array('status'=>'error','message'=>'could not save image'));
	}
}