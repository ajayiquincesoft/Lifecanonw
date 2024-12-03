<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Card extends Model
{
    use HasFactory;

//    protected $fillable = ['CardId',
//        'Suffix',
//        'FirstName',
//        'MiddleName',
//        'LastName',
//        'Dob',
//        'Zender',
//        'HouseNo',
//        'Address',
//        'City',
//        'State',
//        'Zipcode',
//        'Email',
//        'Mobile',
//        'Height',
//        'EyeColor',
//        'NumberOfCreditHoursCompleted',
//        'TypeOfSstIDCard',
//       // 'CardType',
//       // 'CardStatus',
//        'CardIssuerID',
//        'IssueDate',
//        'ExpiryDate',
//        'DOBCourseProviderId',
//        'OSHACardType',
//        'OSHACardNo',
//        'OSHACardTrainerName',
//        'OSHACardCourseIssuedDate',
//        'Url'];
    public $timestamps = false;

    protected $dates = ['OSHACardCourseIssuedDate'];


    // convert blob image to base64
    public function getImagePathAttribute(){
        if($this->image){
            return 'data:image/jpeg;base64,'.base64_encode($this->image);
        }

        return asset('assets/images/default-user.png');
    }

    // return card meta
    public  function cardDetail(){
        return $this->hasMany(CardMeta::class,'card_id','id');
    }

    // return card meta
    public  function cardCourse(){
        return $this->hasMany(CardClass::class,'card_id','id');
    }

    // return card files relation
    public function cardFiles(){
        return $this->hasMany(CardFile::class,'card_id','id');
    }


}
