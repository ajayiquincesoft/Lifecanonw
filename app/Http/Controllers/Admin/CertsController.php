<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Certs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CertsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $datas = Certs::paginate(10);
        return view('admin/certs/index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin/certs/create');
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
        //
        $validatedData = $request->validate([
            'FirstName' => 'required',
            'Email' => 'nullable|email',
        ]);

        try{
            DB::beginTransaction();

            $data = Certs::make();
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
            $data->save();

            DB::commit();


            return redirect()->route('certs.index')->withSuccess('certs added successfully');

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
        $data = Certs::findOrFail($id);
        return view('admin/certs/edit',compact('data'));
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
        //
        $validatedData = $request->validate([
            'FirstName' => 'required',
            'Email' => 'nullable|email',
        ]);

        try{
            DB::beginTransaction();

            $data = Certs::findOrFail($id);
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

            $data->save();


            DB::commit();
            return redirect()->route('certs.index')->withSuccess('certs updated successfully');

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
        $data = Certs::findOrFail($id);
        $data->delete();

        return back()->with('success', 'Certs Deleted Successfully.');
    }

    // copy data certs to cards table
    public function Copy($id){
        $request = Certs::findOrFail($id);

        try{
            $data = Card::make();
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

            $data->save();

            $request->delete();

            DB::commit();
            return back()->withSuccess('Convert to Cards data successfully');

        }catch (\Exception $e){
            DB::rollback();
            return back()->withErrors($e->getMessage());
        }
    }
}
