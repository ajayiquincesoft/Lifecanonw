<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
	protected $fillable = [
        'user_id',
        'client_id',
        'description',
		'date_time',
		'images1',
		'images2',
		'status'
    ];
	
	public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
