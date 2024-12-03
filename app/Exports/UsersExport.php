<?php

namespace App\Exports;

use App\Models\Card;
use App\Models\CardMeta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpParser\Node\Scalar\String_;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon;

class UsersExport implements FromQuery,WithMapping,WithHeadings
{
    use Exportable;

    private $search;
    private $from_date;
    private $to_date;
    private $cardTypeSearch;
    private $cardDateSearch;

    public function __construct($search,$from_date,$to_date,$cardTypeSearch,$cardDateSearch)
    {
        $this->search = $search;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->cardTypeSearch = $cardTypeSearch;
        $this->cardDateSearch = $cardDateSearch;
    }
   /* public function __construct(String_ $search,String_ $from_date, String_ $to_date)
    {
        $this->search = $search;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }*/

    public function query()
    {
        $search = $this->search;
        $from_date = $this->from_date;
        $to_date = $this->to_date;
        $cardTypeSearch = $this->cardTypeSearch;
        $cardDateSearch = $this->cardDateSearch;

        $query = CardMeta::query();

        if($search){
            $query->whereHas('card',function ($q) use($search){
                $q->where('CardId','LIKE','%'.$search.'%')
                    ->orWhere('FirstName','LIKE','%'.$search.'%')
                    ->orWhere('LastName','LIKE','%'.$search.'%')
                    ->orWhere('Email','LIKE','%'.$search.'%');
            });
        }

        if($cardTypeSearch){
            $query->where('TypeOfSstIDCard',$cardTypeSearch);
        }

        // search by card type
        if($cardDateSearch == 'expiry_date'){
            if($from_date){
                $query->whereDate('ExpiryDate','<=',$from_date);
            }

            if($to_date){
                $query->whereDate('ExpiryDate', '>=', $to_date);

            }
        }else{
            if($from_date){
                $query->whereDate('IssueDate','<=',$from_date);
            }

            if($to_date){
                $query->whereDate('IssueDate', '>=', $to_date);
            }
        }



        return $query;
       /* if($search){
            $query->where('cards.CardId','LIKE','%'.$search.'%')
                ->orWhere('cards.FirstName','LIKE','%'.$search.'%')
                ->orWhere('cards.LastName','LIKE','%'.$search.'%')
                ->orWhere('cards.Email','LIKE','%'.$search.'%');
        }*/

        // search by card type
       // if($cardTypeSearch){
            /*$query->whereHas('cardDetail',function ($model) use($cardTypeSearch){
                $model->where('card_metas.TypeOfSstIDCard',$cardTypeSearch);
            });*/
        //}

       /* if($cardDateSearch == 'expiry_date'){
            if($from_date){
                $query->whereHas('cardDetail',function ($model) use ($from_date){
                    $model->whereDate('card_metas.ExpiryDate','>=',$from_date);
                });
            }

            if($to_date){
                $query->whereHas('cardDetail',function ($model) use ($to_date) {
                    $model->whereDate('card_metas.ExpiryDate', '<=', $to_date);
                });
            }
        }else{
            if($from_date){
                $query->whereHas('cardDetail',function ($model) use ($from_date){
                    $model->whereDate('card_metas.IssueDate','>=',$from_date);
                });
            }

            if($to_date){
                $query->whereHas('cardDetail',function ($model) use ($to_date) {
                    $model->whereDate('card_metas.IssueDate', '<=', $to_date);
                });
            }
        }*/

       /* if($from_date){
            $query->whereHas('cardDetail');
        }

        if($to_date){
            $query->whereHas('cardDetail');
        }
        //$user->where('CardId', $this->active);

        return $query->leftJoin('card_metas', function($query) use($cardDateSearch,$from_date,$to_date){
            $query->on('cards.id', '=', 'card_metas.card_id');
            if($cardDateSearch == 'expiry_date'){
                if($from_date){
                    $query->whereDate('card_metas.ExpiryDate', '>=',$from_date);
                }

                if($to_date){
                    $query->whereDate('card_metas.ExpiryDate', '<=',$to_date);
                }
            }else{
                if($from_date){
                    $query->whereDate('card_metas.IssueDate', '>=',$from_date);
                }

                if($to_date){
                    $query->whereDate('card_metas.IssueDate', '<=',$to_date);
                }
            }
          //  $q->whereDate('card_metas.IssueDate', '>=','2021-03-31');
        })->leftJoin('card_types', function ($query) use($cardTypeSearch){
            $query->on('card_metas.TypeOfSstIDCard', '=', 'card_types.id');
            if($cardTypeSearch){
                $query->where('card_metas.TypeOfSstIDCard',$cardTypeSearch);
            }
        })->leftJoin('trainers', 'card_metas.TrainerId', '=', 'trainers.id');
        */
    }

