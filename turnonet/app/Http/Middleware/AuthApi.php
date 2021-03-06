<?php

namespace App\Http\Middleware;
use Closure;

class AuthApi 
{

   public function handle($request, Closure $next)
   {
       

       if (isset($_SERVER['HTTP_ORIGIN'])) {

        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
        header("Access-Control-Allow-Headers: x-api-key");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    }
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

     

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
        header("Access-Control-Allow-Headers: x-api-key");
        header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
    }
    
    return $next($request);

    
}


}
