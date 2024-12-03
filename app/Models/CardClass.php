<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardClass extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $dates = ['CompletionDays'];

    public function courses(){
        return $this->belongsTo(Course::class,'course_id');
    }

    // get belongstoMany course
    public function classCourses(){
        return $this->belongsToMany(Course::class,'card_class_courses','card_classes_id','courses_id');
    }
}
