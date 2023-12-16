<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use GuzzleHttp\Client;
use App\Mail\TwoFactor;
use App\Models\InsuranceAgency;
use App\Models\Notification;
use App\Models\UserReferral;
use AWS;
use Mail;
use Config;
use App\Traits\CommonTrait;

use Illuminate\Support\Facades\Validator;


// use App\Traits\TwoFactorTrait;
use Session;

class TwoFactorController extends Controller
{
    use CommonTrait;
    // use TwoFactorTrait;
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function updateProfile()
    {
        $user = User::where('id', Auth::user()->id)->first();
        $paymentModes = ['upi', 'google_pay', 'phone_pay', 'paytm']; // Define your payment modes here
        $decodedPaymentModes = json_decode($user->payment_modes, true);

        if (!is_array($decodedPaymentModes)) {
            $decodedPaymentModes = [];
        }

        if(Auth::user()->user_role != 'S' ){
            $levelupid = Auth::user()->id;
            for($i=0;$i<1000;$i++){
                $res = $this->findMyAdmin($levelupid);
                if(isset($res) && is_numeric($res)){
                    $levelupid = $res;
                }else{
                    $myadminSlug = $res;
                    break;   
                }
            }
            // $cryptmobile= Crypt::encryptString(Auth::user()->mobile_number);
            // $cryptSlug= Crypt::encryptString($myadminSlug);
            $data['myadminSlug']= $myadminSlug;
            $cryptmobile= base64_encode(Auth::user()->mobile_number);
            $cryptSlug= base64_encode($myadminSlug);
            $data['cryptUrl']= url('/register/').'/'.$cryptmobile.'/'.$cryptSlug;
        }else{
            $levelupid = Auth::user()->id;
            for($i=0;$i<1000;$i++){
                $res = $this->findMyAdmin($levelupid);
                if(isset($res) && is_numeric($res)){
                    $levelupid = $res;
                }else{
                    $myadminSlug = $res;
                    break;   
                }
            }
            // $cryptmobile= Crypt::encryptString(Auth::user()->mobile_number);
            // $cryptSlug= Crypt::encryptString($myadminSlug);
            $data['myadminSlug']= $myadminSlug;
            $cryptmobile= base64_encode(Auth::user()->mobile_number);
            $cryptSlug= base64_encode($myadminSlug);
            $data['cryptUrl']= url('/register/').'/'.$cryptmobile.'/'.$cryptSlug;
        }

        return view('/auth/updateProfile', compact('user', 'paymentModes', 'decodedPaymentModes','data'));
    }



    // public function updateProfileAction(Request $request){
    //     try {
    //         $input = $request->all();
    //         $logged_user = Auth::user();
    //         unset($input['_token']);
    //         $checkrecords =  User::where('email',$logged_user->email)->whereNull('deleted_at')->get();
    //         if(count($checkrecords) > 1){
    //             $user = User::where('id',Auth::id())->update($input);
    //             if($user){
    //                 return $this->checkMfa();
    //             }
    //         }
    //         else{
    //             $checkExist = User::where('user_phone_no',$input['user_phone_no'])->whereNull('deleted_at')->get();
    //             if($checkExist->isEmpty()) {
    //                 $user = User::where('id',Auth::id())->update($input);
    //                 if($user){
    //                     return $this->checkMfa();
    //                 }
    //             }else{
    //                 toastr()->error('User already exist with this Phone Number.');
    //                 return redirect('two-fact-auth/updateProfile')->withInput($request->input());
    //             }
    //         }   

    //     }catch(\Exception $e){
    //         toastr()->error('Something went wrong !!');
    //         return redirect('two-fact-auth/updateProfile')->withInput($request->input());
    //     }
    // }

    public function twoFactor()
    {
        $mfa_type = $this->checkMfaType();
        return view('/auth/twoFactor', compact('mfa_type'));
    }

