<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HttpsProtocol
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
        $header = $request->header();
        if(isset($header['x-forwarded-proto']) && $header['x-forwarded-proto'][0] == 'http' && App::environment() == 'prod'){
            return redirect()->secure($request->getRequestUri());
        }


        return $next($request); 
    }
}
