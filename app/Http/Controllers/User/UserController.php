<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Template;
use App\Models\AddClient;
use App\Models\goal;
use App\Models\Payment;
use App\Models\SelectedPlan;
use App\Models\plan;
use App\Models\Availability;
use App\Models\Note;
use App\Models\Habit;
use App\Models\HabitStatus;
use App\Models\AppFeedback;
use App\Models\StripeKey;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\ChatRooms;
use App\Models\FcmToken;
use Mail;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Session;
use Stripe;
use Symfony\Contracts\Service\Attribute\Required;
use Tymon\JWTAuth\Contracts\Providers\Auth as ProvidersAuth;
use App\Mail\DbTemplateMail;
use DateTime;
use Carbon\Carbon;

class UserController extends Controller
{
    # Function to get User Login
    public function getLogin()
    {
        if (Auth::check()) return \redirect()->route('user.dashboard');

        return view('user/login');
    }

    public function postLogin(Request $request)
    {
		
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->withErrors($validator);
        }
        $credentials['is_varified'] = 1;

        if (!Auth::guard()->attempt($credentials)) {
            return redirect()->back()->withErrors(['Error' => 'Credentials do not match any record or you have not confirm your email.']);
        } else {
			/*  Stripe\Stripe::setApiKey(config('app.stripe_key'));
			$current_subscription = \Stripe\Subscription::retrieve('sub_1LzfMfIFwORoVejtar0KN0uq');
			//$current_subscription->cancel()
			dd($current_subscription);
			die;  */
			
            $user = Auth::user();
            if ($user->user_type == 1) {
				$live_plan_status ='';
			   $plan = $user->selectedPlan()->orderBy('id', 'desc')->first();
			   if(!$plan){
				 return redirect()->route('coach.plan')->withSuccess('Logged in  successfully');  
			   }
			   if(@$plan->user_id == 73){
			    $live_plan_status='active';
			   }else{
				Stripe\Stripe::setApiKey(config('app.stripe_key'));
				$current_subscription1 = \Stripe\Subscription::retrieve(@$plan->subscription_id); 
				$live_plan_status = $current_subscription1->status;
			   }
			  
				/* echo $plan->end_date;
				echo'helloo'. date('Y-m-d h:i:s',$current_subscription1->current_period_start).'<br>';
				echo'helloo'. date('Y-m-d h:i:s',$current_subscription1->current_period_end);
				dd($current_subscription1); */
				
				if (strtotime(date('Y-m-d h:i:s', strtotime(@$plan->end_date))) < strtotime(date('Y-m-d h:i:s')) && @$plan->subscription_status == 1 && @$live_plan_status=='active') {
					
					$iospayment_id = Payment::where('transaction_id',@$plan->subscription_id)->first();
						if(!@$iospayment_id->ios_original_transaction_id){
							if(@$plan->user_id !=73){
								if(@$plan->subscription_id){
								Stripe\Stripe::setApiKey(config('app.stripe_key'));
								$current_subscription = \Stripe\Subscription::retrieve(@$plan->subscription_id);
								
								if (@$current_subscription->status == 'active') {
									if ($plan->plan_id == 2) {
										$plan->end_date = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($plan->end_date)));
									} else {
										$plan->end_date = date('Y-m-d H:i:s', strtotime('+1 month', strtotime($plan->end_date)));
									}
								 $plan->save();
								}
								}
							}
						}
				}
				//die;
				/* echo "".$plan->subscription_id.'<br>';
				echo $plan->start_date;
				die; */ 
				