    public function verifyOtp(Request $request)
    {
        try {
            $user = Auth::user();
            $mfa_type  = $this->checkMfaType();
            $input = $request->all();
            unset($input['_token']);
            if (isset($input['email_otp']) && $input['email_otp'] != '' && isset($input['phone_otp']) && $input['phone_otp'] != '') {

                if ($input['email_otp'] != $user->email_otp) {
                    toastr()->error('A one-time password you entered does not match !!');
                    return redirect('two-fact-auth/twoFactor')->withInput($request->input());
                }
                if ($input['phone_otp'] != $user->phone_otp) {
                    toastr()->error('A one-time password you entered does not match !!');
                    return redirect('two-fact-auth/twoFactor')->withInput($request->input());
                }
            } else if (isset($input['email_otp']) && $input['email_otp'] != '') {

                if ($input['email_otp'] != $user->email_otp) {
                    toastr()->error('A one-time password you entered does not match !!');
                    return redirect('two-fact-auth/twoFactor')->withInput($request->input());
                }
            } else if (isset($input['phone_otp']) && $input['phone_otp'] != '') {
                if ($input['phone_otp'] != $user->phone_otp) {
                    toastr()->error('A one-time password you entered does not match !!');
                    return redirect('two-fact-auth/twoFactor')->withInput($request->input());
                }
            }
            $user->resetTwoFactorCode($mfa_type);
            return redirect()->route('home');
        } catch (\Exception $e) {
            toastr()->error('Something went wrong !!');
            return redirect('two-fact-auth/twoFactor')->withInput($request->input());
        }
    }

    public function resend(Request $request)
    {
        try {
            $mfa_type  = $this->checkMfaType();
            $user = Auth::user();
            $count = $user->count_of_max_otp + 1;
            if ($user->count_of_max_otp >= config('constants.max_no_of_time')) {
                toastr()->error('You’ve reached the maximum limit of resend one-time password. Please logout and try again !!');
                return redirect('two-fact-auth/twoFactor')->withInput($request->input());
            } else {
                User::where('id', Auth::id())->update(['count_of_max_otp' => $count]);
                $user->generateTwoFactorCode($mfa_type);

                $userData = User::where('id', Auth::id())->first();

                if ($mfa_type == "both") {
                    $this->sendOtpMail();
                    $this->sendSNS($userData->user_phone_no);
                } else if ($mfa_type == "email") {
                    $this->sendOtpMail();
                } else if ($mfa_type == "phone") {
                    $this->sendSNS($userData->user_phone_no);
                }

                if ($mfa_type == "both") {
                    toastr()->success('A one-time password has been sent again to your email address and phone number. !!');
                } else if ($mfa_type == "email") {
                    toastr()->success('A one-time password has been sent again to your email address !!');
                } else if ($mfa_type == "phone") {
                    toastr()->success('A one-time password has been sent again to your phone number. !!');
                }
                return redirect('two-fact-auth/twoFactor')->withInput($request->input());
            }
        } catch (\Exception $e) {
            toastr()->error('Something went wrong !!');
            return redirect('two-fact-auth/twoFactor')->withInput($request->input());
        }
    }

    public function updateProfileAction(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'user_fname' => 'required|string|max:100',
                'user_lname' => 'required|string|max:100',
                'user_upi' => 'required|min:10',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $user = Auth::user();
            // Update the user profile fields
            $user->user_fname = $request->input('user_fname');
            $user->user_lname = $request->input('user_lname');
            // CashFree API Validation
            $curl = curl_init();
            $apiUrl = 'https://api.cashfree.com/api/v2/upi/validate/'.$request->input('user_upi');
            curl_setopt_array($curl, array(
                CURLOPT_URL => $apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Cache-Control: no-cache',
                    'X-Client-Id: '.config('custom.custom.cashfree_key'),
                    'X-Client-Secret: '.config('custom.custom.cashfree_secret')
                ),
            ));
            $response = curl_exec($curl);
            if ($response === false) {
                echo 'Curl error: ' . curl_error($curl);
                return redirect()->back()->with('error', curl_error($curl));
            } else {
                $response = json_decode($response);
            }
            if (isset($response->status) && $response->status == 'OK' && isset($response->valid) && $response->valid == 1) {
                $user->upi = $request->input('user_upi');
                // $response->vpa; // If Valid will get this details
                // $response->status; // If Valid will get this details
                // $response->valid; // If Valid will get this details
                // $response->name; // If Valid will get this details
            }
            curl_close($curl);
            // Save the changes
            $user->save();
            // Return a success message
            return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function findMyAdmin($uid){
        if(Auth::user()->user_role == 'S'){
            return User::where('id',$uid)->first()->user_slug;
        }
        $adminslug = UserReferral::where('user_id',$uid)->first();
        $checkrole = (Auth::user()->user_role == 'U' || Auth::user()->user_role == 'L') ? 'A' : 'S';
        if($adminslug){
            $levl2 = User::where('user_slug',$adminslug->admin_slug)->where('user_role',$checkrole)->first();
            if($levl2){
                return $levl2->user_slug;
            }else{
                $adminslugl2 = User::where('mobile_number',$adminslug->referral_id)->first();
                if(isset($adminslugl2) && $adminslugl2->user_role == 'A'){
                    return $adminslugl2->user_slug;
                }else{
                    return $adminslugl2->id;
                }
            }
        }
    }
}
