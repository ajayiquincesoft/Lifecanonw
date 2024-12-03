<?php

namespace App\Imports;

use App\Models\Card;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CartImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
      //  print_r($rows);
        //exit();
        $i=0;
        foreach ($rows as $row)
        {

            if(isset($row[0])  && $i !== 0 ){

                $row12= null;
                $row17= null;
                $row21= null;
                $row25= null;

                /*if($row[12]) {
                    $excel_date = $row[12]; //here is that value 41621 or 41631
                    $unix_date = ($excel_date - 25569) * 86400;
                    $excel_date = 25569 + ($unix_date / 86400);
                    $unix_date = ($excel_date - 25569) * 86400;
                    echo '<br/>'.$row12 = gmdate("Y-m-d", $unix_date);
                }

                if($row[17]) {
                    $excel_date1 = $row[17]; //here is that value 41621 or 41631
                    $unix_date1 = ($excel_date1 - 25569) * 86400;
                    $excel_date1 = 25569 + ($unix_date1 / 86400);
                    $unix_date1 = ($excel_date1 - 25569) * 86400;
                    echo '<br/>'.$row17 = gmdate("Y-m-d", $unix_date1);
                }

                if($row[21]) {
                    $excel_date2 = $row[21]; //here is that value 41621 or 41631
                    $unix_date2 = ($excel_date2 - 25569) * 86400;
                    $excel_date2 = 25569 + ($unix_date2 / 86400);
                    $unix_date2 = ($excel_date2 - 25569) * 86400;
                    echo '<br/>'.$row21 = gmdate("Y-m-d", $unix_date2);
                }

                if($row[25]) {
                    $excel_date3 = $row[25]; //here is that value 41621 or 41631
                    $unix_date3 = ($excel_date3 - 25569) * 86400;
                    $excel_date3 = 25569 + ($unix_date3 / 86400);
                    $unix_date3 = ($excel_date3 - 25569) * 86400;
                    echo '<br/>'.$row25 = gmdate("Y-m-d", $unix_date3);
                }*/
                //exit();
                $url = base64_encode($row[0]);


                DB::table('cards')->insert(
                    array(
                        'CardId'=> 'eqwe',
                        'Suffix'=> 'eqwe',
                        'FirstName'=> 'eqwe',
                        'MiddleName'=> 'eqwe',
                        'LastName'=> 'ewqewq',
                        'Dob'=> 'ewqewq',
                        'Zender'=> 'ewrew',
                        'HouseNo'=> 'rerew',
                        'Address'=> 'eqwewq',
                        'City'=> 'rewrwe',
                        'State'=> 'rewre',
                        'Zipcode'=> 'ewrew',
                        'Email'=> 'wfefew',
                        'Mobile'=> 'fwefew',
                        'Height'=> 'fewfe',
                        'EyeColor'=> 'fwefe',
                        'NumberOfCreditHoursCompleted'=> 'fewfew',
                        'TypeOfSstIDCard'=> 'fewfew',
                        'CardType'=> null,
                        'CardStatus'=> null,
                        'CardIssuerID'=>null,
                        'IssueDate'=> null,
                        'ExpiryDate'=> null,
                        'DOBCourseProviderId'=> null,
                        'OSHACardType'=> null,
                        'OSHACardNo'=> null,
                        'OSHACardTrainerName'=> null,
                        'OSHACardCourseIssuedDate'=> null,
                        'Url'=> null,
                    )
                );
                /*try{
               Card::create([
                    'CardId'=> $row[0],
                    'Suffix'=> $row[4],
                    'FirstName'=> $row[1],
                    'MiddleName'=> $row[2],
                    'LastName'=> $row[3],
                    'Dob'=> ($row[12])?$row12:null,
                    'Zender'=> $row[15],
                    'HouseNo'=> $row[5],
                    'Address'=> $row[6],
                    'City'=> $row[7],
                    'State'=> $row[8],
                    'Zipcode'=> $row[9],
                    'Email'=> $row[10],
                    'Mobile'=> $row[11],
                    'Height'=> $row[13],
                    'EyeColor'=> $row[14],
                    'NumberOfCreditHoursCompleted'=> $row[19],
                    'TypeOfSstIDCard'=> $row[18],
                    'CardType'=> null,
                    'CardStatus'=> $row[16],
                    'CardIssuerID'=> $row[20],
                    'IssueDate'=> ($row[17])?$row17:null,
                    'ExpiryDate'=> ($row[21])?$row21:null,
                    'DOBCourseProviderId'=> $row[16],
                    'OSHACardType'=> $row[22],
                    'OSHACardNo'=> $row[23],
                    'OSHACardTrainerName'=> $row[24],
                    'OSHACardCourseIssuedDate'=> ($row[25])?$row25:null,
                    'Url'=> 'http://app.structurecompliance.com/card/'.$url,
                ]);
                }catch (\Exception $e){
                    print_r($e->getMessage());
                }*/
                print_r($row);
                exit();
               // print_r($row);
               // echo $row[0].'<br/>';
               /*Card::create([
                    'CardId'=> $row[0],
                    'Suffix'=> $row[4],
                    'FirstName'=> $row[1],
                    'MiddleName'=> $row[2],
                    'LastName'=> $row[3],
                    'Dob'=> $row[12],
                    'Zender'=> $row[15],
                    'HouseNo'=> $row[5],
                    'Address'=> $row[6],
                    'City'=> $row[7],
                    'State'=> $row[8],
                    'Zipcode'=> $row[9],
                    'Email'=> $row[10],
                    'Mobile'=> $row[11],
                    'Height'=> $row[13],
                    'EyeColor'=> $row[14],
                    'NumberOfCreditHoursCompleted'=> $row[20],
                    'TypeOfSstIDCard'=> $row[19],
                    'CardType'=> null,
                    'CardStatus'=> $row[17],
                    'CardIssuerID'=> $row[21],
                    'IssueDate'=> ($row[18])?'2021-01-13':null,
                    'ExpiryDate'=> null,
                    'DOBCourseProviderId'=> $row[16],
                    'OSHACardType'=> $row[23],
                    'OSHACardNo'=> $row[24],
                    'OSHACardTrainerName'=> $row[25],
                    'OSHACardCourseIssuedDate'=> null,
                    'Url'=> $row[27],
                ]);*/
            }
            $i++;
        }
       // print_r($row);
       // exit();
       /* return new Card([
            //
            'CardId'=> $row[0],
            'Suffix'=> $row[4],
            'FirstName'=> $row[1],
            'MiddleName'=> $row[2],
            'LastName'=> $row[3],
            'Dob'=> $row[12],
            'Zender'=> $row[15],
            'HouseNo'=> $row[5],
            'Address'=> $row[6],
            'City'=> $row[7],
            'State'=> $row[8],
            'Zipcode'=> $row[9],
            'Email'=> $row[10],
            'Mobile'=> $row[11],
            'Height'=> $row[13],
            'EyeColor'=> $row[14],
            'NumberOfCreditHoursCompleted'=> $row[20],
            'TypeOfSstIDCard'=> $row[19],
            'CardType'=> null,
            'CardStatus'=> $row[17],
            'CardIssuerID'=> $row[21],
            'IssueDate'=> null,
            'ExpiryDate'=> null,
            'DOBCourseProviderId'=> $row[16],
            'OSHACardType'=> $row[23],
            'OSHACardNo'=> $row[24],
            'OSHACardTrainerName'=> $row[25],
            'OSHACardCourseIssuedDate'=> null,
            'Url'=> $row[27],
        ]);*/
    }
}
