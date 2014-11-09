<?php
// use Stripe;
// use Stripe_Charge;
// use Stripe_Customer;
// use Stripe_InvalidRequestError;
// use Stripe_CardError;
// use Config;
// use Exception;
class OrdersController extends \BaseController {
	
	public function __construct(){
		Stripe::setApiKey(Config::get('stripe.secret'));
	}
	/**
	 * Display a listing of the resource.
	 * GET /orders
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /orders/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /orders
	 *
	 * @return Response
	 */
	public function store()	{
		$validator=Validator::make(Input::all(),array(
			'card'=>'required',
			'token'=>'required'
		));
		if($validator->fails()){
			return Response::json(array('status'=>'error','message'=>$validator->messages()->toJson()));
		}
		$user=User::find(ResourceServer::getOwnerId());
		$token=Input::get('token');
		$card=Cards::find(Input::get('card'));
		$number=count($card->addresses);
		$pricings=Pricings::all();
		foreach($pricings as $price){
			if($number>=$price->cards*$card->cardSetting->credit_rate){
				$pricing=$price;
			}
		}
		$user=User::find(ResourceServer::getOwnerId());
		$totalCost=$number*$card->cardSetting->dollar_rate;
		$amount=$totalCost-$pricing->discount*$number;
   	  	$data=$this->charge(array(
			'amount'=>intval($amount*100),
			'user'=>$user,
			'token'=>$token
		));
		$charge=$data['charge'];
		$customer=$data['customer'];
		$error=$data['error'];
		if($error !== null)
		    {
		        return Response::json(array('error'=>$error));
		    }
		if(isset($customer)){
    		$user->stripe_id=$customer->id;
			$user->save();
    	}
		$creditcard=Creditcard::where('stripe_id','=',$charge->card->id)->first();
		if(!isset($creditcard->id)){
			$creditcard=new Creditcard;
			$creditcard->stripe_id=$charge->card->id;
			$creditcard->last4=$charge->card->last4;
			$creditcard->brand=$charge->card->brand;
			$creditcard->exp_month=$charge->card->exp_month;
			$creditcard->exp_year=$charge->card->exp_year;
			$creditcard->fingerprint=$charge->card->fingerprint;
			$creditcard->country=$charge->card->country;
			$creditcard->customer_id=$charge->customer;
			$creditcard->type=$charge->card->type;
			$creditcard->user_id=$user->id;
			$creditcard->save();
		}
		
		if(isset($creditcard->id)){
			$order=new Orders;
			$order->reference=str_random(6);
			$order->user_id=$user->id;
			$order->pricing_id=$card->cardSetting()->first()->id;
			$order->charge=$charge->amount;
			$order->cards=$number;
			$order->cards_id=$card->id;
			$order->creditcards_id=$creditcard->id;
			$order->save();
			if($order->save()){
				$card->finished_at=time();
				if($card->save()){
					Mail::send('emails.card_receipt',array('user'=>$user,'card'=>$card,'pricing'=>$pricing,'order'=>$order,'charge'=>$charge), function($message) use($user)
					{
						$message->from('info@x-presscards.com', 'paul gruenbacher');
					    $message->to($user->email,$user->first)->subject('Card Order');
					});
					return Response::json(array('status'=>'sucess'));
				}
			}
		}
    	
	}

