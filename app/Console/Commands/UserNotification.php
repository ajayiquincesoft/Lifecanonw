<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Habit;
use App\Models\FcmToken;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
class UserNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run commmand when expire data';

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
        $SERVER_API_KEY = 'AAAADSTvQMw:APA91bGA59KdYQtEs9Jp82wq4-du_cYRxONwUBkRO2pm_bMTAC6vwuvd239EbkBjyzo4v-wNUXHslo0pF0FKofNWS_y8_Iur-kHbovVQmQMOEDQ99kKFjuHmuzunpCdTvmTIhmwVeh7y';
        $habits = Habit::whereStatus(1)->get();
        if ($habits) {
            foreach ($habits as $habit) {
                $alert = $habit->alert;
				$coach_user = User::where('id','=',$habit->user_id)->first();
                if ($alert) {
					
					
					$curr = Carbon::now();
					$curr->tz($coach_user->timezone);
					$currdate = $curr->format('Y-m-d');
					//$hdate = Carbon::parse($habit->start_date);
					$s = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($habit->start_date)));
					//echo'---'. date('Y-m-d', strtotime($habit->start_date));
					//die;
                  $passed_session = $this->datediffInWeeks($currdate, $s);
				 
                    if (strtolower($alert) == '5 minutes before') {

                        //$alert_time = date("Y-m-d H:i", strtotime($habit->start_date) - (5 * 60));
						$startDate = Carbon::parse($habit->start_date);
						$newStartDate = $startDate->subMinutes(5);
						$alert_time = $newStartDate->format('Y-m-d H:i');
						
                    } elseif (strtolower($alert) == '10 minutes before') {
                        //$alert_time = date("Y-m-d H:i", strtotime($habit->start_date) - (10 * 60));
						$startDate = Carbon::parse($habit->start_date);
						$newStartDate = $startDate->subMinutes(10);
						$alert_time = $newStartDate->format('Y-m-d H:i');
						
                    } elseif (strtolower($alert) == '15 minutes before') {
                        //$alert_time = date("Y-m-d H:i", strtotime($habit->start_date) - (15 * 60));
						$startDate = Carbon::parse($habit->start_date);
						$newStartDate = $startDate->subMinutes(15);
						$alert_time = $newStartDate->format('Y-m-d H:i');
						
                    } elseif (strtolower($alert) == '30 minutes before') {
						
                        //$alert_time = date("Y-m-d H:i", strtotime($habit->start_date) - (30 * 60));
						$startDate = Carbon::parse($habit->start_date);
						$newStartDate = $startDate->subMinutes(30);
						$alert_time = $newStartDate->format('Y-m-d H:i');
						
                    } elseif (strtolower($alert) == '1 hour before') {
                        //$alert_time = date("Y-m-d H:i", strtotime($habit->start_date) - (60 * 60));
						$startDate = Carbon::parse($habit->start_date);
						$newStartDate = $startDate->subMinutes(60);
						$alert_time = $newStartDate->format('Y-m-d H:i');
						
                    } elseif (strtolower($alert) == '2 hour before') {
                        $alert_time = date("Y-m-d H:i", strtotime($habit->start_date) - (120 * 60));
						$startDate = Carbon::parse($habit->start_date);
						$newStartDate = $startDate->subMinutes(120);
						$alert_time = $newStartDate->format('Y-m-d H:i');
						
                    } elseif (strtolower($alert) == '1 day before') {
                        //$alert_time = date("Y-m-d H:i", strtotime($habit->start_date) - (1440 * 60));
						$startDate = Carbon::parse($habit->start_date);
						$newStartDate = $startDate->subMinutes(1440);
						$alert_time = $newStartDate->format('Y-m-d H:i');
						
                    } else {

                        //$alert_time = date("Y-m-d H:i", strtotime($habit->start_date) - (15 * 60));
						$startDate = Carbon::parse($habit->start_date);
						$newStartDate = $startDate->subMinutes(15);
						$alert_time = $newStartDate->format('Y-m-d H:i');
						
                    }
					
					
					
					$currentDateTime = Carbon::now();
					$currentDateTime->tz($coach_user->timezone);
					$currdTime = $currentDateTime->format('Y-m-d H:i');
					
					
					$alertDateTime = Carbon::createFromFormat('Y-m-d H:i', $alert_time);
					$currtDeTime = Carbon::createFromFormat('Y-m-d H:i', $currdTime);
					
						$time_diffrence = round(abs($alertDateTime->diffInMinutes($currtDeTime)));
						$currentDay = Carbon::now()->tz($coach_user->timezone)->format('l');
					    $habitda = Carbon::parse($habit->start_date)->format('m-d-Y h:m a');
						
                    //$time_diffrence = round(abs(strtotime(\Carbon\Carbon::createFromFormat('Y-m-d H:i', $alert_time)->format('H:i')) - strtotime($currTime)));
                    if (($habit->number_of_session > $passed_session && $passed_session >= 0) && in_array($currentDay, $habit->week_days) && $time_diffrence == 0) {
						
						//$this->habitListalertforcoach($habit->user_id,$habit->client_id,$habitda,$alert);

                        $fcmtoken = FcmToken::Where('user_id', '=', $habit->client_id)->first();
                        if ($fcmtoken) {
								
                            /*for send and save notification  */
							
                            $msgdata["title"] = $habit->name;
                            $usertype = 'client ';
                            $msgdata["body"] =''; /* "Habit start reminder! Your coach (".$coach_user->name.") habit will start at ".$habitda.""; */
                            $msgdata['type'] = "Habit List";
                            $msgdata['user_type'] = $usertype;
                            $msgdata['coach_id'] = $habit->user_id;
                            $msgdata['client_id'] =  $habit->client_id;

                            /*for send and save notification  */
                            $saveNotification = Notification::make();
                            $saveNotification->user_id = $habit->client_id;
                            $saveNotification->title = $habit->name;
                            $saveNotification->body = '';
                            $saveNotification->type = $msgdata['type'];
                            $saveNotification->coach_id = @$msgdata['coach_id'];
                            $saveNotification->client_id = @$msgdata['client_id'];
							$saveNotification->ndate = $currtDeTime;
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
        return $this->info('Notification send successfully');
    }

    function datediffInWeeks($date1, $date2)
    {
        // if ($date1 > $date2) return $this->datediffInWeeks($date2, $date1);
		$dateCarbon = Carbon::parse($date1);
		$first = $dateCarbon->format('Y-m-d');
		
		$dateCarbon2 = Carbon::parse($date2);
		$second = $dateCarbon2->format('Y-m-d');
       /*  echo $first = date('m/d/Y', strtotime($date1));
        echo $second = date('m/d/Y', strtotime($date2)); */
		
        return floor((strtotime($first) - strtotime($second)) / 604800);
    }
	
	function habitListalertforcoach($user_id,$client_id,$habitdate,$alerttime){
		$client_user = User::where('id','=',$client_id)->first();
		$SERVER_API_KEY = 'AAAADSTvQMw:APA91bGA59KdYQtEs9Jp82wq4-du_cYRxONwUBkRO2pm_bMTAC6vwuvd239EbkBjyzo4v-wNUXHslo0pF0FKofNWS_y8_Iur-kHbovVQmQMOEDQ99kKFjuHmuzunpCdTvmTIhmwVeh7y';
		
		$fcmtoken = FcmToken::Where('user_id', '=', $user_id)->first();
			if ($fcmtoken) {
					
				/*for send and save notification  */
				$msgdata["title"] = $client_user->name.' session starts in '.$alerttime.' minutes.';
				$usertype = 'client ';
				$msgdata["body"]='';
				$msgdata1["body1"] = "Habit start reminder! Your client (".$client_user->name.") habit will start at ".$habitdate."";
				$msgdata['type'] = "Habit List";
				$msgdata['user_type'] = $usertype;
				$msgdata['coach_id'] = $user_id;
				$msgdata['client_id'] =  $client_id;

				/*for send and save notification  */
				$saveNotification = Notification::make();
				$saveNotification->user_id = $user_id;
				$saveNotification->title = 'Habit List';
				$saveNotification->body = $msgdata1['body1'];
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
