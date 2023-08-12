<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class EnsureUser
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
        if(Session::get('USER_TYPE') == 'O' || Session::get('USER_TYPE') == 'A' || Session::get('USER_TYPE') == 'SA'){
            
                return redirect('home');
            
        }
        return $next($request);
    }
}
