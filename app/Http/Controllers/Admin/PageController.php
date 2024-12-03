<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;

class PageController extends Controller
{
     public function index()
    {
        //
        $data = Page::orderBy('id','DESC')->paginate(10);
        return view('admin/page/index',['data'=>$data]);
    }
	public function create()
    {
        //
        return view('admin/page/create');
    }
	public function store(Request $request)
    {
		
        //
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',            
			'status'=>'required'
        ]);

        try{
            DB::beginTransaction();
			$slug = null;
			 if(isset($request->title) && !empty( $request->title)){
            $slug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower( $request->title)));
			}

			if(!empty($slug) && $slug != null){
				$is_slug_exist = page::where('slug', $slug)->first();
				if($is_slug_exist){
					return redirect()->route('page.create')->with('Slug already exists.');
				}
			}

            $data = Page::make();
            $data->page_title = $request->title;
            $data->slug = $slug;
            $data->page_content = $request->content;
            $data->meta_data = $request->meta_data;
            $data->meta_description = $request->meta_description;
            $data->status = $request->status;           
            $data->save();


            DB::commit();
            return redirect()->route('page.index')->withSuccess('Page created successfully');

        }catch (\Exception $e){
            DB::rollback();
            return back()->withErrors($e->getMessage())->withInput($request->all());
        }
    }
	 /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $data = Page::find($id);
	
        return view('admin/page/edit',['data'=>$data]);
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
            'content' => 'required',            
			'status'=>'required'
        ]);

        try{
            DB::beginTransaction();
			$slug = null;
			 if(isset($request->title) && !empty( $request->title)){
            $slug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower( $request->title)));
			}

			/* if(!empty($slug) && $slug != null){
				$is_slug_exist = page::where('slug', $slug)->first();
				if($is_slug_exist){
					return redirect()->route('page.create')->with('Slug already exists.');
				}
			} */
            $data = Page::findOrFail($id);
			$data->page_title = $request->title;
            $data->slug = $slug;
            $data->page_content = $request->content;
            $data->meta_data = $request->meta_data;
            $data->meta_description = $request->meta_description;
            $data->status = $request->status;
            $data->update();


            DB::commit();
            return redirect()->route('page.index')->withSuccess('Page updated successfully');

        }catch (\Exception $e){
            DB::rollback();
            return back()->withErrors($e->getMessage())->withInput($request->all());
        }
    }
	
	 public function destroy($id)
    {
        //
        $user = Page::findOrFail($id);
        $user->delete();

        return back()->withSuccess('Page deleted successfully');
    }
}
