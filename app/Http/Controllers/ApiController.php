<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Models\Template;
use App\Models\plan;
use App\Models\Availability;
use App\Models\Payment;
use App\Models\SelectedPlan;
use App\Models\AddClient;
use App\Models\AppFeedback;
use App\Models\HabitItems;
use App\Models\ChatRooms;
use App\Models\Habit;
use App\Models\HabitStatus;
use App\Models\Journal;
use App\Models\Appointment;
use App\Models\Page;
use App\Models\FcmToken;
use App\Models\goal;
use App\Models\Notification;
use App\Models\StripeKey;
use Stripe;
use Illuminate\Support\Facades\DB;
use DateTime;
use Carbon\Carbon;
class ApiController extends Controller
{

	public function register(Request $request)
	{
		//Validate data
		$data = $request->only('name', 'email', 'password', 'confirm_password', 'user_type','timezone');
		$validator = Validator::make($data, [
			'name' => 'required|string',
			'email' => 'required|email|unique:users',
			'password' => 'required|string|min:6|max:50',
			'confirm_password' => 'required|same:password|min:6',
			'user_type' => 'required',
			'timezone' => 'required',
		],[
			'email.unique' => 'A user is already registered with this email id.'
		]);

		//Send failed response if request is not valid
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		//Request is valid, create new user
		/* $user = User::create([
        	'name' => $request->name,
        	'email' => $request->email,
        	'password' => bcrypt($request->password)
        ]); */
		// print_r(Storage::disk('public'));
		// die;
		$data = User::make();
		$data->name = $request->name;
		$data->email = $request->email;
		$data->user_type = $request->user_type;
		$data->timezone = $request->timezone;
		$data->status = 1;
		$data->password = Hash::make($request->password);
		if ($request->profile_pic) {
			$frontimage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->profile_pic));
			$profile_pic = time() . '.jpeg';
			// $new_path = Storage::disk('public')->put($profile_pic, $frontimage);
			file_put_contents('public/'.$profile_pic, $frontimage);
			$data->profile_pic = $profile_pic;
		}

		$data->save();
		/* mail process start */
		// $email = $request->email;
		// $name = $request->name;
		// $subject = "Welcome to Life Canon,";
		// $body = Template::where('type', 2)->orderBy('id', 'DESC')->first()->content;
		// $content = array('name' => $name, 'user_id' => encrypt($data->id));
		// foreach ($content as $key => $parameter) {
		// 	$body = str_replace('{{' . $key . '}}', $parameter, $body); // this will replace {{username}} with $data['username']
		// }
		// Mail::to($email)->send(new DbTemplateMail($body, $subject));
		/* mail process end */

		/* mail process start */
		$from_email = config('app.email_from');
		$email = $request->email;
		$name = $request->name;
		$subject = "Welcome to Life Canon,";
		$body = Template::where('type', 2)->orderBy('id', 'DESC')->first()->content;
		$encrypt_user_id = encrypt($data->id);
		$content = array('name' => $name, 'user_id' => $encrypt_user_id);
		foreach ($content as $key => $parameter) {
			$body = str_replace('{{' . $key . '}}', $parameter, $body); // this will replace {{username}} with $data['username']
		}

		Mail::send('emails.dynamic', ['template' => $body, 'name' => $name, 'user_id' => $encrypt_user_id], function ($m) use ($from_email, $email, $name, $subject) {
			$m->from($from_email, 'Life Canon');

			$m->to($email, $name)->subject($subject);
		});
		/* mail process end */
		$user_type = ($request->user_type == 1) ? 'coach' : 'client';
		return response()->json([
			'success' => true,
			'message' => "Thank you for registering as " . $user_type . ". We have sent you an email for your verification to your registered email account. Please verify to activate your account!",
			'data' => $data
		], Response::HTTP_OK);
	}
	public function updatePassword(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'current_password' => 'required|string|min:6|max:50',
			'new_password' => 'required|string|min:6|max:50',
			'confirm_password' => 'required|same:new_password|min:6'
		]);

		//Send failed response if request is not valid
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if (\Hash::check($request->current_password, $user->password)) {
			$u = User::find($user->id);
			$u->password = Hash::make($request->new_password);
			$u->save();
			return response()->json([
				'success' => true,
				'message' => 'Your Password has been changed successfully.'
			]);
		} else {
			return response()->json([
				'success' => true,
				'message' => 'Your Current Password does not match.'
			]);
		}
	}
	public function login(Request $request)
	{
		$credentials = $request->only('email', 'password');

		//valid credential
		$validator = Validator::make($credentials, [
			'email' => 'required|email',
			'password' => 'required|string|min:6|max:50'
		]);

		//Send failed response if request is not valid
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$credentials['is_varified'] = 1;
		$credentials['status'] = 1;
		//Request is validated
		//Crean token
		try {
			if (!$token = JWTAuth::attempt($credentials, ['exp' => \Carbon\Carbon::now()->addDays(365)->timestamp])) {


				return response()->json([
					'success' => false,
					'message' => 'Login credentials are invalid or you have not verified the email yet. To verify email please resend the code.',

				], 400);
			}

			/* if($token){
				$user = JWTAuth::authenticate($token);
			} */
		} catch (JWTException $e) {
			return $credentials;
			return response()->json([
				'success' => false,
				'message' => 'Could not create token.',
			], 500);
		}

		//Token created, return with success response and jwt token


		return response()->json([
			'success' => true,
			'token' => $token,
		]);
	}
	public function logout(Request $request)
	{
		//valid credential
		$validator = Validator::make($request->only('token'), [
			'token' => 'required'
		]);

		//Send failed response if request is not valid
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		//Request is validated, do logout        
		try {
			JWTAuth::invalidate($request->token);

			return response()->json([
				'success' => true,
				'message' => 'User has been logged out'
			]);
		} catch (JWTException $exception) {
			return response()->json([
				'success' => false,
				'message' => 'Sorry, user cannot be logged out'
			], Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	public function get_user(Request $request)
	{
		$this->validate($request, [
			'token' => 'required'
		]);

		$user = JWTAuth::authenticate($request->token);
		if ($user->availability()->get()) {
			$availability = 'yes';
		} else {
			$availability = 'no';
		}
		$slectedPlans = $user->selectedPlan()->orderBy('id', 'desc')->first();
		
		
		if($slectedPlans && $slectedPlans->user_id !=73){
		
			$checkpaymenttype = substr($slectedPlans->subscription_id, 0, 4);
			if ($checkpaymenttype === "sub_") {
				Stripe\Stripe::setApiKey(config('app.stripe_key'));
				$current_subscription1 = \Stripe\Subscription::retrieve($slectedPlans->subscription_id); 
				$live_plan_status = $current_subscription1->status;
				if(@$current_subscription1 and $live_plan_status !='active'){
					 $slectedPlans->start_date= date('Y-m-d h:i:s', $current_subscription1->current_period_start);
					$slectedPlans->end_date = date('Y-m-d h:i:s', $current_subscription1->canceled_at);
					$slectedPlans->status=0;
					$slectedPlans->subscription_status=0;
					$slectedPlans->save();
				}
			} 
		}	
		$fcm = $user->fcmtoken()->first()->fcmtoken;
		if ($slectedPlans && $slectedPlans->user_id !=73) {
			if (strtotime(date('Y-m-d h:i:s', strtotime($slectedPlans->end_date))) < strtotime(date('Y-m-d h:i:s')) && $slectedPlans->subscription_status == 1) {
				
			$iospayment_id = Payment::where('transaction_id',$slectedPlans->subscription_id)->first();
			
				if(!@$iospayment_id->ios_original_transaction_id){
				if ($this->checkSubscriptionStatusforcoach($slectedPlans->subscription_id) ) {
			
					if ($slectedPlans->plan_id == 2) {

						$slectedPlans->end_date = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($slectedPlans->end_date)));
					} else {

						$slectedPlans->end_date = date('Y-m-d H:i:s', strtotime('+1 month', strtotime($slectedPlans->end_date)));
					}


					$slectedPlans->save();
				}
			}
			}
			
		}
		if(@$slectedPlans && $slectedPlans->status ===0){
			return response()->json(['user' => $user, 'fcmtoken' => $fcm, 'availability' => $availability, 'selectedPlans' => '']);
		}
		else {
		return response()->json(['user' => $user, 'fcmtoken' => $fcm, 'availability' => $availability, 'selectedPlans' => $slectedPlans]);
		}
		
	}

	public function updateProfile(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'name' => 'required|string'
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$user->name = $request->name;
			$user->experience = $request->experience;
			$user->area_of_expertise = $request->area_of_expertise;
			$user->description = $request->description;
			$user->phone = $request->phone;
			$user->timezone = $request->timezone;
			$user->appointment_fees = $request->appointment_fees;
			if ($request->profile_pic) {
				$frontimage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->profile_pic));
				$profile_pic = time() . '.jpeg';
				// $new_path = Storage::disk('public')->put($profile_pic, $frontimage);
				file_put_contents('public/'.$profile_pic, $frontimage);
				$user->profile_pic = $profile_pic;
			}
			$user->save();
			return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}
	public function getDaysAvailability(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'coach_id' => 'required',

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		$data = array();
		$availability = Availability::where('user_id', '=', $request->coach_id)->first();

		$days  = unserialize($availability->days);
		$times = unserialize($availability->time);

		$data['id'] = $availability->id;
		$data['user_id'] = $availability->user_id;
		$data['days'] = $days;
		//$data['times']=$times;

		return response()->json(['availability' => $data]);
	}

	public function getAvalableTime(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'coach_id' => 'required',
			'date' => 'required',
			'day' => 'required'

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		$data = array();
		$scheduled_time_arr = array();
		$time_arr = array();
		$appointment = Appointment::where('date', '=', $request->date)->where('status', '=', 1)->get();
		foreach ($appointment as $app) {
			$scheduled_time_arr[] = $app->time;
			
		}
		//$repeat = $appointment->repeat;
		$availability = Availability::where('user_id', '=', $request->coach_id)->first();
		$times = unserialize($availability->time);
$days = unserialize($availability->days);
if($availability and in_array($request->day,$days)){
		foreach ($times as $time) {
			if (strtotime($request->date) == strtotime(date('y-m-d'))) {
				$t = array();
				$t = explode('-', $time);
				if ((!in_array($time, $scheduled_time_arr)) and (strtotime(date('h:i A', strtotime($t[0]))) > strtotime(date('h:i A')))) {
					$time_arr[] = (object)array("key" => $time);
				}
			} else {

				if (!in_array($time, $scheduled_time_arr)) {
					$time_arr[] = (object)array("key" => $time);
				}
			}
		}
	}
		$data['times'] = $time_arr;
		//$data['repeat']=$repeat;
		return response()->json(['available_times' => $data]);
	}

	public function addAppointment(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'coach_id' => 'required',
			'date' => 'required',
			'day' => 'required',
			'time' => 'required',
			'repeat' => 'required',
			'stripe_token' => 'required',
			'amount' => 'required',
			'payment_for' => 'required'
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			try {
				
				if($user->user_type==1){
						$appointment = Appointment::make();						
						$appointment->user_id = $user->id;
						$appointment->client_id = $request->coach_id;
						$appointment->date = $request->date;
						$appointment->day = $request->day;
						$appointment->time = $request->time;
						$appointment->repeat = $request->repeat;
						$appointment->payment_id = 0;
						$appointment->schedule_by ='freeByCoach';
						if ($request->repeat == 1) {
							$appointment->end_date = $request->end_date;
						}
						$appointment->save();
						/*for send and save notification  */
						$msg["title"] = "New Appointment";
						$usertype = 'coach';
						$msg["body"] = "Your coach " . $user->name . " scheduled a new appointment";
						$msg['type'] = "Appointment";
						$msg['user_type'] = $usertype;
						$this->sendNotification($request->coach_id, $msg);
						/*for send and save notification  */
						/*for send and save notification  */
						/*for send and save notification  */
						return response()->json([
							'success' => true,
							'message' => 'Your appointment has been schedule successfully.'
						]);
				}else{
				$coach_stripe_key = StripeKey::where('user_id', '=', $request->coach_id)->first();
				Stripe\Stripe::setApiKey(config('app.stripe_test') ? $coach_stripe_key->secret_key : $coach_stripe_key->secret_key);
				$appointment_amount = AddClient::whereUserId($request->coach_id)->whereClientId($user->id)->first()->appointment_fee;

				if ($request->repeat == 1) {
					/*try {
						if ($user->stripe_customer_id) {
							$stripe_customer_id = $user->stripe_customer_id;
						} else {
							$customer = Stripe\Customer::create(array(
								'email' => $user->email,
								'source'  => $request->stripe_token
							));
							$user->stripe_customer_id = $customer->id;
							$user->save();
							$stripe_customer_id = $customer->id;
						}
					} catch (Exception $e) {
						$api_error = $e->getMessage();
					}
					if (empty($api_error) && $stripe_customer_id) {
						try {
								$planName = 'Appoint by ' . $user->name . ' with coach';
							$planInterval = 'week';
							$priceCents = $appointment_amount * 100;

							$plan = \Stripe\Plan::create(array(
								"product" => [
									"name" => $planName
								],
								"amount" => $priceCents,
								"currency" => 'USD',
								"interval" => $planInterval,
								"interval_count" => 1
							));
						} catch (Exception $e) {
							$api_error = $e->getMessage();
						}
						if (empty($api_error) && $plan) {

							try {
								$subscription = \Stripe\Subscription::create(array(
									"customer" => $stripe_customer_id,
									"items" => array(
										array(
											"plan" => $plan->id,
										),
									),
								));
							} catch (Exception $e) {
								$api_error = $e->getMessage();
							}
							if (empty($api_error) && $subscription) {
								
								$customerpay = Payment::make();
								$customerpay->user_id = $user->id;
								$customerpay->amount = $appointment_amount;
								$customerpay->status = 1;
								$customerpay->payee_id = $request->coach_id;
								$customerpay->payment_for = $request->payment_for;
								$customerpay->subscription_id = $subscription->id;
								$customerpay->payment_date = date('Y-m-d H:i:s', $subscription->created);
								$customerpay->save();
								$payment_id=$customerpay->id;
								
								$appointment = Appointment::make();
								$appointment->user_id = $request->coach_id;
								$appointment->client_id = $user->id;
								$appointment->date = $request->date;
								$appointment->day = $request->day;
								$appointment->time = $request->time;
								$appointment->repeat = $request->repeat;
								$appointment->subscription_id = $subscription->id;
								$appointment->subscription_status = 1;
								$appointment->payment_id = $payment_id;
								if ($request->repeat == 1) {
									$appointment->end_date = $request->end_date;
								}
								$appointment->save();
							
								$msg["title"] = "New Appointment";
								$usertype = 'coach';
								$msg["body"] = "Your client " . $user->name . " scheduled a new appointment";
								$msg['type'] = "Appointment";
								$msg['user_type'] = $usertype;
								$this->sendNotification($request->coach_id, $msg);
							
								
								$msg["title"] = "Payment Received";
								$usertype = 'coach';
								$msg["body"] = "You have received $" . $appointment_amount . " payment for appointment";
								$msg['type'] = "payment";
								$msg['user_type'] = $usertype;
								$this->sendNotification($request->coach_id, $msg);
								
								return response()->json([
									'success' => true,
									'message' => 'Your appointment has been schedule successfully.'
								]);
							} else {
								return response()->json([
									'success' => false,
									'message' => 'Unable to create subscription.Please try later or contact to support.',
								]);
							}
						} else {
							return response()->json([
								'success' => false,
								'message' => 'Unable to create Plan for subscription.Please try later or contact to support.',
							]);
						}
					} else {
						return response()->json([
							'success' => false,
							'message' => 'Unable to create customer for subscription.Please try later or contact to support.',
						]);
					}*/
					$ap_amount = ($appointment_amount * 100) * $request->numberofweek;
					$pay = Stripe\Charge::create([
						"amount" => $ap_amount,
						"currency" => "USD",
						"source" => $request->stripe_token,
						"description" => "test",
					]);

					$customerpay = Payment::make();
					$customerpay->user_id = $user->id;
					$customerpay->amount = $pay->amount / 100;
					$customerpay->transaction_id = $pay->balance_transaction;
					$customerpay->status = $pay->paid;
					$customerpay->payment_for = $request->payment_for;
					$customerpay->payee_id = $request->coach_id;
					$customerpay->payment_date = date('Y-m-d H:i:s', $pay->created);
					$customerpay->save();
					$payment_id = $customerpay->id;
					if ($pay->balance_transaction) {
						$appointment = Appointment::make();
						$appointment->user_id = $request->coach_id;
						$appointment->client_id = $user->id;
						$appointment->date = $request->date;
						$appointment->day = $request->day;
						$appointment->time = $request->time;
						$appointment->repeat = $request->repeat;
						$appointment->payment_id = $payment_id;
						if ($request->repeat == 1) {
							$appointment->end_date = $request->end_date;
						}
						$appointment->save();
						/*for send and save notification  */
						$msg["title"] = "New Appointment";
						$usertype = 'coach';
						$msg["body"] = "Your client " . $user->name . " scheduled a new appointment";
						$msg['type'] = "Appointment";
						$msg['user_type'] = $usertype;
						$this->sendNotification($request->coach_id, $msg);
						/*for send and save notification  */
						/*for send and save notification  */
						$msg["title"] = "Payment Received";
						$usertype = 'coach';
						$msg["body"] = "You have received $" . $appointment_amount * $request->numberofweek . " payment for appointment";
						$msg['type'] = "payment";
						$msg['user_type'] = $usertype;
						$this->sendNotification($request->coach_id, $msg);
						/*for send and save notification  */
						return response()->json([
							'success' => true,
							'message' => 'Your appointment has been schedule successfully.'
						]);
					} else {
						return response()->json([
							'success' => false,
							'message' => 'Your payment has been failed.Please try again or contact to admin.',
						]);
					}
				} else {
					$pay = Stripe\Charge::create([
						"amount" => $appointment_amount * 100,
						"currency" => "USD",
						"source" => $request->stripe_token,
						"description" => "test",
					]);

					$customerpay = Payment::make();
					$customerpay->user_id = $user->id;
					$customerpay->amount = $pay->amount / 100;
					$customerpay->transaction_id = $pay->balance_transaction;
					$customerpay->status = $pay->paid;
					$customerpay->payment_for = $request->payment_for;
					$customerpay->payee_id = $request->coach_id;
					$customerpay->payment_date = date('Y-m-d H:i:s', $pay->created);
					$customerpay->save();
					$payment_id = $customerpay->id;
					if ($pay->balance_transaction) {
						$appointment = Appointment::make();
						$appointment->user_id = $request->coach_id;
						$appointment->client_id = $user->id;
						$appointment->date = $request->date;
						$appointment->day = $request->day;
						$appointment->time = $request->time;
						$appointment->repeat = $request->repeat;
						$appointment->payment_id = $payment_id;
						$appointment->save();
						/*for send and save notification  */
						$msg["title"] = "New Appointment";
						$usertype = 'coach';
						$msg["body"] = "Your client " . $user->name . " scheduled a new appointment";
						$msg['type'] = "Appointment";
						$msg['user_type'] = $usertype;
						$this->sendNotification($request->coach_id, $msg);
						/*for send and save notification  */
						/*for send and save notification  */
						$msg["title"] = "Payment Received";
						$usertype = 'coach';
						$msg["body"] = "You have received $" . $appointment_amount . " payment for appointment";
						$msg['type'] = "payment";
						$msg['user_type'] = $usertype;
						$this->sendNotification($request->coach_id, $msg);
						/*for send and save notification  */
						return response()->json([
							'success' => true,
							'message' => 'Your appointment has been schedule successfully.'
						]);
					} else {
						return response()->json([
							'success' => false,
							'message' => 'Your payment has been failed.Please try again or contact to admin.',
						]);
					}
				}
			}
			}

			//catch exception
			catch (Stripe\Exception\InvalidRequestException $e) {
				return response()->json([
					'success' => false,
					'message' => $e->getMessage(),
				]);
			}
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}
	public function addAppointmentByCoach(Request $request){
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'client_id' => 'required',
			'date' => 'required',
			'day' => 'required',
			'time' => 'required',
			'repeat' => 'required',
			
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			if ($request->repeat == 1) {
					$appointment = Appointment::make();
					$appointment->user_id = $user->id;
					$appointment->client_id = $request->client_id;
					$appointment->date = $request->date;
					$appointment->day = $request->day;
					$appointment->time = $request->time;
					$appointment->repeat = $request->repeat;
					$appointment->payment_id = '';
					$appointment->schedule_by = 'freeByCoach';
					$appointment->end_date = $request->end_date;
					$appointment->save();
					/*for send and save notification  */
						$msg["title"] = "New Appointment";
						$usertype = 'client';
						$msg["body"] = "Your client " . $user->name . " scheduled a new appointment";
						$msg['type'] = "Appointment";
						$msg['user_type'] = $usertype;
						$this->sendNotification($request->client_id, $msg);
						/*for send and save notification  */
						/*for send and save notification  */
						/*for send and save notification  */
						return response()->json([
							'success' => true,
							'message' => 'Your appointment has been schedule successfully.'
						]);
				
			}else{
					$appointment = Appointment::make();
					$appointment->user_id = $user->id;
					$appointment->client_id = $request->client_id;
					$appointment->date = $request->date;
					$appointment->day = $request->day;
					$appointment->time = $request->time;
					$appointment->repeat = $request->repeat;
					$appointment->payment_id ='';
					$appointment->schedule_by ='freeByCoach';
					$appointment->save();
					
					/*for send and save notification  */
					$msg["title"] = "New Appointment";
					$usertype = 'client';
					$msg["body"] = "Your client " . $user->name . " scheduled a new appointment";
					$msg['type'] = "Appointment";
					$msg['user_type'] = $usertype;
					$this->sendNotification($request->client_id, $msg);
					/*for send and save notification  */
					return response()->json([
						'success' => true,
						'message' => 'Your appointment has been schedule successfully.'
					]);
			}
			
		}else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
		
	}
	public function getAppointment(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required'
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$update = Appointment::where('status', '=', 1)->where('repeat', '=', 0)->where('date', '<', date('Y-m-d'));
			$update->update(array('status' => 0));
			if ($user->user_type == 1) {
				return response()->json([
					'success' => true,
					'appointments' => $user->CoachAppointment()->where('status', '=', 1)->with('client', 'paymentforapp')->get()
				]);
			} else {
				return response()->json([
					'success' => true,
					'appointments' => $user->ClientAppointment()->where('status', '=', 1)->with('addedclientdata', 'coach', 'paymentforapp')->get()
				]);
			}
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}	
	public function getAppointmenttest(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required'
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$update = Appointment::where('status', '=', 1)->where('repeat', '=', 0)->where('date', '<', date('Y-m-d'));
			$update->update(array('status' => 0));
			
			if ($user->user_type == 1) {
				$getappointment = $user->CoachAppointment()->where('status', '=', 1)->with('client', 'payment')->get();
				$getarray = array();
				$getid01 = array();
				 foreach($getappointment as $getappointment1)
					{
						$startdate = $getappointment1->date;
						$enddate = strtotime($getappointment1->end_date);
						$repeat = $getappointment1->repeat;
						$days = $getappointment1->day;
						$appointmentid =  $getappointment1->id;

						if($repeat == "1")
						{
							for($ij = strtotime($days, strtotime($startdate)); $ij <= $enddate; $ij = strtotime('+1 week', $ij))
							{
								$getdata1 = array('id'=>$appointmentid,
									'startDate'=>date('Y-m-d', $ij),
									'endDate'=>date('Y-m-d', $ij),
									'summary'=>'',); 

								array_push($getarray,$getdata1);
							}
						}
						else
						{
							$getdata1 = array('id'=>$appointmentid,
									'startDate'=>$startdate,
									'endDate'=>$startdate,
									'summary'=>'',); 

							array_push($getarray,$getdata1);
						}
					}
					for ($j=0; $j < count($getarray); $j++) 
					{
						array_push($getid01,$getarray[$j]['id']);	
					}
					//print_r($getid01);
					$client_data = $user->CoachAppointment()->whereIn('id',$getid01)->get();
					$getapp = array();

						foreach($client_data as $row)
						{
							$getappdata = array('profile'=>$row->client->profile_pic,
									'client_name'=>$row->client->name,
									'date'=>$row->date,
									'time'=>$row->time,
									'appoint_id'=>$row->id,); 

							array_push($getapp,$getappdata);

						}
				print_r($getapp);
				/* return response()->json([
					'success' => true,
					'appointments' => $user->CoachAppointment()->where('status', '=', 1)->with('client', 'payment')->get()
				]); */
			} else {
				return response()->json([
					'success' => true,
					'appointments' => $user->ClientAppointment()->where('status', '=', 1)->with('addedclientdata', 'coach', 'payment')->get()
				]);
			}
			
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}
	public function getAppointmentByCoach(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'coach_id' => 'required'
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$update = Appointment::where('status', '=', 1)->where('repeat', '=', 0)->where('date', '<', date('Y-m-d'));
			$update->update(array('status' => 0));

			return response()->json([
				'success' => true,
				'appointments' => $user->ClientAppointment()->where('status', '=', 1)->where('user_id', '=', $request->coach_id)->with('addedclientdata', 'coach', 'paymentforapp')->get()
			]);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}
	public function getPastAppointment(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required'
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			if ($user->user_type == 1) {
				return response()->json([
					'success' => true,
					'appointments' => $user->CoachAppointment()->where('date', '<', date('Y-m-d'))->with('client', 'payment')->get()
				]);
			} else {
				return response()->json([
					'success' => true,
					'appointments' => $user->ClientAppointment()->where('date', '<', date('Y-m-d'))->with('coach', 'payment')->get()
				]);
			}
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}

	public function rescheduleAppointment(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'appointment_id' => 'required'
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$appointment = Appointment::find($request->appointment_id);
			if ($appointment->user_id == $user->id) {
				$appointment->date = $request->date;
				$appointment->day = $request->day;
				$appointment->time = $request->time;
				$appointment->schedule_by = 'coach';
				if ($appointment->repeat == 1) {
					$appointment->end_date = $request->end_date;
				}
				$appointment->save();
				/*for send and save notification  */
				$msg["title"] = "Reschedule Appointment";
				$usertype = 'client ';
				$msg["body"] = "Your appointment with coach " . $user->name . " rescheduled";
				$msg['type'] = "Reschedule";
				$msg['user_type'] = $usertype;
				$this->sendNotification($appointment->client_id, $msg);
				/*for send and save notification  */
				return response()->json([
					'success' => true,
					'message' => 'appointment has been reschedule successfully.'
				]);
			} else {
				return response()->json([
					'success' => false,
					'message' => 'Unable to reschedule.'
				]);
			}
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}
	public function getAppointmentDetail(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'id' => 'required',
			'coach_id' => 'required',
			'client_id' => 'required'
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$appointment = Appointment::where('id', '=', $request->id)->where('user_id', '=', $request->coach_id)->where('client_id', '=', $request->client_id)->first();
			return response()->json([
				'success' => true,
				'detail' => $appointment
			]);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}
	public function verifyEmail($id)
	{
		$now = date('Y-m-d H:i:s');
		$user_id = decrypt($id);
		$user_details = User::where('id', $user_id)->first();
		if ($user_details) {
			if ($user_details->is_varified) {
				$msg = "Email already confirmed.";
				//return redirect('/')->with(['email_already_confirmed' => 'Email already confirmed.', 'email_verified_at' => $user_details->email_verified_at]);
			} else {
				$update = User::where('id', $user_id)->update(['is_varified' => 1]);
				$msg = "Email Confirmed successfully.";
				//return redirect('/')->with(['email_confirmed' => 'Email confirmed successfully.']);
			}
		} else {
			$msg =  "No such user found. please contact to the admin.";
		}

		return view('confirm', compact('msg'));
	}

	public function resend(Request $request)
	{
		$this->validate($request, [
			'email' => 'required'
		]);
		$user_details = User::where('email', $request->email)->first();
		if ($user_details) {
			if ($user_details->is_varified) {
				return response()->json([
					'success' => true,
					'message' => 'Email already confirmed.',
				]);
			} else {
				/* mail process start */
				// $email = $user_details->email;
				// $name = $user_details->name;
				// $subject = "Please verify your email address.";
				// $body = Template::where('type', 2)->orderBy('id', 'DESC')->first()->content;
				// $content = array('name' => $name, 'user_id' => encrypt($user_details->id));
				// foreach ($content as $key => $parameter) {
				// 	$body = str_replace('{{' . $key . '}}', $parameter, $body); // this will replace {{username}} with $data['username']
				// }
				// Mail::to($email)->send(new DbTemplateMail($body, $subject));
				/* mail process end */

				/* mail process start */
				$from_email = config('app.email_from');
				$email = $user_details->email;
				$name = $user_details->name;
				$subject = "Welcome to Life Canon,";
				$body = Template::where('type', 2)->orderBy('id', 'DESC')->first()->content;
				$encrypt_user_id = encrypt($user_details->id);
				$content = array('name' => $name, 'user_id' => $encrypt_user_id);
				foreach ($content as $key => $parameter) {
					$body = str_replace('{{' . $key . '}}', $parameter, $body); // this will replace {{username}} with $data['username']
				}
				Mail::send('emails.dynamic', ['template' => $body, 'name' => $name, 'user_id' => $encrypt_user_id], function ($m) use ($from_email, $email, $name, $subject) {
					$m->from($from_email, 'Life Canon');

					$m->to($email, $name)->subject($subject);
				});
				/* mail process end */

				return response()->json([
					'success' => true,
					'message' => 'Please check your email to verify.',
				]);
			}
		} else {
			return response()->json([
				'success' => false,
				'message' => 'No such user found. please contact to the admin.',
			]);
		}
	}

	# Function to send a forgot password link
	public function forgotPassword(Request $request)
	{
		# validate the email
		$validation = Validator::make($request->all(), [
			'email' => 'required|email',
		]);
		#if validation fails
		if ($validation->fails()) {
			$response = [
				'status' => false,
				'errors' => $validation->errors()->all(),
			];
			return $response;
		}
		$credentials = $request->only('email');
		#get user details with Email id
		$user_details = User::where('email', $request->get('email'))->first();
		$url = url('/');
		if (!empty($user_details)) {

			/* mail process start */
			// $email = $user_details->email;
			// $name = $user_details->name;
			// $url = url('/public');
			// $subject = "Please verify your email address.";
			// $body = Template::where('type', 3)->orderBy('id', 'DESC')->first()->content;
			// $content = array('name' => $name, 'user_id' => encrypt($user_details->id));
			// foreach ($content as $key => $parameter) {
			// 	$body = str_replace('{{' . $key . '}}', $parameter, $body); // this will replace {{username}} with $data['username']
			// }
			// Mail::to($email)->send(new DbTemplateMail($body, $subject));

			/* mail process start */
			$from_email = 'lifecanon2022@gmail.com';
			$email = $user_details->email;
			$name = $user_details->name;
			$subject = "Reset Password,";
			$body = Template::where('type', 3)->orderBy('id', 'DESC')->first()->content;
			$encrypt_user_id = encrypt($user_details->id);
			$content = array('name' => $name, 'user_id' => $encrypt_user_id);
			foreach ($content as $key => $parameter) {
				$body = str_replace('{{' . $key . '}}', $parameter, $body); // this will replace {{username}} with $data['username']
			}
			Mail::send('emails.dynamic', ['template' => $body, 'name' => $name, 'user_id' => $encrypt_user_id], function ($m) use ($from_email, $email, $name, $subject) {
				$m->from($from_email, 'Life Canon');

				$m->to($email, $name)->subject($subject);
			});
			/* mail process end */

			return response()->json([
				'success' => true,
				'message' => 'Reset Password link sent to your email. please check your email.',
			]);
			/* mail process end */
		} else {
			return response()->json([
				'success' => false,
				'message' => 'No such user found. please contact to the admin.',
			]);
		}
	}

	public function ResetPassword($id)
	{

		return view('reset', ['id' => $id]);
	}

	# Function to post reset password
	public function postResetPassword(Request $request)
	{
		//$logged_user = Auth::guard('user')->user();
		# validate the passwords
		$validatedData = $request->validate([
			'new_password' => 'required|min:8',
			'confirm_new_password' => 'required|same:new_password|min:8',
		]);

		/* $validation = Validator::make($request->all(), [
            'new_password' => 'required|min:8',
            'confirm_new_password' => 'required|same:new_password|min:8',
        ]); */
		/* if($logged_user){
            # validate the passwords
            $validation = Validator::make($request->all(), [
                'password' => 'required',
            ]);
        } */
		#if validation fails
		/* if ($validation->fails()) {
            $response = [
                'status' => 302,
                'errors' => $validation->errors()->all(),
            ];
            return $response;
			
        } */
		/* if($logged_user){
            $credentials = $request->only('email', 'password');
            if (!Auth::guard('user')->attempt($credentials)) {
                return 500;
            } else {
                $user = Auth::guard('user')->user()->id;
                #get password hash
                $newPassword = bcrypt($request->get('new_password'));
                $data = $this->user::where('id', $user)->update(['password' => $newPassword]);
                if($data){
                     return 205;
                }else{
                    return 401;
                }
            }
        }else{ */

		try {
			DB::beginTransaction();

			#get user details by id
			$id = decrypt($request->id);
			$user = User::findorfail($id);


			#get password hash
			$newPassword = Hash::make($request->new_password);
			#update password
			$updatePassword = User::where('id', $id)->update(['password' => $newPassword]);
			if ($updatePassword) {
				// echo "You have updated your password successfully!";
				return redirect('password-updated')->withSuccess('You have updated your password successfully!');
			} else {
				// echo "Something went wrong. Please try again.";
				return redirect('password-updated')->withErrors('Something went wrong. Please try again.');
			}


			DB::commit();
			//return redirect('passwordupdated')->withSuccess('User updated successfully');

		} catch (\Exception $e) {
			DB::rollback();
			return back()->withErrors($e->getMessage())->withInput($request->all());
		}

		/* } */
	}

	public function passwordUpdated()
	{
		return view('passwordupdated');
	}
	public function plans()
	{


		$plans = plan::where('status', '=', 1)->get();

		return response()->json(['plans' => $plans]);
	}

	public function availability(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'days' => 'required',
			'time' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user_id = JWTAuth::authenticate($request->token)->id;
		$exist = Availability::where('user_id', '=', $user_id)->first();



		if ($exist) {
			$availability = Availability::find($exist->id);
			$availability->days = serialize($request->days);
			$availability->time = serialize($request->time);
			$availability->save();
		} else {

			$availability = Availability::make();
			$availability->user_id = $user_id;
			$availability->days = serialize($request->days);
			$availability->time = serialize($request->time);
			$availability->save();
		}
		return response()->json([
			'success' => true,
			'message' => 'saved successfully.',
		]);
	}
	public function getCoachAvailability(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required'
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {

			$data = array();
			$availability = $user->availability()->first();

			$days  = unserialize($availability->days);
			$times = unserialize($availability->time);

			$data['id'] = $availability->id;
			$data['user_id'] = $availability->user_id;
			$data['days'] = $days;
			$data['times'] = $times;

			return response()->json([
				'success' => true,
				'availability' => $data
			]);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}

	/* for user payment */

	public function makePayment(Request $request)
	{

		if ($request->payment_for == 1) {
			$validator = Validator::make($request->all(), [
				'token' => 'required',
				'stripe_token' => 'required',
				'amount' => 'required',
				'payment_for' => 'required',
				'plan_id' => 'required'
			]);
		} elseif ($request->payment_for == 2) {
			$validator = Validator::make($request->all(), [
				'token' => 'required',
				'stripe_token' => 'required',
				'amount' => 'required',
				'payment_for' => 'required',
				'plan_id' => 'nullable',
				'client_name' => 'required',
				'client_email' => 'required',
				'plan_name' => 'required',
				'plan_amount' => 'required',
				'cycle' => 'required',
				'appointment_fee' => 'required',


			]);
		} else {
			$validator = Validator::make($request->all(), [
				'token' => 'required',
				'code' => 'required',
				'stripe_token' => 'required',
				'amount' => 'required',
				'payment_for' => 'required',
				'plan_id' => 'nullable'
			]);
		}

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		$user_id = JWTAuth::authenticate($request->token)->id;
		if ($user_id) {

			if ($request->payment_for == 1) {
				Stripe\Stripe::setApiKey(config('app.stripe_key'));
				try {
					if ($user->stripe_customer_id) {
						$stripe_customer_id = $user->stripe_customer_id;
						// $slectedPlans = $user->selectedPlan()->orderBy('id', 'desc')->first();
						// $current_subscription = \Stripe\Subscription::retrieve($slectedPlans->subscription_id);
						// $current_subscription->cancel();
						// $slectedPlans->subscription_status = 0;
						// $slectedPlans->save();
					} else {
						$customer = Stripe\Customer::create(array(
							'email' => $user->email,
							'source'  => $request->stripe_token
						));
						$user->stripe_customer_id = $customer->id;
						$user->save();
						$stripe_customer_id = $customer->id;
					}
				} catch (Exception $e) {
					$api_error = $e->getMessage();
				}
				if (empty($api_error) && $stripe_customer_id) {
					try {
						$planName = ($request->plan_id == 1) ? 'Monthely plan' : 'Yearly plan';
						$planInterval = ($request->plan_id == 1) ? 'month' : 'year';
						$priceCents = $request->amount * 100;

						$plan = \Stripe\Plan::create(array(
							"product" => [
								"name" => $planName
							],
							"amount" => $priceCents,
							"currency" => 'USD',
							"interval" => $planInterval,
							"interval_count" => 1
						));
					} catch (Exception $e) {
						$api_error = $e->getMessage();
					}
					if (empty($api_error) && $plan) {

						try {
							$subscription = \Stripe\Subscription::create(array(
								"customer" => $stripe_customer_id,
								"items" => array(
									array(
										"plan" => $plan->id,
									),
								),
							));
						} catch (Exception $e) {
							$api_error = $e->getMessage();
						}
						if (empty($api_error) && $subscription) {
							/* record save in payment table */
							$customerpay = Payment::make();
							$customerpay->user_id = $user_id;
							$customerpay->amount = $request->amount;
							$customerpay->status = 1;
							$customerpay->payment_for = $request->payment_for;
							$customerpay->subscription_id = $subscription->id;
							$customerpay->payment_date = date('Y-m-d H:i:s', $subscription->created);
							$customerpay->save();
							/* record save in payment table */
							/* for selected plan */
							$plan_id = $request->plan_id;
							$selectedPlan = SelectedPlan::make();
							$selectedPlan->user_id = $user_id;
							$selectedPlan->plan_id = $plan_id;
							$selectedPlan->start_date = date('Y-m-d H:i:s', $subscription->created);
							if ($plan_id == 1) {
								$selectedPlan->end_date = date('Y-m-d H:i:s', strtotime('+1 month', $subscription->created));
							} else {
								$selectedPlan->end_date = date('Y-m-d H:i:s', strtotime('+1 year', $subscription->created));
							}

							$selectedPlan->status = 1;
							$selectedPlan->subscription_id = $subscription->id;
							$selectedPlan->subscription_status = 1;
							$selectedPlan->save();
							/* for selected plan */
							return response()->json([
								'success' => true,
								'message' => 'Payment has been completed.',
								'code' => ''
							]);
						} else {
							return response()->json([
								'success' => false,
								'message' => 'Unable to create subscription for subscription.Please try later or contact to support.',
							]);
						}
					} else {
						return response()->json([
							'success' => false,
							'message' => 'Unable to create Plan for subscription.Please try later or contact to support.',
						]);
					}
				} else {
					return response()->json([
						'success' => false,
						'message' => 'Unable to create customer for subscription.Please try later or contact to support.',
					]);
				}
			} elseif ($request->payment_for == 2) {
				Stripe\Stripe::setApiKey(config('app.stripe_key'));
				try {
					if ($user->stripe_customer_id) {
						$stripe_customer_id = $user->stripe_customer_id;
					} else {
						$customer = Stripe\Customer::create(array(
							'email' => $user->email,
							'source'  => $request->stripe_token
						));
						$user->stripe_customer_id = $customer->id;
						$user->save();
						$stripe_customer_id = $customer->id;
					}
				} catch (Exception $e) {
					$api_error = $e->getMessage();
				}
				if (empty($api_error) && $stripe_customer_id) {
					try {
						$planName = 'Client ' . $request->client_name . ' added by coach ' . $user->name;
						$planInterval = 'month';
						$priceCents = $request->amount * 100;

						$plan = \Stripe\Plan::create(array(
							"product" => [
								"name" => $planName
							],
							"amount" => $priceCents,
							"currency" => 'USD',
							"interval" => $planInterval,
							"interval_count" => 1
						));
					} catch (Exception $e) {
						$api_error = $e->getMessage();
					}
					if (empty($api_error) && $plan) {

						try {
							$subscription = \Stripe\Subscription::create(array(
								"customer" => $stripe_customer_id,
								"items" => array(
									array(
										"plan" => $plan->id,
									),
								),
							));
						} catch (Exception $e) {
							$api_error = $e->getMessage();
						}
						if (empty($api_error) && $subscription) {
							/* for selected plan */
							$addClient = AddClient::make();
							$code = $addClient->generateCodeNumber();
							$addClient->user_id = $user_id;
							$addClient->client_name = $request->client_name;
							$addClient->client_email = $request->client_email;
							$addClient->plan_name = $request->plan_name;
							$addClient->plan_amount = $request->plan_amount;
							$addClient->start_date = date('Y-m-d H:i:s', $subscription->created);
							$addClient->end_date = date('Y-m-d H:i:s', strtotime('+1 month', $subscription->created));
							$addClient->code = $code;
							$addClient->subscription_id_for_coach = $subscription->id;
							$addClient->subscription_status_for_coach = 1;
							$addClient->cycle = $request->cycle;
							$addClient->phone = $request->phone;
							$addClient->appointment_fee = $request->appointment_fee;
							$addClient->save();
							/* for selected plan */
							/* record save in payment table */
							$customerpay = Payment::make();
							$customerpay->user_id = $user_id;
							$customerpay->amount = $request->amount;
							$customerpay->status = 1;
							$customerpay->payment_for = $request->payment_for;
							$customerpay->added_client_id = $addClient->id;
							$customerpay->subscription_id = $subscription->id;
							$customerpay->payment_date = date('Y-m-d H:i:s', $subscription->created);
							$customerpay->save();
							/* record save in payment table */
							
						/** Code send to client */
					
							$from_email = config('app.email_from');
							$email = $request->client_email;
							$name = $request->client_name;
							$email1 = $user->email;
							$name1 = $user->name;
							$subject = "Add Client Generated Code By Coach,";
							$body = Template::where('type', 4)->orderBy('id', 'DESC')->first()->content;
							$content = array('client_code' => $code);
							foreach ($content as $key => $parameter) {
								$body = str_replace('{{' . $key . '}}', $parameter, $body); // this will replace {{username}} with $data['username']
							}

							Mail::send('emails.dynamic', ['template' => $body, 'name' => $name], function ($m) use ($from_email, $email, $name, $subject) {
								$m->from($from_email, 'Life Canon');

								$m->to($email, $name)->subject($subject);
								
							});
							Mail::send('emails.dynamic', ['template' => $body, 'name' => $name1], function ($m) use ($from_email, $email1, $name1, $subject) {
								$m->from($from_email, 'Life Canon');
								$m->to($email1, $name1)->subject($subject);
							});
							
						/** Code send to client */	
							

							return response()->json([
								'success' => true,
								'message' => 'Payment has been completed.',
								'code' => $code
							]);
						} else {
							return response()->json([
								'success' => false,
								'message' => 'Unable to create subscription for subscription.Please try later or contact to support.',
							]);
						}
					} else {
						return response()->json([
							'success' => false,
							'message' => 'Unable to create Plan for subscription.Please try later or contact to support.',
						]);
					}
				} else {
					return response()->json([
						'success' => false,
						'message' => 'Unable to create customer for subscription.Please try later or contact to support.',
					]);
				}
			} else {
				$AddedClientData = AddClient::with('user')->whereCode($request->code)->whereNull('client_id')->where('status', '=', 0)->first();
				$coach_stripe_key = StripeKey::where('user_id', '=', $AddedClientData->user_id)->first();
				Stripe\Stripe::setApiKey(config('app.stripe_test') ? $coach_stripe_key->secret_key : $coach_stripe_key->secret_key);
				if ($AddedClientData->plan_name == 'Weekly' or $AddedClientData->plan_name == 'Monthely') {
					try {
						if ($user->stripe_customer_id) {
							$stripe_customer_id = $user->stripe_customer_id;
						} else {
							$customer = Stripe\Customer::create(array(
								'email' => $user->email,
								'source'  => $request->stripe_token
							));
							$user->stripe_customer_id = $customer->id;
							$user->save();
							$stripe_customer_id = $customer->id;
						}
					} catch (Exception $e) {
						$api_error = $e->getMessage();
					}
					if (empty($api_error) && $stripe_customer_id) {
						try {
							$planName = $AddedClientData->plan_name;
							if ($AddedClientData->plan_name == 'Weekly') {
								$planInterval = 'week';
							} else {
								$planInterval = 'month';
							}
							$priceCents = $request->amount * 100;

							$plan = \Stripe\Plan::create(array(
								"product" => [
									"name" => $planName
								],
								"amount" => $priceCents,
								"currency" => 'USD',
								"interval" => $planInterval,
								"interval_count" => 1
							));
						} catch (Exception $e) {
							$api_error = $e->getMessage();
						}
						if (empty($api_error) && $plan) {

							try {
								$subscription = \Stripe\Subscription::create(array(
									"customer" => $stripe_customer_id,
									"items" => array(
										array(
											"plan" => $plan->id,
										),
									),
								));
							} catch (Exception $e) {
								$api_error = $e->getMessage();
							}
							if (empty($api_error) && $subscription) {
								/* record save in payment table */
								$customerpay = Payment::make();
								$customerpay->user_id = $user_id;
								$customerpay->amount = $request->amount;
								$customerpay->status = 1;
								$customerpay->payee_id = $AddedClientData->user_id;
								$customerpay->added_client_id = $AddedClientData->id;
								$customerpay->payment_for = $request->payment_for;
								$customerpay->subscription_id = $subscription->id;
								$customerpay->payment_date = date('Y-m-d H:i:s', $subscription->created);
								$customerpay->save();
								/* record save in payment table */
								$client = AddClient::find($AddedClientData->id);
								$client->client_id = $user_id;
								$client->status = 1;
								$client->client_start_date = date('Y-m-d H:i:s', $subscription->created);
								$client->client_end_date = date('Y-m-d H:i:s', $subscription->created);
								$client->subscription_id_for_client = $subscription->id;
								$client->subscription_status_for_client = 1;
								$client->save();
								/*for send and save notification  */
								$msg["title"] = "New Client Subscribed";
								$usertype = 'coach';
								$msg["body"] = "Your client " . $user->name . " added you as coach";
								$msg['type'] = "New Client Subscribed";
								$msg['user_type'] = $usertype;
								$this->sendNotification($client->user_id, $msg);
								/*for send and save notification  */
								/*for send and save notification  */
								$msg["title"] = "Payment Recieved";
								$usertype = 'coach';
								$msg["body"] = "You have received $ " . $client->plan_amount . " payment from client " . $client->client_name . "";
								$msg['type'] = "Payment Recieved";
								$msg['user_type'] = $usertype;
								$this->sendNotification($client->user_id, $msg);
								/*for send and save notification  */
								return response()->json([
									'success' => true,
									'message' => 'Payment has been completed.',
									'code' => ''
								]);
							} else {
								return response()->json([
									'success' => false,
									'message' => 'Unable to create subscription for subscription.Please try later or contact to support.',
								]);
							}
						} else {
							return response()->json([
								'success' => false,
								'message' => 'Unable to create Plan for subscription.Please try later or contact to support.',
							]);
						}
					} else {
						return response()->json([
							'success' => false,
							'message' => 'Unable to create customer for subscription.Please try later or contact to support.',
						]);
					}
				} else {
					$pay = Stripe\Charge::create([
						"amount" => $request->amount * 100,
						"currency" => "USD",
						"source" => $request->stripe_token,
						"description" => "test",
					]);
					$AddedClientData = AddClient::with('user')->whereCode($request->code)->whereNull('client_id')->where('status', '=', 0)->first();

					$customerpay = Payment::make();
					$customerpay->user_id = $user_id;
					$customerpay->amount = $pay->amount / 100;
					$customerpay->transaction_id = $pay->balance_transaction;
					$customerpay->status = $pay->paid;
					$customerpay->payee_id = $AddedClientData->user_id;
					$customerpay->payment_for = $request->payment_for;
					$customerpay->payment_date = date('Y-m-d H:i:s', $pay->created);

					$customerpay->save();
					if ($pay->balance_transaction) {
						$client = AddClient::find($AddedClientData->id);
						$client->client_id = $user_id;
						$client->status = 1;
						$client->client_start_date = date('Y-m-d H:i:s', $pay->created);
						$client->client_end_date = date('Y-m-d H:i:s', $pay->created);
						$client->save();
						/*for send and save notification  */
						$msg["title"] = "New Client Subscribed";
						$usertype = 'coach';
						$msg["body"] = "Your client " . $user->name . " added you as coach";
						$msg['type'] = "New Client Subscribed";
						$msg['user_type'] = $usertype;
						$this->sendNotification($client->user_id, $msg);
						/*for send and save notification  */
						/*for send and save notification  */
						$msg["title"] = "Payment Recieved";
						$usertype = 'coach';
						$msg["body"] = "You have received $ " . $client->plan_amount . " payment from client " . $client->client_name . "";
						$msg['type'] = "Payment Recieved";
						$msg['user_type'] = $usertype;
						$this->sendNotification($client->user_id, $msg);
						/*for send and save notification  */
						/* for selected plan */
						return response()->json([
							'success' => true,
							'message' => 'Payment has been completed.',
							'code' => ''
						]);
					}
				}
			}
			return response()->json([
				'success' => true,
				'message' => 'Your payment has been failed.Please try again or contact to admin.',
			]);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}
	
	/* for Ios payment */
	
	public function makePaymentForIos(Request $request){
		
		$validator = Validator::make($request->all(), [
				'token' => 'required',
				'payment_for' => 'required',
				'plan_id' => 'required',
				'receipt'=>'required',
				'amount'=>'required'
			]);
			
			if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		
		$user = JWTAuth::authenticate($request->token);
		$user_id = JWTAuth::authenticate($request->token)->id;
	if($user_id){
			
			if($request->receipt){
				

				$curl = curl_init();

				curl_setopt_array($curl, array(
				 /*  CURLOPT_URL => 'https://sandbox.itunes.apple.com/verifyReceipt', */
				  CURLOPT_URL => 'https://buy.itunes.apple.com/verifyReceipt',
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'POST',
				  CURLOPT_POSTFIELDS =>'{
				"receipt-data":"'.$request->receipt.'",
				"password":"60632e427bc6439d916b705819b01b1a",
				"exclude-old-transactions":true


				}',
				  CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json'
				  ),
				));

				$response = curl_exec($curl);

				curl_close($curl);			
								
							
			}
				$result = json_decode($response);
				if($result->status==0){
				/* record save in payment table */
						 $customerpay = Payment::make();
							$customerpay->user_id = $user_id;
							$customerpay->amount = $request->amount;
							$customerpay->status = 1;
							$customerpay->payment_for = $request->payment_for;
							$customerpay->transaction_id = $result->latest_receipt_info[0]->transaction_id;
							$customerpay->ios_original_transaction_id = $result->latest_receipt_info[0]->original_transaction_id;
							$customerpay->subscription_id = $result->latest_receipt_info[0]->transaction_id;
							$customerpay->payment_date = date('Y-m-d H:i:s', strtotime($result->latest_receipt_info[0]->purchase_date));
							$customerpay->save();
							/* record save in payment table */
							/* for selected plan */
							$plan_id = $request->plan_id;
							$selectedPlan = SelectedPlan::make();
							$selectedPlan->user_id = $user_id;
							$selectedPlan->plan_id = $plan_id;
							$selectedPlan->start_date = date('Y-m-d H:i:s', strtotime($result->latest_receipt_info[0]->purchase_date));
							if ($plan_id == 1) {
								$selectedPlan->end_date = date('Y-m-d H:i:s', strtotime('+1 month', strtotime($result->latest_receipt_info[0]->purchase_date)));;
							} else {
								$selectedPlan->end_date = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($result->latest_receipt_info[0]->purchase_date)));;
							}

							$selectedPlan->status = 1;
							$selectedPlan->subscription_id = $result->latest_receipt_info[0]->transaction_id;
							$selectedPlan->subscription_status = 1;
							$selectedPlan->receipt = $request->receipt;
							$selectedPlan->save();
							/* for selected plan */
							return response()->json([
								'success' => true,
								'message' => 'Payment has been completed.',
							]);
		}else{
			return response()->json([
				'success' => false,
				'message' => 'Unable to payment. please try again or contact to the admin.',
			]);
		}
		}else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}