                if (($plan && ($plan->end_date > date('Y-m-d h:i:s'))) && (@$live_plan_status !='active')){
					return redirect()->route('coach.plan')->withSuccess('Logged in  successfully');
				}else{
					
                return redirect()->route('user.dashboard')->withSuccess('Logged in  successfully');
				}
            } else {
                return redirect()->route('user.dashboard')->withSuccess('Logged in  successfully');
            }
        }
    }

    public function logout()
    {
        $user = Auth::guard()->user();
        if ($user) {
            Auth::logout();
            Session::flush();
            return redirect('user-login');
        } else {
            return redirect('user-login');
        }
    }
    public function getSignUp()
    {
        if (Auth::check()) return \redirect()->route('user.dashboard');
        return view('user/signup');
    }
    public function getClientsignUp()
    {

        return view('user/clientsignup');
    }
    public function getCoachsignUp()
    {

        return view('user/coachsignup');
    }
    public function saveSignUp(Request $request)
    {

        //Validate data
        $data = $request->only('name', 'email', 'password', 'confirm_password', 'user_type','timezone');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50',
            'confirm_password' => 'required|same:password|min:6',
            'user_type' => 'required',
			'timezone' => 'required'
			
        ]);
		
		
	
        //Send failed response if request is not valid
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->messages()], 200);
            return redirect()->back()->withErrors($validator);
        }
        try {
            $data = User::make();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->user_type = $request->user_type;
            $data->status = 1;
			$data->timezone = $request->timezone;
            $data->password = Hash::make($request->password);
            // if ($request->profile_pic) {
            //     //$frontimage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->profile_pic));
            //     $profile_pic = time() . '.jpeg';
            //     $new_path = Storage::disk('public')->put('userimages', $request->profile_pic);
            //     //file_put_contents($profile_pic, $frontimage);
            //     $data->profile_pic = $profile_pic;
            // }
            if ($request->hasfile('profile_pic')) {
                $file = $request->file('profile_pic');
                $filename = ((string)(microtime(true) * 10000)) . "-" . $file->getClientOriginalName();
                $destinationPath = public_path('/');

                $file->move($destinationPath, $filename);
                $data->profile_pic = $filename;
            }
            $data->save();
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
            return redirect()->back()->withSuccess("Thank you for registering as " . $user_type . ". We have sent you an email for your verification to your registered email account. Please verify to activate your account!");
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput($request->all());
        }
        // return view('user/signup'); 
    }
    public function contactUs()
    {
        return view('/contact');
    }
    public function dashboard(Request $request)
    {
        $user = Auth::user();
		
		$user_id = $user->id;
		$stripekeys = '';
		$stripe = StripeKey::where('user_id','=',$user_id)->first();
		
		if(@$stripe){
			$stripekeys = $stripe;
		}else{
			$stripekeys='';
		}
	
        if ($user->user_type == 1) 
        {  $live_plan_status='';
            $plan = $user->selectedPlan()->orderBy('id', 'desc')->first();
			if(@$plan){
				if(@$plan->user_id == 73){
					$live_plan_status='active';
					}else{
					Stripe\Stripe::setApiKey(config('app.stripe_key'));
					$current_subscription1 = \Stripe\Subscription::retrieve(@$plan->subscription_id); 
					$live_plan_status = $current_subscription1->status;
				   }
			}
			
            /* if ((!$plan and @$plan->end_date < date('Y-m-d h:i:s')) OR ($plan and @$plan->end_date < date('Y-m-d h:i:s'))) */
			if ((!$plan and (@$plan->end_date < date('Y-m-d h:i:s'))) OR ( @$plan && (@$live_plan_status !='active')))  
			return redirect()->route('coach.plan')->withSuccess('Logged in  successfully');

            $addedclients = $user->getAddedClient()->where('client_name', 'like', '%' . $request->s . '%')->with(['user', 'client'])->get();

            // dd($addedclients->appointment->toArray());

            $getappointment = $user->CoachAppointment()->where('status', '=', 1)->get();

            $getarray = array();

            foreach($getappointment as $getappointment1)
            {
                $startdate = $getappointment1->date;
                $enddate = strtotime($getappointment1->end_date);
                $repeat = $getappointment1->repeat;
                $days = $getappointment1->day;

                if($repeat == "1")
                {
                    for($i = strtotime($days, strtotime($startdate)); $i <= $enddate; $i = strtotime('+1 week', $i))
                    {
                        $getdata1 = array('startDate'=>date('Y-m-d', $i),
                            'endDate'=>date('Y-m-d', $i),
                            'summary'=>'',); 

                        array_push($getarray,$getdata1);
                    }
                }
                else
                {
                    $getdata = array('startDate'=>$startdate,
                    'endDate'=>$startdate,
                    'summary'=>'',); 

                    array_push($getarray,$getdata);
                }
            }

            //dd($getarray);
            $totalapp = json_encode($getarray);

            //dd($totalapp);

            return view('user/coach/dashboard', compact('addedclients','totalapp','stripekeys'));
        } 
        else 
        {
            $coachdata = AddClient::with(['user', 'stripe'])->where('status', '=', 1)->where('client_id', '=', $user->id)->get();

            $getappointment = $user->ClientAppointment()->where('status', '=', 1)->get();

            $getarray = array();

            foreach($getappointment as $getappointment1)
            {
                $startdate = $getappointment1->date;
                $enddate = strtotime($getappointment1->end_date);
                $repeat = $getappointment1->repeat;
                $days = $getappointment1->day;

                if($repeat == "1")
                {
                    for($i = strtotime($days, strtotime($startdate)); $i <= $enddate; $i = strtotime('+1 week', $i))
                    {
                        $getdata1 = array('startDate'=>date('Y-m-d', $i),
                            'endDate'=>date('Y-m-d', $i),
                            'summary'=>'',); 

                        array_push($getarray,$getdata1);
                    }
                }
                else
                {
                    $getdata = array('startDate'=>$startdate,
                    'endDate'=>$startdate,
                    'summary'=>'',); 

                    array_push($getarray,$getdata);
                }
            }

            //dd($getarray);
            $totalapp = json_encode($getarray);

            return view('user/dashboard', compact('coachdata','totalapp','stripekeys'));
        }
    }
    public function viewCoach(Request $request)
    {
        $coachdata = User::find($request->coach_id);

        $user = Auth::user();
        $getappointment = $user->ClientAppointment()->where('user_id',$request->coach_id)->where('status', '=', 1)->get();

        $getarray = array();

        foreach($getappointment as $getappointment1)
        {
            $startdate = $getappointment1->date;
            $enddate = strtotime($getappointment1->end_date);
            $repeat = $getappointment1->repeat;
            $days = $getappointment1->day;

            if($repeat == "1")
            {
                for($i = strtotime($days, strtotime($startdate)); $i <= $enddate; $i = strtotime('+1 week', $i))
                {
                    $getdata1 = array('startDate'=>date('Y-m-d', $i),
                        'endDate'=>date('Y-m-d', $i),
                        'summary'=>'',); 

                    array_push($getarray,$getdata1);
                }
            }
            else
            {
                $getdata = array('startDate'=>$startdate,
                'endDate'=>$startdate,
                'summary'=>'',); 

                array_push($getarray,$getdata);
            }
        }

        $totalapp2 = json_encode($getarray);

        $totaldt2 = '';

        $habit = Habit::where('user_id',$request->coach_id)->where('client_id',$user->id)->where('status','!=',0)->get();

        foreach($habit as $habit1)
        {
            $startdate = date('Y-m-d',strtotime($habit1->start_date));
            $startdate2 = date('Y-m-d', strtotime('-1 day', strtotime($startdate)));
            $weekcycle = $habit1->number_of_session;
            $weekday = $habit1->week_days;

            $full_week_day = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            for($i = 0; $i < count($weekday); $i++)
            {
                //$key = array_search($weekday[$i], $full_week_day);

                for($j = 0; $j < $weekcycle; $j++)
                {
                    $key = date('Y-m-d', strtotime('next '.$weekday[$i].' '.$j.' week', strtotime($startdate2)));

                    $totaldt2 .= "{startDate: '".$key."',endDate: '".$key."',summary: ''},";
                }
            }
        }
            
        $totaldt2 = rtrim($totaldt2,",");

        $journal = $user->clientJournals()->where('client_id',$user->id)->where('user_id',$request->coach_id)->orderBy('date_time', 'DESC')->get();

        $availability = Availability::where('user_id',$request->coach_id)->first();

        $availdays  = unserialize($availability->days);
        $availtimes = unserialize($availability->time);

        $availabdata = array();

        $availabdata['days'] = $availdays;
        $availabdata['times'] = $availtimes;
        $availabdata['user_id'] = $availability->user_id;

        $goals = goal::where('user_id', '=', $request->coach_id)->where('client_id', '=', $user->id)->get();

        //dd($availabdata);
        return view('user/viewcoach', compact('coachdata','totalapp2','totaldt2','journal','availabdata','goals'));
    }

    public function viewprofile()
    {
        return view('user.profile.view');
    }

    public function saveprofile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->withErrors($validator);
        }
        try {
            $user = Auth::user();
            $user->name = $request->name;
            $user->experience = $request->experience;
            $user->area_of_expertise = $request->area_of_expertise;
            $user->description = $request->description;
            $user->timezone = $request->timezone;
			$user->phone = $request->phone;
            $user->appointment_fees = $request->appointment_fees;
            if ($request->hasfile('profile_pic')) {
                $file = $request->file('profile_pic');
                $filename = ((string)(microtime(true) * 10000)) . "-" . $file->getClientOriginalName();
                $destinationPath = public_path('/');

                $file->move($destinationPath, $filename);
                $user->profile_pic = $filename;
            }
            $user->save();
            return redirect()->back()->withSuccess("Updated Successfully");
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput($request->all());
        }
    }
    public function resetPassword()
    {
        return view('user.profile.resetpassword');
    }

    public function saveResetPassword(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'new_password' => 'required|min:8',
            'confirm_new_password' => 'required|same:new_password|min:8',
        ]);

        if ($validation->fails()) {
            return response()->withErrors($validation);
        }

        try {
            $user = Auth::user();
            #get password hash
            $newPassword = Hash::make($request->new_password);
            #update password
            $updatePassword = $user->update(['password' => $newPassword]);
            return redirect()->back()->withSuccess("Updated Successfully");
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput($request->all());
        }
    }

    public function addClient()
    {
		
        return view('user/coach/addclient');
    }
    public function saveaddClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stripeToken' => 'required',
            'plan_id' => 'nullable',
            'client_name' => 'required',
            'client_email' => 'required',
            'plan_name' => 'required',
            'plan_amount' => 'required',
            'cycle' => 'required',
            'appointment_fee' => 'required',


        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $request->amount = 8;
        $request->payment_for = 2;


        $user = Auth::user();
		$user_email = $user->email;
        $user_id = Auth::user()->id;
        Stripe\Stripe::setApiKey(config('app.stripe_key'));
        try {
            if ($user->stripe_customer_id) {
                $stripe_customer_id = $user->stripe_customer_id;
            } else {
                $customer = Stripe\Customer::create(array(
                    'email' => $user->email,
                    'source'  => $request->stripeToken
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

                    return redirect()->route('coach.clientcodegeneratebycoach')->with(['code' => $code, 'clientamountpaid' => $request->plan_amount . "/" . $request->plan_name, 'coachamountpaid' => $request->amount]);
                } else {

                    return redirect()->back()->withErrors("Unable to create subscription for subscription.Please try later or contact to support.");
                }
            } else {
                return redirect()->back()->withErrors("Unable to create plan for subscription.Please try later or contact to support.");
            }
        } else {
            return redirect()->back()->withErrors("Unable to create plan for subscription.Please try later or contact to support.");
        }
    }

    public function clientCodeGenerateByCoach()
    {
        return view('user/coach/clientcodegeneratebycoach');
    }

    public function ViewAddedClient(Request $request)
    {
        $user = Auth::user();
        $client_data = $user->getAddedClient()->with(['user', 'client', 'goal', 'note','Habit', 'journals'])->where('id', $request->client_id)->first();

        $totaldt = '';

        $habit = Habit::where('client_id',$client_data->client_id)->where('user_id',$user->id)->where('status','!=',0)->get();

        foreach($habit as $habit1)
        {
            $startdate = date('Y-m-d',strtotime($habit1->start_date));
            $startdate2 = date('Y-m-d', strtotime('-1 day', strtotime($startdate)));
            $weekcycle = $habit1->number_of_session;
            $weekday = $habit1->week_days;

            $full_week_day = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            for($i = 0; $i < count($weekday); $i++)
            {
                //$key = array_search($weekday[$i], $full_week_day);

                for($j = 0; $j < $weekcycle; $j++)
                {
                    $key = date('Y-m-d', strtotime('next '.$weekday[$i].' '.$j.' week', strtotime($startdate2)));

                    $totaldt .= "{startDate: '".$key."',endDate: '".$key."',summary: ''},";
                }
            }
        }
            
        $totaldt = rtrim($totaldt,",");

        $getappointment = $user->CoachAppointment()->where('client_id',$client_data->client_id)->where('status', '=', 1)->get();

        $getarray = array();

        foreach($getappointment as $getappointment1)
        {
            $startdate = $getappointment1->date;
            $enddate = strtotime($getappointment1->end_date);
            $repeat = $getappointment1->repeat;
            $days = $getappointment1->day;

            if($repeat == "1")
            {
                for($i = strtotime($days, strtotime($startdate)); $i <= $enddate; $i = strtotime('+1 week', $i))
                {
                    $getdata1 = array('startDate'=>date('Y-m-d', $i),
                        'endDate'=>date('Y-m-d', $i),
                        'summary'=>'',); 

                    array_push($getarray,$getdata1);
                }
            }
            else
            {
                $getdata = array('startDate'=>$startdate,
                'endDate'=>$startdate,
                'summary'=>'',); 

                array_push($getarray,$getdata);
            }
        }

        //dd($getarray);
        $totalapp2 = json_encode($getarray);

        return view('user/coach/viewclient', compact('client_data','totaldt','totalapp2'));
    }

    public function ClientAppointmentDate(Request $request)
    {
        $appointment_date = $request->appointment_date;
        $client_id = $request->client_id;

        $user = Auth::user();

        $getappointment = $user->ClientAppointment()->where('status', '=', 1)->get();

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

        //dd($getarray);
        for ($j=0; $j < count($getarray); $j++) 
        {
            if (strpos($getarray[$j]['startDate'], $request->appointment_date) !== false) 
            {
                array_push($getid01,$getarray[$j]['id']);
            }
        }

        if(empty($client_id))
        {
            $client_data = $user->ClientAppointment()->whereIn('id',$getid01)->get();
        }
        else
        {
            $client_data = $user->ClientAppointment()->where('user_id','=',$client_id)->whereIn('id',$getid01)->get();
        }
        
        //dd($client_data);
        $getapp = array();

        foreach($client_data as $row)
        {
            $getappdata = array('profile'=>$row->coach->profile_pic,
                    'client_name'=>$row->coach->name,
                    'date'=>$row->date,
                    'time'=>$row->time,'appoint_id'=>$row->id,); 

            array_push($getapp,$getappdata);
        }

        return response()->json(['success' => true,  'data' => $getapp]);
    }

    public function CouchAppointmentDate(Request $request)
    {
        $appointment_date = $request->appointment_date;
        $client_id = $request->client_id;

        $user = Auth::user();

        $getappointment = $user->CoachAppointment()->where('status', '=', 1)->get();

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
            if (strpos($getarray[$j]['startDate'], $request->appointment_date) !== false) 
            {
                array_push($getid01,$getarray[$j]['id']);
            }
        }

        if(empty($client_id))
        {
            $client_data = $user->CoachAppointment()->whereIn('id',$getid01)->get();
        }
        else
        {
            $client_data = $user->CoachAppointment()->where('client_id','=',$client_id)->whereIn('id',$getid01)->get();
        }
        
        $getapp = array();

        foreach($client_data as $row)
        {
            $getappdata = array('profile'=>$row->client->profile_pic,
                    'client_name'=>$row->client->name,
                    'date'=>$row->date,
                    'time'=>$row->time,'appoint_id'=>$row->id,); 

            array_push($getapp,$getappdata);

            /*$html .= '<li><a href="#"><img src="'.asset('/').''.$row->client->profile_pic.'">
                <p>'.$row->client->name .'<span>'.$row->date.'<br>'.$row->time.'</span></p>
            </a></li>';*/
        }

        //return $html;
        return response()->json(['success' => true,  'data' => $getapp]);
    }

    public function getappointmentdetails(Request $request)
    {	
        $appoint_id = $request->appoint_id;
        $client_id = $request->client_id;
        $appoint_date = $request->appoint_date;

        $user = Auth::user();

        if($user->user_type == 1)
        {   
            if(empty($client_id))
            {
                $appointmentdetails = Appointment::with('client','coach','addedclientdata','payment')->where('id', '=', $appoint_id)->where('user_id', '=', $user->id)->first();
            }
            else
            {
                $appointmentdetails = Appointment::with('client','coach','addedclientdata','payment')->where('id', '=', $appoint_id)->where('client_id', '=', $client_id)->where('user_id', '=', $user->id)->first();
            } 
        }
        else
        {
            if(empty($client_id))
            {
                $appointmentdetails = Appointment::with('client','coach','addedclientdata','payment')->where('id', '=', $appoint_id)->where('client_id', '=', $user->id)->first();
            }
            else
            {
                $appointmentdetails = Appointment::with('client','coach','addedclientdata','payment')->where('id', '=', $appoint_id)->where('user_id', '=', $client_id)->where('client_id', '=', $user->id)->first();
            } 
        }

        
        $schedule_by = $appointmentdetails->schedule_by;
        if($appointmentdetails->repeat == 1)
        {
            $dateto01 = date('Y-m-d', strtotime($appointmentdetails->date));
            $datefrom = date('Y-m-d',strtotime($appointmentdetails->end_date));
			

            $dateto = new DateTime($dateto01);
            $datefrom = new DateTime($datefrom);

            $interval = $datefrom->diff($dateto);
            $week_total = $interval->format('%a')/7;

            $ap_amount = ($appointmentdetails->addedclientdata[0]->appointment_fee) * (int)$week_total;
        }
        else
        {
            $ap_amount = $appointmentdetails->addedclientdata[0]->appointment_fee;
        }
        

        $getarray = array();
		if($schedule_by=='freeByCoach'){
			$app_fee=0;
			$app_amount=0;
			$payment_date='';
		}else{
			$app_fee = $appointmentdetails->addedclientdata[0]->appointment_fee;
			$app_amount = $ap_amount;
			$payment_date = date(('F d, Y'),strtotime($appointmentdetails->payment->payment_date));
		}
		
		$getdata = array('coach_name'=>$appointmentdetails->coach->name,
                'coach_id'=>$appointmentdetails->coach->id,
                'appointment_id'=>$appointmentdetails->id,
                'client_name'=>$appointmentdetails->client->name,
                'client_id'=>$appointmentdetails->client->id,
				'schedule_by'=>$schedule_by,
                'coach_profile'=>$appointmentdetails->coach->profile_pic,
                'client_profile'=>$appointmentdetails->client->profile_pic,
                'appointment_fee'=>$app_fee,
                'appointment_time'=>$appointmentdetails->time,
                'get_appoint_date'=>date(('F d, Y'),strtotime($appoint_date)),
                'appointment_amount'=>$app_amount,
                'payment_date'=>$payment_date,
                ); 
                
               
			
		
        array_push($getarray,$getdata);

        return response()->json(['success' => true,  'data' => $getdata]);
    }

    public function updategoalstatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = Auth::user();
		
        if ($user) 
        {
            if($user->user_type == 1)
            {
                $goal = goal::where('user_id', $user->id)->where('id', $request->id)->first();
            }
            else if($user->user_type == 2)
            {
                $goal = goal::where('client_id', $user->id)->where('id', $request->id)->first();
            }
            
            if ($request->name) {

                $goal->name = $request->name;
            }

            $goal->status = ($goal->status == 1) ? 0 : 1;
            $goal->save();
         return response()->json(['success' => true,  'message' => 'You have successfully done your goal','activeTab'=>'goals','goalstatus'=>$goal->status]);
        } else {
            return response()->json([
				'success' => false,
				'message' => 'Please contact to the admin.',
			]);
        }
    }

    public function addupdategoal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'client_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $user = Auth::user();
        if ($user) {
            if ($request->id) {
                $goal = goal::find($request->id);
                $goal->name = $request->name;
                $goal->save();
            } else {
                $goal = goal::make();
                $goal->user_id = ($user->user_type == 1)? $user->id:$request->client_id;
                $goal->client_id = ($user->user_type == 1)? $request->client_id:$user->id;
                $goal->name = $request->name;
                $goal->save();
            }
            return redirect()->back()->with(['success'=>'You have added goal successfully','activeTab'=>'goals']);
        } else {
            return redirect()->back()->withErrors("Something went wrong.Please try later or contact to support.");
        }
    }

    public function deleteGoal(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type == 1) {
            $goal = goal::where('user_id', $user->id)->where('id', $request->id)->first();
            $goal->delete();
            return redirect()->back()->with(['success'=>'You have successfully delete goal.','activeTab'=>'goals']);
        }
        else if ($user->user_type == 2) {
            $goal = goal::where('client_id', $user->id)->where('id', $request->id)->first();
            $goal->delete();
            return redirect()->back()->with(['success'=>'You have successfully delete goal.','activeTab'=>'goals']);
        } else {
            return redirect()->back()->withErrors("Something went wrong.Please try later or contact to support.");
        }
    }
    public function plan()
    {
        $plans = plan::where('status', '=', 1)->get();
        return view('user/coach/plan', compact('plans'));
    }
    public function pay(Request $request)
    {
        return view('user/pay');
    }
    public function postPay(Request $request)
    {

        if ($request->payment_for == 1) {
            $validator = Validator::make($request->all(), [
                'stripeToken' => 'required',
                'payment_for' => 'required',
                'plan_id' => 'required'
            ]);
        } elseif ($request->payment_for == 3) {
            $validator = Validator::make($request->all(), [
                'stripeToken' => 'required',
                'payment_for' => 'required',
                'code' => 'required'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'stripeToken' => 'required',
                'payment_for' => 'required'
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $user = Auth::user();
        $user_id = Auth::user()->id;
        if ($request->payment_for == 1) {
            $planData = plan::find($request->plan_id);
            if ($planData) {
                $request->amount = $planData->price;
            } else {
                return redirect()->back()->withErrors("plan not found.");
            }
            Stripe\Stripe::setApiKey(config('app.stripe_key'));
            try {
                if ($user->stripe_customer_id) {
                    $stripe_customer_id = $user->stripe_customer_id;
                } else {
                    $customer = Stripe\Customer::create(array(
                        'email' => $user->email,
                        'source'  => $request->stripeToken
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
                    $planName = ($request->plan_id == 1) ? 'Monthly plan' : 'Yearly plan';
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
                        return redirect()->route('user.dashboard')->with(['plan_selected' => 'Payment Successfull..']);
                    } else {
                        return redirect()->back()->withErrors("Unable to create plan for subscription.Please try later or contact to support.");
                    }
                } else {
                    return redirect()->back()->withErrors("Unable to create plan for subscription.Please try later or contact to support.");
                }
            } else {
                return redirect()->back()->withErrors("Unable to create plan for subscription.Please try later or contact to support.");
            }
        } elseif ($request->payment_for == 3) {
            $AddedClientData = AddClient::with('user')->whereCode($request->code)->whereNull('client_id')->where('status', '=', 0)->first();
            $coach_stripe_key = StripeKey::where('user_id', '=', $AddedClientData->user_id)->first();
            Stripe\Stripe::setApiKey(config('app.stripe_test') ? $coach_stripe_key->secret_key : $coach_stripe_key->secret_key);
            $request->amount = $AddedClientData->plan_amount;
            if ($AddedClientData->plan_name == 'Weekly' or $AddedClientData->plan_name == 'Monthly') {
                try {
                    if ($user->stripe_customer_id) {
                        $stripe_customer_id = $user->stripe_customer_id;
                    } else {

                        $customer = Stripe\Customer::create(array(
                            'email' => $user->email,
                            'source'  => $request->stripeToken
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
                            // $msg["title"] = "New Client Subscribed";
                            // $usertype = 'coach';
                            // $msg["body"] = "Your client " . $user->name . " added you as coach";
                            // $msg['type'] = "New Client Subscribed";
                            // $msg['user_type'] = $usertype;
                            // $this->sendNotification($client->user_id, $msg);
                            /*for send and save notification  */
                            /*for send and save notification  */
                            // $msg["title"] = "Payment Recieved";
                            // $usertype = 'coach';
                            // $msg["body"] = "You have received $ " . $client->plan_amount . " payment from client " . $client->client_name . "";
                            // $msg['type'] = "Payment Recieved";
                            // $msg['user_type'] = $usertype;
                            // $this->sendNotification($client->user_id, $msg);
                            /*for send and save notification  */
							
                            return redirect()->route('user.dashboard')->with(['plan_selected' => 'Payment Successfull..']);
                        } else {
                            return redirect()->back()->withErrors("Something went wrong.Please try later or contact to support.");
                        }
                    } else {
                        return redirect()->back()->withErrors("Something went wrong.Please try later or contact to support.");
                    }
                } else {
                    return redirect()->back()->withErrors("Something went wrong.Please try later or contact to support.");
                }
            } else {
                $pay = Stripe\Charge::create([
                    "amount" => $request->amount * 100,
                    "currency" => "USD",
                    "source" => $request->stripeToken,
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
                    // $msg["title"] = "New Client Subscribed";
                    // $usertype = 'coach';
                    // $msg["body"] = "Your client " . $user->name . " added you as coach";
                    // $msg['type'] = "New Client Subscribed";
                    // $msg['user_type'] = $usertype;
                    // $this->sendNotification($client->user_id, $msg);
                    /*for send and save notification  */
                    /*for send and save notification  */
                    // $msg["title"] = "Payment Recieved";
                    // $usertype = 'coach';
                    // $msg["body"] = "You have received $ " . $client->plan_amount . " payment from client " . $client->client_name . "";
                    // $msg['type'] = "Payment Recieved";
                    // $msg['user_type'] = $usertype;
                    // $this->sendNotification($client->user_id, $msg);
                    /*for send and save notification  */
                    /* for selected plan */
                    return redirect()->route('user.dashboard')->with(['plan_selected' => 'Payment Successfull..']);
                }
            }
        } elseif ($request->payment_for == 6) {
            $exist = $user->stripe()->first();
            if ($exist and !$exist->verified) {
                Stripe\Stripe::setApiKey(config('app.stripe_test') ? $exist->secret_key : $exist->secret_key);
                $request->amount=1;
                $pay = Stripe\Charge::create([
                    "amount" => $request->amount * 100,
                    "currency" => "USD",
                    "source" => $request->stripeToken,
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
                    return redirect()->route('coach.getaddorupdatestripe')->withSuccess('Stripe verified successfully.');
                } else {
                    return redirect()->route('coach.getaddorupdatestripe')->withErrors('Your payment has been failed.Please try again or contact to admin or Please try again.');
                   
                }
            } else {
                return redirect()->route('coach.getaddorupdatestripe')->withSuccess('Already Verified.');
            }
        }
    }
    public function addCoach(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = Auth::user();
        $user_id = Auth::user()->id;
        if ($user_id) {
            $AddedClientData = AddClient::with(['user', 'stripe'])->whereCode($request->code)->whereNull('client_id')->where('status', '=', 0)->first();
            if ($user and $AddedClientData) {
                return response()->json(['success' => true, 'data' => $AddedClientData]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Token or code is not valid. please contact to the admin.',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Somtething went wrong. please contact to the admin.',
            ]);
        }
    }

    public function getAddOrUpdateStripe(Request $request)
    {
        return view('user.coach.stripe');
    }

    public function saveaddorupdatestripe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret_key' => 'required',
            'published_key' => 'required'

        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $user = Auth::user();
		$user_email =$user->email;
        $exist = $user->stripe()->first();
        if ($exist) {

            if (!$exist->auth_code or !$request->auth_code or strtotime('+5 minutes',strtotime($user->stripe->updated_at)) <= strtotime(date('Y-m-d H:i:s'))) {
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
                Mail::to($email)->send(new DbTemplateMail($body, $subject));
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
                return redirect()->back()->withSuccess('OTP sent on your email.Please check.Otp valid for 5 minutes.');
            }
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
                        return redirect()->back()->withSuccess('Stripe added successfully.Please verify it by paying $1.');
                    } else {
                        return redirect()->back()->withErrors('Your auth code is not verified.Please verify it.');
                    }
                } else {
                    if ($exist->auth_code == $request->auth_code) {
                        $stripe = StripeKey::find($exist->id);
                        $stripe->secret_key = $request->secret_key;
                        $stripe->published_key = $request->published_key;
                        $stripe->auth_code = NULL;
                        $stripe->save();
                        return redirect()->back()->withSuccess('Stripe added successfully.Please verify it by paying $1.');
                    } else {
                        return redirect()->back()->withErrors('Your auth code is not verified.Please verify it.');
                    }
                }
            } else {
                return redirect()->back()->withErrors('Please provide the auth code.');
            }
        } else {
            $stripe = $user->stripe()->make();
            $stripe->secret_key = $request->secret_key;
            $stripe->published_key = $request->published_key;
            $stripe->auth_code = NULL;
            $stripe->save();
            return redirect()->back()->withSuccess('Stripe added successfully.Please verify it by paying $1.');
        }
    }

	public function addnotes(Request $request){
		 $validator = Validator::make($request->all(), [
            'description' => 'required',
			 'client_id' => 'required'
        ]);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator);
		}

        $user = Auth::user();
		if ($user) {
			$note = $user->CoachNote()->make();
			$note->user_id = $user->id;
			$note->client_id = $request->client_id;
			$note->description = $request->description;
			$note->date_time = date('Y-m-d H:i:s');
			 if ($request->hasfile('images1')) {
                $file = $request->file('images1');
                $filename = ((string)(microtime(true) * 10000)) . "-" . $file->getClientOriginalName();
                $destinationPath = public_path('/');

                $file->move($destinationPath, $filename);
                $note->images1 = $filename;
            }
			/* if ($request->images1) {
				$frontimage1 =  $request->images1;
				$img_name1 = time() . '.jpg';
				// $new_path = Storage::disk('public')->put($profile_pic, $frontimage);
				file_put_contents($img_name1, $frontimage1);
				$note->images1 = $img_name1;
			} */
			
			/* if ($request->images2) {
				$frontimage2 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->images2));
				$img_name2 = time() . '1.jpeg';
				// $new_path = Storage::disk('public')->put($profile_pic, $frontimage);
				file_put_contents($img_name2, $frontimage2);
				$note->images2 = $img_name2;
			} */
			$note->save();

			return redirect()->back()->with(['success'=>'You have added notes successfully','activeTab'=>'notes']);
		} else {
			 return redirect()->back()->withErrors("Something went wrong.Please try later or contact to support.");
		}
	}
	public function updatenote(Request $request){
		$validator = Validator::make($request->all(), [
            'description' => 'required',
			'client_id' => 'required'
        ]);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator);
		}
	
        $user = Auth::user();
		if ($user) {
			$note = $user->CoachNote()->findorfail($request->id);
			$note->user_id = $user->id;
			$note->client_id = $request->client_id;
			$note->description = $request->description;
			$note->date_time = date('Y-m-d H:i:s');
			if ($request->hasfile('images1')) {
                $file = $request->file('images1');
                $filename = ((string)(microtime(true) * 10000)) . "-" . $file->getClientOriginalName();
                $destinationPath = public_path('/');
				
                $file->move($destinationPath, $filename);
                $note->images1 = $filename;
            }
			/* if ($request->images1) {
				$frontimage1 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->images1));
				$img_name1 = time() . '.jpeg';
				// $new_path = Storage::disk('public')->put($profile_pic, $frontimage);
				file_put_contents($img_name1, $frontimage1);
				$note->images1 = $img_name1;
			} */
		
			$note->save();

			return redirect()->back()->with(['success'=>'You have updated successfully','activeTab'=>'notes']);

		} else {
			 return redirect()->back()->withErrors("Something went wrong.Please try later or contact to support.");
		}
	
	}
	public function deletenote(Request $request){
		$user = Auth::user();
			if ($user) {
				$note = Note::where('user_id', $user->id)->where('id', $request->id)->where('client_id', $request->client_id)->first();
				$note->delete();
				return redirect()->back()->with(['success'=>'You have deleted successfully','activeTab'=>'notes']);
			} else {
				return redirect()->back()->withErrors("Something went wrong.Please try later or contact to support.");
			}
	
	}

    public function addjournal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            
            'user_id' => 'required'
        ]);

        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator);
        }

        $user = Auth::user();
        if ($user) 
        {
            $journal = $user->clientJournals()->make();
            $journal->user_id = $request->user_id;
            $journal->client_id = $user->id;
            $journal->description = $request->description;
            $journal->date_time = date('Y-m-d H:i:s');

            if ($request->hasfile('images1')) 
            {
                $file = $request->file('images1');
                $filename = ((string)(microtime(true) * 10000)) . "-" . $file->getClientOriginalName();
                $destinationPath = public_path('/');

                $file->move($destinationPath, $filename);
                $journal->images = $filename;
            }
            $journal->save();

            return redirect()->back()->with(['success'=>'You have added journal successfully','activeTab'=>'journal']);
        } 
        else 
        {
            return redirect()->back()->withErrors("Something went wrong.Please try later or contact to support.");
        }
    }

    public function addappointment(Request $request)
    {
       /* $validator = Validator::make($request->all(), [
            'app_days' => 'required',
            'user_id' => 'required',
            'repeat' => 'required',
            'app_times' => 'required',
            'stripe_token' => 'required',
            'payment_for' => 'required',
        ]);
        
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator);
        }*/
        
        $user = Auth::user();

        $coach_stripe_key = StripeKey::where('user_id', '=', $request->user_id)->first();
        Stripe\Stripe::setApiKey(config('app.stripe_test') ? $coach_stripe_key->secret_key : $coach_stripe_key->secret_key);
        
        $appointment_amount = AddClient::whereUserId($request->user_id)->whereClientId($user->id)->first()->appointment_fee;

        if ($request->repeat == 1) 
        {
            $dateto01 = date('Y-m-d', strtotime("next ".$request->app_days, strtotime(date('Y-m-d',strtotime("-1 days")))));
            $datefrom = date('Y-m-d',strtotime($request->end_date));

            $dateto = new DateTime($dateto01);
            $datefrom = new DateTime($datefrom);

            $interval = $datefrom->diff($dateto);
            $week_total = $interval->format('%a')/7;

            //dd((int)$week_total);
            $ap_amount = ($appointment_amount * 100) * (int)$week_total;

            //dd($ap_amount);
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
            $customerpay->payee_id = $request->user_id;
            $customerpay->payment_date = date('Y-m-d H:i:s', $pay->created);
            $customerpay->save();

            $payment_id = $customerpay->id;
            if ($pay->balance_transaction) 
            {
                $appointment = Appointment::make();
                $appointment->user_id = $request->user_id;
                $appointment->client_id = $user->id;
                $appointment->date = date('Y-m-d', strtotime("next ".$request->app_days, strtotime(date('Y-m-d',strtotime("-1 days")))));
                $appointment->day = $request->app_days;
                $appointment->time = $request->app_time;
                $appointment->repeat = $request->repeat;
                $appointment->payment_id = $payment_id;
                if ($request->repeat == 1) 
                {
                    $appointment->end_date = $request->end_date;
                }
                $appointment->save();
                 
                return redirect()->back()->withSuccess('Your appointment has been schedule successfully.');       
            } 
            else 
            {
                return redirect()->back()->withErrors('Your payment has been failed.Please try again or contact to admin.'); 
            }
        } 
        else 
        {
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
            $customerpay->payee_id = $request->user_id;
            $customerpay->payment_date = date('Y-m-d H:i:s', $pay->created);
            $customerpay->save();
            $payment_id = $customerpay->id;
            
            if ($pay->balance_transaction) 
            {
                $appointment = Appointment::make();
                $appointment->user_id = $request->user_id;
                $appointment->client_id = $user->id;
                $appointment->date = date('Y-m-d', strtotime("next ".$request->app_days, strtotime(date('Y-m-d',strtotime("-1 days")))));
                $appointment->day = $request->app_days;
                $appointment->time = $request->app_time;
                $appointment->repeat = $request->repeat;
                $appointment->payment_id = $payment_id;
                $appointment->save();
       
                return redirect()->back()->withSuccess('Your appointment has been schedule successfully.'); 
            } 
            else 
            {
                return redirect()->back()->withErrors('Your payment has been failed.Please try again or contact to admin.'); 
            }
        }

        /*$user = Auth::user();
        if ($user) 
        {
            $appointment = Appointment::make();
            $appointment->user_id = $request->user_id;
            $appointment->client_id = $user->id;
            $appointment->date = date('Y-m-d', strtotime("next ".$request->app_days, strtotime(date('Y-m-d',strtotime("-1 days")))));
            $appointment->day = $request->app_days;
            $appointment->time = $request->app_times;
            $appointment->repeat = $request->repeat;
            if ($request->repeat == 1) 
            {
                $appointment->end_date = $request->end_date;
            }
            $appointment->save();
            return redirect()->back()->withSuccess('You have added Client Appointment successfully');
        }
        else
        {
            return redirect()->back()->withErrors("Something went wrong.Please try later or contact to support.");
        }*/
    }

    public function reschedule_appointment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_days' => 'required',
            'user_id' => 'required',
            'app_time' => 'required',
        ]);
        
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator);
        }

        $user = Auth::user();

        $appointment = Appointment::find($request->appointment_id);

        if ($appointment->user_id == $user->id) 
        {
            $appointment->date = date('Y-m-d', strtotime("next ".$request->app_days, strtotime(date('Y-m-d',strtotime("-1 days")))));;
            $appointment->day = $request->app_days;
            $appointment->time = $request->app_time;
            $appointment->schedule_by = 'coach';
			if ($appointment->repeat == 1) {
					$appointment->end_date = $request->end_date;
				}
            $appointment->save();

            $msg["title"] = "Reschedule Appointment";
            $usertype = 'client ';
            $msg["body"] = "Your appointment with coach " . $user->name . " rescheduled";
            $msg['type'] = "Reschedule";
            $msg['client_id'] = $appointment->client_id;
            $msg['coach_id'] = $appointment->user_id;
            $this->sendNotification($appointment->client_id, $msg);

            return redirect()->back()->withSuccess('Your appointment has been schedule successfully.');
        } 
    }

	public function addhabits(Request $request)
    {
		$validator = Validator::make($request->all(), [
			'habit_name' => 'required',
			'client_id' => 'required',
			'start_date' => 'required',
			'number_of_session' => 'required',
			'end_date' => 'required',
			'week_days' => 'required',
			'alert' => 'required'
		]);
		
		if ($validator->fails()) 
        {
			return redirect()->back()->withErrors($validator);
		}
		
		$user = Auth::user();
		if ($user) 
        {
			
			/*  $str_date = date('Y-m-d H:i:s',strtotime($request->start_date." ".$request->start_time));
			 $end_date = date('Y-m-d H:i:s',strtotime($request->end_date." ".$request->end_time)); */
			 $str_date = Carbon::make($request->start_date." ".$request->start_time);
				 $end_date = Carbon::make($request->end_date." ".$request->end_time);
			
			 /* $str_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_date." ".$request->start_time);
			 $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->end_date." ".$request->end_time); */
			
			/* 
			$date = new DateTime($request->start_date);
			$time = new DateTime(date('h:i:s', strtotime($request->start_time)));
			$merge = new DateTime($date->format('Y-m-d') .' ' .$time->format('H:i:s'));
			$str_date =$merge->format('Y-m-d H:i:s'); // Outputs '2017-03-14 13:37:42'
			$date1 = new DateTime($request->end_date);
			$time1 = new DateTime(date('h:i:s', strtotime($request->end_time)));
			$merge = new DateTime($date1->format('Y-m-d') .' ' .$time1->format('H:i:s'));
			$end_date =$merge->format('Y-m-d H:i:s'); */
		
		
			$habit = Habit::make();
			$habit->user_id = ($user->user_type == 1)? $user->id:$request->client_id;
			$habit->client_id = ($user->user_type == 1)? $request->client_id:$user->id;
			$habit->name = $request->habit_name;
			$habit->repeat = '';
			$habit->all_day = 0;
			$habit->start_date = $str_date;
			$habit->end_date = $end_date;
			$habit->alert = $request->alert;
			$habit->number_of_session = $request->number_of_session;
			$habit->week_days = serialize($request->week_days);
			$habit->save();

            $msg["title"] = "Habit List";
            $usertype = ($user->user_type == 1) ? 'coach ' : 'client ';
            $msg["body"] = "A new habit added by your " . $usertype . " " . $user->name . "";
            $msg['type'] = "Habit List";
            $msg['user_type'] = $usertype;
            $msg['coach_id'] = ($user->user_type == 1)? $user->id:$request->client_id;
            $msg['client_id'] =  ($user->user_type == 1)? $request->client_id:$user->id;

            $this->sendNotification($request->client_id, $msg);

			return redirect()->back()->withSuccess('You have added Client Habit successfully');
		}
        else
        {
			return redirect()->back()->withErrors("Something went wrong.Please try later or contact to support.");
		}
	}
	
	public function updatehabit(Request $request)
    {
        //dd(date('Y-m-d', strtotime($request->start_date)));
		/* echo date('Y-m-d',strtotime($request->end_date)).'<br>';
		echo $request->end_date.'<br>';
		echo date('Y-m-d H:i',strtotime($request->end_date)); */
		
		//dd($request->all());
		$validator = Validator::make($request->all(), [
			'habit_name' => 'required',
			'client_id' => 'required',
			/* 'start_date' => 'required',
			'number_of_session' => 'required',
			'end_date' => 'required', */
			'week_days' => 'required',
			'alert' => 'required'
		]);
		
		if ($validator->fails()) 
        {
			return redirect()->back()->withErrors($validator);
		}

	    $user = Auth::user();
		if ($user) 
        {
			$habit = Habit::findorfail($request->id);
			$habit->user_id = ($user->user_type == 1)? $user->id:$request->client_id;
			$habit->client_id = ($user->user_type == 1)? $request->client_id:$user->id;
			$habit->name = $request->habit_name;
			$habit->repeat = '';
			$habit->all_day = 0;
			
		/* 	$habit->start_date = date('Y-m-d',strtotime($request->start_date));
			$habit->end_date = date('Y-m-d',strtotime($request->end_date)); */
			
			/* $habit->start_date = date('Y-m-d H:i',strtotime($request->start_date));
			$habit->end_date = date('Y-m-d H:i',strtotime($request->end_date));
			$habit->number_of_session = $request->number_of_session; */
			$habit->alert = $request->alert;
			$habit->week_days = serialize($request->week_days);
			$habit->save();
		    return redirect()->back()->with(['success'=>'You have update Client Habit successfully','activeTab'=>'']);
		}
        else
        {
			return redirect()->back()->withErrors("Something went wrong.Please try later or contact to support.");
		}
	}
	 public function deletehabit(Request $request){
		
		 $user = Auth::user();

         $user_id = ($user->user_type == 1)? $user->id:$request->user_id;
         $client_id = ($user->user_type == 1)? $request->client_id:$user->id;
			if ($user) 
            {
				$habit = Habit::where('user_id', $user_id)->where('id', $request->id)->where('client_id', $client_id)->first();
				$habit->delete();
				return redirect()->back()->with(['success'=>'You have deleted Client Habit successfully','activeTab'=>'']);
			}
            else {
				return redirect()->back()->withErrors("Something went wrong.Please try later or contact to support.");
			}
		
		 
	 }
	public function archiveAddedClients(Request $request){				
		$validator = Validator::make($request->all(), [
			'added_client_id' => 'required'	
		]);
		
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator);
		}
			
		$user = Auth::user();
		
			
			if ($user) {
			
			 $client = $user->getAddedClient()->where('id', '=', $request->added_client_id)->where('status', '!=', 0)->get();
			
			if (count($client)) {
				$data = AddClient::findorfail($request->added_client_id);
				
				$existinguserstripe = StripeKey::where('user_id',$user->id)->first();
				
				Stripe\Stripe::setApiKey($existinguserstripe['secret_key']);
				try{
					
				$current_subscription = Stripe\Subscription::retrieve(@$request->subscription_id_for_client);
				    
					if($current_subscription->status=='canceled' OR $current_subscription->status=='incomplete_expired'){
						$payment1 = Payment::where('subscription_id', '=', $request->subscription_id_for_client)->first();
							$payment1->subscription_status = 0;
							$payment1->save();
						$data->status = 2;
					}else{
						$return = $current_subscription->cancel();
						if ($return->status == 'canceled') {
							$payment = Payment::where('subscription_id', '=', $request->subscription_id_for_client)->first();
							$payment->subscription_status = 0;
							$payment->save();
							$data->status = 2;
							
						}
					}
					
					$coach_subscription = $user->getAddedClient()->where('id', '=', $request->added_client_id)->where('status', '!=', 0)->first();
					
					if($coach_subscription->subscription_id_for_coach){	
					
						Stripe\Stripe::setApiKey(config('app.stripe_key'));
						
						$current_subscription_forcoach = Stripe\Subscription::retrieve(@$coach_subscription->subscription_id_for_coach);
						if($current_subscription_forcoach->status=='active'){
							
							$current_subscription_forcoach->cancel();
							
							$data->status = 2;
						}
					}	
					
				}catch(\Stripe\Exception\ApiErrorException $e) {
					//return redirect()->back()->withErrors("Unable to archive client.Please try again or contact to admin.");
					return response()->json(['success' => false,  
					'data' => 'Unable to archive client.Please try again or contact to admin.'
					]);
				  
				}

				
					/* foreach($client as $clientdata){
							$subscription_coach_id = $clientdata->subscription_id_for_coach;
							$subscription_client_id = $clientdata->subscription_id_for_client;
						 if($subscription_coach_id){
								$payment = Payment::where('subscription_id', '=', $subscription_coach_id)->first();
								Stripe\Stripe::setApiKey(config('app.stripe_key'));
								$current_subscription = Stripe\Subscription::retrieve($subscription_coach_id);
								$return = $current_subscription->cancel();
								if ($return->status == 'canceled') {
									$payment->subscription_status = 0;
									$payment->save();
									
								}
							} 
							if($subscription_client_id){
								$payment = Payment::where('subscription_id', '=', $subscription_client_id)->first();
								Stripe\Stripe::setApiKey(config('app.stripe_key'));
								$current_subscription = Stripe\Subscription::retrieve($subscription_client_id);
								
								$return = $current_subscription->cancel();
								if ($return->status == 'canceled') {
									$payment->subscription_status = 0;
									$payment->save();
									
								}
							}
					} */
					
				$data->subscription_status_for_client = 0;
				if ($data->save()) {
					return redirect()->route('user.dashboard')->with(['success' => 'Your client has been archived.']);
 
				} else {
					return redirect()->back()->withErrors("Unable to update client.Please try again or contact to admin.");
				}
			} else {
				return redirect()->back()->withErrors("you can not update this client.Please make it arhive.");
			}
		} else {
			return redirect()->back()->withErrors("Token is not valid. please contact to the admin.");
			
		}
		
	}
	
	public function get_availabletime(Request $request)
    {
        $app_date = date('Y-m-d', strtotime("next ".$request->app_days, strtotime(date('Y-m-d',strtotime("-1 days")))));
        
        $data = array();
        $scheduled_time_arr = array();
        $time_arr = array();

        $appointment = Appointment::where('date', '=',  $app_date)->where('status', '=', 1)->get();
        foreach (@$appointment as $app) {
            $scheduled_time_arr[] = $app->time;
        }

       
		
        $availability = Availability::where('user_id', '=', $request->user_id)->first();
		//print_r($availability);

        $days = unserialize($availability->days);
        if($availability and in_array($request->app_days,$days))
        {
            $times = unserialize($availability->time);
            foreach ($times as $time) 
            {
                if ($app_date == date('Y-m-d')) 
                {
                    $t = array();
                    $t = explode('-', $time);

                    if ((!in_array($time, $scheduled_time_arr)) and (strtotime(date('h:i A', strtotime($t[0]))) > strtotime(date('h:i A')))) 
                    {
                        $time_arr1 = array("gettime" => $time);

                        array_push($time_arr,$time_arr1);
                    }
                } 
                else 
                {
                    //dd("no");
                    if (!in_array($time, $scheduled_time_arr)) 
                    {
                        $time_arr1 = array("gettime" => $time);

                        array_push($time_arr,$time_arr1);
                    }
                }
            }
			
        }
        else
        {
            $time_arr = "";
        }
        
        //dd($time_arr);
        
        return response()->json(['success' => true,  'data' => $time_arr]);
	}
	public function clientappointment(Request $request){
		print_r($request->all());
		die;
	}
	/*public function listhabits(Request $request){
		
		$validator = Validator::make($request->all(), [
			'habit_date' => 'required',
			'client_id' => 'required',
			
		]);
		
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = Auth::user();
        if ($user) 
        {
            $habit = Habit::where('client_id',$request->client_id )->where('status','!=',0)->whereDate('start_date',$request->habit_date)->first();
			
            $startdate = date('Y-m-d',strtotime($habit->start_date));
            $startdate2 = date('Y-m-d', strtotime('-1 day', strtotime($startdate)));
            $weekcycle = $habit->number_of_session;
            $weekday = $habit->week_days;

            $totaldt = array();

            $full_week_day = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            for($i = 0; $i < count($weekday); $i++)
            {
                //$key = array_search($weekday[$i], $full_week_day);

                for($j = 0; $j < $weekcycle; $j++)
                {
                    $key = date('Y-m-d', strtotime('next '.$weekday[$i].' '.$j.' week', strtotime($startdate2)));

                    $totaldt[] = $key;
                }
            }

			$data= array(
			
					'id'=>$habit->id,
					'client_id'=>$habit->client_id,
					'user_id'=>$habit->user_id,
					'status'=>$habit->status,
					'start_date'=>date('F j, Y',strtotime($habit->start_date)),
					'end_date'=>date('F j, Y',strtotime($habit->end_date)),
					'title' => ucfirst(trans($habit->name)),
					'startime'=>ucfirst(date('g:i A',strtotime($habit->start_date))),
					'endtime'=>ucfirst(date('g:i A',strtotime($habit->end_date))),
					'weekcycle'=>$habit->number_of_session,
					'alert' =>$habit->alert,
					'weekdays' =>$habit->week_days,
					'weekdaysHtml' =>$habit->week_days_html,
                    'eventdate'=>$totaldt,
			); 
			
            return response()->json(['success' => true,  'data' => $data]);
        }
		
	}*/

    public function listhabits(Request $request){
        
        $validator = Validator::make($request->all(), [
            'habit_date' => 'required',
            'client_id' => 'required',
            
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = Auth::user();
        if ($user) 
        {
            $currentdate = date('Y-m-d');

            $getday = date('l', strtotime($request->habit_date));

            $getallevent = '';

            $allhabitevent = Habit::with('habitStatus')->where('client_id',$request->client_id)->where('user_id',$user->id)->where('status','!=',0)->get();
		/* $habit_list = Habit::where('user_id', '=', $user->id)->where('client_id', '=', $request->client_id)->where('status', '=', 2)->get();
			foreach ($habit_list as $habit) {
				if (date('Y-m-d', strtotime('+5 hour +30 minutes', strtotime($habit->updated_at))) < date("Y-m-d")) {

					$update_status = Habit::find($habit->id);
					$update_status->status = 1;
					$update_status->save();
				}
			} */
            foreach($allhabitevent as $allhabit1)
            {
				
                $tmp = array();
                $tmp['id'] = $allhabit1->id;

                $getallevent .= "{ id=>".$allhabit1->id;

                //array_push($getallevent,$habit1->id);

                $startdate = date('Y-m-d',strtotime($allhabit1->start_date));
                $startdate2 = date('Y-m-d', strtotime('-1 day', strtotime($startdate)));
                $weekcycle = $allhabit1->number_of_session;
                $weekday = $allhabit1->week_days;

                $full_week_day = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                $totaldt = '';
                for($i = 0; $i < count($weekday); $i++)
                {
                    //$key = array_search($weekday[$i], $full_week_day);

                    for($j = 0; $j < $weekcycle; $j++)
                    {
                        $key = date('Y-m-d', strtotime('next '.$weekday[$i].' '.$j.' week', strtotime($startdate2)));

                        $totaldt .= $key.","; 
                    }
                }

                $tmp['date'] = rtrim($totaldt,",");

                $getallevent .= ", date=>".rtrim($totaldt,",")."},";
            }

            $exp = explode("},",rtrim($getallevent,","));
            $getid = '';

            for ($j=0; $j < count($exp); $j++) 
            { 
                $exp1 = explode("date=>",$exp[$j]);
                //print_r($exp1);
                if (strpos($exp1[1], $request->habit_date) !== false) 
                {
                    $getid .= str_replace("{ id=>","",$exp1[0]);
                }
            }
            
            $getid = explode(",",rtrim($getid,", "));

            $habit = Habit::where('client_id',$request->client_id)->where('user_id',$user->id)->whereIn('id', $getid)->get();
            $getarray = array();
            
            foreach($habit as $habit1)
            {
                $getdata = array('id'=>$habit1->id,
                    'client_id'=>$habit1->client_id,
                    'user_id'=>$habit1->user_id,
                    'status'=>($habit1->habitStatus()->where('date',$request->habit_date)->whereStatus(1)->first())?$habit1->habitStatus()->where('date',$request->habit_date)->whereStatus(1)->first()->status:'',
                    /* 'start_date'=>date('F j, Y',strtotime($habit1->start_date)),
                    'end_date'=>date('F j, Y',strtotime($habit1->end_date)),  */
					'start_date'=>date('m/d/Y, g:i A',strtotime($habit1->start_date)),
                    'end_date'=>date('m/d/Y, g:i A',strtotime($habit1->end_date)),
                    'title' => ucfirst(trans($habit1->name)),
                    'startime'=>ucfirst(date('g:i A',strtotime($habit1->start_date))),
                    'endtime'=>ucfirst(date('g:i A',strtotime($habit1->end_date))),
                    'weekcycle'=>$habit1->number_of_session,
                    'alert' =>$habit1->alert,
                    'weekdays' =>$habit1->week_days,
                    'weekdaysHtml' =>$habit1->week_days_html,
                    'selectdate'=>date('F j, Y',strtotime($request->habit_date)),
                    'selectdate2'=>$currentdate,); 

                array_push($getarray,$getdata);
            } 
            
            return response()->json(['success' => true,  'data' => $getarray]);
        }
        
    }

    public function clientlisthabits(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'habit_date' => 'required',
            'client_id' => 'required',
            
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = Auth::user();
        if ($user) 
        {
            $currentdate = date('Y-m-d');

            $getday = date('l', strtotime($request->habit_date));

            $getallevent = '';

            $allhabitevent = Habit::with('habitStatus')->where('user_id',$request->client_id)->where('client_id',$user->id)->where('status','!=',0)->get();
			
/* $habit_list = Habit::where('user_id', '=', $request->client_id)->where('client_id', '=', $user->id)->where('status', '=', 2)->get();
			foreach ($habit_list as $habit) {
				if (date('Y-m-d', strtotime('+5 hour +30 minutes', strtotime($habit->updated_at))) < date("Y-m-d")) {

					$update_status = Habit::find($habit->id);
					$update_status->status = 1;
					$update_status->save();
				}
			} */
            foreach($allhabitevent as $allhabit1)
            {
				
                $tmp = array();
                $tmp['id'] = $allhabit1->id;

                $getallevent .= "{ id=>".$allhabit1->id;

                //array_push($getallevent,$habit1->id);

                $startdate = date('Y-m-d',strtotime($allhabit1->start_date));
                $startdate2 = date('Y-m-d', strtotime('-1 day', strtotime($startdate)));
                $weekcycle = $allhabit1->number_of_session;
                $weekday = $allhabit1->week_days;

                $full_week_day = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                $totaldt = '';
                for($i = 0; $i < count($weekday); $i++)
                {
                    //$key = array_search($weekday[$i], $full_week_day);

                    for($j = 0; $j < $weekcycle; $j++)
                    {
                        $key = date('Y-m-d', strtotime('next '.$weekday[$i].' '.$j.' week', strtotime($startdate2)));

                        $totaldt .= $key.","; 
                    }
                }

                $tmp['date'] = rtrim($totaldt,",");

                $getallevent .= ", date=>".rtrim($totaldt,",")."},";
            }

            $exp = explode("},",rtrim($getallevent,","));
            $getid = '';

            for ($j=0; $j < count($exp); $j++) 
            { 
                $exp1 = explode("date=>",$exp[$j]);
                //print_r($exp1);
                if (strpos($exp1[1], $request->habit_date) !== false) 
                {
                    $getid .= str_replace("{ id=>","",$exp1[0]);
                }
            }
            
            $getid = explode(",",rtrim($getid,", "));
			
			
			

            $habit = Habit::with('habitStatus')->where('user_id',$request->client_id)->where('client_id',$user->id)->whereIn('id', $getid)->get();
            $getarray = array();
           
            foreach($habit as $habit1)
            {
                $getdata = array('id'=>$habit1->id,
                    'client_id'=>$habit1->client_id,
                    'user_id'=>$habit1->user_id,
                    'status'=>($habit1->habitStatus()->where('date',$request->habit_date)->whereStatus(1)->first())?$habit1->habitStatus()->where('date',$request->habit_date)->whereStatus(1)->first()->status:'',
                    /* 'start_date'=>date('F j, Y',strtotime($habit1->start_date)),
                    'end_date'=>date('F j, Y',strtotime($habit1->end_date)), */
					/* 'start_date'=>$habit1->start_date,
                    'end_date'=>$habit1->end_date, */
					'start_date'=>date('m/d/Y, g:i A',strtotime($habit1->start_date)),
                    'end_date'=>date('m/d/Y, g:i A',strtotime($habit1->end_date)),
                    'title' => ucfirst(trans($habit1->name)),
                    'startime'=>ucfirst(date('g:i A',strtotime($habit1->start_date))),
                    'endtime'=>ucfirst(date('g:i A',strtotime($habit1->end_date))),
                    'weekcycle'=>$habit1->number_of_session,
                    'alert' =>$habit1->alert,
                    'weekdays' =>$habit1->week_days,
                    'weekdaysHtml' =>$habit1->week_days_html,
                    'selectdate'=>date('F j, Y',strtotime($request->habit_date)),
                    'selectdate2'=>$currentdate,); 

                array_push($getarray,$getdata);
            } 
            
            return response()->json(['success' => true,  'data' => $getarray]);
        }
    }
 public function gaolscore(Request $request){
	
	 $validator = Validator::make($request->all(), [
			'client_id' => 'required'
		]);
		
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		 $user = Auth::user();
		if ($user) {
            $user_id = ($user->user_type == 1)? $user->id:$request->client_id;
            $client_id = ($user->user_type == 1)? $request->client_id:$user->id;

		$goals = goal::where('user_id', $user_id)->where('client_id', $client_id)->get();
        $completegoal = goal::where('user_id', $user_id)->where('client_id', $client_id)->where('status', '=',1)->get();
		
		$total = count($goals);
		$compgoals =count($completegoal);
		$percentage = round(($compgoals/$total)*100);
		return response()->json(['success' => true,  'data' => $percentage]);
		}
 }

 public function mysubscription(Request $request)
    {
		$user = Auth::user();
		if ($user) {
				if ($user->user_type == 1) {
					
					$plans = $user->selectedPlan()->where('subscription_id', '!=', '')->with('plan')->get();
					$addClient = $user->getAddedClient()->where('subscription_id_for_coach', '!=', '')->get();
					return view('user.coach.mysubscription',compact('plans','addClient'));
				} else {
					$plans = AddClient::where('client_id', '=', $user->id)->where('subscription_id_for_client', '!=', '')->get();
				
					$appointments = $user->ClientAppointment()->where('subscription_id', '!=', '')->with('coach')->get();
					
				   return view('user.coach.mysubscription',compact('plans','appointments'));
				}
			/* if ($user->user_type == 1) {
				$plans = $user->selectedPlan()->where('subscription_id', '!=', '')->with('plan')->get();
				$addClient = $user->getAddedClient()->where('subscription_id_for_coach', '!=', '')->get();	
			} else {
				$plans = AddClient::where('client_id', '=', $user->id)->where('subscription_id_for_client', '!=', '')->get();
				$appointments = $user->ClientAppointment()->where('subscription_id', '!=', '')->with('coach')->get();
			}
			$added_client = $user->getAddedClient()->where('client_name', 'like', '%' . $request->s . '%')->with(['user', 'client'])->get(); */
			
		}
	
       return view('user.coach.mysubscription',compact('plans','added_client'));
    }
	public function cancelsubscription(Request $request){
		$validator = Validator::make($request->all(), [
			'subscription_id' => 'required'
		]);
		
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = Auth::user();
		if ($user) {
			
		try{
		$payment = $user->payment()->where('subscription_id', '=', $request->subscription_id)->first();
		
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
		
							return redirect()->back()->withSuccess('Unsubscribe successfully.');
						}
					} else {
						return redirect()->back()->withErrors('Unauthorized subscription id.');
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
						
						return redirect()->back()->withSuccess('Unsubscribe successfully.');
					}
				}
			} else {
				return redirect()->back()->withErrors('Unauthorized subscription id.');
			}
		}catch (\Exception $e) 
        {
			return redirect()->back()->withErrors('Unauthorized subscription id.');
        }
		}else{
			return redirect()->back()->withErrors('Unauthorized user.Please try again or contact to admin.');
		}
		
		
	}
	 public function Getfeedback(){
		 
		 return view('user.coach.feedback'); 
		 
	 } 
	 
	 public function AddFeedback(Request $request){
		 
		$validator = Validator::make($request->all(), [
			'description' => 'required'
		]);
		
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		} 
		 
		$user = Auth::user();
		$useremail = $user->email;
		$username = $user->name;
		
		if ($user) {
			$description = $request->description;
			$feedback = AppFeedback::make();
			$feedback->user_id = $user->id;
			$feedback->description = $request->description;
			$feedback->save();
			
			/* mail process start */
			$from_email = config('app.email_from');
			
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
			
			

			return redirect()->back()->withSuccess('Feedback post successfully.');
		}else{
			return redirect()->back()->withErrors('Something went wrong!.Please contact to the admin.');
		}
		
	 }
	 
	 public function earning(Request $request){
		 
		$user = Auth::user();
		$filter = $request->filter_date;
		if (isset($request->filter_date)) {
			$enddate = strtotime(date('Y-m-d'). ' 23:59:59');
			$startdate = strtotime($request->filter_date.' 00:00:00');
			
		}else{
			$enddate =  strtotime($request->filter_date. ' 23:59:59');
			$startdate = strtotime('2021-01-01 00:00:00');
		}
		if ($user) {
			$coach_stripe_key = StripeKey::where('user_id', '=', $user->id)->first();
			/* client subscription data */
				$payments_group_by = array();
				 $alladdedclients = AddClient::where('user_id','=',$user->id)->where('subscription_id_for_client','!=',' ')->get();
				if(@$alladdedclients){
					$totalamountearned=0;
					$i=0;
					foreach(@$alladdedclients as $alladdedclient){
						
						 $clientsub_id  =  $alladdedclient->subscription_id_for_client;
						 $stripe  =  Stripe\Stripe::setApiKey($coach_stripe_key->secret_key);
						 //$allsubscriptions = \Stripe\Subscription::retrieve('sub_1LR273E9f3PxiVuYV33ekWJb');
						 //dd($allsubscriptions);
						 $invoices = Stripe\Invoice::all(['subscription' => $clientsub_id,'expand' => ['data.charge'],'created' => ['gte' => $startdate ,'lte' => $enddate]]);
						 $client_id = $alladdedclient->client_id;
						 $clients = User::where('id','=',$client_id)->first();
							
							$totalAmount = 0;
							foreach ($invoices->autoPagingIterator() as $invoice) {
								 $totalAmount += $invoice->amount_paid;
							} 
						 $totalAmount = @$totalAmount/100;
						 $payments_group_by[$i] = array('paidtodate'=>$totalAmount, 'users'=>$clients,'payment_date'=>$alladdedclient->start_date);
						 
						 //$totalamountearned = @$totalamountearned + $totalAmount;
						$i++;
					}
					
				
				
				}  
			
			/* End client subscription data */
			
			/* if($coach_stripe_key){
				
				Stripe\Stripe::setApiKey(config('app.stripe_test') ? $coach_stripe_key->secret_key : $coach_stripe_key->secret_key);
			}
			
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
			//echo $amount;
			//print_r($payments_group_by);
			//return response()->json(['success' => true, 'payment' => $payments_group_by, 'totalEarning' => $amount]);
		}
		 
		 //return view('user.coach.earning',compact('amount','payments_group_by','filter'));
	
		 return view('user.coach.earning',compact('payments_group_by','filter'));  
	 }
	 
	 
	public function getCoachAvailability(){
		
		$user = Auth::user();
		if ($user) {
			$data = array();
			$availability = $user->availability()->first();
			if($availability){
				$days  = unserialize($availability->days);
				$times = unserialize($availability->time);
				$data['id'] = $availability->id;
				$data['user_id'] = $availability->user_id;
				$data['days'] = $days;
				$data['times'] = $times;
			}else{
				$data['days'] =array();
				$data['times'] =array();
			}
			
		}
		
		return view('user.coach.availability',compact('data')); 
	 }
	public function availability(Request $request){
		$validator = Validator::make($request->all(), [
			'days' => 'required',
			'time' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		
		$user_id = Auth::user()->id;
	
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
		return redirect()->back()->withSuccess('You have successfully added time slot');
	}
	public function getNotifications($cliendId){
		$user = Auth::user();
		if($user){
		 //$data = Notification::where('coach_id', '=', $user->id)->where('client_id', '=', $cliendId)->where('type', 'LIKE', "%Message%")->orderBy('id','ASC')->get();
		$chatroom_id='';
		if (ChatRooms::whereCoachId($user->id)->whereClientId($cliendId)->count() > 0) {
				$chatroom = ChatRooms::whereCoachId($user->id)->whereClientId($cliendId)->first();
				$chatroom_id = $chatroom->id;
				/* return response()->json(['success' => true, 'message' => 'Chat room already exists.', 'room_id' => $chatroom->id]); */
			
			} else {
				$createchatroom = ChatRooms::make();
				$createchatroom->coach_id = $user->id;
				$createchatroom->client_id = $cliendId;
				$createchatroom->save();
				$chatroom_id =$createchatroom->id;
			}
		
		 
		}
	return view('user.coach.messages',compact('chatroom_id'));	
	}
	public function unreadnotifications(){
		return view('user.coach.notification');
	}
	public function readNotificationStatus(Request $request){
		$validator = Validator::make($request->all(), [
			'id' => 'required',
			'status' => 'required'

		]);
		
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		$user = Auth::user();
		if ($user) {
			$notification = Notification::find($request->id);
			$notification->status = $request->status;
			$notification->save();
			return redirect()->back()->withSuccess('You have successfully read message');
		}
		
	}
    public function get_reinstateclient($clientId){
		//echo "hello".$clientId;
		$user = Auth::user();
		$addedArchiveclient = AddClient::where('user_id', $user->id)->where('client_id', $clientId)->where('status', '=',2)->first();
		//print_r($addedArchiveclient);
		return view('user.coach.reinstateclient',compact('addedArchiveclient'));		
	} 
	public function reinstateClient(Request $request){
		$validator = Validator::make($request->all(), [
			'stripe_token' => 'required',
			'amount' => 'required',
			'added_client_id' => 'required'
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->messages()], 200);
		}
		
		$user = Auth::user();
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
							return redirect()->route('user.dashboard')->with(['success'=>'Client has been reinstate successfully.']);	
						} else {
							return redirect()->back()->withErrors('Unable to reinstate the client.Please try again or contact to admin.');
						}
					} else {
						return redirect()->back()->withErrors('Unable to create subscription.Please try later or contact to support.');
					}
				} else {
					return redirect()->back()->withErrors('Unable to create subscription.Please try later or contact to support.');
				
				}
			} else {
				return redirect()->back()->withErrors('Unable to create subscription.Please try later or contact to support.');
			}


			if ($pay->balance_transaction) {
				$addedclient = AddClient::find($request->added_client_id);
				if ($addedclient->user_id == $user->id) {
					$addedclient->status = 1;
					$addedclient->start_date = date('Y-m-d H:i:s', $pay->created);
					$addedclient->end_date = date('Y-m-d H:i:s', strtotime('+1 month', $pay->created));
					$addedclient->save();
					return redirect()->route('user.dashboard')->with(['success'=>'Client has been reinstate successfully.']);	
				} else {
					return redirect()->back()->withErrors('Unable to reinstate the client.Please try again or contact to admin.');
				}
			} else {
				return redirect()->back()->withErrors('Your payment has been failed.Please try again or contact to admin.');
			}
		}else{
			return redirect()->back()->withErrors('Something went wrong!.Please contact to the admin.');
		}
        
		
	}
	
