<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
	
	protected $fillable = ['id','user_id','amount','transaction_id','status','payment_for','payment_date','subscription_id','subscription_status'];
	
	 public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
	
}
