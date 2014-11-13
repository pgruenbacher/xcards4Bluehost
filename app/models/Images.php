<?php

class Images extends \Eloquent {
	protected $guarded = ['id'];
	protected $table = 'images';
	public function parent(){
        return $this->belongsTo('Images', 'parent_id');
    }
    public function children()
    {
        return $this->hasMany('Images', 'parent_id');
    }
	public function thumbnail(){
		return $this->hasOne('Images','parent_id')->where('type','=','thumbnail');
	}
	public function user(){
		return $this->belongsTo('User');
	}
	public function crop($x=null,$y=null,$w=null,$h=null,$user_id=null){
		if(!(isset($x)&&isset($y)&&$w>0&&$h>0&&isset($user_id))){
			return false;
		}
		$filename=$this->filename;
		$filepath=$this->file_path;
		$quality=95;
		$new_path=public_path('assets/images/cropped/'.$filename);
		$img = Image::make($filepath);
		$img->crop($w,$h,$x,$y);
		$img->save($new_path,$quality);
		//Get information about the saved image
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mimetype=finfo_file($finfo,$new_path);
		$size=filesize($new_path);
		finfo_close($finfo);
		$dimensions=getimagesize($new_path);
		//Save the cropped image mysql
		$cropped=new Images;
		$cropped->filename=basename($new_path);
		$cropped->type='cropped';
		$cropped->user_id=$user_id;
		$cropped->cards_id=$this->cards_id;
		$cropped->file_path=$new_path;
		$cropped->url_path=URL::asset('assets/images/cropped/'.rawurlencode($filename));
		$cropped->size=$size;
		$cropped->width=$dimensions[0];
		$cropped->height=$dimensions[1];
		$cropped->mimetype=$mimetype;
		return $this->children()->save($cropped);
	}
	public function saveThumbnail($id){
		$old_image=Images::find($id);
		$old_path=$old_image->file_path;
		$thumbnail=Image::make($old_path);
		$quality=60;
		// resize the image to a width of 150 and constrain aspect ratio (auto height)
		$filename=basename($old_path);
		$thumb_path = public_path('assets/images/thumbnails/'.$filename);
		$thumbnail->resize(150, null, function ($constraint) {
		    $constraint->aspectRatio();
		});
		$thumbnail->save($thumb_path,$quality);
		//Get information about the saved image
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mimetype=finfo_file($finfo,$thumb_path);
		$size=filesize($thumb_path);
		finfo_close($finfo);
		$dimensions=getimagesize($thumb_path);
		//Save the thumbnail image mysql
		$this->filename=basename($thumb_path);
		$this->parent_id=$id;
		$this->user_id=$old_image->user()->first()->id;
		$this->file_path=$thumb_path;
		$this->url_path=URL::asset('assets/images/thumbnails/'.rawurlencode($filename));
		$this->size=$size;
		$this->type='thumbnail';
		$this->width=$dimensions[0];
		$this->height=$dimensions[1];
		$this->mimetype=$mimetype;
		return $this->save();
	}
	public static function boot(){
	 	parent::boot();
		static::deleting(function($image) {
			// if(File::exists($image->file_path)){
             	// File::delete($image->file_path);
            // }
			$image->thumbnail()->delete();
		});
	}
}