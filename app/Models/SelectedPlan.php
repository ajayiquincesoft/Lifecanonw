<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectedPlan extends Model
{
    use HasFactory;
	protected $fillable = ['id','user_id','plan_id','start_date','end_date','status','subscription_id','subscription_status'];
	
	 public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
	public function plan(){
		return $this->belongsTo(plan::class,'plan_id','id');
	}
}
