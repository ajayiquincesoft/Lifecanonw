<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slider = Banner::orderBy('id','DESC')->paginate(10);
        return view('admin/banner/index',['slider'=>$slider]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin/banner/create');
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
            'title' => 'required',
            'description' => 'nullable',
            'url' => 'nullable',
            'image'  =>'required|mimes:jpeg,jpg,png,gif|max:20480'
        ]);

        try{
            DB::beginTransaction();

            $slider = Banner::make();
            $slider->title = $request->title;
            $slider->description = $request->description;
            $slider->url = $request->url;
            $slider->upload_image($request->image);
            $slider->save();


            DB::commit();
            return redirect()->route('banner.index')->withSuccess('Banner created successfully');

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
        $slider = Banner::find($id);
        return view('admin/banner/edit',['slider'=>$slider]);
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
            'title' => 'required',
            'description' => 'nullable',
            'url'   => 'nullable',
            'image'  =>'nullable|mimes:jpeg,jpg,png,gif|max:20480'
        ]);

        try{
            DB::beginTransaction();

            $slider = Banner::findOrFail($id);
            $slider->title = $request->title;
            $slider->description = $request->description;
            $slider->url = $request->url;
            $slider->upload_image($request->image);
            $slider->save();


            DB::commit();
            return redirect()->route('banner.index')->withSuccess('Banner updated successfully');

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
        $slider = Banner::findOrFail($id);
        $slider->delete();

        return back()->withSuccess('Banner deleted successfully');
    }
}