/* for Ios payment */
	
/* for Restore Ios payment */

	public function restorePaymentForIos(Request $request){
		
		$validator = Validator::make($request->all(), [
				'token' => 'required',
				'payment_for' => 'required',
			]);
			
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		
		$user_id = JWTAuth::authenticate($request->token)->id;	
		if($user_id){
			$curr_date = date('Y-m-d h:i:s ');
			$plans = SelectedPlan::where('user_id','=',$user_id)->where('subscription_status','==',0)->where('subscription_id','!=','')->where('end_date', '>=',$curr_date)->get()->last();
			if($plans){
				return response()->json(['success' => true, 'message' => 'Your plan already exist.']);
			}else{
				return response()->json([
				'success' => false,
				'message' => 'You have not any subscription.',
			]);
			}

		}else{
			return response()->json([
				'success' => false,
				'message' => 'You have not any subscription.',
			]);
		}
	}
/* for Restore Ios payment */	
	public function updateAddedClient(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'id'    => 'required',
			'client_name' => 'required',
			'client_email' => 'required',
			'plan_name' => 'required',
			'plan_amount' => 'required',
			'cycle' => 'required'

		]);
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$client = $user->getAddedClient()->where('id', '=', $request->id)->where('status', '==', 0)->get();
			if (count($client)) {
				$addClient = AddClient::findorfail($request->id);
				$addClient->client_name = $request->client_name;
				$addClient->client_email = $request->client_email;
				$addClient->plan_name = $request->plan_name;
				$addClient->plan_amount = $request->plan_amount;
				$addClient->cycle = $request->cycle;
				$addClient->phone = $request->phone;
				$addClient->appointment_fee = $request->appointment_fee;
				if ($addClient->save()) {
					return response()->json(['success' => true, 'message' => 'Client has been updated successfully.']);
				} else {
					return response()->json(['success' => false, 'message' => 'Unable to update client.Please try again or contact to admin.']);
				}
			} else {
				return response()->json(['success' => true, 'message' => 'you can not update this client.']);
			}
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
		/* for selected plan */
		// $addClient = AddClient::make();
		// $code = $addClient->generateCodeNumber();
		// $addClient->user_id = $user_id;
		// $addClient->client_name = $request->client_name;
		// $addClient->client_email = $request->client_email;
		// $addClient->plan_name = $request->plan_name;
		// $addClient->plan_amount = $request->plan_amount;
		// $addClient->start_date = date('Y-m-d H:i:s', $subscription->created);
		// $addClient->end_date = date('Y-m-d H:i:s', strtotime('+1 month', $subscription->created));
		// $addClient->code = $code;
		// $addClient->subscription_id_for_coach = $subscription->id;
		// $addClient->subscription_status_for_coach = 1;
		// $addClient->cycle = $request->cycle;
		// $addClient->phone = $request->phone;
		// $addClient->save();
		/* for selected plan */
	}

	public function reInstateClient(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'stripe_token' => 'required',
			'amount' => 'required',
			'added_client_id' => 'required'
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			Stripe\Stripe::setApiKey(config('app.stripe_key'));
			try {
				if ($user->stripe_customer_id) {
					$stripe_customer_id = $user->stripe_customer_id;
				} else {
					$customer = Stripe\Customer::create(array(
						'email' => $user->email,
						'source'  => $request->stripe_token
					));
					$user->stripe_customer_id = $customer->id;
					$user->save();
					$stripe_customer_id = $customer->id;
				}
			} catch (Exception $e) {
				$api_error = $e->getMessage();
			}
			if (empty($api_error) && $stripe_customer_id) {
				try {
					$planName = 'Client ' . $request->client_name . ' added by coach ' . $user->name;
					$planInterval = 'month';
					$priceCents = $request->amount * 100;

					$plan = \Stripe\Plan::create(array(
						"product" => [
							"name" => $planName
						],
						"amount" => $priceCents,
						"currency" => 'USD',
						"interval" => $planInterval,
						"interval_count" => 1
					));
				} catch (Exception $e) {
					$api_error = $e->getMessage();
				}
				if (empty($api_error) && $plan) {

					try {
						$subscription = \Stripe\Subscription::create(array(
							"customer" => $stripe_customer_id,
							"items" => array(
								array(
									"plan" => $plan->id,
								),
							),
						));
					} catch (Exception $e) {
						$api_error = $e->getMessage();
					}
					if (empty($api_error) && $subscription) {
						/* record save in payment table */
						$customerpay = Payment::make();
						$customerpay->user_id = $user->id;
						$customerpay->amount = $request->amount;
						$customerpay->status = 1;
						$customerpay->payment_for = 5;
						$customerpay->subscription_id = $subscription->id;
						$customerpay->payment_date = date('Y-m-d H:i:s', $subscription->created);
						$customerpay->save();
						/* record save in payment table */
						/* for selected plan */
						$addedclient = AddClient::find($request->added_client_id);
						if ($addedclient->user_id == $user->id) {
							$addedclient->status = 1;
							$addedclient->start_date = date('Y-m-d H:i:s', $subscription->created);
							$addedclient->end_date = date('Y-m-d H:i:s', strtotime('+1 month', $subscription->created));
							$addedclient->subscription_id_for_coach = $subscription->id;
							$addedclient->subscription_status_for_coach = 1;
							$addedclient->save();
							return response()->json([
								'success' => true,
								'message' => 'Client has been reinstate successfully.'
							]);
						} else {
							return response()->json([
								'success' => false,
								'message' => 'Unable to reinstate the client.Please try again or contact to admin.'
							]);
						}
					} else {
						return response()->json([
							'success' => false,
							'message' => 'Unable to create subscription.Please try later or contact to support.',
						]);
					}
				} else {
					return response()->json([
						'success' => false,
						'message' => 'Unable to create Plan for subscription.Please try later or contact to support.',
					]);
				}
			} else {
				return response()->json([
					'success' => false,
					'message' => 'Unable to create customer for subscription.Please try later or contact to support.',
				]);
			}


			if ($pay->balance_transaction) {
				$addedclient = AddClient::find($request->added_client_id);
				if ($addedclient->user_id == $user->id) {
					$addedclient->status = 1;
					$addedclient->start_date = date('Y-m-d H:i:s', $pay->created);
					$addedclient->end_date = date('Y-m-d H:i:s', strtotime('+1 month', $pay->created));
					$addedclient->save();
					return response()->json([
						'success' => true,
						'message' => 'Client has been reinstate successfully.'
					]);
				} else {
					return response()->json([
						'success' => false,
						'message' => 'Unable to reinstate the client.Please try again or contact to admin.'
					]);
				}
			} else {
				return response()->json([
					'success' => true,
					'message' => 'Your payment has been failed.Please try again or contact to admin.',
				]);
			}
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}

	public function getAddedClients(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);

		if ($user) {
			return response()->json(['success' => true, 'addedclients' => $user->getAddedClient()->where('client_name', 'like', '%' . $request->s . '%')->with(['user', 'client'])->get()]);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}


	public function AddCoach(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'code' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		$AddedClientData = AddClient::with(['user', 'stripe'])->whereCode($request->code)->whereNull('client_id')->where('status', '=', 0)->first();
		if ($user and $AddedClientData) {
			return response()->json(['success' => true, 'data' => $AddedClientData]);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token or code is not valid. please contact to the admin.',
			]);
		}
	}

	public function clientCoachList(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required'

		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$coachdata = AddClient::with(['user', 'stripe'])->where('status', '=', 1)->where('client_id', '=', $user->id)->get();
			return response()->json(['success' => true, 'data' => $coachdata]);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token or code is not valid. please contact to the admin.',
			]);
		}
	}

	public function deleteAddedClients(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'id' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			
			$client = $user->getAddedClient()->where('id', '=', $request->id)->where('status', '=', 0)->whereNull('client_id')->first();
			$subscription_id = $client->subscription_id_for_coach;
			if ($subscription_id) {
				
				Stripe\Stripe::setApiKey(config('app.stripe_key'));
				try{
					
					$current_subscription = \Stripe\Subscription::retrieve($subscription_id);
					$return = $current_subscription->cancel();
					
					if ($return->status == 'canceled') {
						$payment = Payment::where('subscription_id', '=', $subscription_id)->first();
						$payment->subscription_status = 0;
						$payment->save();
						if ($user->getAddedClient()->where('id', '=', $request->id)->where('status', '=', 0)->whereNull('client_id')->delete()) {
							return response()->json(['success' => true, 'message' => 'Client has been deleted successfully.']);
						} else {
							return response()->json(['success' => false, 'message' => 'Unable to delete client.Please try again or contact to admin.']);
						}
					}
				}catch(\Stripe\Exception\ApiErrorException $e) {
					
					return response()->json(['success' => true, 'message' => 'Something went wrong.Please try again later.']);
				}
				
				
			} else {
				return response()->json(['success' => true, 'message' => 'you can not delete this client.Please make it arhive.']);
			}
			
			
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}
	public function archiveAddedClients(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'id'    => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$client = $user->getAddedClient()->where('id', '=', $request->id)->where('status', '!=', 0)->get();
			if (count($client)) {
				$data = AddClient::findorfail($request->id);
				
					foreach($client as $clientdata){
						$subscription_client_id = $clientdata->subscription_id_for_client;
						$subscription_id_for_coach = $clientdata->subscription_id_for_coach;
							if($subscription_client_id){
									$payment = Payment::where('subscription_id', '=', $subscription_client_id)->first();
									$existinguserstripe = StripeKey::where('user_id',$user->id)->first();
									Stripe\Stripe::setApiKey($existinguserstripe['secret_key']);
									
									$current_subscription = Stripe\Subscription::retrieve($subscription_client_id);
									if($current_subscription->status=='canceled' OR $current_subscription->status=='incomplete_expired'){
										$payment1 = Payment::where('subscription_id', '=', $request->subscription_id_for_client)->first();
											$payment1->subscription_status = 0;
											$payment1->save();
										$data->status = 2;
									}else{
										$return = $current_subscription->cancel();
										if ($return->status == 'canceled') {
											$payment->subscription_status = 0;
											$payment->save();
											$data->status = 2;
											
										}
									}
								}
							if($subscription_id_for_coach){	
								Stripe\Stripe::setApiKey(config('app.stripe_key'));
								$current_subscription_forcoach = Stripe\Subscription::retrieve(@$subscription_id_for_coach);
								if($current_subscription_forcoach->status=='active'){
									$current_subscription_forcoach->cancel();	
									$data->status = 2;
								}
							}	
								
					}
				$data->subscription_status_for_client = 0;
				
				/* $coach_subscription = $user->getAddedClient()->where('id', '=', $request->id)->where('status', '!=', 0)->first();	
				Stripe\Stripe::setApiKey(config('app.stripe_key'));
				$current_subscription_forcoach = Stripe\Subscription::retrieve(@$coach_subscription->subscription_id_for_coach);
				$current_subscription_forcoach->cancel(); */
				
				 if ($data->save()) {
					return response()->json(['success' => true, 'message' => 'Client has been archived successfully.']);
				} else {
					return response()->json(['success' => false, 'message' => 'Unable to update client.Please try again or contact to admin.']);
				} 
			} else {
				return response()->json(['success' => true, 'message' => 'you can not update this client.Please make it arhive.']);
			}
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}

	public function AddHabitsOld(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'habit_name' => 'required',
			'repeat' => 'required',
			// 'item_name' => 'required',
			// 'item_time' => 'required',
			'coach_id' => 'required',
			'client_id' => 'required',
			// 'all_day' => 'required',
			'start_date' => 'required',
			'number_of_session' => 'required',
			'end_date' => 'required'
		]);



		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		$i = 0;


		if ($user) {

			$habit = Habit::make();
			$habit->user_id = $request->coach_id;
			$habit->client_id = $request->client_id;
			$habit->name = $request->habit_name;
			$habit->repeat = $request->repeat;
			$habit->all_day = 0;
			$habit->start_date = $request->start_date;
			$habit->end_date = $request->end_date;
			$habit->alert = $request->alert;
			$habit->number_of_session = $request->number_of_session;
			$habit->save();
			// foreach ($request->item_name as $item) {

			// 	$habitItems = new HabitItems();
			// 	$habitItems->item_name = $request->item_name[$i];
			// 	$habitItems->item_time = $request->item_time[$i];
			// 	$habit->habitItems()->save($habitItems);
			// 	$i++;
			// }
			/*for send and save notification  */
			$msg["title"] = "Habit List";
			$usertype = ($user->user_type == 1) ? 'coach ' : 'client ';
			$msg["body"] = "A new habit added by your " . $usertype . " " . $user->name . "";
			$msg['type'] = "Habit List";
			$msg['user_type'] = $usertype;
			$this->sendNotification(($user->user_type == 1) ? $request->client_id : $request->coach_id, $msg);
			/*for send and save notification  */

			return response()->json(['success' => true, 'message' => 'Habit list saved successfully.']);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}
	public function AddHabits(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'habit_name' => 'required',
			'coach_id' => 'required',
			'client_id' => 'required',
			'start_date' => 'required',
			'number_of_session' => 'required',
			'end_date' => 'required',
			'week_days' => 'required',
			'alert' => 'required'
		]);



		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);

		if ($user) {

			$str_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_date);
			$end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->end_date);
			$habit = Habit::make();
			$habit->user_id = $request->coach_id;
			$habit->client_id = $request->client_id;
			$habit->name = $request->habit_name;
			$habit->repeat = '';
			$habit->all_day = 0;
			$habit->start_date = $str_date;
			$habit->end_date = $end_date;
			$habit->alert = $request->alert;
			$habit->number_of_session = $request->number_of_session;
			$habit->week_days = serialize($request->week_days);
			$habit->save();
			/*for send and save notification  */
			$msg["title"] = "Habit List";
			$usertype = ($user->user_type == 1) ? 'coach ' : 'client ';
			$msg["body"] = "A new habit added by your " . $usertype . " " . $user->name . "";
			$msg['type'] = "Habit List";
			$msg['user_type'] = $usertype;
			$msg['coach_id'] = $request->coach_id;
			$msg['client_id'] =  $request->client_id;
			$this->sendNotification(($user->user_type == 1) ? $request->client_id : $request->coach_id, $msg);
			/*for send and save notification  */

			return response()->json(['success' => true, 'message' => 'Habit list saved successfully.']);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}

	public function UpdateHabits(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'habit_id' => 'required',
			'habit_name' => 'required',
			// 'repeat' => 'required',
			'coach_id' => 'required',
			'client_id' => 'required',
			// 'all_day' => 'required',
			/* 'start_date' => 'required',
			'number_of_session' => 'required',
			'end_date' => 'required', */
			'week_days' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}


		$user = JWTAuth::authenticate($request->token);
		$i = 0;

		if ($user) {

			/* if ($request->item_id[0] == '') {

				HabitItems::where('habit_id', '=', $request->habit_id)->delete();
			} else {
				HabitItems::where('habit_id', '=', $request->habit_id)->whereNotIn('id', $request->item_id)->delete();
			} */


			$habit = Habit::findorfail($request->habit_id);
			$habit->user_id = $request->coach_id;
			$habit->client_id = $request->client_id;
			$habit->name = $request->habit_name;
			$habit->repeat = '';
			$habit->all_day = 0;
			/* $habit->start_date = $request->start_date;
			$habit->end_date = $request->end_date;
			$habit->number_of_session = $request->number_of_session; */
			$habit->alert = $request->alert;
			$habit->week_days = serialize($request->week_days);
			$habit->save();
			// if (isset($request->item_name)) {

			// 	foreach (@$request->item_name as $item) {
			// 		$habitItems = new HabitItems();
			// 		// $habitItems->item_name = $item;
			// 		$habitItems->item_name = $request->item_name[$i];
			// 		$habitItems->item_time = $request->item_time[$i];
			// 		$habit->habitItems()->save($habitItems);
			// 		$i++;
			// 	}
			// }

			return response()->json(['success' => true, 'message' => 'Habit list updated successfully.']);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}

	public function GetHabits(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'coach_id' => 'required',
			'client_id' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			/* $habit_list = Habit::where('user_id', '=', $request->coach_id)->where('client_id', '=', $request->client_id)->where('status', '=', 2)->get();
			foreach ($habit_list as $habit) {
				if (date('Y-m-d', strtotime('+5 hour +30 minutes', strtotime($habit->updated_at))) < date("Y-m-d")) {

					$update_status = Habit::find($habit->id);
					$update_status->status = 1;
					$update_status->save();
				}
			} */
			$habits = Habit::with('habitStatus')->where('user_id', '=', $request->coach_id)->where('client_id', '=', $request->client_id)->get();

			return response()->json(['success' => true, 'habit' => $habits]);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}

	public function DeleteHabit(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'id' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user->habits()->where('id', '=', $request->id)->delete()) {
			HabitItems::where('habit_id', '=', $request->id)->delete();
			return response()->json(['success' => true, 'message' => 'Habit has been deleted successfully.']);
		} else {
			return response()->json(['success' => false, 'message' => 'Unable to delete Habit.Please try again or contact to admin.']);
		}
	}

	public function UpdateHabitStatus(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'habit_id' => 'required',
			'date'     => 'required',
			'status'  => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);

		if ($user) {
			
			/* $habit = Habit::find($request->habit_id);
			$habit->status = $request->status;
			$habit->save(); */
			
			$item=HabitStatus::where('habit_id',$request->habit_id)->where('date', $request->date)->first();
			if(@$item){
			 HabitStatus::where('habit_id',$request->habit_id)->where('date', $request->date)->first()->update(['status' =>$request->status]);	
			 return response()->json(['success' => true, 'message' => 'status has been updated successfully.']);
			}else{
				$habititem = HabitStatus::make();
				$habititem->habit_id = $request->habit_id;
				$habititem->date = $request->date;
				$habititem->status =$request->status;
				$habititem->save();	
				return response()->json(['success' => true, 'message' => 'status has been updated successfully.']);
			}
			return response()->json(['success' => true, 'message' => 'Status has been updated successfully.']);
		} else {
			return response()->json(['success' => false, 'message' => 'Unable to update status.Please try again or contact to admin.']);
		}
	}
	/* public function UpdateHabitStatus(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'habit_id' => 'required',
			'status'  => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);

		if ($user) {
			$habit = Habit::find($request->habit_id);
			$habit->status = $request->status;
			$habit->save();
			return response()->json(['success' => true, 'message' => 'Status has been updated successfully.']);
		} else {
			return response()->json(['success' => false, 'message' => 'Unable to update status.Please try again or contact to admin.']);
		}
	} */
