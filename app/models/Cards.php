<?php

class Cards extends \Eloquent {
	protected $guarded = array('id');
	protected $table='cards';
	public function originalImage(){
		return $this->hasOne('Images')->where('type','=','original')->orderBy('created_at','desc');
	}
	public function croppedImage(){
		return $this->hasOne('Images')->where('type','=','cropped')->orderBy('created_at','desc');
	}
	public function user(){
		return $this->belongsTo('User');
	}
	public function frontDrawing(){
		return $this->hasOne('Images')->where('type','=','frontDrawing')->orderBy('created_at','desc');
	}
	public function reuse(){
		$card=$this->replicate();
		$card->finished_at='0000-00-00 00:00:00';
		$card->save();
		if(isset($this->originalImage->id)){
			$originalImage=$this->originalImage->replicate();
			$thumbnail=$this->originalImage->thumbnail->replicate();
			$originalImage->cards_id=$card->id;
			$originalImage->save();
			$thumbnail->parent_id=$originalImage->id;
			$thumbnail->save();
		}
		if(isset($this->croppedImage->id)){
			$croppedImage=$this->croppedImage->replicate();
			$thumbnail=$this->croppedImage->thumbnail->replicate();
			$croppedImage->cards_id=$card->id;
			$croppedImage->save();
			$thumbnail->parent_id=$croppedImage->id;
			$thumbnail->save();
		}
		if(isset($this->frontDrawing->id)){
			$frontImage=$this->frontDrawing->replicate();
			$thumbnailFront=$this->frontDrawing->thumbnail->replicate();
			$frontImage->cards_id=$card->id;
			$backImage=$this->backDrawing->replicate();
			$thumbnailBack=$this->backDrawing->thumbnail->replicate();
			$backImage->cards_id=$card->id;
			$frontImage->save();
			$backImage->save();
			$thumbnailFront->parent_id=$frontImage->id;
			$thumbnailBack->parent_id=$backImage->id;
			$thumbnailFront->save();
			$thumbnailBack->save();
		}
		return $card->id;
	}
	public function backDrawing(){
		return $this->hasOne('Images')->where('type','=','backDrawing')->orderBy('created_at','desc');
	}
	public function cardSetting(){
		return $this->belongsTo('CardSettings','cardsetting_id');
	}
	public function addresses(){
		return $this->belongsToMany('Addresses');
	}
	public function number(){
		return count($this->addresses()->get());
	}
	public static function boot(){
	 	parent::boot();
		static::deleting(function($card) { // before delete() method call this
             $card->originalImage()->delete();
			 $card->originalImage()->delete();
			 $card->backDrawing()->delete();
			 $card->croppedImage()->delete();
			 $card->addresses()->detach();
        });
	 }
}