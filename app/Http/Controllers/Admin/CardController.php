<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\CourseImport;
use App\Models\CardClass;
use App\Models\CardMeta;
use App\Models\Course;
use App\Models\Trainer;
use Exception;
use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\CardType;
use App\Exports\UsersExport;
//use Excel;
use App\Imports\CartImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Image;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $search = $request->search;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $cardTypeSearch = $request->card_type;
        $cardDateSearch = $request->card_date;
        $query = Card::query();

        $cardType = CardType::all();

        if($search){
            $query->where('CardId','LIKE','%'.$search.'%')
                ->orWhere('FirstName','LIKE','%'.$search.'%')
                ->orWhere('LastName','LIKE','%'.$search.'%')
                ->orWhere('Email','LIKE','%'.$search.'%');
        }

        // search by card type
        if($cardTypeSearch){
            $query->whereHas('cardDetail',function ($model) use($cardTypeSearch){
               $model->where('TypeOfSstIDCard',$cardTypeSearch);
            });
        }

        if($cardDateSearch == 'expiry_date'){
            if($from_date){
                $query->whereHas('cardDetail',function ($model) use ($from_date){
                    $model->whereDate('ExpiryDate','<=',$from_date);
                });
            }

            if($to_date){
                $query->whereHas('cardDetail',function ($model) use ($to_date) {
                    $model->whereDate('ExpiryDate', '>=', $to_date);
                });
            }
        }else{
            if($from_date){
                $query->whereHas('cardDetail',function ($model) use ($from_date){
                    $model->whereDate('IssueDate','<=',$from_date);
                });
            }

            if($to_date){
                $query->whereHas('cardDetail',function ($model) use ($to_date) {
                    $model->whereDate('IssueDate', '>=', $to_date);
                });
            }
        }

        $cardData = $query->orderBy('CardId','DESC')->paginate(10);
        return view('admin/card/index',compact('cardData','cardType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin/card/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            //'CardId' => 'required',
            'FirstName' => 'required',
            'Email' => 'nullable|email',
            'image'  =>'nullable|mimes:jpeg,jpg,png|max:20480',
            'cardFiles.*'  =>'nullable|mimes:jpeg,jpg,png,gif,pdf|max:20480'
        ]);

        try{
            DB::beginTransaction();

            $data = Card::make();
          //  $data->CardId = 'vjhgj';
            $data->Suffix = $request->Suffix;
            $data->FirstName = $request->FirstName;
            $data->MiddleName = $request->MiddleName;
            $data->LastName = $request->LastName;
            $data->Dob = $request->Dob;
            $data->Zender = $request->Zender;
            $data->HouseNo = $request->HouseNo;
            $data->Address = $request->Address;
            $data->City = $request->City;
            $data->State = $request->State;
            $data->Zipcode = $request->Zipcode;
            $data->Email = $request->Email;
            $data->Mobile = $request->Mobile;
            $data->Height = $request->Height;
            $data->EyeColor = $request->EyeColor;
            $data->NumberOfCreditHoursCompleted = $request->NumberOfCreditHoursCompleted;
            $data->OSHACardType = $request->OSHACardType;
            $data->OSHACardNo = $request->OSHACardNo;
            $data->OSHACardTrainerName = $request->OSHACardTrainerName;
            $data->OSHACardCourseIssuedDate = $request->OSHACardCourseIssuedDate;
           // $data->Url = env('APP_URL').'/card/'.base64_encode('dasdsa');
            /*if($request->file('image')){
                $data->image = $request->file('image')->getRealPath();
            }*/
            /*if($request->file('image')){
                $image_file = $request->image;

                $image = Image::make($image_file);

                Response::make($image->encode('jpeg'));
                $data->image = $image;
            }*/
            if($request->cropImage) {
                $image_parts = explode(";base64,", $request->cropImage);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);

                $image = Image::make($image_base64);

                Response::make($image->encode('jpeg'));
                $data->image = $image;
            }


            $data->save();

            // upload other image
            if(@count($request->cardFiles)>0){
                collect($request->cardFiles)->each(function ($val,$key) use ($data){
                    if($val) {
                        $cardFiles = $data->cardFiles()->make();
                        $cardFiles->card_id = $data->id;
                        $cardFiles->upload_image($val);
                        $cardFiles->description = collect(request()->Filedescription)->get($key);
                        $cardFiles->save();
                    }
                });
            }

            DB::commit();

