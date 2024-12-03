<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;

    protected $appends = ['image'];

    function upload_image($images){
        if($images){
            $disk = Storage::disk("public");
            $disk->delete($this->image);
            $new_path = $disk->putFile("",$images);
            $this->image_path = $new_path;
            return $new_path;
        }
    }

    function getImageAttribute()
    {
        $image = Storage::disk('public')->url($this->image_path);
        if ($image) {
            return $image;
        }

        return asset('assets/images/banner.jpg');
    }
}
