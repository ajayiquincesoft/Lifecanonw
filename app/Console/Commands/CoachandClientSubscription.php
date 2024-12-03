<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\User;
use App\Models\AddClient;
use Stripe;
use App\Models\StripeKey;
use App\Models\Payment;
class CoachandClientSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:coachandclient';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Coach and Client subscription verifications.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		
		$users = AddClient::where('client_id', '!=','NULL')->where('id','!=',1)->get();
		
		if($users){
			
			foreach($users as $user){
				// coach data
				$coach_id = $user->user_id;
				$subscription_id_for_coach = $user->subscription_id_for_coach;
				if($subscription_id_for_coach){
					\Stripe\Stripe::setApiKey(config('app.stripe_key'));
					$current_subscription_forcoach = \Stripe\Subscription::retrieve($subscription_id_for_coach);
					if($current_subscription_forcoach->status=='incomplete_expired' OR 					$current_subscription_forcoach->status=='canceled'){
					 $coach_data = AddClient::where('subscription_id_for_coach', '=', $subscription_id_for_coach)->where('status','=',1)->first();
					 if($coach_data){
						 $coach_data->subscription_status_for_coach =0;
						 $coach_data->status =2;
						 $coach_data->save();
					 }
						$payment = Payment::where('subscription_id', '=', $subscription_id_for_coach)->first();
						
						if($payment){
							$payment->subscription_status = 0;
							$payment->save();
						}
					}
				}
				
				// client
				$client_id = $user->client_id;
				$subscription_id_for_client = $user->subscription_id_for_client;
				if($subscription_id_for_client){
					
					$existinguserstripe = StripeKey::where('user_id',$coach_id)->first();
					Stripe\Stripe::setApiKey($existinguserstripe['secret_key']);
					$current_subscription1 = Stripe\Subscription::retrieve($subscription_id_for_client);
					if($current_subscription1->status=='canceled' OR $current_subscription1->status=='incomplete_expired'){
							$payment1 = Payment::where('subscription_id', '=', $subscription_id_for_client)->first();
							if($payment1){
								$payment1->subscription_status = 0;
								$payment1->save();
							}
							$coach_data1 = AddClient::where('subscription_id_for_client', '=', $subscription_id_for_client)->first();
							if($coach_data1){
								$coach_data1->subscription_status_for_client =0;
								$coach_data1->status =2;
								$coach_data1->save();
							}
					}
					
				}
				
				
			}
		 echo "Run successfully.";	
		}
			
	}
}