	/**
	 * Display the specified resource.
	 * GET /orders/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /orders/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /orders/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /orders/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	public function credit(){
		$validator=Validator::make(Input::all(),array(
			'card'=>'required',
		));
		if($validator->fails()){
			return Response::json(array('status'=>'error','message'=>$validator->messages()->toJson()));
		}
		$user=User::find(ResourceServer::getOwnerId());
		$card=Cards::find(Input::get('card'));
		if(isset($user->credits)&&isset($card->id)){
			$number=$card->number();
			$creditRate=$card->cardSetting->credit_rate;
			$creditCost=intval($creditRate*$number);
			$credits=$user->credits;
			if($credits >= $creditCost){
				$order=new Orders;
				$order->reference=str_random(6);
				$order->user_id=$user->id;
				$order->credits=$creditCost;
				$order->pricing_id=$card->cardSetting()->first()->id;
				$order->cards=$number;
				$order->cards_id=$card->id;
				if($order->save()){
					$card->finished_at=time();
					if($card->save()){
						$user->credits=$credits-$creditCost;
						if($user->save()){
							return Response::json(array('status'=>'success','user'=>$user,'cardAmount'=>$creditRate));
						}
					}
				}
			}
		}
	}
	public function product(){
		$validator=Validator::make(Input::all(),array(
			'product'=>'required|integer',
			'token'=>'required'
		));
		if($validator->fails()){
			return Response::json(array('status'=>'error','message'=>$validator->messages()->toJson()));
		}
		$user=User::find(ResourceServer::getOwnerId());
		$pricing=Pricings::find(Input::get('product'));
		$amount=intval($pricing->price)*100;
		$data=$this->charge(array(
			'amount'=>$amount,
			'user'=>$user,
			'token'=>Input::get('token')
		));
		$charge=$data['charge'];
		$customer=$data['customer'];
		$error=$data['error'];
		if($error!==null){
			return Response::json(array('error'=>$error));
		}
		if(isset($user->credits)&&isset($pricing->id)){
			$credits=$pricing->amount;
			$user->credits=$user->credits+$credits;
			if($user->save()){
				$order=new Orders;
				$order->reference=str_random(6);
				$order->user_id=$user->id;
				$order->credits=$credits;
				$order->pricing_id=$pricing->id;
				if($order->save()){
					Mail::send('emails.credit_receipt',array('user'=>$user,'pricing'=>$pricing,'order'=>$order,'charge'=>$charge), function($message) use($user)
					{
						$message->from('info@x-presscards.com', 'paul gruenbacher');
					    $message->to($user->email,$user->first)->subject('Credit Order');
					});
					return Response::json(array('status'=>'success'));
				}
			}
		}
	}
	public function charge($data){
		$user=$data['user'];
		$amount=$data['amount'];
		$token=$data['token'];
		$customer=null;
		$charge=null;
		$error=null;
		try{
   	  		if(!isset($user->stripe_id)){
   	  			$customer = Stripe_Customer::create(array(
	                'card' => $token,
	                'description' => $user->email
	            ));
				$charge=Stripe_Charge::create(array(
	                'customer' => $customer->id,
	                'amount' => $amount, // $10
	                'currency' => 'usd'
	            ));
   	  		}else{
   	  			$charge=Stripe_Charge::create(array(
	                'customer' => $user->stripe_id,
	                'amount' => $amount, // $10
	                'currency' => 'usd'
	            ));
   	  		}
    
        }
        catch(Stripe_CardError $e)
	       {
	           $body = $e->getJsonBody();
	           $err = $body['error'];
	           Log::write('error', 'Stripe: ' . $err['type'] . ': ' . $err['code'] . ': ' . $err['message']);
	           $error = $err['message'];
	       }
       	catch (Stripe_InvalidRequestError $e)
	       {
	           $body = $e->getJsonBody();
	           $err = $body['error'];
	           Log::write('error', 'Stripe: ' . $err['type'] . ': ' . $err['message']);
	           $error = $err['message'];
	       }
       catch (Stripe_ApiConnectionError $e)
	       {
	         // Network communication with Stripe failed
	           $error = 'A network error occurred.';
	       }
       catch (Stripe_AuthenticationError $e)
	       {
	           Log::write('error','Stripe: API key rejected!', 'stripe');
	           $error = 'Payment processor API key error.';
	       }
       catch (Stripe_Error $e)
       	{
           Log::write('error', 'Stripe: Stripe_Error - Stripe could be down.');
           $error = 'Payment processor error, try again later.';
 		}
       catch (Exception $e)
       	{
           Log::write('error', 'Stripe: Unknown error.');
           $error = 'There was an error, try again later.';
       	}
		return array(
			'error'=>$error,
			'charge'=>$charge,
			'customer'=>$customer
		);
	}
}