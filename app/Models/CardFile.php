<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CardFile extends Model
{
    use HasFactory;

    function upload_image($images){
        if($images){
            $year = date("Y");
            $month = date("m");
            $uploadPath = $year.'/'.$month.'/';
            $disk = Storage::disk("public");
            $new_path = $disk->putFile($uploadPath,$images);
            $this->file_path = Storage::disk('public')->url($new_path);
            return $new_path;
        }
    }

    function getImageUrlAttribute()
    {
        if ($this->file_path) {
            return $this->file_path;
        }
    }
}
