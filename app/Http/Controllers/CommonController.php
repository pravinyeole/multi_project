<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Traits\CommonTrait;
use App\Models\User;
use App\Models\UserOtp;
use App\Models\MobileCircle;
use Auth;
use Session;
use DB;


class CommonController extends Controller
{
    //
    use CommonTrait;
    // Common function for delete records from table
    public function deleteRecord(Request $request)
    {
        $this->deleteItem($request, 'deleted_at');
    }

    // Common function for update status
    public function updateStatus(Request $request)
    {
        // $this->modifyStatus($request, 'status');
        $this->modifyStatus($request, 'User', 'user_status');

    }

 
    // Common function for impersnate
    public function loginUser(Request $request, $id)
    {
        $currentId = Auth::id();
        if (Auth::user()->getRole() == 'SA') {
            Session::put('super_admin_id', $currentId);
        }
        Auth::loginUsingId($id);
        return redirect('home');
    }

    // Exit from impersonate login
    public function getReturnLogin(Request $request, $id)
    {
        Auth::logout();
        Auth::loginUsingId($id);
        return redirect('home');
    }

   


    public function sendOTP(Request $request)
    {
            $validatedData = $request->validate([
                'mobileNumber' => 'required|numeric|digits:10',
            ]);
        
            $mobileNumber = $validatedData['mobileNumber'];

            $user = User::where('mobile_number', $mobileNumber)->first();

            if (!$user) {
                
                // Handle the case where the user does not exist
                // return redirect()->route('register');
                $mobileNumberD = str_split($mobileNumber, 4);
                $circle_data = MobileCircle::where('serial',$mobileNumberD[0])->first();
                $user = new User();
                $user->mobile_number = $mobileNumber;
                $user->user_status = 'Inactive';
                if($circle_data){
                    $user->operator = $circle_data->operator;
                    $user->circle = $circle_data->circle;
                }
                $user->save();
            }else{
                if(isset($user->operator) && empty($user->operator)){
                    $mobileNumberD = str_split($mobileNumber, 4);
                    $circle_data = MobileCircle::where('serial',$mobileNumberD[0])->first();
                    if($circle_data){
                        User::where('id',$user->id)->update(['operator'=>$circle_data->operator,'circle'=>$circle_data->circle]);
                    }
                }
            }
            // Delete the used OTP
            UserOtp::where('user_id', $user->id)->delete();
            // Generate a random 6-digit OTP
            $otp = mt_rand(100000, 999999);
        
            // Create a new UserOtp record
            $userOtp = UserOtp::create([
                'user_id' => $user->id,
                'phone_otp' => $otp,
            ]);
        // Check if the entered OTP matches the stored OTP for the user
        $userOtp = UserOtp::where('user_id', $user->id)->first();
         // After successfully sending the OTP, prepare the redirect URL
         $userId = $user->id;
         $mobileNumber =  $user->mobile_number; 
         $redirectUrl = route('show-enter-otp', ['user_id' => $userId,'mobileNumber'=>$mobileNumber]);
         return response()->json(['message' => 'OTP sent successfully',"redirect_url"=>$redirectUrl], 200);
    }

    public function resendOTP(Request $request)
    {
        $validatedData = $request->validate([
            'mobileNumber' => 'required|numeric|digits:10',
        ]);
    
        $mobileNumber = $validatedData['mobileNumber'];

        $user = User::where('mobile_number', $mobileNumber)->first();

        if (!$user) {
            // Handle the case where the user does not exist
            return response()->json(['message' => 'User does not exist'], 404);
        }
            // Delete the used OTP
            UserOtp::where('user_id', $user->id)->delete();
            // Generate a random 6-digit OTP
            $otp = mt_rand(100000, 999999);
        
            // Create a new UserOtp record
            $userOtp = UserOtp::create([
                'user_id' => $user->id,
                'phone_otp' => $otp,
            ]);
        // Check if the entered OTP matches the stored OTP for the user
        $userOtp = UserOtp::where('user_id', $user->id)->first();
        return response()->json(['message' => 'OTP resent successfully'], 200);
    }
}
