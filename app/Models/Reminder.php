<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;
	protected $fillable = [
        'client_id',       
		'title',
		'day',
		'time',
		'details',
		'status'
    ];
	
	public function user(){
        return $this->belongsTo(User::class,'client_id');
    }
}
