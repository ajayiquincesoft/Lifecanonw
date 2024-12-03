<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Template;
use App\Mail\DbTemplateMail;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;
use Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		
		$type=$request->type;
        //
        $data = User::orderBy('id','DESC')->where('id','!=',1)->where('user_type','=',$type)->paginate(10);
        return view('admin/user/index',['data'=>$data,'type'=>$type]);
    }
	/* public function viewClientUser()
    {
        //
        $data = User::orderBy('id','DESC')->where('id','!=',1)->where('user_type','=',2)->paginate(10);
        return view('admin/user/index',['data'=>$data]);
    } */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin/user/create');
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
		$type=$request->user_type;
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
			'confirm_password' => 'required|same:password|min:6',
			'user_type'=>'required'
        ]);

        try{
            DB::beginTransaction();

            $data = User::make();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->user_type = $request->user_type;
           $data->status=1;
            $data->password = Hash::make($request->password);
            $data->save();


            DB::commit();
            return redirect()->route('user.index',['type'=>$type])->withSuccess('User created successfully');

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
         $data = User::find($id);
	
        return view('admin/user/edit',['data'=>$data]);
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
		$type=$request->user_type;
        //
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
			'user_type'=>'required',
			'status'=>'required'
        ]);

        try{
            DB::beginTransaction();

            $data = User::findOrFail($id);
			$data->name = $request->name;
            $data->email = $request->email;
            $data->user_type = $request->user_type;
            $data->status = $request->status;
			
			if (!Hash::check($request->get('password'), $data->password) && $request->get('password') !='') {
                    $data->password = Hash::make($request->get('password'));
					
                      
            }
            $data->save();


            DB::commit();
            return redirect()->route('user.index',['type'=>$type])->withSuccess('User updated successfully');

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
		
        $user = User::findOrFail($id);
		$user->status = 0;
		$user->save();
        //$user->delete();
        return back()->withSuccess('User deleted successfully');
    }

    // get admin profile
    public function  profile(Request $request){
        $input = $request->all();
        if($input){
            $validatedData = $request->validate([
                'name'  =>'required',
                'email'  =>'required|email'
            ]);
            try{

                $user = User::findOrFail(Auth::User()->id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->save();

                return back()->with('success','Profile updated successfully');
            }catch (\Exception $e){
                return back()->withErrors($e->getMessage());
            }

        }

        return view('admin/profile');
    }

    public function updatePassword(Request $request){
        $input = $request->all();
        if($input) {
            $validatedData = $request->validate([
                'currentPassword' => 'required',
                'newPassword' => 'required|min:8',
                'confirmPassword' => 'required|same:newPassword',
            ]);
            try {

                $user = User::find(Auth::User()->id);
                if (Hash::check($request->get('currentPassword'), $user->password)) {
                    $user->password = Hash::make($request->get('newPassword'));
                    $user->save();

                    return back()->with('success', 'Admin Password change successfully');
                }
                return back()->withErrors('Old password not match')->withInput($request->all());
            } catch (Exception $e) {
                return back()->withErrors($e->getMessage())->withInput($request->all());
            }
        }

        return view('admin/change-password');
    }

    // update setting data
    public function setting(Request $request){
        $input = $request->all();
        $setting = Setting::all();
        if($input) {
            $validatedData = $request->validate([
                'phone' => 'required',
                'email' => 'required|email'
            ]);
            try {
                $data = $request->except('_method', '_token');
                collect($data)->each(function ($val,$key){
                    $setting = Setting::where('name',$key)->first();
                    $setting->value = $val;
                    $setting->save();
                });

                return back()->with('success', 'Data updated successfully');
            } catch (Exception $e) {
                return back()->withErrors($e->getMessage())->withInput($request->all());
            }
        }

        return view('admin/setting',['setting'=>$setting]);
    }


    /// return twilio sms logs
    function smsLogs(Request $request){

        // Find your Account Sid and Auth Token at twilio.com/console
        // and set the environment variables. See http://twil.io/secure
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);
        $searchData = array();
        if($request->search){
            $searchData = array(
                //"dateSent" => new \DateTime('2016-08-31T00:00:00Z'),
                //"from" => "+15017122661",
                "to" => $request->search
            );
        }
        $calls = $twilio->messages
            ->read($searchData, 200);

       // print_r($calls);
        //exit();
        return view('admin/sms-logs',['smslogs'=>$calls]);
    }
	function viewReportedUser($type){
		$data = User::where("status",'=','2')->where('user_type','=',$type)->paginate(10);
	
	
        return view('admin/user/reported',['data'=>$data,'type'=>$type]);
	}
	
	function testEmail(){
		
		 $body = Template::find(1)->content;
		 $data=array('name'=>'ajeet','lastname'=>'singh');
		foreach($data as $key=>$parameter)
     {
          $body = str_replace('{{'.$key.'}}', $parameter, $body); // this will replace {{username}} with $data['username']
     }
    echo  Mail::to('ajeet.iquincesoft@gmail.com')->send(new DbTemplateMail($body));
	}
}
