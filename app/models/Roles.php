<?php
class Roles extends \Eloquent {
	protected $guarded = 'id';
	protected $table='roles';
	public function users(){
		return $this->belongsToMany('User');
	}
	
}

