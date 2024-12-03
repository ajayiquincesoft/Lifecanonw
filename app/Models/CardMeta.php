<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon;

class CardMeta extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $dates = ['IssueDate','ExpiryDate'];

    // get card status
    public function getStatusAttribute(){
        if(empty($this->ExpiryDate)){
            return 'Active';
        }else if($this->ExpiryDate < Carbon\Carbon::today()){
            return 'Expired';
        }
        return 'Active';
    }

    public function type(){
        return $this->belongsTo(CardType::class,'TypeOfSstIDCard');
    }

    public function trainer(){
        return $this->belongsTo(Trainer::class,'TrainerId');
    }

    public function card(){
        return $this->belongsTo(Card::class,'card_id');
    }
}
