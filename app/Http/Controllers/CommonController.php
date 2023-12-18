<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Traits\CommonTrait;
use App\Models\User;
use App\Models\UserOtp;
use App\Models\UserMpin;
use App\Models\UserRole;
use App\Models\MobileCircle;
use Illuminate\Support\Facades\Auth;
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
        $resultArr = [];
        if ($request->type == "Admin") {
            $result = UserRole::where('user_id', $request->id)->count();

            if ($result != 0) {
                $res = User::where('id', $request->id)->update(['user_role' => 'A']);
                $res = UserRole::where('user_id', $request->id)->update(['role' => 'A']);
                $resultArr['title'] = 'Success';
                $resultArr['message'] = 'Status updated successfully';
            } else {
                $resultArr['title'] = 'Error';
                $resultArr['message'] = 'User Role Not Defined';
            }
        } else {
            if ($request->type == "Inactive") {
                $new_type = "Active";
            } else {
                $new_type = "Inactive";
            }
            $res = User::where('id', $request->id)->update(['user_status' => $new_type]);
            $resultArr['title'] = 'Success';
            $resultArr['message'] = 'Status updated successfully';
        }

        return json_encode($resultArr);
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


    function sendOTPAPI($mobileNum, $otp)
    {

        // $apipath = config('custom.custom.apipath');
        $apipath = 'https://web.smsgw.in/smsapi/jsonapi.jsp';
        $username = config('custom.custom.username');
        $password = config('custom.custom.password');
        $sender_id = config('custom.custom.sender_id');
        $pe_id = config('custom.custom.PE_ID');
        $template_id = config('custom.custom.template_id');
        $template_text =  'Welcome to INR Bharat Login Portal . Please use OTP ' . $otp . ' for login. OTP is valid for 5 Minutes. INR BHARAT';
        $apiBody = json_encode(['username' => $username, 'password' => $password, 'from' => $sender_id, 'to' => [$mobileNum], 'text' => $template_text, 'pe_id' => $pe_id, 'template_id' => $template_id]);
        $curl = curl_init('http://49.50.67.32/smsapi/jsonapi.jsp');
        curl_setopt_array($curl, array(
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $apiBody,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data_res = json_decode($response);
        if (isset($data_res->data->ack_id) && !empty($data_res->data->ack_id)) {
            return true;
        } else {
            return false;
        }
    }

    public function sendOTP(Request $request)
    {
        $validatedData = $request->validate([
            'mobileNumber' => 'required|numeric|digits:10',
        ]);
        $mobileNumber = $validatedData['mobileNumber'];
        if (isset($request->pagename) && $request->pagename == 'registerpage') {
            $user = User::where('mobile_number', $mobileNumber)->first();
            if ($user == null) {
                $mobileNumberD = str_split($mobileNumber, 4);
                $circle_data = MobileCircle::where('serial', $mobileNumberD[0])->first();
                $user = new User();
                $user->mobile_number = $mobileNumber;
                $user->user_status = 'Inactive';
                if ($circle_data) {
                    $user->operator = $circle_data->operator;
                    $user->circle = $circle_data->circle;
                }
                $user->save();
            }
            if($user->user_status == 'Inactive' && $user->user_fname == "" && $user->user_lname == ""){
                UserOtp::where('user_id', $user->id)->delete();
                $otp = mt_rand(100000, 999999);
                $userOtp = UserOtp::create([
                    'user_id' => $user->id,
                    'phone_otp' => $otp,
                ]);
                // Send OTP API Call
                $response  = $this->sendOTPAPI($mobileNumber, $otp);
                if ($response != 1) {
                    // $redirectUrl = route('login');
                    return response()->json(['status' => 'error', 'message' => 'Somthing Went Wrong,Please Try again'], 200);
                }
                // Check if the entered OTP matches the stored OTP for the user
                $userOtp = UserOtp::where('user_id', $user->id)->first();
                // After successfully sending the OTP, prepare the redirect URL
                // $redirectUrl = route('show-enter-otp', ['user_id' => $userId,'mobileNumber'=>$mobileNumber]);
                return response()->json(['status' => 'success', 'message' => 'OTP sent successfully', 'uid' => $user->id], 200);
            }else{
                $checkMypin = UserMpin::where('uid', $user->id)->get();
                if (count($checkMypin) == 1) {
                    $redirectUrl = route('show-enter-mpin', ['user_id' => $user->id, 'mobileNumber' => $mobileNumber, 'mpincheck' => 1]);
                    return response()->json(['message' => 'Mobile NUmber already Registerd.<br>Enter your mPIN for Login', "redirect_url" => $redirectUrl], 200);
                }
            }
        }
        $user = User::where('mobile_number', $mobileNumber)->first();
        if (!$user) {
            // Handle the case where the user does not exist
            // return redirect()->route('register');
            $redirectUrl = route('login');
            return response()->json(['message' => 'Mobile Number Not Registerd', "redirect_url" => $redirectUrl], 400);

            $mobileNumberD = str_split($mobileNumber, 4);
            $circle_data = MobileCircle::where('serial', $mobileNumberD[0])->first();
            $user = new User();
            $user->mobile_number = $mobileNumber;
            $user->user_status = 'Inactive';
            if ($circle_data) {
                $user->operator = $circle_data->operator;
                $user->circle = $circle_data->circle;
            }
            $user->save();
        } else {
            if (isset($user->operator) && empty($user->operator)) {
                $mobileNumberD = str_split($mobileNumber, 4);
                $circle_data = MobileCircle::where('serial', $mobileNumberD[0])->first();
                if ($circle_data) {
                    User::where('id', $user->id)->update(['operator' => $circle_data->operator, 'circle' => $circle_data->circle]);
                }
            }
        }

        $checkMypin = UserMpin::where('uid', $user->id)->get();
        if (count($checkMypin) == 1) {
            $redirectUrl = route('show-enter-mpin', ['user_id' => $user->id, 'mobileNumber' => $mobileNumber, 'mpincheck' => 1]);
            return response()->json(['message' => 'Mobile NUmber already Registerd.<br>Enter your mPIN for Login', "redirect_url" => $redirectUrl], 200);
        }
        $now = Carbon::now();
        $otpvalidtime = config('custom.custom.otpvalidtime');
        $diffMinutes = 100;
        $previousOTP = UserOtp::where('user_id', $user->id)->first();
        if (isset($previousOTP->created_at) && !empty($previousOTP->created_at)) {
            $created_at = Carbon::parse($previousOTP->created_at);
            $diffMinutes = $created_at->diffInMinutes($now);
        }
        if ($diffMinutes <= $otpvalidtime) {
            $redirectUrl = route('show-enter-otp', ['user_id' => $user->id, 'mobileNumber' => $mobileNumber]);
            return response()->json(['message' => 'OTP sent successfully', "redirect_url" => $redirectUrl], 200);
        }
        UserOtp::where('user_id', $user->id)->delete();
        $otp = mt_rand(100000, 999999);
        $userOtp = UserOtp::create([
            'user_id' => $user->id,
            'phone_otp' => $otp,
        ]);
        // Send OTP API Call
        $response  = $this->sendOTPAPI($mobileNumber, $otp);
        if ($response != 1) {
            $redirectUrl = route('login');
            return response()->json(['message' => 'Somthing Went Wrong,Please Try again', "redirect_url" => $redirectUrl], 200);
        }
        // Check if the entered OTP matches the stored OTP for the user
        $userOtp = UserOtp::where('user_id', $user->id)->first();
        // After successfully sending the OTP, prepare the redirect URL
        $userId = $user->id;
        $mobileNumber =  $user->mobile_number;
        $redirectUrl = route('show-enter-otp', ['user_id' => $userId, 'mobileNumber' => $mobileNumber]);
        return response()->json(['message' => 'OTP sent successfully', "redirect_url" => $redirectUrl], 200);
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
    public function verifyOTP(Request $request)
    {
        $userOtp = UserOtp::where('user_id', $request->userid)->where('phone_otp', $request->resetOtp)->first();
        if ($userOtp) {
            return response()->json(['status' => 'success', 'message' => 'OTP verified successfully'], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Sorry, Incorrect OTP!'], 201);
        }
    }
    public function verifyUPI(Request $request)
    {
        if (isset($request->userUPI) && $request->userUPI != null) {
            $curl = curl_init();
            $apiUrl = 'https://api.cashfree.com/api/v2/upi/validate/' . $request->userUPI;
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
                    'X-Client-Id: ' . config('custom.custom.cashfree_key'),
                    'X-Client-Secret: ' . config('custom.custom.cashfree_secret')
                ),
            ));
            $response = curl_exec($curl);
            if ($response === false) {
                return response()->json(['status' => 'error', 'message' => curl_error($curl)], 201);
            } else {
                $response = json_decode($response);
            }
            if (isset($response->status) && $response->status == 'OK' && isset($response->valid) && $response->valid == 1) {
                // $user->upi = $request->my_upi_id;
                // $response->vpa; // If Valid will get this details
                // $response->status; // If Valid will get this details
                // $response->valid; // If Valid will get this details
                // $response->name; // If Valid will get this details
                return response()->json(['status' => 'success', 'message' => 'UPI verified succesfully.'], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Invalid UPI,please provide Valid UPI'], 201);
            }
            curl_close($curl);
        }
    }
}
