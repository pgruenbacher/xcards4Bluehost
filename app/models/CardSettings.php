<?php

class CardSettings extends \Eloquent {
	protected $fillable = [];
	protected $table='card_settings';
	public function cards(){
		return $this->hasMany('Cards');
	}
}