//            $Savadata = Card::find($data->id);
//            $Savadata->Url = env('APP_URL').'/card/'.base64_encode($Savadata->CardId);
//            $Savadata->save();
            
            return redirect()->route('card.index')->withSuccess('card added successfully');

        }catch (\Exception $e){
            DB::rollback();
            return back()->withErrors($e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $message = '';
        $cardData = array();
        $cardClasses = array();
        $cardIdentity = '';

        $cardIdentity = Card::findOrFail($id);

        $cardData = CardMeta::where('card_id',$id)->get();

        $cardClasses = CardClass::where('card_id',$id)->get();

        return view('admin/card/show',compact('cardIdentity','cardData','cardClasses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data = Card::findOrFail($id);
        return view('admin/card/edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // dd($request->all());
        //
        $validatedData = $request->validate([
            'FirstName' => 'required',
            'Email' => 'nullable|email',
            'image'  =>'nullable|mimes:jpeg,jpg,png|max:20480',
            'cardFilesType.*'  =>'nullable|mimes:jpeg,jpg,png,gif,pdf|max:20480'
        ]);

        try{
            DB::beginTransaction();

            $data = Card::findOrFail($id);
            $data->Suffix = $request->Suffix;
            $data->FirstName = $request->FirstName;
            $data->MiddleName = $request->MiddleName;
            $data->LastName = $request->LastName;
            $data->Dob = $request->Dob;
            $data->Zender = $request->Zender;
            $data->HouseNo = $request->HouseNo;
            $data->Address = $request->Address;
            $data->City = $request->City;
            $data->State = $request->State;
            $data->Zipcode = $request->Zipcode;
            $data->Email = $request->Email;
            $data->Mobile = $request->Mobile;
            $data->Height = $request->Height;
            $data->EyeColor = $request->EyeColor;
            $data->NumberOfCreditHoursCompleted = $request->NumberOfCreditHoursCompleted;
            $data->OSHACardType = $request->OSHACardType;
            $data->OSHACardNo = $request->OSHACardNo;
            $data->OSHACardTrainerName = $request->OSHACardTrainerName;
            $data->OSHACardCourseIssuedDate = $request->OSHACardCourseIssuedDate;
            /*if($request->file('image')){
                $image_file = $request->image;

                $image = Image::make($image_file);

                Response::make($image->encode('jpeg'));
                $data->image = $image;
            }*/

            if($request->cropImage) {
                $image_parts = explode(";base64,", $request->cropImage);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);

                $image = Image::make($image_base64);

                Response::make($image->encode('jpeg'));
                $data->image = $image;
            }


            $data->save();

            $oldImage = ($request->old_images)?:array();

            // delete all image where not found in product image
            $data->cardFiles()->whereNotIn("id", $oldImage)->each(function ($image) {
                $image->delete();
            });

            // upload other image
           /* if(@count($request->cardFiles)>0){
                collect($request->cardFiles)->each(function ($val,$key) use ($data){
                    if($val) {
                        $cardFiles = $data->cardFiles()->make();
                        $cardFiles->card_id = $data->id;
                        $cardFiles->upload_image($val);
                        $cardFiles->description = collect(request()->Filedescription)->get($key);
                        $cardFiles->save();
                    }
                });
            }*/
            if(@count($request->cardFiles)>0){
                foreach ($request->cardFiles as $key=>$allFile){
                    $cardFiles = ($data->cardFiles()->find($allFile['id']))?:$data->cardFiles()->make();
                    $cardFiles->card_id = $data->id;
                    if($allFile['file'] != null){
                        $cardFiles->upload_image(@$request->cardFilesType[$allFile['file']]);
                    }
                    $cardFiles->description = $allFile['description'];
                    $cardFiles->save();
                }
            }




            DB::commit();
            return redirect()->route('card.index')->withSuccess('card updated successfully');

        }catch (\Exception $e){
            DB::rollback();
            return back()->withErrors($e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $cardClasses = Card::findOrFail($id);
        $cardClasses->delete();

        return back()->with('success', 'Card Deleted Successfully.');
    }

    // export data
    public function CardExport(Request $request){

        ini_set('memory_limit', '-1');
        $search = ($request->search)?:'';
        $from_date = ($request->from_date)?:'';
        $to_date = ($request->to_date)?:'';
        $cardTypeSearch = ($request->card_type)?:'';
        $cardDateSearch = ($request->card_date)?:'';
        //$query = Card::query();

       /* if($search){
            $query->where('CardId','LIKE','%'.$search.'%')
                ->orWhere('FirstName','LIKE','%'.$search.'%')
                ->orWhere('LastName','LIKE','%'.$search.'%')
                ->orWhere('Email','LIKE','%'.$search.'%')
                ->orWhere('CardType','LIKE','%'.$search.'%');
        }

        if($from_date){
            $query->whereDate('ExpiryDate','>=',$from_date);
        }

        if($to_date){
            $query->whereDate('ExpiryDate','<=',$to_date);
        }
        $cardData = $query->get()->toArray();*/

        //$data = Post::get()->toArray();
       /* return Excel::create('laravelcode', function($excel) use ($cardData) {
            $excel->sheet('mySheet', function($sheet) use ($cardData)
            {
                $sheet->fromArray($cardData);
            });
        })->download('xlsx');*/

        return \Excel::download(new UsersExport($search,$from_date,$to_date,$cardTypeSearch,$cardDateSearch), 'card.xlsx');
        /*\Excel::create('file', function($excel) use ($cardData) {
            $excel->sheet('New sheet', function($sheet)use ($cardData) {
                $data[] = ['sr.no','CardId','FirstName','LastName',
                    'Email','Mobile','Height','EyeColor','CardType','IssueBy','IssueDate','ExpiryDate','DOBCourseProviderId','Url'];

                if(count($cardData) > 0){
                    foreach($cardData as $key=>$cardDatas){
                        $data[] = [++$key,
                            $cardDatas->CardId,
                            $cardDatas->FirstName,
                            $cardDatas->LastName,
                            $cardDatas->Email,
                            $cardDatas->Height,
                            $cardDatas->EyeColor,
                            $cardDatas->CardType,
                            $cardDatas->IssueBy,
                            $cardDatas->IssueDate,
                            $cardDatas->ExpiryDate,
                            $cardDatas->DOBCourseProviderId,
                            $cardDatas->Url
                        ];
                    }
                }

                $sheet->fromArray($data, null, 'A1', false,false);

            });
        })->download("xlsx");*/
       // return back()->with('success', 'Export Data Successfully.');
    }

    public function importExcel(Request $request)
    {
        /*if($request->hasFile('import_file')){
            \Excel::load($request->file('import_file')->getRealPath(), function ($reader) {
                foreach ($reader->toArray() as $key => $row) {
                    //$data['title'] = $row['title'];
                    //$data['description'] = $row['description'];
                    print_r($row);
                    /*if(!empty($data)) {
                        DB::table('post')->insert($data);
                    }
                }
            });
        }*/

        Excel::import(new CartImport, $request->file('import_file'));
        //Excel::import(new CourseImport, $request->file('import_file'));

        exit();

        Session::put('success', 'Youe file successfully import in database!!!');

        return back();
    }

    /// add or associate card completed course
    public function addCardCourse($id,Request $request){
        $course = Course::all();

        $input = $request->all();
        if($input) {
            $validatedData = $request->validate([
               // 'course' => 'required|numeric',
                'DaysAttended' => 'required|numeric',
                //'HourAttended' => 'required',
                'CompletionDays' => 'required',
                //'Status' => 'required',
            ]);
            try {

                collect($request->course)->each(function ($val, $key) use($id,$request){

                    $courseData = Course::find($val);

                    $data = CardClass::make();
                    $data->card_id = $id;
                    $data->course_id = $val;
                    $data->DaysAttended = $request->DaysAttended;
                    $data->HourAttended = $courseData->hour;
                    $data->CompletionDays = $request->CompletionDays;
                    //$data->Status = $request->Status;
                    $data->save();
                });


                // add data in card clase course table
                //$courseData = Course::whereIn('id',$request->course)->get();
                //$data->classCourses()->sync($courseData);

                return redirect()->route('card.show', $id)->withSuccess('Course added successfully.');
            } catch (Exception $e) {
                return back()->withErrors($e->getMessage())->withInput($request->all());
            }
        }
        return view('admin/card/addCardCourse',compact('course'));
    }

    // edit card course controller
    public function editCardCourse($id,Request $request){
        $course = Course::all();
        $cardClasses = CardClass::findOrFail($id);
        $input = $request->all();
        if($input) {
            $validatedData = $request->validate([
                'course' => 'required|numeric',
                'DaysAttended' => 'required|numeric',
                //'HourAttended' => 'required',
                'CompletionDays' => 'required',
                //'Status' => 'required',
            ]);
            try {

                $courseData = Course::find($request->course);

                $data = CardClass::findOrFail($id);
                $data->course_id = $request->course;
                $data->DaysAttended = $request->DaysAttended;
                $data->HourAttended = $courseData->hour;
                $data->CompletionDays = $request->CompletionDays;
               // $data->Status = $request->Status;
                $data->save();

                // add data in card clase course table
               // $courseData = Course::whereIn('id',$request->course)->get();
               // $data->classCourses()->sync($courseData);

                return redirect()->route('card.show', $cardClasses->card_id)->withSuccess('Course updated successfully.');
            } catch (Exception $e) {
                return back()->withErrors($e->getMessage())->withInput($request->all());
            }
        }

        //$selectedCourses = $cardClasses->classCourses()->pluck('courses.id')->toArray();
        return view('admin/card/editCardCourse',compact('course','cardClasses'));
    }

    // delete card course
    public function deleteCardCourse($id){
        $cardClasses = CardClass::findOrFail($id);
        $cardClasses->delete();

        return back()->with('success', 'Courses Deleted Successfully.');
    }


    // add card meta
    public function addCardType($id,Request $request){
        $carttype = CardType::all();
        $trainertype = Trainer::all();

        $input = $request->all();
        if($input) {
            $validatedData = $request->validate([
                'TypeOfSstIDCard' => 'required|numeric'
            ]);
            try {

                $data = CardMeta::make();
                $data->card_id = $id;
                $data->TypeOfSstIDCard = $request->TypeOfSstIDCard;
                $data->TrainerId = $request->TrainerId;
                $data->CardIssuerID = $request->CardIssuerID;
                $data->IssueDate = $request->IssueDate;
                $data->ExpiryDate = $request->ExpiryDate;
                $data->DOBCourseProviderId = $request->DOBCourseProviderId;
                $data->save();

                $saveUrl = CardMeta::find($data->id);
                $saveUrl->Url = env('APP_URL').'/card/'.base64_encode($saveUrl->card->CardId).'/'.$data->id;
                $saveUrl->save();

                return redirect()->route('card.show', $id)->withSuccess('Card Type Added successfully.');
            } catch (Exception $e) {
                return back()->withErrors($e->getMessage())->withInput($request->all());
            }
        }
        return view('admin/card/addCardType',compact('carttype','trainertype'));
    }

    // edit card type controller
    public function editCardType($id,Request $request){
        $carttype = CardType::all();
        $trainertype = Trainer::all();
        $data = CardMeta::findOrFail($id);
        $input = $request->all();
        if($input) {
            $validatedData = $request->validate([
                'TypeOfSstIDCard' => 'required|numeric'
            ]);
            try {

                $datas = CardMeta::findOrFail($id);
                $datas->TypeOfSstIDCard = $request->TypeOfSstIDCard;
                $datas->TrainerId = $request->TrainerId;
                $datas->CardIssuerID = $request->CardIssuerID;
                $datas->IssueDate = $request->IssueDate;
                $datas->ExpiryDate = $request->ExpiryDate;
                $datas->DOBCourseProviderId = $request->DOBCourseProviderId;
                $datas->save();

                return redirect()->route('card.show', $data->card_id)->withSuccess('Card Type updated successfully.');
            } catch (Exception $e) {
                return back()->withErrors($e->getMessage())->withInput($request->all());
            }
        }
        return view('admin/card/editCardType',compact('carttype','trainertype','data'));
    }

    // delete card type
    public function deleteCardType($id){
        $data = CardMeta::findOrFail($id);
        $data->delete();

        return back()->with('success', 'Card Type Deleted Successfully.');
    }

}
