<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;
use Redirect;

class RoleMiddleware
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {        
        if (Auth::guard('admin')->guest()){
           
            return redirect('login');
        }else{
            if(Auth::guard('admin')->User()->level=='2'){

                $permission= DB::table('permissions')->where('role',Auth::guard('admin')->User()->rol)->where('submodule',$role)->where('status','1')->get();

                if (count($permission)==0){

                    return redirect('dashboard');
                }else{
                   
                   return $next($request);
               }

           }
       }
  
        return $next($request);
    }


}

?>