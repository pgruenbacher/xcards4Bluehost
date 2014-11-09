<?php

class CardsImagesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /cardsimages
	 *
	 * @return Response
	 */
	public function index($cardId)
	{
		return $cardId;
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /cardsimages/create
	 *
	 * @return Response
	 */
	public function create($cardId)
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /cardsimages
	 *
	 * @return Response
	 */
	public function store($cardId)
	{
		return $this->upload($cardId);
	}

	/**
	 * Display the specified resource.
	 * GET /cards/{cardId}/images/{imageId}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($cardId,$imageId)
	{
		return $this->crop($cardId,$imageId);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /cardsimages/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($cardId,$imageId)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /cardsimages/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($cardId,$imageId)
	{
		$this->crop($cardId,$imageId);
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /cards/{cardId}/Images/{imageId}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($cardId,$imageId)
	{
		//
	}
	public function message($cardId,$imageId){
		if(Input::has('front')){
			$folder='frontDrawing';
			$image=Image::make(Input::get('front'));
		}else if(Input::has('back')){
			$folder='backDrawing';
			$image=Image::make(Input::get('back'));
		}else{
			return Response::json(array('status'=>'error','input'=>Input::all()));
		}
		$filename=time().$folder.'.png';
		$filepath=public_path('assets/images/'.$folder.'/'.$filename);
		$image->save($filepath);
		//Save $drawing
		$drawing=new Images;
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mimetype=finfo_file($finfo,$filepath);
		$size=filesize($filepath);
		finfo_close($finfo);
		$dimensions=getimagesize($filepath);
		//Save the cropped image mysql
		$card=Cards::find($cardId);
		$user=User::find(ResourceServer::getOwnerId());
		$drawing=new Images;
		$drawing->filename=$filename;
		$drawing->type=$folder; //frontDrawing or backDrawing
		$drawing->cards_id=$card->id;
		$drawing->file_path=$filepath;
		$drawing->url_path=URL::asset('assets/images/'.$folder.'/'.rawurlencode($filename));
		$drawing->size=$size;
		$drawing->width=$dimensions[0];
		$drawing->height=$dimensions[1];
		$drawing->mimetype=$mimetype;
		if($drawing->save()){
			return Response::json(array('status'=>'success','name'=>$folder,'drawing'=>$drawing));
		}
	}
	public function upload($cardId){
		$validator = Validator::make(
		    array('image' => Input::file('image')),
		    array('image' => array('required', 'max:2000','image'))
		);
		if($validator->fails()){
			return Response::json(array('status'=>'invalid'));
		}
		$user=User::find(ResourceServer::getOwnerId()); //Logged In User
		$card=Cards::find($cardId);
		$file = Input::file('image');
		// $data=array(
			// 'user'=>User::find(ResourceServer::getOwnerId()),
			// 'file'=>Input::file('image'),
		// );
		$date=time();
		$filename=$date.$file->getClientOriginalName();
		$publicPath='assets/images/original';
		$urlPath=URL::asset($publicPath);
		$destinationPath=public_path($publicPath);
		$file_path=$destinationPath.'/'.$filename;
		$url_path=$urlPath.'/'.rawurlencode($filename);
		$image=new Images;
		$image->user_id=$user->id;
		$image->file_path=$file_path;
		$image->url_path=$url_path;
		$image->mimetype=$file->getMimeType();
		$image->extension=$file->getClientOriginalExtension();
		$image->filename=$filename;
		$image->size=$file->getSize();
		$image->type='original';
		$file->move($destinationPath,$filename);
		$dimensions=getimagesize($file_path);
		$image->width=$dimensions[0];
		$image->height=$dimensions[1];
		if($saved=$card->originalImage()->save($image)){
			$card->original_image=$saved->id;
			$card->save();
			$job_id=Queue::push('JobsController@thumbnail', array('id' => $image->id));
			Jobs::create(array('job_id' => $job_id));
			return Response::json(array('status'=>'success','image'=>$image,'card'=>$card));
		}
		return Response::json(array('status'=>'error','message'=>'could not save image'));
	}
	public function crop($cardId,$imageId){
		$validate=Validator::make(Input::all(),array(
			'x'=>'required',
			'y'=>'required',
			'w'=>'required',
			'h'=>'required'
		));
		if($validate->fails()){
			return Response::json(array('status'=>'invalid','error'=>$validate->messages()->toJson()));
		}
		$data=array(
			'cardId'=>$cardId,
			'imageId'=>$imageId,
			'x'=>Input::get('x'),
			'y'=>Input::get('y'),
			'w'=>Input::get('w'),
			'h'=>Input::get('h')
		);
		$card=Cards::find($cardId);
		$image=Images::find($imageId);
		$user=User::find(ResourceServer::getOwnerId());
		if(isset($card->id)&&isset($image->id)&&isset($user->id)){
			$cropped=$image->crop($data['x'],$data['y'],$data['w'],$data['h'],$user->id);
			if(isset($cropped->id)){
				$card->cropped_image=$cropped->id;
				$card->save();
				$job_id=Queue::push('JobsController@thumbnail', array('id' => $cropped->id));
				Jobs::create(array('job_id' => $job_id));
				return Response::json(array('status'=>'success','image'=>$cropped));
			}else{
				return Response::json(array('status'=>'error','message'=>$cropped));
			}
		}else{
			return Response::json(array('status'=>'error','message'=>'missing unit'));
		}
	}
}