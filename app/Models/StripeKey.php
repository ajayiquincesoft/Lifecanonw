<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeKey extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'secret_key',
		'published_key',
        'verified',
        'auth_code',
		'status'
    ];
	
	public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
