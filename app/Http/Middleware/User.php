<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
           
            return redirect()->route('user-login');
          }
         
    
            if( Auth::guard()->user()->user_type != 5){
               return $next($request);   
            }
    
        
            return \redirect()->route('user.index',['type'=>1]);
    
    }
}
