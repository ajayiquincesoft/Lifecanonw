<?php

namespace App\Http\Controllers;

use App\Models\CardClass;
use App\Models\CardMeta;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Card;
use App\Models\Page;
use App\Models\plan;
use Mail;
use App\Models\User;

use App\Mail\DbTemplateMail;
use App\Models\Template;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class FrontController extends Controller
{
    //
    public function index(Request $request)
    {
        // $input = $request->all();
        // $message = '';
        // $cardData = array();
        // $cardClasses = array();
        // $cardIdentity = '';
        // if ($input) {
        //     $validatedData = $request->validate([
        //         'search' => 'required',
        //         'g-recaptcha-response' => 'required|recaptcha'
        //     ], [
        //         'search.required' => 'Card Number required',
        //         'g-recaptcha-response.required' => 'Please select recaptcha',
        //     ]);

        //     $cardIdentity = Card::where('CardId', $request->search)->first();

        //     if ($cardIdentity) {
        //         $message = 'true';
        //         $cardData = CardMeta::where('card_id', $cardIdentity->id)->get();
        //         $cardClasses = CardClass::where('card_id', $cardIdentity->id)->get();
        //     } else {
        //         $message = 'false';
        //     }
        // }

        // $bannerData = Banner::all();
        // return view('welcome', compact('bannerData', 'message', 'cardData', 'cardIdentity', 'cardClasses'));
		$plans = plan::where('status', '=', 1)->get();
		//print_r($plans);
        return view('welcome',compact('plans'));
    }

    // search card by card data
    public function SearchQrCode(Request $request)
    {
        $message = '';
        $cardData = array();
        $cardClasses = array();
        $cardIdentity = '';

        $cardIdentity = Card::whereHas('cardDetail', function ($model) use ($request) {
            $model->where('CardId', base64_decode($request->cardId))->where('id', $request->Id);
        })->first();
        if ($cardIdentity) {
            $message = 'true';
            $cardData = CardMeta::where('card_id', $cardIdentity->id)->get();
            $cardClasses = CardClass::where('card_id', $cardIdentity->id)->get();
        } else {
            $message = 'false';
        }

        $bannerData = Banner::all();
        return view('welcome', compact('bannerData', 'message', 'cardData', 'cardIdentity', 'cardClasses'));
    }

    public function show($slug)
    {
        $content = Page::where('slug', $slug)->first();

        if (is_null($content)) {
            abort(404);
        }

        //return response($content->page_content);
        return view('page', compact('content'));
    }

public function contactform(Request $request){
	
	  $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
			'captcha' => 'required|captcha'
        ],['captcha.captcha'=>'Invalid captcha code.']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
		$receiver_email = config('app.contact_form_email');
		$receiver_email2 = config('app.contact_form_email2');
		
		Mail::send('contactMail', array(
            'subject' =>'Life Canon Contact Form',
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'messages' => $request->message,
        ), function($message) use ($request){
            $message->from($request->email);
            $message->to($receiver_email, 'Admin')->subject('Life Canon Contact Form');
			$message->to($receiver_email2, 'Admin')->subject('Life Canon Contact Form');
        });
		
        return redirect()->back()->with(['success' => 'You have successfully submitted your query.','contact_success'=>'yes']);
	 
 }
 public function refreshCaptcha(Request $request){
	 return response()->json(['captcha'=> captcha_img()]);
 }
 public function firstFreeSession(){
	 return view('firstfreesession'); 
 } 
 public function firstPaidSession(){
	 return view('paidsession'); 
 }
 public function useraccountdelete(){
	 return view('account_delete'); 
 }
 public function useraccountMessage(Request $request){
	 
	 $validator = Validator::make($request->all(), [
  
            'email' => 'required|email',
			
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
		
		$user = User::where('email','=',$request->email)->get();
		
		
		if($user->count()>0){
			return redirect()->back()->with(['success'=>'Thank you; we have processed your account deletion request. Your account will be deleted within 2 to 3 working days.']);
		}else{
		 return redirect()->back()->withErrors("Your email is not exist.Please put the correct email id.");
		}
		 //
	 
 }
}
