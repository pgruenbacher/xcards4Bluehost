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
	public function homeAddress(){
		return $this->hasOne('Addresses')->where('users_home','=',1);
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
	public function incomingTransfers(){
		return $this->hasMany('Transfers','recipient_id')->orderBy('created_at','desc');
	}
	public function outgoingTransfers(){
		return $this->hasMany('Transfers','sender_id')->orderBy('created_at','desc');
	}
	public function requestSender(){
		return $this->hasMany('AddressRequests','sender_id');
	}
	public function requestResponder(){
		return $this->hasMany('AddressRequests','responder_id');
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
			case 'customer':
				$assigned_roles[]= 3;
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
	
	public static function boot(){
	 	parent::boot();
		static::deleting(function($user) { // before delete() method call this
			 $user->cards()->delete();
			 $user->addresses()->delete();
			 $user->roles()->detach();
			 $user->creditCards()->delete();
			 $user->incomingTransfers()->delete();
			 $user->outgoingTransfers()->delete();
			 $user->requestSender()->delete();
			 $user->requestResponder()->delete();
        });
	 }
}