/* 	public function fcmTokensave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        } 

        $user = Auth::user();

        $fcm = $user->fcmToken()->where('type', '2')->first();

        //dd($fcm);

        if ($fcm) 
        {
            $fcmtoken = FcmToken::findorfail($fcm->id);
            $fcmtoken->user_id = $user->id;
            $fcmtoken->fcmtoken = $request->fcm_token;
            $fcmtoken->type = '2';
            $fcmtoken->save();

            return response()->json(['success' => true,  'Message' => 'Token Update Successfully']);
        } 
        else 
        {
            $fcmtoken = FcmToken::make();
            $fcmtoken->user_id = $user->id;
            $fcmtoken->fcmtoken = $request->fcm_token;
            $fcmtoken->type = '2';
            $fcmtoken->save();

            return response()->json(['success' => true,  'Message' => 'Token Save Successfully']);
        }
    } */

    public function sendNotification($user_id, $msgdata = array())
    {
		$user = Auth::user();
	
		if($user){
			if($user->timezone){
				 $currentDateTime = Carbon::now()->setTimezone($user->timezone)->format('Y-m-d H:i:s');
			}else{
				 $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
			}
			
		}
		
        $SERVER_API_KEY = 'AAAAUp_qDig:APA91bHkq4AoPWvFN5lQP_AwH7sjp7D2N6ZkxAkaQYueezrPDip6TRdjV46NUATJQJhAnMcdKzZuUJ_DAyhoMuDZaHabR6AbIhDUjLcTVorGXlIjV6mNmVcwTBa2pr5gVL4QdDI27VyO';
        
        $regfcm = FcmToken::whereUserId($user_id)->where('type','2')->first();
        
        $msgdata['sound'] = 'default';

        if($regfcm)
        {
            $data = [
                "registration_ids" => array($regfcm->fcmtoken),
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

            //dd($dataString);

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

            //dd($response);
        }
    }
	public function UpdateHabitItemStatus(Request $request){
		
		$validator = Validator::make($request->all(), [
            'id' => 'required',
			'year'=>'required',
			'month'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
		$user = Auth::user();
		if($user){
			$date =$request->year.'-'.$request->month.'-'.$request->days;
			$habititem = HabitStatus::make();
			$habititem->habit_id = $request->id;
			$habititem->date = $date;
			$habititem->status =1;
			$habititem->save();
			/* $habit = Habit::find($request->id);
			$habit->status = 2;
			$habit->save(); */
			return response()->json(['success' => true,  'message' => 'You have successfully updated your Habit Item']);
		}else{
			 return response()->json([
				'success' => false,
				'message' => 'Please contact to the admin.',
			]);
		}
	}
  public function UpdateHabitItemStatusOne(Request $request){
	  
		$validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
		$user = Auth::user();
		if($user){
			$date =$request->year.'-'.$request->month.'-'.$request->days;
			HabitStatus::where('habit_id',$request->id)->where('date', $date)->first()->update(['status' =>0]);
			
			/* $habit = Habit::find($request->id);
			$habit->status = 1;
			$habit->save(); */
			return response()->json(['success' => true,  'message' => 'You have successfully updated your Habit Item']);
		}else{
			 return response()->json([
				'success' => false,
				'message' => 'Please contact to the admin.',
			]);
		}
	}
	public function coachaddappointment(Request $request){
		
		$user = Auth::user();
		if($user){
			if ($request->repeat == 1) 
			{
				/* $dateto01 = date('Y-m-d', strtotime("next ".$request->app_days, strtotime(date('Y-m-d',strtotime("-1 days")))));
				 $datefrom = date('Y-m-d',strtotime($request->end_date)); */

				/* $dateto = new DateTime($dateto01);
				$datefrom = new DateTime($datefrom); */
				
				if($user->timezone){
					 $appDays = $request->app_days;
					 $nextDate = Carbon::parse("next ".$appDays, $user->timezone)->timezone($user->timezone);
					 $dateto01 = new DateTime($nextDate);
					 $dateto = Carbon::instance($dateto01);
					 $datefrom = Carbon::parse($request->end_date);
				}else{
					 $appDays = $request->app_days;
					 $nextDate = Carbon::parse("next ".$appDays, $user->timezone)->timezone($user->timezone);
					 $dateto01 = Carbon::createFromFormat('Y-m-d H:i:s', $nextDate->toDateTimeString());
					 $dateto = Carbon::instance($dateto01);
					 $datefrom = Carbon::parse($request->end_date);
				}
				
				$interval = $datefrom->diff($dateto);
				$week_total = $interval->format('%a')/7;
					$appointment = Appointment::make();
					$appointment->user_id = $user->id;
					$appointment->client_id = $request->client_id;
					$appointment->date = date('Y-m-d', strtotime("next ".$request->app_days, strtotime(date('Y-m-d',strtotime("-1 days")))));
					$appointment->day = $request->app_days;
					$appointment->time = $request->app_time;
					$appointment->repeat = $request->repeat;
					$appointment->payment_id = '';
					$appointment->schedule_by = 'freeByCoach';
					$appointment->end_date = date('Y-m-d',strtotime($request->end_date));
					$appointment->save();
					return redirect()->back()->withSuccess('Your appointment has been schedule successfully.');       
			}else{
				$appointment = Appointment::make();
                $appointment->user_id = $user->id;
				$appointment->client_id = $request->client_id;
                $appointment->date = date('Y-m-d', strtotime("next ".$request->app_days, strtotime(date('Y-m-d',strtotime("-1 days")))));
                $appointment->day = $request->app_days;
                $appointment->time = $request->app_time;
                $appointment->repeat = $request->repeat;
                $appointment->payment_id = '';
				$appointment->schedule_by = 'freeByCoach';
                $appointment->save();
				return redirect()->back()->withSuccess('Your appointment has been schedule successfully.');
			}
		}else{
			 return redirect()->back()->withErrors('Something went wrong.Please try again later.');
		}	
	}
	
	public function deleteaddedclient($id){
		$user = Auth::user();
		if ($user) {
			$client = $user->getAddedClient()->where('id', '=', $id)->where('status', '=', 0)->whereNull('client_id')->first();
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
						if ($user->getAddedClient()->where('id', '=', $id)->where('status', '=', 0)->whereNull('client_id')->delete()) {
							return redirect()->back()->withSuccess('Client has been deleted successfully.');
						}else {
							return redirect()->back()->withErrors('Unable to delete client.Please try again or contact to admin.');	
						}
						
					}
				
				}catch(\Stripe\Exception\ApiErrorException $e) {
					
				  return redirect()->back()->withErrors('Something went wrong.Please try again later.');
				}
			}else {
				return redirect()->back()->withErrors('Unable to delete client.Please try again or contact to admin.');	
			}
		} else {
			return redirect()->back()->withErrors('Something went wrong.Please try again later.');	
		}
	}
	
}
