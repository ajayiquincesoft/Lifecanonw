<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Stripe\Plan;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
		'user_type',
        'profile_pic' ,
        'stripe_customer_id',
        'experience',
        'area_of_expertise',
        'description',
        'phone',
		'timezone',
        'appointment_fees',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
	public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
	
	 public  function availability(){
        return $this->hasMany(Availability::class,'user_id','id');
    }

	public  function payment(){ 
        return $this->hasMany(Payment::class,'user_id','id');
    }
	public  function habits(){ 
        return $this->hasMany(Habit::class,'user_id','id');
    }
	public  function selectedPlan(){ 
        return $this->hasMany(SelectedPlan::class,'user_id','id');
    }
	public  function getAddedClient(){ 
        return $this->hasMany(AddClient::class,'user_id','id');
    }
	
	public  function clientJournals(){ 
        return $this->hasMany(Journal::class,'client_id','id');
    }
	
	public function coachJournals(){
		return $this->hasMany(Journal::class,'user_id','id');
	}
	public function ClientReminder(){
		return $this->hasMany(Reminder::class,'client_id','id');
	}
	public function CoachNote(){
		return $this->hasMany(Note::class,'user_id','id');
	}
	public function ClientAppointment(){
		return $this->hasMany(Appointment::class,'client_id','id');
	}
	public function CoachAppointment(){
		return $this->hasMany(Appointment::class,'user_id','id');
	}
    public function stripe(){
        return $this->hasOne(StripeKey::class,'user_id','id');
	}
    
    public function fcmToken(){
        return $this->hasOne(FcmToken::class,'user_id','id');
	}
    public function notifications(){
        return $this->hasMany(Notification::class,'user_id','id');
    }
    
    public function goals(){
        return $this->hasMany(goal::class,'user_id','id');
    }
    
    public function feedback(){
        return $this->hasMany(AppFeedback::class,'user_id','id');
    }

    public  function addedclientdata(){ 
        return $this->hasMany(AddClient::class,'client_id','id');
    }
    
}
