<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Trainer::orderBy('id','DESC')->paginate(10);
        return view('admin/trainer/index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin/trainer/create');
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
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric'
        ]);

        try{
            DB::beginTransaction();

            $data = Trainer::make();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->status = $request->status;
            $data->save();


            DB::commit();
            return redirect()->route('trainer.index')->withSuccess('Trainer created successfully');

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
        $data = Trainer::find($id);
        return view('admin/trainer/edit',['data'=>$data]);
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
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric'
        ]);

        try{
            DB::beginTransaction();

            $data = Trainer::findOrFail($id);
            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->status = $request->status;
            $data->save();


            DB::commit();
            return redirect()->route('trainer.index')->withSuccess('Trainer updated successfully');

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
        $data = Trainer::findOrFail($id);
        $data->delete();

        return back()->withSuccess('Trainer deleted successfully');
    }
}
