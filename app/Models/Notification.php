<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','title','body','type','status','client_id','coach_id','ndate'];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
	
}
