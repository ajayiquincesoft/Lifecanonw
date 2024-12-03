<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
	 protected $fillable = [
        'page_title', 'slug', 'meta_description', 'meta_data', 'page_content'
    ];
}
