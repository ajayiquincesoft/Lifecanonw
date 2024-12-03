<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;

class FeedbackController extends Controller
{
     public function index()
    {
        //
        $data = Feedback::orderBy('id','DESC')->paginate(10);
        return view('admin/feedback/index',['data'=>$data]);
    }
	
	 public function destroy($id)
    {
        //
        $user = Feedback::findOrFail($id);
        $user->delete();

        return back()->withSuccess('Feedback deleted successfully');
    }
}
