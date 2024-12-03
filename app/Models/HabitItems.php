<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitItems extends Model
{
    use HasFactory;
	protected $fillable = ['id','habit_id','item_name','item_status'];
	public function habit(){
        return $this->belongsTo(Habit::class,'habit_id');
    }
}
