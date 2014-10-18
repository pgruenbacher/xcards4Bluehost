<?php

class Addresses extends \Eloquent {
	protected $guarded = array('id');
	protected $table = 'addresses';
	public function user(){
		return $this->belongsTo('User','user_id');
	}
}