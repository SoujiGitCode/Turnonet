<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;
use Redirect;

class VerifyShift
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {        
        if (Auth::guard('user')->User()->level=='2' && Auth::guard('user')->User()->rol=='1'){
           
            return redirect('agenda');
        }

        return $next($request);
    }


}

?>