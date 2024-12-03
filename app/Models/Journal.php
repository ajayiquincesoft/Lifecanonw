<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;
	protected $fillable = [
        'user_id',
        'client_id',
        'description',
		'images',
		'date_time',
		'status'
    ];
	
	 public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
