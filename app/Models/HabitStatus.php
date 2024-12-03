<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitStatus extends Model
{
    use HasFactory;
	protected $guarded = [];
	public function habit(){
        return $this->belongsTo(Habit::class,'habit_id');
    }
}
