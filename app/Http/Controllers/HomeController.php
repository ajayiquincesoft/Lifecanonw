<?php

namespace App\Http\Controllers;

use App\Models\CardMeta;
use App\Models\Certs;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Card;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       $userCount = 1;
       $certsCount =1;
       $totalCard = 1;
       return redirect('/Admin/user?type=1');
    //    return view('admin.dashboard',compact(['userCount','certsCount','totalCard']));
    }
}
