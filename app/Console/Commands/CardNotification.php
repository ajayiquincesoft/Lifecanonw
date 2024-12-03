<?php

namespace App\Console\Commands;

use App\Models\Card;
use Illuminate\Console\Command;
use Twilio\Jwt\ClientToken;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Models\Setting;

class CardNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CardNotification:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending Email and Mobile Notification of card expire User';

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


        $fourteendays = Carbon::now()->addDays(14)->format('Y-m-d');
        $thirtydays = Carbon::now()->addDays(30)->format('Y-m-d');

            $cardFourteenData = Card::whereDate('ExpiryDate','=',$fourteendays)->get();
            if(count($cardFourteenData) > 0){
                foreach ($cardFourteenData as $cardFourteenDatas){

                   // $message = 'Your card expire on '. $cardFourteenDatas->ExpiryDate .'. Please contact with Structure Complilance Group.';
                    $message = 'Your '. $cardFourteenDatas->CardType .' is expiring on '. $cardFourteenDatas->ExpiryDate .' (14 days), please contact Structure Compliance Group on +1718 383 5372 or visit our website to make a reservation: https://structurecompliance.com/calendar/. It is your responsibility to renew this card before it expires, once expired you will not be permitted on site until you renew your card and receive the necessary training.';
                    $to = '+1'.$cardFourteenDatas->Mobile;
                    $this->sendingMessage($message,$to);
                    $this->sendingMail($message,$cardFourteenDatas);
                }
            }

            $cardThirtyData = Card::whereDate('ExpiryDate','=',$thirtydays)->get();
            if(count($cardThirtyData) > 0){
                foreach ($cardThirtyData as $cardThirtyDatas){

                   // $message = 'Your card expire on '. $cardThirtyDatas->ExpiryDate .'. Please contact with Structure Complilance Group.';
                    $message = 'Your '. $cardThirtyDatas->CardType .' is expiring on '. $cardThirtyDatas->ExpiryDate .' (30 days), please contact Structure Compliance Group on +1718 383 5372 or visit our website to make a reservation: https://structurecompliance.com/calendar/. It is your responsibility to renew this card before it expires, once expired you will not be permitted on site until you renew your card and receive the necessary training.';
                    $to = '+1'.$cardThirtyDatas->Mobile;
                    $this->sendingMessage($message,$to);
                    $this->sendingMail($message,$cardThirtyDatas);
                }
            }

        $this->info('mail and notification send');
        return 0;
    }

    public function sendingMessage($message,$to){
        $accountSid = config('app.twilio')['TWILIO_ACCOUNT_SID'];
        $authToken = config('app.twilio')['TWILIO_AUTH_TOKEN'];

        // check twillio notification on or not
        $twilionotification = Setting::whereName('twillio_notification')->first();

        if($twilionotification->value ==1) {
            try {
                $client = new Client(['auth' => [$accountSid, $authToken]]);
                $result = $client->post('https://api.twilio.com/2010-04-01/Accounts/' . $accountSid . '/Messages.json',
                    ['form_params' => [
                        'Body' => $message, //set message body
                        'To' => $to,
                        //'To' => '+16464570219',
                        'From' => '+19713511827' //we get this number from twilio
                    ]]);
                print_r($result);
            } catch (\Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    public function sendingMail($message,$data){

        // check email notification on or not
        $emailnotification = Setting::whereName('email_notification')->first();

        if($emailnotification->value ==1){
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom("notifications@structurecompliance.com", $data->FirstName);
            $email->setSubject("Structure Compliance Group");
            //$email->addTo('shivamgpt08@gmail.com', $data->FirstName);
            $email->addTo($data->Email, $data->FirstName);
            //$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
           /* $email->addContent(
                "text/html", "<strong>Hello <br/><br/></strong><strong>".$message."<br/><br/>Thanks & Regards<br>Structture Compliance Group</strong>"
            );*/
            $email->addContent(
                "text/html", "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
 
<head>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
  <title>A Simple Responsive HTML Email</title>
  <style type=\"text/css\">
  body {margin: 0; padding: 0; min-width: 100%!important;}
  img {height: auto;}
  .content {width: 100%; max-width: 600px;}
  .header {padding: 40px 30px 20px 30px;}
  .innerpadding {padding: 30px 30px 30px 30px;}
  .borderbottom {border-bottom: 1px solid #f2eeed;}
  .subhead {font-size: 15px; color: #ffffff; font-family: sans-serif; letter-spacing: 10px;}
  .h1, .h2, .bodycopy {color: #153643; font-family: sans-serif;}
  .h1 {font-size: 33px; line-height: 38px; font-weight: bold;}
  .h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
  .bodycopy {font-size: 16px; line-height: 22px;}
  .button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}
  .button a {color: #ffffff; text-decoration: none;}
  .footer {padding: 20px 30px 15px 30px;}
  .footercopy {font-family: sans-serif; font-size: 14px; color: #ffffff;}
  .footercopy a {color: #ffffff; text-decoration: underline;}

  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
  body[yahoo] .hide {display: none!important;}
  body[yahoo] .buttonwrapper {background-color: transparent!important;}
  body[yahoo] .button {padding: 0px!important;}
  body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}
  body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}
  }

  /*@media only screen and (min-device-width: 601px) {
    .content {width: 600px !important;}
    .col425 {width: 425px!important;}
    .col380 {width: 380px!important;}
    }*/

  </style>
</head>

<body yahoo bgcolor=\"#f6f8f1\">
<table width=\"100%\" bgcolor=\"#f6f8f1\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
  <td>
    <!--[if (gte mso 9)|(IE)]>
      <table width=\"600\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
        <tr>
          <td>
    <![endif]-->     
    <table bgcolor=\"#ffffff\" class=\"content\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
      <tr>
        <td bgcolor=\"#c7d8a7\" class=\"header\">
          <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">  
            <tr>
              <td height=\"70\" style=\"padding: 0 20px 20px 0;\">
                <img class=\"fix\" src=\"https://app.structurecompliance.com/assets/images/logo.png\" width=\"250\" height=\"70\" border=\"0\" alt=\"\" />
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
      <tr>
        <td class=\"innerpadding borderbottom\">
          <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
              <td class=\"h2\">
               Hello,
              </td>
            </tr>
            <tr>
              <td class=\"bodycopy\">
                ".$message."
                </td>
            </tr>
          </table>
        </td>
      </tr>
     
      
      <tr>
        <td class=\"footer\" bgcolor=\"#44525f\">
          <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
              <td align=\"center\" class=\"footercopy\">
                &reg; Copyright 2021 Structure Compliance. All Rights Reserved<br/>
				<span class=\"hide\">Visit Site</span>
                <a href=\"https://structurecompliance.com/\" class=\"unsubscribe\"><font color=\"#ffffff\">Structure Compliance</font></a> 
                
              </td>
            </tr>
            <tr>
              <td align=\"center\" style=\"padding: 20px 0 0 0;\">
                <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                  <tr>
                    <td width=\"37\" style=\"text-align: center; padding: 0 10px 0 10px;\">
                      <a href=\"http://www.facebook.com/\">
                        <img src=\"https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/facebook.png\" width=\"37\" height=\"37\" alt=\"Facebook\" border=\"0\" />
                      </a>
                    </td>
                    <td width=\"37\" style=\"text-align: center; padding: 0 10px 0 10px;\">
                      <a href=\"http://www.twitter.com/\">
                        <img src=\"https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/twitter.png\" width=\"37\" height=\"37\" alt=\"Twitter\" border=\"0\" />
                      </a>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <!--[if (gte mso 9)|(IE)]>
          </td>
        </tr>
    </table>
    <![endif]-->
    </td>
  </tr>
</table>
</body>
</html>"
            );
            $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
            try {
                $response = $sendgrid->send($email);
                print $response->statusCode() . "\n";
                print_r($response->headers());
                print $response->body() . "\n";
            } catch (Exception $e) {
                echo 'Caught exception: '. $e->getMessage() ."\n";
            }
        }

    }
}
