<?php

class Orders extends \Eloquent {
	protected $fillable = [];
	protected $table='orders';
	public function card(){
		return $this->belongsTo('Cards','cards_id');
	}
	public function user(){
		return $this->belongsTo('User','user_id');
	}
	public function creditcard(){
		return $this->belongsTo('CreditCard','creditcards_id');
	}
}