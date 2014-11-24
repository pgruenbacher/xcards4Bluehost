<?php

class Transfers extends \Eloquent {
	protected $guarded = array('id','recipient_id','sender_id');
	protected $dates = array('created_at','updated_at','deleted_at');
	
	public function recipient(){
		return $this->belongsTo('User','recipient_id');
	}
	public function sender(){
		return $this->belongsTo('User','sender_id');
	}
	// public function transfer(){
		// if(! $this->confirmed){
			// $recipient=$this->recipient()->first();
			// $recipient->credits += $this->credits;
			// $add=$recipient->save();
			// if($add){
				// $giver=$this->giver()->first();
				// $giver->credits -= $this->credits;
				// $sub=$giver->save();
				// if($sub){
					// $this->confirmed=1;
					// $dt=new DateTime;
					// $this->confirmed_at=$dt->format('Y-m-d H:i:s');
					// $finish=$this->save();
					// if($finish){
						// return true;
					// }
				// }else{
					// return false;
				// }
			// }else{
				// return false;
			// }
		// }
	// }
	public function hasRecipient(){
		if(isset($this->recipient()->first()->id)){
			return true;
		}else{
			return false;
		}
	}
	public function revert(){
		if(! $this->confirmed){
			if(isset($this->sender()->first()->id)){
				$sender=$this->sender()->first();
				$sender->credits += $this->credits;
				$sender->save();
				$dt=new DateTime;
				$this->reverted=1;
				$this->reverted_at=$dt->format('Y-m-d H:i:s');
				if($this->save()){
					return true;
				}
				return false;
			}
		}
	}
	public function accept(){
		if(! $this->confirmed){
			$recipient=$this->recipient()->first();
			$recipient->credits += $this->credits;
			$recipient->save();
			$this->confirmed=1;
			$dt=new DateTime;
			$this->confirmed_at=$dt->format('Y-m-d H:i:s');
			if($this->save()){
				return $recipient->credits;
			}
			return false;
		}
	}
	public static function boot()
    {
        parent::boot();
        static::deleting(function($transfer)
        {
        	if(!$transfer->confirmed){
            	$transfer->revert();
        	}
        });
    }
}