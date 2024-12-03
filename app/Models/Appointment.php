<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
	protected $fillable = [
        'user_id',
        'client_id',
        'date',
		'day',
		'time',
		'schedule_by',
		'status',
		'repeat',
        'subscription_id',
        'subscription_status',
        'payment_id'
    ];
	
	public function coach(){
        return $this->belongsTo(User::class,'user_id');
    }
	public function client(){
        return $this->belongsTo(User::class,'client_id');
    }
	public function payment(){
        return $this->belongsTo(Payment::class,'payment_id');
    }
	public function paymentforapp(){
        return $this->belongsTo(Payment::class,'payment_id')->withDefault(["id"=> 0,"user_id"=> 0,"amount"=> 0,"transaction_id"=> "0", "ios_original_transaction_id"=>0, "status"=> 0, "payment_for"=> 0,   "payment_date"=> "0", "payee_id"=> 0, "subscription_id"=> 0,  "subscription_status"=> 0, "added_client_id"=> 0, "created_at"=> "0", "updated_at"=> "0"]);
    }
    public  function addedclientdata(){ 
        return $this->hasMany(AddClient::class,'client_id','client_id');
    }

  
}
