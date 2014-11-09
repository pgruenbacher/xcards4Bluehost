<?php

class AddressRequests extends \Eloquent {
	protected $fillable = [];
	protected $table='address_requests';
	public function sender(){
		return $this->belongsTo('user','sender_id');
	}
	public function responder(){
		return $this->belongsTo('user','responder_id');
	}
}