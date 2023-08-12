<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\InsuranceAgency;

use App\Traits\CommonTrait;

use Config;
use Session;

class TwoFactorAuth
{
    use CommonTrait;
    public function handle(Request $request, Closure $next)
    {

     if(Session::get('USER_TYPE')!= 'A' && Session::get('USER_TYPE')!= 'SA'){

         //check country code & phone no empty or not
        if(Auth::user()->user_country_code == '' || Auth::user()->user_phone_no == ''){

            return redirect('two-fact-auth/updateProfile');
        }

        if(Session::get('USER_TYPE') == 'O'){
            //Check agency admin or not
            // if(!$this->isAdmin()){
            //     $is_mfa = Auth::user()->insurance_agencies->is_mfa;
            // }else{
                $insurance_agency_id = Auth::user()->getInsuranceAgencyID();
                $insurance = InsuranceAgency::where('insurance_agency_id',$insurance_agency_id)->first();
                $is_mfa = $insurance->is_mfa;
            // }
        }else if(Session::get('USER_TYPE') == 'T' || Session::get('USER_TYPE') == 'U'){
            $insurance_agency_id = Auth::user()->getInsuranceAgencyID();
            $insurance = InsuranceAgency::where('insurance_agency_id',$insurance_agency_id)->first();
            $is_mfa =  $insurance->is_mfa;
        }
        //Check MFA Yes
        if(isset($is_mfa) == 'Y'){
            if(Auth::user()->email_otp != null || Auth::user()->phone_otp != null){
                return redirect('two-fact-auth/twoFactor');
            }
        }

      }

        return $next($request);
    }

}
