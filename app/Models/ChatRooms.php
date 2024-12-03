<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRooms extends Model
{
    use HasFactory;
    protected $fillable = ['id','coach_id','client_id'];
}
