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
	protected static function boot(){
	 	parent::boot();
		static::deleting(function($address) { // before delete() method call this
             $address->cards()->detach();
        });
	 }
}