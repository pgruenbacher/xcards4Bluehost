<?php

class AddressRequests extends \Eloquent {
	protected $fillable = [];
	protected $table='address_requests';
	public function sender(){
		return $this->belongsTo('User','sender_id');
	}
	public function responder(){
		return $this->belongsTo('User','responder_id');
	}
}