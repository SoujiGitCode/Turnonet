<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use redirect;

class User { /**
 * Handle an incoming request.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \Closure  $next
 * @return mixed
 */

    public function handle($request, Closure $next, $guard = 'user') {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return redirect('iniciar-sesion');
            } else {
                return redirect('iniciar-sesion');
            }
        }
        return $next($request);
    }

}
