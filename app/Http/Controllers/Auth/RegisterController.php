<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UserReferral;
use App\Models\UserRole;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\MobileCircle;
use App\Models\UserMpin;
use App\Models\UserOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'my_upi_id' => ['required', 'string', 'max:13'],
            'mobile_number' => ['required', 'numeric', 'min:10'],
            'referal_code' => ['nullable', 'string', 'max:255'],
            'admin_referal_code' => ['required']
        ], [
            'first_name.required' => 'The first name field is required.',
            'last_name.required' => 'The last name field is required.',
        ]);
    }

    // /**
    //  * Create a new user instance after a valid registration.
    //  *
    //  * @param  array  $data
    //  * @return \App\Models\User
    //  */
    // protected function create(array $data)
    // {
    //     return User::create([
    //         'user_fname' => $data['user_fname'],
    //         'user_lname' => $data['user_lname'],
    //         'mobile_number'=>$data['mobile_number'],
    //         'email' => $data['email'],
    //         'created_at' => round(microtime(true) * 1000),
    //         'modified_at' => round(microtime(true) * 1000),
    //     ]);
    // }

    /**
     * register  function
     *
     * @param string|null $invitationID
     * @return void
     */
    public function showRegistrationForm(string $mobile_num = null, string $invitationID = null)
    {
        $invitation_ID = '';
        $invitation_mobile = '';
        if ((isset($mobile_num) && $mobile_num != null && strlen($mobile_num) < 30) && (isset($invitationID) && $invitationID != null && strlen($mobile_num) < 30)) {
            $invitation_ID = base64_decode($invitationID);
            $invitation_mobile = base64_decode($mobile_num);
        }
        if ((isset($invitation_mobile) && strlen($invitation_mobile) == 10) && (isset($invitation_ID) && strlen($invitation_ID) == 6)) {
            // $invitation_ID = Crypt::decryptString($invitationID);
            // $invitation_mobile = Crypt::decryptString($mobile_num);
            // $invitation_ID = base64_decode($invitationID);
            // $invitation_mobile = base64_decode($mobile_num);
            // $invitation_mobile = $mobile_num;
            // $invitation_ID = $invitationID;
            $adminSlug = User::where('user_slug', $invitation_ID)->count();
            $userMobile = User::where('mobile_number', $invitation_mobile)->count();
            if ($adminSlug && $userMobile) {
                $invitation_mobile = $invitation_mobile;
            } else {
                return redirect('login')->with('error', 'Invalid Refferal Code!');
            }
            return view('auth.register', compact('invitation_ID', 'invitation_mobile'));
        }
        if ((isset($mobile_num) && strlen($mobile_num) > 30) && (isset($invitationID) && strlen($invitationID) > 30)) {
            $invitation_ID = Crypt::decryptString($invitationID);
            $invitation_mobile = Crypt::decryptString($mobile_num);
            $adminSlug = User::where('user_slug', $invitation_ID)->count();
            $userMobile = User::where('mobile_number', $invitation_mobile)->count();
            if ($adminSlug && $userMobile) {
                $invitation_mobile = $invitation_mobile;
            } else {
                return redirect('login')->with('error', 'Invalid Refferal Code!');
            }
            return view('auth.register', compact('invitation_ID', 'invitation_mobile'));
        }
        return redirect('login');
    }

    public function showEnterOtp(Request $request, $user_id, $mobileNumber)
    {
        return view('auth.login_otp', compact('user_id', 'mobileNumber'));
    }
    public function showEnterMpin(Request $request, $user_id, $mobileNumber)
    {
        return view('auth.login_mpin', compact('user_id', 'mobileNumber'));
    }
    public function showResetMpin(Request $request, $user_id, $mobileNumber)
    {
        UserOtp::where('user_id', $user_id)->delete();
        $otp = mt_rand(100000, 999999);
        $userOtp = UserOtp::create([
            'user_id' => $user_id,
            'phone_otp' => $otp,
        ]);
        // $apipath = config('custom.custom.apipath');
        $apipath = 'https://web.smsgw.in/smsapi/jsonapi.jsp';
        $username = config('custom.custom.username');
        $password = config('custom.custom.password');
        $sender_id = config('custom.custom.sender_id');
        $pe_id = config('custom.custom.PE_ID');
        $template_id = config('custom.custom.template_id');
        $template_text =  'Welcome to INR Bharat Login Portal . Please use OTP ' . $otp . ' for login. OTP is valid for 5 Minutes. INR BHARAT';
        $apiBody = json_encode(['username' => $username, 'password' => $password, 'from' => $sender_id, 'to' => [$mobileNumber], 'text' => $template_text, 'pe_id' => $pe_id, 'template_id' => $template_id]);
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
            return view('auth.reset_mpin', compact('user_id', 'mobileNumber'));
        } else {
            return redirect()->back()->with('error','OTP send Failed this time, please try again.');
        }
    }
    public function updateMpin(Request $request)
    {
        $otpOne = implode($request->otp);
        $otpTwo = implode($request->otpTwo);
        if ($otpOne == $otpTwo) {
            UserMpin::updateOrCreate(['uid' => $request->id], ['mpin' => $otpOne]);
            return redirect()->route('login')->with('success', 'mPIN Reset susscessfully.');
        } else {
            return redirect()->back()->with('error', 'Somthing went wrong,Please try again.');
        }
    }

    public function generateUserSlug($fname, $lname, $mn, $check = '')
    {
        if (isset($check) && $check >= 1) {
            $slug = rand(0001, 9999);
            $user_slug = substr($fname, 0, 1) . substr($lname, 0, 1) . $slug;
            $prvCheck = User::where('user_slug', $user_slug)->count();
        } else {
            $user_slug = substr($fname, 0, 1) . substr($lname, 0, 1) . substr($mn, 6, 9);
            $prvCheck = User::where('user_slug', $user_slug)->count();
        }
        if ($prvCheck > 0) {
            return $this->generateUserSlug($fname, $lname, $mn, $prvCheck);
        }
        return strtoupper($user_slug);
    }
    public function register(Request $request)
    {
        // $this->validator($request->all())->validate();
        if (isset($request->mobile_number) && !is_numeric($request->mobile_number) && strlen($request->mobile_number) != 10) {
            return redirect()->back()->withInput()->with('error', 'Invalid Mobile Number.');
        }
        $user = User::where('mobile_number', $request->mobile_number)->first();
        $admin = User::where('user_slug', $request->admin_referal_code)->whereIn('user_role', ['A', 'S'])->first();
        if (!$admin) {
            return redirect()->back()->withInput()->with('error', 'Admin Referral Code does not exist');
        } else {
            // Check if referral ID exists in the user table
            $referralUser = User::where('mobile_number', $request->referal_code)->first();
            if (!$referralUser) {
                toastr()->error('Referral does not exist');
                return redirect()->back()->withInput();
            }
            if ($user == null) {
                $mobileNumberD = str_split($request->mobile_number, 4);
                $circle_data = MobileCircle::where('serial', $mobileNumberD[0])->first();
                $user = new User();
                $user->mobile_number = (int)$request->mobile_number;
                $user->user_status = 'Active';
                if ($circle_data) {
                    $user->operator = $circle_data->operator;
                    $user->circle = $circle_data->circle;
                }
                $user->save();
                $user = User::where('mobile_number', $request->mobile_number)->first();
            }
            $user->user_fname = $request->user_fname;
            $user->user_lname = $request->user_lname;
            if (isset($user->email) && $user->email == null) {
                $user->email = $request->user_fname . $request->mobile_number . '@' . 'yahoo.com';
            }
            if (isset($user->tel_chat_Id) && $user->tel_chat_Id == null || empty($user->tel_chat_Id) || $user->tel_chat_Id == '') {
                $user->tel_chat_Id = $request->telegram_chat_Id;
            }
            if(isset($request->my_upi_id) && $request->my_upi_id != null){
                $curl = curl_init();
                $apiUrl = 'https://api.cashfree.com/api/v2/upi/validate/'.$request->my_upi_id;
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
                    return redirect()->back()->withInput()->with('error', curl_error($curl));
                } else {
                    $response = json_decode($response);
                }
                if (isset($response->status) && $response->status == 'OK' && isset($response->valid) && $response->valid == 1) {
                    $user->upi = $request->my_upi_id;
                    // $response->vpa; // If Valid will get this details
                    // $response->status; // If Valid will get this details
                    // $response->valid; // If Valid will get this details
                    // $response->name; // If Valid will get this details
                }
                curl_close($curl);
            }
            // $user->user_status = 'Inactive';
            if($user->user_role == 'S'){
                $user->user_role = 'S';
            }elseif($user->user_role == 'A'){
                $user->user_role = 'A';
            }elseif($user->user_role == 'L'){
                $user->user_role = 'L';
            }else{
                $user->user_role = 'U';
            }
            $user->user_slug = $this->generateUserSlug($request->user_fname, $request->user_lname, $request->mobile_number); // Set the user_slug value
            $user->update();

            $prvcheck = UserReferral::where('user_id', $user->id)
                ->where('referral_id', $request->referal_code)
                ->where('admin_slug', $admin->user_slug)
                ->count();
            if (isset($request->my_mpin) && isset($request->confirm_my_mpin) && $request->confirm_my_mpin == $request->my_mpin) {
                UserMpin::updateOrCreate(['uid' => $user->id], ['mpin' => $request->confirm_my_mpin]);
            }
            if ($prvcheck == 0) {
                $userReferral = new UserReferral();
                $userReferral->user_id = $user->id;
                $userReferral->referral_id = $request->referal_code;
                $userReferral->admin_slug = $admin->user_slug;
                $userReferral->save();
            }

            $userRole = new UserRole();
            $userRole->user_id = $user->id;
            $userRole->role = 'U';
            $userRole->save();
            if ($user->user_status == 'Inactive') {
                toastr()->error('Your account is not active');
                return redirect()->route('login')->with('error', 'Your account is not active');
            }
            Auth::login($user);
            return redirect('/home');
        }
    }
}