    /**
     * @var Invoice $invoice
     */
    public function map($user): array
    {
        //echo $this->from_date;
        //exit();
        //dd($user);

       /* $CardStatus = @$user->ExpiryDate;
        if(empty($CardStatus)){
            $cardValid =  'Active';
        }else if($CardStatus < Carbon\Carbon::today()){
            $cardValid = 'Expired';
        }
        $cardValid = 'Active';*/
        return [
            @$user->card->CardId,
            @$user->card->FirstName,
            @$user->card->MiddleName,
            @$user->card->LastName,
            @$user->card->Suffix,
            @$user->card->HouseNo,
            @$user->card->Address,
            @$user->card->City,
            @$user->card->State,
            @$user->card->Zipcode,
            @$user->card->Email,
            @$user->card->Mobile,
            @$user->card->Dob,
            @$user->card->Height,
            @$user->card->EyeColor,
            @$user->card->Zender,
            '',
            @$user->status,
            @$user->IssueDate,
            @$user->type->name,
            @$user->CardIssuerID,
            @$user->ExpiryDate,
            '',
            @$user->card->OSHACardType,
            @$user->card->OSHACardNo,
            @$user->card->OSHACardCourseIssuedDate,
            @$user->trainer->name,
            '',
            '',
            '',
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
   /* public function collection()
    {
        return Card::get(['CardId',
            'Suffix',
            'FirstName',
            'MiddleName',
            'LastName',
            'Dob',
            'Zender',
            'HouseNo',
            'Address',
            'City',
            'State',
            'Zipcode',
            'Email',
            'Mobile',
            'Height',
            'EyeColor',
            'NumberOfCreditHoursCompleted',
           // 'TypeOfSstIDCard',
            //'CardType',
           // 'CardStatus',
            //'CardIssuerID',
            //'IssueDate',
            //'ExpiryDate',
            //'DOBCourseProviderId',
            'OSHACardType',
            'OSHACardNo',
            'OSHACardTrainerName',
            'OSHACardCourseIssuedDate',
            //'Url'
        ]);
       // return Card::get(['CardId','FirstName','LastName',
         //   'Email','Mobile','Height','EyeColor','CardType','IssueBy','IssueDate','ExpiryDate','DOBCourseProviderId','Url']);
    }*/

    /*public function query()
    {
       $query =  Card::query();
       if($this->search){
            $query->where('CardId','LIKE','%'.$this->search.'%')
                ->orWhere('FirstName','LIKE','%'.$this->search.'%')
                ->orWhere('LastName','LIKE','%'.$this->search.'%')
                ->orWhere('Email','LIKE','%'.$this->search.'%')
                ->orWhere('CardType','LIKE','%'.$this->search.'%');
        }

        if($this->from_date){
            $query->whereDate('ExpiryDate','>=',$this->from_date);
        }

        if($this->to_date){
            $query->whereDate('ExpiryDate','<=',$this->to_date);
        }

        $cardData = $query->get(['CardId']);
       return $cardData;
    }*/

    public function headings(): array
    {
        return [
            'Applicant SST ID Card Number',
            'Applicant First Name',
            'Applicant Middle Name',
            'Applicant Last Name',
            'Applicant Suffix',
            'House Number',
            'Address Name',
            'Applicant City',
            'Applicant State',
            'Applicant Zip Code',
            'Applicant Email address',
            'Applicant Telephone Number',
            'Applicant Birthdate',
            'Applicant Height',
            'Applicant Eye Color',
            'Applicant Gender',
            'Course Provider ID #',
            'Status of Card',
            'Card Issue Date',
            'Type of SST ID Card',
            'Number of credit hours completed',
            'Card Issuer ID',
            'Expiration Date',
            'OSHA Card Type',
            'OSHA Card No',
            'OSHA Card Course Issued Date',
            'OSHA Card Trainer Name',
            'Course ID',
            'Certificate Date',
            'Issued Course Provider ID'
        ];
        //return ['CardId','FirstName','LastName',
          //  'Email','Mobile','Height','EyeColor','CardType','IssueBy','IssueDate','ExpiryDate','DOBCourseProviderId','Url'];
    }
}
