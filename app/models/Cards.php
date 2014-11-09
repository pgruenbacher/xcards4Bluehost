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
}