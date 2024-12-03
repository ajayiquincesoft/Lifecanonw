<?php

namespace App\Imports;

use App\Models\Course;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class CourseImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        //
        $i=0;
        foreach ($rows as $row)
        {

            if(isset($row[0])  && $i !== 0 ){
                //print_r($row);
                // echo $row[0].'<br/>';
                Course::create([
                    'name'=> $row[1],
                    'type'=> $row[2],
                    'hour'=> $row[3],
                    'status'=> 1,

                ]);
            }
            $i++;
        }
    }
}