/* public function UpdateHabitItemStatus(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'habit_id' => 'required',
			'date'     => 'required',
			'status'  => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);

		if ($user) {
			$item=HabitStatus::where('habit_id',$request->habit_id)->where('date', $request->date)->first();
			if(@$item){
			 HabitStatus::where('habit_id',$request->habit_id)->where('date', $request->date)->first()->update(['status' =>$request->status]);	
			 return response()->json(['success' => true, 'message' => 'status has been updated successfully.']);
			}else{
				$habititem = HabitStatus::make();
				$habititem->habit_id = $request->habit_id;
				$habititem->date = $request->date;
				$habititem->status =$request->status;
				$habititem->save();	
				return response()->json(['success' => true, 'message' => 'status has been updated successfully.']);
			}
			
		} else {
			return response()->json(['success' => false, 'message' => 'Unable to update status.Please try again or contact to admin.']);
		}
	} */
	 public function UpdateHabitItemStatus(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'habit_id' => 'required',
			'item_id'  => 'required',
			'status'  => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);

		if ($user) {
			$habit = Habit::find($request->habit_id);
			$item = HabitItems::where('id', '=', $request->item_id)->where('habit_id', '=', $request->habit_id)->firstorfail();
			if ($item) {
				$item->item_status = $request->status;
				if ($item->next_date >= date('Y-m-d H:i:s') && $habit->end_date <= date('Y-m-d H:i:s')) {
					if ($habit->repeat != 'Never' && $habit->end_date <= date('Y-m-d H:i:s')) {
						$item->next_date = $this->nextDate($habit, $item->next_date);
					}
				} else {

					if ($habit->repeat != 'Never' && $habit->end_date <= date('Y-m-d H:i:s')) {
						$item->next_date = $this->nextDate($habit, $habit->start_date);
					}
				}
				$item->save();
				return response()->json(['success' => true, 'message' => 'status has been updated successfully.']);
			} else {
				return response()->json(['success' => false, 'message' => 'Unable to update status.Please try again or contact to admin.']);
			}
		} else {
			return response()->json(['success' => false, 'message' => 'Unable to update status.Please try again or contact to admin.']);
		}
	} 
	public function nextDate($habit, $date)
	{
		if ($habit->repeat == 'Daily') {
			$n_date = date('Y-m-d H:i:s', strtotime('+1 days', $date));
		} elseif ($habit->repeat == 'Weekly') {
			$n_date = date('Y-m-d H:i:s', strtotime('+1 weeks', $date));
		} elseif ($habit->repeat == '2 Weeks') {
			$n_date = date('Y-m-d H:i:s', strtotime('+2 weeks', $date));
		} elseif ($habit->repeat == 'Monthly') {
			$n_date = date('Y-m-d H:i:s', strtotime('+1 months', $date));
		} else {
			$n_date = date('Y-m-d H:i:s', strtotime('+1 years', $date));
		}

		if ($n_date >= date('Y-m-d H:i:s')) {
			return $n_date;
		} elseif ($n_date >= $habit->end_date) {
			return $habit->end_date;
		} else {
			$this->nextDate($habit, $n_date);
		}
	}
	public function AddJournals(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'coach_id' => 'required',
			'description' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$journal = Journal::make();
			$journal->user_id = $request->coach_id;
			$journal->client_id = $user->id;
			$journal->description = $request->description;
			$journal->date_time = date('Y-m-d H:i:s');
			// if ($request->hasfile('image')) {
			// 	$img = array();
			// 	$files = $request->file('image');
			// 	foreach ($files as $file) {
			// 		$filename = ((string)(microtime(true) * 10000)) . "-" . $file->getClientOriginalName();
			// 		$file->move('images/', $filename);
			// 		$img[] = 'images/' . $filename;
			// 	}
			// 	$journal->images = json_encode($img);
			// }

			if ($request->image) {
				
				$frontimage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
				$Journalname = time().'.jpeg';
				file_put_contents('public/'.$Journalname, $frontimage);
					
				$journal->images = $Journalname;
				
				
			/* 	foreach ($files as $file) {
					$frontimage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $file));
					$filename = $i . time() . '.jpeg';
					file_put_contents('images/' . $filename, $frontimage);
					$img[] = 'images/' . $filename;
					$i++;
				} */
				
			}
			$journal->save();
			return response()->json(['success' => true, 'message' => 'journal saved successfully.']);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}

	public function getJournals(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'coach_id' => 'required',
			'client_id' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$journals = Journal::where('user_id', '=', $request->coach_id)->where('client_id', '=', $request->client_id)->orderBy('date_time', 'DESC')->get();
			$group = array();
			foreach ($journals as $key => $value) {

				$group[date('Y-m-d', strtotime($value['date_time']))][] = $value;
			}


			return response()->json(['success' => true, 'journals' => $group]);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}

	public function AddReminder(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'title' => 'required',
			'day' => 'required',
			'time' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$reminder = $user->clientReminder()->make();
			$reminder->client_id = $user->id;
			$reminder->title = $request->title;
			$reminder->day = $request->day;
			$reminder->details = $request->details;
			$reminder->time = date('H:i:s', strtotime($request->time));

			$reminder->save();

			return response()->json(['success' => true, 'message' => 'Reminder saved successfully.']);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}

	public function UpdateReminder(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'token' => 'required', ~'reminder_id' => 'required',
			'title' => 'required',
			'day' => 'required',
			'time' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}


		$user = JWTAuth::authenticate($request->token);

		if ($user) {
			$reminder = $user->clientReminder()->findorfail($request->reminder_id);
			$reminder->client_id = $user->id;
			$reminder->title = $request->title;
			$reminder->day = $request->day;
			$reminder->details = $request->details;
			$reminder->time = date('H:i:s', strtotime($request->time));

			$reminder->save();

			return response()->json(['success' => true, 'message' => 'Reminder updated successfully.']);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}
	public function DeleteReminder(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'id' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user->clientReminder()->where('id', '=', $request->id)->delete()) {
			return response()->json(['success' => true, 'message' => 'Reminder has been deleted successfully.']);
		} else {
			return response()->json(['success' => false, 'message' => 'Unable to delete Reminder.Please try again or contact to admin.']);
		}
	}
	public function GetReminder(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'token' => 'required'

		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			return response()->json(['success' => true, 'reminders' => $user->clientReminder()->get()]);
		} else {
			return response()->json(['success' => false, 'message' => 'Unable to delete Reminder.Please try again or contact to admin.']);
		}
	}

	public function AddNote(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'description' => 'required',
			'client_id' => 'required',
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$note = $user->CoachNote()->make();
			$note->user_id = $user->id;
			$note->client_id = $request->client_id;
			$note->description = $request->description;
			$note->date_time = date('Y-m-d H:i:s');
			if ($request->images1) {
				$frontimage1 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->images1));
				$img_name1 = time() . '.jpeg';
				// $new_path = Storage::disk('public')->put($profile_pic, $frontimage);
				file_put_contents('public/'.$img_name1, $frontimage1);
				$note->images1 = $img_name1;
			}
			if ($request->images2) {
				$frontimage2 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->images2));
				$img_name2 = time() . '1.jpeg';
				// $new_path = Storage::disk('public')->put($profile_pic, $frontimage);
				file_put_contents('public/'.$img_name2, $frontimage2);
				$note->images2 = $img_name2;
			}
			$note->save();

			return response()->json(['success' => true, 'message' => 'Note saved successfully.']);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}

	public function UpdateNote(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'description' => 'required',
			'note_id' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}


		$user = JWTAuth::authenticate($request->token);

		if ($user) {
			$note = $user->CoachNote()->findorfail($request->note_id);
			$note->user_id = $user->id;
			$note->description = $request->description;
			$note->date_time = date('Y-m-d H:i:s');
			if ($request->images1) {
				$frontimage1 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->images1));
				$img_name1 = time() . '1.jpeg';
				// $new_path = Storage::disk('public')->put($profile_pic, $frontimage);
				file_put_contents('public/'.$img_name1, $frontimage1);
				$note->images1 = $img_name1;
			}
			if ($request->images2) {
				$frontimage2 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->images2));
				$img_name2 = time() . '.jpeg';
				// $new_path = Storage::disk('public')->put($profile_pic, $frontimage);
				file_put_contents('public/'.$img_name2, $frontimage2);
				$note->images2 = $img_name2;
			}
			$note->save();

			return response()->json(['success' => true, 'message' => 'Note updated successfully.']);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}

	public function DeleteNote(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'id' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user->CoachNote()->where('id', '=', $request->id)->delete()) {
			return response()->json(['success' => true, 'message' => 'Note has been deleted successfully.']);
		} else {
			return response()->json(['success' => false, 'message' => 'Unable to delete Note.Please try again or contact to admin.']);
		}
	}

	public function GetNote(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'client_id' => 'required'

		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			return response()->json(['success' => true, 'notes' => $user->CoachNote()->whereClientId($request->client_id)->orderBy('id', 'desc')->get()]);
		} else {
			return response()->json(['success' => false, 'message' => 'Unable to delete Reminder.Please try again or contact to admin.']);
		}
	}

	public function pages(Request $request)
	{
		return response()->json(['success' => true, 'Pages' => Page::where('status', '=', '1')->get()]);
	}

	public function chatRoom(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'coach_id' => 'required',
			'client_id' => 'required'

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {

			if (ChatRooms::whereCoachId($request->coach_id)->whereClientId($request->client_id)->count() > 0) {
				$chatroom = ChatRooms::whereCoachId($request->coach_id)->whereClientId($request->client_id)->first();
				return response()->json(['success' => true, 'message' => 'Chat room already exists.', 'room_id' => $chatroom->id]);
			} else {
				$createchatroom = ChatRooms::make();
				$createchatroom->coach_id = $request->coach_id;
				$createchatroom->client_id = $request->client_id;
				$createchatroom->save();
				return response()->json(['success' => true, 'message' => 'Chat room created successfully.', 'room_id' => $createchatroom->id]);
			}
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}
	public function addOrUpdateStripe(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'secret_key' => 'required',
			'published_key' => 'required'

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$exist = $user->stripe()->first();
			if ($exist) {
				if ($request->auth_code) {
					if ($exist->verified == 1) {
						if ($exist->auth_code == $request->auth_code) {
							$stripe = StripeKey::find($exist->id);
							$stripe->secret_key = $request->secret_key;
							$stripe->published_key = $request->published_key;
							$stripe->verified = 0;
							$stripe->status = 0;
							$stripe->auth_code = NULL;
							$stripe->save();
							return response()->json(['success' => true, 'message' => 'Stripe updated successfully.Please verify it by paying $1.']);
						} else {
							return response()->json(['success' => false, 'message' => 'Your auth code is not verified.Please verify it.']);
						}
					} else {
						if ($exist->auth_code == $request->auth_code) {
							$stripe = StripeKey::find($exist->id);
							$stripe->secret_key = $request->secret_key;
							$stripe->published_key = $request->published_key;
							$stripe->auth_code = NULL;
							$stripe->save();
							return response()->json(['success' => true, 'message' => 'Stripe updated successfully.Please verify it by paying $1.']);
						} else {
							return response()->json(['success' => false, 'message' => 'Your auth code is not verified.Please verify it.']);
						}
					}
				} else {
					return response()->json(['success' => false, 'message' => 'Please provide the auth code.']);
				}
			} else {
				$stripe = $user->stripe()->make();
				$stripe->secret_key = $request->secret_key;
				$stripe->published_key = $request->published_key;
				$stripe->auth_code = NULL;
				$stripe->save();
				return response()->json(['success' => true, 'message' => 'Stripe added successfully.Please verify it by paying $1.']);
			}
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}
	public function sendCodeForStripe(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required'

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$exist = $user->stripe()->first();

			if ($exist) {
				$otp = mt_rand(1000, 9999);
				$exist->auth_code = $otp;
				$exist->save();
				/* mail process start */
				$email = $user->email;
				$name = $user->name;
				$subject = "Request to update stripe keys.";
				$body = Template::where('type', 4)->orderBy('id', 'DESC')->first()->content;
				$content = array('name' => $name, 'otp' => $otp);
				foreach ($content as $key => $parameter) {
					$body = str_replace('{{' . $key . '}}', $parameter, $body); // this will replace {{username}} with $data['username']
				}
				//Mail::to($email)->send(new DbTemplateMail($body, $subject));
				/* mail process end */

				/* mail process start */
				$from_email = config('app.email_from');
				$email = $user->email;
				$name = $user->name;
				$subject = "Request to update stripe keys,";
				$body = Template::where('type', 4)->orderBy('id', 'DESC')->first()->content;
				$content = array('name' => $name, 'otp' => $otp);
				foreach ($content as $key => $parameter) {
					$body = str_replace('{{' . $key . '}}', $parameter, $body); // this will replace {{username}} with $data['username']
				}
				Mail::send('emails.dynamic', ['template' => $body, 'name' => $name, 'otp' => $otp], function ($m) use ($from_email, $email, $name, $subject) {
					$m->from($from_email, 'Life Canon');

					$m->to($email, $name)->subject($subject);
				});
				/* mail process end */
				return response()->json(['success' => true, 'message' => "A code is sent to your email. Please check"]);
			} else {
				return response()->json(['success' => false, 'message' => 'Please add your stripe key.']);
			}
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}
	public function getStripe(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required'

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			return response()->json(['success' => true, 'stripe' => $user->stripe()->first()]);
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}

	public function verifyStripe(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'stripe_token' => 'required',
			'amount' => 'required'
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$exist = $user->stripe()->first();
			if ($exist and !$exist->verified) {
				Stripe\Stripe::setApiKey(config('app.stripe_test') ? $exist->secret_key : $exist->secret_key);
				$pay = Stripe\Charge::create([
					"amount" => $request->amount * 100,
					"currency" => "USD",
					"source" => $request->stripe_token,
					"description" => "test",
				]);

				$customerpay = Payment::make();
				$customerpay->user_id = $user->id;
				$customerpay->amount = $pay->amount / 100;
				$customerpay->transaction_id = $pay->balance_transaction;
				$customerpay->status = $pay->paid;
				$customerpay->payment_for = 6;
				$customerpay->payment_date = date('Y-m-d H:i:s', $pay->created);
				$customerpay->save();
				if ($pay->balance_transaction) {
					$exist->verified = 1;
					$exist->status = 1;
					$exist->save();
					return response()->json([
						'success' => true,
						'message' => 'Stripe verified successfully.',
					]);
				} else {
					return response()->json([
						'success' => false,
						'message' => 'Your payment has been failed.Please try again or contact to admin.',
					]);
				}
			} else {
				return response()->json(['success' => false, 'message' => 'Already Verified.']);
			}
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}

	public function listSubscription(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required'
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {

			if ($user->user_type == 1) {
				$plans = $user->selectedPlan()->where('subscription_id', '!=', '')->where('subscription_status','!=','')->with('plan')->get();
				$addClient = $user->getAddedClient()->where('subscription_id_for_coach', '!=', '')->get();
				return response()->json(['success' => true, 'plans' => $plans, 'addedclients' => $addClient]);
			} else {
				$plans = AddClient::where('client_id', '=', $user->id)->where('subscription_id_for_client', '!=', '')->get();
				$appointments = $user->ClientAppointment()->where('subscription_id', '!=', '')->with('coach')->get();
				return response()->json(['success' => true, 'plans' => $plans, 'appointments' => $appointments]);
			}
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}

	public function cancelSubscription(Request $request)
	{
		
		
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'subscription_id' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$payment = $user->payment()->where('subscription_id', '=', $request->subscription_id)->first();
			if($request->type==1 && $user->user_type == 1){
				if($payment){
					$payment->subscription_status = 0;
					$payment->save();
					$plan = $user->selectedPlan()->where('subscription_id', '=', $request->subscription_id)->first();
					$plan->subscription_status = 0;
					$plan->save();
					return response()->json(['success' => true, 'message' => 'Unsubscribe successfully.']);	
				}
			}else{
			if ($payment) {
				if ($user->user_type == 1) {
					Stripe\Stripe::setApiKey(config('app.stripe_key'));
					if ($payment->user_id == $user->id) {
						$current_subscription = \Stripe\Subscription::retrieve($request->subscription_id);
						$return = $current_subscription->cancel();
						if ($return->status == 'canceled') {
							$payment->subscription_status = 0;
							$payment->save();
							if ($payment->payment_for == 1) {
								$plan = $user->selectedPlan()->where('subscription_id', '=', $request->subscription_id)->first();
								$plan->subscription_status = 0;
								$plan->save();
								/*for send and save notification  */
								$msg["title"] = "Plan Cancelled";
								$usertype = 'client';
								$msg["body"] = "Your coach " . $user->name . " cancelled the subscription plan ";
								$msg['type'] = "Plan Cancelled";
								$msg['user_type'] = $usertype;
								$this->sendNotification($plan->client_id, $msg);
								/*for send and save notification  */
							}
							if ($payment->payment_for == 2 or $payment->payment_for == 5) {
								$addClient = $user->getAddedClient()->where('subscription_id_for_coach', '=', $request->subscription_id)->first();
								$addClient->subscription_status_for_coach = 0;
								if (!$addClient->client_id) {
									$addClient->status = 2;
								}
								$addClient->save();
							}
							return response()->json(['success' => true, 'message' => 'Unsubscribe successfully.']);
						}
					} else {
						return response()->json(['success' => false, 'message' => 'Unauthorized subscription id.']);
					}
				} else {
					$coach_stripe_key = StripeKey::where('user_id', '=', $payment->payee_id)->first();
					Stripe\Stripe::setApiKey(config('app.stripe_test') ? $coach_stripe_key->secret_key : $coach_stripe_key->secret_key);
					$current_subscription = \Stripe\Subscription::retrieve($request->subscription_id);
					$return = $current_subscription->cancel();
					if ($return->status == 'canceled') {
						$payment->subscription_status = 0;
						$payment->save();
						if ($payment->payment_for == 3) {
							$plan = AddClient::where('client_id', '=', $user->id)->where('subscription_id_for_client', '=', $request->subscription_id)->first();
							$plan->subscription_status_for_client = 0;
							$plan->save();
							/*for send and save notification  */
							$msg["title"] = "Plan Cancelled";
							$usertype = 'coach';
							$msg["body"] = "Your client " . $user->name . " cancelled the subscription plan ";
							$msg['type'] = "Plan Cancelled";
							$msg['user_type'] = $usertype;
							$this->sendNotification($plan->user_id, $msg);
							/*for send and save notification  */
						}
						if ($payment->payment_for == 4) {
							$appointments = $user->ClientAppointment()->where('subscription_id', '=', $request->subscription_id)->first();
							$appointments->subscription_status = 0;
							$appointments->save();
							/*for send and save notification  */
							$msg["title"] = "Appointment Cancelled";
							$usertype = 'coach ';
							$msg["body"] = "Your client " . $user->name . " cancelled the appointment ";
							$msg['type'] = "Appointment Cancelled";
							$msg['user_type'] = $usertype;
							$this->sendNotification($appointments->user_id, $msg);
							/*for send and save notification  */
						}
						return response()->json(['success' => true, 'message' => 'Unsubscribe successfully.']);
					}
				}
			} else {
				return response()->json(['success' => false, 'message' => 'Unauthorized subscription id.']);
			}
		  }
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}

	public function earning(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required'

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			if (isset($request->filter_date)) {
				$enddate = strtotime(date('Y-m-d'). ' 23:59:59');
				$startdate = strtotime($request->filter_date.' 00:00:00');
				
			}else{
				$enddate =  strtotime($request->filter_date. ' 23:59:59');
				$startdate = strtotime('2021-01-01 00:00:00');
			}
			$coach_stripe_key = StripeKey::where('user_id', '=', $user->id)->first();
			/* client subscription data */
				$payments_group_by = array();
				 $alladdedclients = AddClient::where('user_id','=',$user->id)->where('subscription_id_for_client','!=',' ')->get();
				if(@$alladdedclients){
					$amount=0;
					$i=0;
					foreach(@$alladdedclients as $alladdedclient){
						
						 $clientsub_id  =  $alladdedclient->subscription_id_for_client;
						 $stripe  =  Stripe\Stripe::setApiKey($coach_stripe_key->secret_key);
						 $allsubscriptions = \Stripe\Subscription::retrieve($clientsub_id);
						 $invoices = Stripe\Invoice::all(['subscription' => $clientsub_id,'expand' => ['data.charge'],'created' => ['gte' => $startdate ,'lte' => $enddate]]);
						 $client_id = $alladdedclient->client_id.'<br/>';
						 $clients = User::where('id','=',$client_id)->first();
							
							$totalAmount = 0;
							foreach ($invoices->autoPagingIterator() as $invoice) {
									$totalAmount += $invoice->amount_paid;	 
							} 
						 $totalAmount = @$totalAmount/100;
						 $payments_group_by[$i] = array('paidtodate'=>$totalAmount, 'users'=>$clients,'payment_date'=>$alladdedclient->start_date);
						 
						 $amount = @$amount + $totalAmount;
						$i++;
					}
					
				
				
				} 
			/* $coach_stripe_key = StripeKey::where('user_id', '=', $user->id)->first();
			Stripe\Stripe::setApiKey(config('app.stripe_test') ? $coach_stripe_key->secret_key : $coach_stripe_key->secret_key);
			if (isset($request->filter_date)) {
				$payments = Payment::with('user')->wherePayeeId($user->id)->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->filter_date)))->get();
				$payments_group_by = Payment::with('user')->wherePayeeId($user->id)->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->filter_date)))->groupBy('user_id')->selectRaw('*, sum(amount) as tamount')->get();
			} else {
				$payments = Payment::with('user')->wherePayeeId($user->id)->get();
				$payments_group_by = Payment::with('user')->wherePayeeId($user->id)->groupBy('user_id')->selectRaw('*, sum(amount) as tamount')->get();
			}
			$amount = 0;
			$ontimeamount = 0;
			foreach ($payments as $payment) {
				$subscription_amount = 0;
				$ontimeamount = 0;
				if ($payment->subscription_id) {
					$subscriptions = \Stripe\Subscription::retrieve($payment->subscription_id);
					$subscription_amount = $payment->amount * $subscriptions->items->total_count;
				} else {
					$ontimeamount = $payment->amount;
				}
				$amount = $amount + ($subscription_amount + $ontimeamount);
			} */

			return response()->json(['success' => true, 'payment' => $payments_group_by, 'totalEarning' => $amount]);
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}

	public function savefcmToken(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'fcmtoken' => 'required',

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$fcm = $user->fcmToken()->first();
		
			if ($fcm) {

				$fcmtoken = FcmToken::findorfail($fcm->id);
				$fcmtoken->user_id = $user->id;
				$fcmtoken->fcmtoken = $request->fcmtoken;
				$fcmtoken->save();
				return response()->json(['success' => true,  'message' => 'Token updated successfully.']);
			} else {
				$fcmtoken = FcmToken::make();
				$fcmtoken->user_id = $user->id;
				$fcmtoken->fcmtoken = $request->fcmtoken;
				$fcmtoken->save();
				return response()->json(['success' => true,  'message' => 'Token saved successfully.']);
			}

			
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}
	public function saveNotifications(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'title' => 'required',
			'body' => 'required',
			'user_id' => 'required',
			'type' => 'required',
			'usertype' => 'required'

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {

			$notification = Notification::make();
			$notification->user_id = $request->user_id;
			$notification->title = $request->title;
			$notification->body = $request->body;
			$notification->type = $request->type;
			$notification->client_id = $request->client_id;
			$notification->coach_id = $request->coach_id;
			// $notification->save();

			/*for send and save notification  */
			$msg["title"] =  $request->title;
			$msg["body"] = $request->body;
			$msg['type'] = $request->type;
			$msg['user_type'] = $request->usertype;
			$msg['client_id'] = $request->client_id;
			$msg['coach_id'] = $request->coach_id;
			$this->sendNotification($request->user_id, $msg);
			/*for send and save notification  */
			return response()->json(['success' => true,  'message' => 'saved successfully.']);
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}

	public function readNotificationStatus(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'id' => 'required',
			'status' => 'required'

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$notification = Notification::find($request->id);
			$notification->status = $request->status;
			$notification->save();
			return response()->json(['success' => true,  'message' => 'read successfully.']);
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}
	public function getUnreadNotifications(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required'

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$notification = $user->notifications()->whereStatus(1)->orderBy('id', 'desc')->get();
			return response()->json(['success' => true,  'notifications' => $notification]);
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}
	public function getAllNotifications(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required'

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$notification = $user->notifications()->orderBy('id', 'desc')->paginate(20);
			return response()->json(['success' => true,  'notifications' => $notification]);
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}
	public function deleteNotification(Request $request){
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'id'	=> 'required'

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		
		$user = JWTAuth::authenticate($request->token);
		if($user){
			Notification::where('id', '=', $request->id)->delete();
			return response()->json(['success' => true,  'message' => 'Notification Deleted successfully.']);
		}else{
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}
	public function addGoal(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'name' => 'required',
			'client_id' => 'required',
			'user_id' => 'required'

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$goal = goal::make();
			$goal->user_id = $request->user_id;
			$goal->client_id = $request->client_id;
			$goal->name = $request->name;
			$goal->save();
			return response()->json(['success' => true,  'message' => 'saved successfully.']);
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}
	public function updateGoal(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'id' => 'required'

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$goal = goal::find($request->id);
			if ($request->name) {

				$goal->name = $request->name;
			}
			$goal->status = $request->status;
			$goal->save();
			return response()->json(['success' => true,  'message' => 'update successfully.']);
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}

	public function deleteGoal(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'id' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		$user_type = ($user->user_type == 1) ? "user_id" : "client_id";
		if ($user) {
			if (goal::where('id', '=', $request->id)->where($user_type, '=', $user->id)->delete()) {
				return response()->json(['success' => true, 'message' => 'goal has been deleted successfully.']);
			} else {
				return response()->json(['success' => false, 'message' => 'Unable to delete goal.Please try again or contact to admin.']);
			}
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}


	public function getGoal(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'client_id' => 'required',
			'user_id' => 'required'

		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			$goals = goal::where('user_id', '=', $request->user_id)->where('client_id', '=', $request->client_id)->get();
			return response()->json(['success' => true,  'goals' => $goals]);
		} else {
			return response()->json(['success' => false, 'message' => 'Unauthorized user.Please try again or contact to admin.']);
		}
	}

	public function sendNotification($user_id, $msgdata = array())
	{
		$user = User::where('id','=',$user_id)->first();
		if($user){
			if($user->timezone){
				 $currentDateTime = Carbon::now()->setTimezone($user->timezone)->format('Y-m-d H:i:s');
			}else{
				 $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
			}
			
		}
		$SERVER_API_KEY = 'AAAADSTvQMw:APA91bGA59KdYQtEs9Jp82wq4-du_cYRxONwUBkRO2pm_bMTAC6vwuvd239EbkBjyzo4v-wNUXHslo0pF0FKofNWS_y8_Iur-kHbovVQmQMOEDQ99kKFjuHmuzunpCdTvmTIhmwVeh7y';
		// $msgdata["body"] ="Test by Vijay.";
		// $msgdata["title"] = "PHP ADVICES";
		// $msgdata["sound"] = "default"; 
		// $msgdata["type"] = 1;
		// $regfcm=array('ed6FYCvJTVeGJGl4Q1oZmx:APA91bHzyDudFt5UY2ai81fNp1ZLb0sxCQlHkUDKn-anpXBJke6Ud5AD4p4j-YL94v3csIBytr6cOUdrRtlovZbOUmWbO2Gp3aStfBofd4Gp6Np6B7VIRuNuYqeza7cRCl1zzhYNVf8L');

		$regfcm = FcmToken::whereUserId($user_id)->first()->fcmtoken;
		$msgdata['sound'] = 'default';

		$data = [
			"registration_ids" => array($regfcm),
			"data" => $msgdata,
			"notification" => $msgdata
		];

		$saveNotification = Notification::make();
		$saveNotification->user_id = $user_id;
		$saveNotification->title = $msgdata['title'];
		$saveNotification->body = $msgdata['body'];
		$saveNotification->type = $msgdata['type'];
		$saveNotification->coach_id = @$msgdata['coach_id'];
		$saveNotification->client_id = @$msgdata['client_id'];
		$saveNotification->ndate = $currentDateTime;
		$saveNotification->save();
		$dataString = json_encode($data);

		$headers = [
			'Authorization: key=' . $SERVER_API_KEY,
			'Content-Type: application/json',
		];

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

		$response = curl_exec($ch);
	}

	public function sendNotificationForAllUser($regfcm = array(), $msgdata = array())
	{
		$SERVER_API_KEY = 'AAAADSTvQMw:APA91bGA59KdYQtEs9Jp82wq4-du_cYRxONwUBkRO2pm_bMTAC6vwuvd239EbkBjyzo4v-wNUXHslo0pF0FKofNWS_y8_Iur-kHbovVQmQMOEDQ99kKFjuHmuzunpCdTvmTIhmwVeh7y';
		$habits = Habit::whereStatus(1)->get();
		if ($habits) {
			foreach ($habits as $habit) {
				$alert = $habit->alert;
				if ($alert) {
					$passed_session = $this->datediffInWeeks(date('Y-m-d'), date('Y-m-d', strtotime($habit->start_date)));
					if ($alert == '5 minutes before') {

						$alert_time = date("Y-m-d H:i:s", strtotime($habit->start_date) - (5 * 60));
					} elseif ($alert == '10 minutes before') {
						$alert_time = date("Y-m-d H:i:s", strtotime($habit->start_date) - (10 * 60));
					} elseif ($alert == '15 minutes before') {
						$alert_time = date("Y-m-d H:i:s", strtotime($habit->start_date) - (15 * 60));
					} elseif ($alert == '30 minutes before') {
						$alert_time = date("Y-m-d H:i:s", strtotime($habit->start_date) - (30 * 60));
					} elseif ($alert == '1 hour before') {
						$alert_time = date("Y-m-d H:i:s", strtotime($habit->start_date) - (60 * 60));
					} elseif ($alert == '2 hour before') {
						$alert_time = date("Y-m-d H:i:s", strtotime($habit->start_date) - (120 * 60));
					} elseif ($alert == '1 day before') {
						$alert_time = date("Y-m-d H:i:s", strtotime($habit->start_date) - (1440 * 60));
					} else {

						$alert_time = date("Y-m-d H:i:s", strtotime($habit->start_date) - (15 * 60));
					}

					$time_diffrence = round(abs(strtotime(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $alert_time)->format('H:i')) - strtotime(date('H:i'))) / 60, 2);


					if (($habit->number_of_session > $passed_session && $passed_session >= 0) && in_array(date('l'), $habit->week_days) && $time_diffrence == 0) {

						$fcmtoken = FcmToken::Where('user_id', '=', $habit->client_id)->first();
						if ($fcmtoken) {

							/*for send and save notification  */
							$msgdata["title"] = "Habit List";
							$usertype = 'client ';
							$msgdata["body"] = "Habit start reminder";
							$msgdata['type'] = "Habit List";
							$msgdata['user_type'] = $usertype;
							$msgdata['coach_id'] = $habit->user_id;
							$msgdata['client_id'] =  $habit->client_id;

							// $this->sendNotification(($user->user_type == 1) ? $request->client_id : $request->coach_id, $msg);
							/*for send and save notification  */
							$saveNotification = Notification::make();
							$saveNotification->user_id = $habit->client_id;
							$saveNotification->title = $msgdata['title'];
							$saveNotification->body = $msgdata['body'];
							$saveNotification->type = $msgdata['type'];
							$saveNotification->coach_id = @$msgdata['coach_id'];
							$saveNotification->client_id = @$msgdata['client_id'];
							$saveNotification->save();
							$regfcm[] = $fcmtoken->fcmtoken;
							$data = [
								"registration_ids" => $regfcm,
								"data" => $msgdata,
								"notification" => $msgdata

							];
							$dataString = json_encode($data);

							$headers = [
								'Authorization: key=' . $SERVER_API_KEY,
								'Content-Type: application/json',
							];

							$ch = curl_init();

							curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
							curl_setopt($ch, CURLOPT_POST, true);
							curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

							echo $response = curl_exec($ch);
						}
					}
				}
			}
		}
	}

	function datediffInWeeks($date1, $date2)
	{
		// if ($date1 > $date2) return $this->datediffInWeeks($date2, $date1);
		$first = date('m/d/Y', strtotime($date1));
		$second = date('m/d/Y', strtotime($date2));
		return floor((strtotime($first) - strtotime($second)) / 604800);
	}

	public function AddFeedback(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'description' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}

		$user = JWTAuth::authenticate($request->token);
		$useremail = $user->email;
		$username = $user->name;
		if ($user) {
			$description = $request->description;
			$feedback = AppFeedback::make();
			$feedback->user_id = $user->id;
			$feedback->description = $request->description;
			$feedback->save();
			
			/* mail process start */
			$from_email = $useremail;
			$email = 'lifecoachpoul@gmail.com';
			$email1 = 'scott@contractorwebsiteservices.com';
			//$name = $user_details->name;
			$subject = "New feedback";
			$body = Template::where('type', 5)->orderBy('id', 'DESC')->first()->content;
			
			$content = array('description' =>$description,'user_name'=>$username);
			foreach ($content as $key => $parameter) {
				$body = str_replace('{{' . $key . '}}', $parameter, $body); // this will replace {{username}} with $data['username']
			}
			Mail::send('emails.dynamic', ['template' => $body, 'description' => $description,'user_name'=>$username], function ($m) use ($from_email, $email, $subject) {
				$m->from($from_email, 'Life Canon');

				$m->to($email)->subject($subject);
			});
			Mail::send('emails.dynamic', ['template' => $body, 'description' => $description,'user_name'=>$username], function ($m) use ($from_email, $email1, $subject) {
				$m->from($from_email, 'Life Canon');

				$m->to($email1)->subject($subject);
			});

			return response()->json(['success' => true, 'message' => 'Feedback post successfully.']);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Token is not valid. please contact to the admin.',
			]);
		}
	}

	public function GetFeedback(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'token' => 'required'

		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = JWTAuth::authenticate($request->token);
		if ($user) {
			return response()->json(['success' => true, 'feedbacks' => $user->feedback()->with('user')->get()]);
		} else {
			return response()->json(['success' => false, 'message' => 'Unable to find user.Please try again or contact to admin.']);
		}
	}
	public function GetAllFeedback(Request $request)
	{
		$feedbacks = AppFeedback::with('user')->whereStatus(1)->get();
		return response()->json(['success' => true, 'feedbacks' => $feedbacks]);
	}

	public function checkSubscriptionStatusforcoach($subscription_id)
	{
		Stripe\Stripe::setApiKey(config('app.stripe_key'));
		$current_subscription = \Stripe\Subscription::retrieve($subscription_id);
		if ($current_subscription->status == 'active') {
			return true;
		} else {
			return false;
		}
	}
	public function get_ios_user(Request $request)
	{
		$this->validate($request, [
			'token' => 'required'
		]);

		$user = JWTAuth::authenticate($request->token);
		if ($user->availability()->get()) {
			$availability = 'yes';
		} else {
			$availability = 'no';
		}
		$slectedPlans = $user->selectedPlan()->orderBy('id', 'desc')->first();
		//print_r($slectedPlans);
		$fcm = $user->fcmtoken()->first()->fcmtoken;
				  $receipt='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';
				  $curl = curl_init();

				  curl_setopt_array($curl, array(
				  CURLOPT_URL => 'https://sandbox.itunes.apple.com/verifyReceipt',
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'POST',
				  CURLOPT_POSTFIELDS =>'{
				"receipt-data":"'.$receipt.'",
				"password":"60632e427bc6439d916b705819b01b1a",
				"exclude-old-transactions":true


				}',
				  CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json'
				  ),
				));

				$response = curl_exec($curl);
				print_r($response);
				die;
				curl_close($curl);
				
				
		
		
	}

	public function testEmail()
	{
		/* mail process start */
		echo $encrypt_user_id = encrypt(66);
		$from_email = config('app.email_from');
		$email = 'ajeet.singh429@gmail.com';
		$name = 'Ajeet singh';
		$subject = "Welcome to Life Canon,";
		$body = Template::where('type', 2)->orderBy('id', 'DESC')->first()->content;
		$content = array('name' => $name, 'user_id' => encrypt(2));
		foreach ($content as $key => $parameter) {
			$body = str_replace('{{' . $key . '}}', $parameter, $body); // this will replace {{username}} with $data['username']
		}
		// Mail::to($email)->send(new DbTemplateMail($body, $subject));
		Mail::send('emails.dynamic', ['template' => $body, 'name' => $name, 'user_id' => encrypt(2)], function ($m) use ($from_email, $email, $name, $subject) {
			$m->from($from_email, 'Life Canon');

			$m->to($email, $name)->subject($subject);
		});
		/* mail process end */
	}
}
