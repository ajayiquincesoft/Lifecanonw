<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
	 protected $fillable = [
        'contect_name',
        'contect_email',
        'contect_phone',
		'feedback'
    ];
}
