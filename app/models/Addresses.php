<?php

class Addresses extends \Eloquent {
	protected $guarded = array('id');
	protected $table = 'addresses';
	public function user(){
		return $this->belongsTo('User','user_id');
	}
	public function cards(){
		return $this->belongsToMany('Cards');
	}
	public function copyTo($user_id){
		$id=$this->id;
		$address=Addresses::find($id)->replicate();
		$address->user_id=$user_id;
		$address->save();
		return true;
	}
	protected static function boot(){
	 	parent::boot();
		static::deleting(function($address) { // before delete() method call this
             $address->cards()->detach();
        });
	 }
}