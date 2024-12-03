<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Course::orderBy('id','DESC')->paginate(10);
        return view('admin/course/index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin/course/create');
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
            'type' => 'required',
            'hour' => 'required',
            'status'  =>'required'
        ]);

        try{
            DB::beginTransaction();

            $data = Course::make();
            $data->name = $request->name;
            $data->type = $request->type;
            $data->hour = $request->hour;
            $data->status = $request->status;
            $data->save();


            DB::commit();
            return redirect()->route('course.index')->withSuccess('Course created successfully');

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
        $data = Course::find($id);
        return view('admin/course/edit',['data'=>$data]);
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
            'type' => 'required',
            'hour' => 'required',
            'status'  =>'required'
        ]);

        try{
            DB::beginTransaction();

            $data = Course::findOrFail($id);
            $data->name = $request->name;
            $data->type = $request->type;
            $data->hour = $request->hour;
            $data->status = $request->status;
            $data->save();


            DB::commit();
            return redirect()->route('course.index')->withSuccess('Course updated successfully');

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
        $data = Course::findOrFail($id);
        $data->delete();

        return back()->withSuccess('Courses deleted successfully');
    }
}
