<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\FcmToken;
use Carbon\Carbon;
use App\Models\User;
class SendAppointmentAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:appointmentalerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send appointmentalerts';

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
        /***************** appointment schedule  *****************/
		
					$appointments = Appointment::where('status','=',1)->get();
						$j=0;
						 $All_dates = array();
						foreach($appointments as $appointment){
							$S_date = $appointment->date;
							$End_date = $appointment->end_date;
							$time  = explode('-',$appointment->time);
							$startdate1 = Carbon::createFromFormat('Y-m-d', $S_date);
							$start_dateWithTime = $startdate1->setTimeFromTimeString(trim($time[0]));
							$start_datetime = $start_dateWithTime->format('Y-m-d H:i');
							$day = $appointment->day;
							$startDate = Carbon::parse($start_datetime);
							$endDate = Carbon::parse($End_date);
							if(@$End_date){
								$occurrences = 0;
								$dates = [];
								while ($startDate->lte($endDate)) {
									if ($startDate->isDayOfWeek($day)) {
										$occurrences++;
										$dates[] = $startDate->format('Y-m-d H:i');
									}
									$startDate->addDay();
								}
								if(count($dates)>0){
									foreach($dates as $date){
										$dateTime = Carbon::parse($date);
										$dateTime->subMinutes(15);
										$newDate = $dateTime->format('Y-m-d H:i');
										$mydatetime = Carbon::parse($newDate);
										
										$coach_user = User::where('id','=',$appointment->user_id)->first();
										$client_user = User::where('id','=',$appointment->client_id)->first();
										
										$currentDateTime = Carbon::now();
										$currentDateTime->tz($coach_user->timezone);
										$currtDateTime = $currentDateTime->format('Y-m-d H:i');
										$minutesDiff = $mydatetime->diffInMinutes($currtDateTime);
										$app_dt = Carbon::parse($mydatetime)->format('m-d-Y h:m a');
										if($minutesDiff==0){
											if($appointment->schedule_by=='coach' OR $appointment->schedule_by=='freeByCoach'){
												$fcmtoken = FcmToken::Where('user_id', '=', $appointment->client_id)->first();
												$user_name = $coach_user->name;
												
												$this->Appointmentalertforcoach($appointment->user_id,$appointment->client_id,$app_dt,$currtDateTime);
												
											}else{
												$fcmtoken = FcmToken::Where('user_id', '=', $appointment->user_id)->first();
												$user_name = $client_user->name;
												$this->Appointmentalertforcoach($appointment->client_id,$appointment->user_id,$app_dt,$currtDateTime);
											}
											  if ($fcmtoken) {

												/*for send and save notification  */
												$msgdata["title"] = $user_name ." session Starts in 15 minutes";
												$msgdata["body"] = ''; /* "Appointment Reminder! Your Appointment will start at ".$app_dt.""; */
												$msgdata['type'] = "Appointment Schedule";
												if($appointment->schedule_by){
													$msgdata['user_type'] = $appointment->schedule_by;
												}
												$msgdata['coach_id'] = $appointment->user_id;
												$msgdata['client_id'] =  $appointment->client_id;

												/*for send and save notification  */
												$saveNotification = Notification::make();
												$saveNotification->user_id = $appointment->client_id;
												$saveNotification->title = $msgdata['title'];
												$saveNotification->body = ''; //$msgdata['body'];
												$saveNotification->type = $msgdata['type'];
												$saveNotification->coach_id = @$msgdata['coach_id'];
												$saveNotification->client_id = @$msgdata['client_id'];
												$saveNotification->ndate = @$currtDateTime;
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
							}else{
									$dateTime1 = Carbon::parse($startDate);
									$dateTime1->subMinutes(15);
									$newDate1 = $dateTime1->format('Y-m-d H:i');
									$mydatetime1 = Carbon::parse($newDate1);
									
									$coach_user1 = User::where('id','=',$appointment->user_id)->first();
									$client_user1 = User::where('id','=',$appointment->client_id)->first();
									
									$currentDateTime1 = Carbon::now();
									$currentDateTime1->tz($coach_user1->timezone);
									$currdTime1 = $currentDateTime->format('Y-m-d H:i');
									
									
									
									$minutesDiff1 = $mydatetime1->diffInMinutes($currdTime1);
										if($minutesDiff1==0){
											if($appointment->schedule_by=='coach' OR $appointment->schedule_by=='freeByCoach'){
												$fcmtoken = FcmToken::Where('user_id', '=', $appointment->client_id)->first();
												$this->Appointmentalertforcoach($appointment->user_id,$appointment->client_id,$mydatetime1,$currdTime1);
												$user_name1 = $coach_user1->name;
											}else{
												$fcmtoken = FcmToken::Where('user_id', '=', $appointment->user_id)->first();
												$this->Appointmentalertforcoach($appointment->client_id,$appointment->user_id,$mydatetime1,$currdTime1);
												$user_name1 = $client_user1->name;
											}
											  if ($fcmtoken) {

												/*for send and save notification  */
												$msgdata["title"] = $user_name1 ." session Starts in 15 minutes";
												$msgdata["body"] = '';/* "Appointment Reminder! Your Appointment will start at ".$mydatetime1.""; */
												$msgdata['type'] = "Appointment Reminder";
												if($appointment->schedule_by){
													$msgdata['user_type'] = $appointment->schedule_by;
												}
												$msgdata['coach_id'] = $appointment->user_id;
												$msgdata['client_id'] =  $appointment->client_id;

												/*for send and save notification  */
												$saveNotification = Notification::make();
												$saveNotification->user_id = $appointment->client_id;
												$saveNotification->title = $msgdata['title'];
												$saveNotification->body = $msgdata['body'];
												$saveNotification->type = $msgdata['type'];
												$saveNotification->coach_id = @$msgdata['coach_id'];
												$saveNotification->client_id = @$msgdata['client_id'];
												$saveNotification->ndate = @$currdTime1;
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
						echo "Notification Send successfully";

/***************** appointment schedule  *****************/

  }
  
function Appointmentalertforcoach($user_id,$client_id,$appointmenttdate,$cdTime){
	$client_user = User::where('id','=',$client_id)->first();
	$SERVER_API_KEY = 'AAAADSTvQMw:APA91bGA59KdYQtEs9Jp82wq4-du_cYRxONwUBkRO2pm_bMTAC6vwuvd239EbkBjyzo4v-wNUXHslo0pF0FKofNWS_y8_Iur-kHbovVQmQMOEDQ99kKFjuHmuzunpCdTvmTIhmwVeh7y';
	
	$fcmtoken = FcmToken::Where('user_id', '=', $user_id)->first();
		if ($fcmtoken) {
				
			/*for send and save notification  */
			$msgdata["title"] = $client_user->name." session Starts in 15 minutes";
			$msgdata1["body1"] = '';/* "Appointment Reminder! Your Appointment will start at ".$appointmenttdate.""; */
			$msgdata['type'] = "Appointment Reminder";
			$msgdata['user_type'] ='Coach';
			$msgdata['coach_id'] = $user_id;
			$msgdata['client_id'] =  $client_id;

			/*for send and save notification  */
			$saveNotification = Notification::make();
			$saveNotification->user_id = $user_id;
			$saveNotification->title = $msgdata['title'];
			$saveNotification->body = $msgdata1["body1"];
			$saveNotification->type = $msgdata['type'];
			$saveNotification->coach_id = @$msgdata['coach_id'];
			$saveNotification->client_id = @$msgdata['client_id'];
			$saveNotification->ndate = @$cdTime;
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
