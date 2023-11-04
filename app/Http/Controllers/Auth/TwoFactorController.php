<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Mail\TwoFactor;
use App\Models\InsuranceAgency;
use App\Models\Notification;
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
    public function __construct(){
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
    
        return view('/auth/updateProfile', compact('user', 'paymentModes', 'decodedPaymentModes'));
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

    public function twoFactor(){
        $mfa_type = $this->checkMfaType();
        return view('/auth/twoFactor',compact('mfa_type'));
    }

    public function verifyOtp(Request $request)
    {
        try{
            $user = Auth::user();
            $mfa_type  = $this->checkMfaType();
            $input = $request->all();
            unset($input['_token']);
            if( isset($input['email_otp']) && $input['email_otp'] !='' && isset($input['phone_otp']) && $input['phone_otp'] !=''){

                if($input['email_otp'] != $user->email_otp ){
                    toastr()->error('A one-time password you entered does not match !!');
                    return redirect('two-fact-auth/twoFactor')->withInput($request->input());
                }
                if($input['phone_otp'] != $user->phone_otp ){
                    toastr()->error('A one-time password you entered does not match !!');
                    return redirect('two-fact-auth/twoFactor')->withInput($request->input());
                }
            }
            else if( isset($input['email_otp']) && $input['email_otp'] !=''){

                if($input['email_otp'] != $user->email_otp ){
                    toastr()->error('A one-time password you entered does not match !!');
                    return redirect('two-fact-auth/twoFactor')->withInput($request->input());
                }
            }
            else if(isset($input['phone_otp']) && $input['phone_otp'] !=''){
                if($input['phone_otp'] != $user->phone_otp ){
                    toastr()->error('A one-time password you entered does not match !!');
                    return redirect('two-fact-auth/twoFactor')->withInput($request->input());
                }
            }
            $user->resetTwoFactorCode($mfa_type);
            return redirect()->route('home');
        }catch(\Exception $e){
            toastr()->error('Something went wrong !!');
            return redirect('two-fact-auth/twoFactor')->withInput($request->input());
        }
    }

    public function resend(Request $request)
    {
        try{
            $mfa_type  = $this->checkMfaType();
            $user = Auth::user();
            $count = $user->count_of_max_otp+1;
            if($user->count_of_max_otp >= config('constants.max_no_of_time')){
                toastr()->error('You’ve reached the maximum limit of resend one-time password. Please logout and try again !!');
                return redirect('two-fact-auth/twoFactor')->withInput($request->input());
            }else{
                User::where('id', Auth::id())->update(['count_of_max_otp' => $count]);
                $user->generateTwoFactorCode($mfa_type);

                $userData = User::where('id',Auth::id())->first();

                if($mfa_type == "both" ){
                    $this->sendOtpMail();
                    $this->sendSNS($userData->user_phone_no);
                }else if($mfa_type == "email"){
                    $this->sendOtpMail();
                }else if($mfa_type == "phone"){
                    $this->sendSNS($userData->user_phone_no);
                }

                if($mfa_type == "both" ){
                    toastr()->success('A one-time password has been sent again to your email address and phone number. !!');
                }else if($mfa_type == "email"){
                    toastr()->success('A one-time password has been sent again to your email address !!');
                }else if($mfa_type == "phone"){
                    toastr()->success('A one-time password has been sent again to your phone number. !!');
                }
                return redirect('two-fact-auth/twoFactor')->withInput($request->input());
            }
        }catch(\Exception $e){
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
                'email' => 'required|email',
                'user_upi' => 'required|min:10|max:15',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            // Get the authenticated user
            $user = Auth::user();
    
            // Update the user profile fields
            $user->user_fname = $request->input('user_fname');
            $user->user_lname = $request->input('user_lname');
            $user->email = $request->input('email');
            $user->upi = $request->input('user_upi');
            // Save the changes
            $user->save();
            // Return a success message
            return redirect()->back()->with('success','Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error','Something went wrong!');
        }
    }
    
}
