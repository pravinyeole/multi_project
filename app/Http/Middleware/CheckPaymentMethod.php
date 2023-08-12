<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Traits\CommonTrait;
use Session;

class CheckPaymentMethod
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    use CommonTrait;

    public function handle(Request $request, Closure $next)
    {
        // if($this->isAdmin() == '0'){
            if(Session::get('USER_TYPE') == 'O'){
                if (!Auth::user()->hasDefaultPaymentMethod()) {
                    return redirect('subscription');
                }
                if($this->checkEndSubscription(Auth::id())){
                    toastr()->error('Your subscription is expired.');
                    Auth::logout();
                    return redirect('login');
                }

            }
        // }
        return $next($request);
    }
}
