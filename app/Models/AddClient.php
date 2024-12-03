<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AddClient extends Model
{
    use HasFactory;
	use SoftDeletes;
	/* protected $fillable = ['id','user_id','client_id','client_name','client_email','plan_name','plan_amount','start_date','end_date','status','code','subscription_id_for_coach','subscription_status_for_coach','subscription_id_for_client','subscription_status_for_client','cycle','phone','appointment_fee']; */
	protected $fillable = [];
	
	 public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
	public function client(){
        return $this->belongsTo(User::class,'client_id');
    }

    public function stripe(){
        return $this->belongsTo(StripeKey::class,'user_id','user_id');
    }

    public function goal(){
        return $this->hasMany(goal::class,'client_id','client_id');
    }
    public function journals(){
        return $this->hasMany(Journal::class,'client_id','client_id')->orderBy('id','desc');
    }
	public function note(){
        return $this->hasMany(Note::class,'client_id','client_id')->orderBy('id','desc');
    }
	public function habit(){
        return $this->hasMany(Habit::class,'client_id','client_id');
    }

   

	function generateCodeNumber() {
    $number = mt_rand(1,999999); // better than rand()

    // call the same function if the barcode exists already
    if ($this->CodeNumberExists($number)) {
        return generateCodeNumber();
    }

    // otherwise, it's valid and can be used
    return $number;
	}

	function CodeNumberExists($number) {
    // query the database and return a boolean
    // for instance, it might look like this in Laravel
    return AddClient::whereCode($number)->exists();
	}
}
