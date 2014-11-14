<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Laravel\Cashier\BillableTrait;
use Laravel\Cashier\BillableInterface;
class User extends Eloquent implements UserInterface, RemindableInterface, BillableInterface {
	use UserTrait, RemindableTrait, BillableTrait;
	protected $dates = ['trial_ends_at', 'subscription_ends_at'];
	public function images(){
		return $this->hasMany('Images');
	}
	public function addresses(){
		return $this->hasMany('Addresses')->orderBy('created_at','desc');
	}
	public function cards(){
		return $this->hasMany('Cards')->where('original_image','>',0)->orderBy('created_at','desc');
	}
	public function roles(){
		return $this->belongsToMany('Roles');
	}
	public function orders(){
		return $this->hasMany('Orders');
	}
	public function creditcards(){
		return $this->hasMany('Creditcard');
	}
	public function requestSender(){
		return $this->hasMany('AddressRequests');
	}
	public function requestResponder(){
		return $this->hasMany('AddressRequests');
	}
	public function recoverPassword(){
		$code=str_random(60);
		$password=str_random(6);
		$this->code=$code;
		$this->password_temp=Hash::make($password);
		$link=URL::route('retrievePassword',array('code'=>$code));
		$user=$this;
		if($this->save()){
			Mail::queue('emails.auth.forgot',array('name'=>$user->name,'link'=>$link,'password'=>$password), function($message) use($user)
			{
				$message->from('info@x-presscards.com', 'paul gruenbacher');
			    $message->to($user->email,$user->name)->subject('Forgot Password');
			});
			return true;
		}
		return false;
	}
	public function assignRole($title)
    {
        $assigned_roles = array();
        switch ($title) {
            case 'super_admin':
                $assigned_roles[] = 1;
				break;
            case 'admin':
                $assigned_roles[] = 1;
				break;
            case 'guest':
                $assigned_roles[] = 2;
                break;
            default:
                throw new \Exception("The role entered does not exist");
        }
        $this->roles()->attach($assigned_roles);
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
