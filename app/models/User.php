<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {
	use UserTrait, RemindableTrait;
	public function addresses(){
		return $this->hasMany('Addresses')->orderBy('created_at','desc');
	}
	public function recoverPassword(){
	 	$code=str_random(60);
		$password=str_random(10);
		$this->code=$code;
		$this->password_temp=Hash::make($password);
		if($this->save()){
			$mandrill=new Mandrill(Config::get('mandrill.api_key'));
			$html=View::make('emails.auth.forgot')->with(array(
				'link'=>URL::route('account-recover',$code),
				'username'=>$this->first,
				'password'=>$password
			));
			$html=$html->render();
			$message = array(
		        'html' => $html,
		        'text' => $html,
		        'subject' => 'your new password',
		        'from_email' => 'info@x-presscards.com',
		        'from_name' => 'X-Press Cards',
		        'to' => array(
		            array(
		                'email' => $this->email,
		                'name' => $this->fullName(),
		                'type' => 'to'
		            	)
		        	),
	    		);
			$mailed=$mandrill->messages->send($message); 
		}
		if($mailed){
			return true;
		}else{
			return false;
		}
	 }
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

}
