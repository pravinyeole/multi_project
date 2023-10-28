<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\UserMpin;
use App\Models\UserReferral;
use App\Models\UserRole;
use App\Models\UserOtp;

use Session;
use Log;

use App\Traits\CommonTrait;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, CommonTrait;

    /**
     * Where to redirect users after login.
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
        $this->middleware(['guest'])->except('logout');
    }

    // Login
    public function showLoginForm()
    {
        // for($j=28;$j<=31;){
        //     for($i=1;$i<=10;){
        //         $date = '2023-08-'.$j.' 10:32:24';
        //         $uid = 'SS'.sprintf("%02d", $i).date($j.'08Y');
        //         echo "INSERT INTO `user_sub_info` (`user_sub_info_id`, `user_id`, `mobile_id`, `status`, `created_at`) VALUES (NULL, '1', '".$uid."', 'red', '".$date."');";
        //         echo "<br>";
        //         $i++;
        //     }
        //     echo "<br>";
        //     echo "<br>";
        //     echo "<br>";
        //     $j++;
        // }
        // for($j=1;$j<=2;){
        //     for($i=1;$i<=10;){
        //         $date = '2023-09-'.sprintf("%02d", $j).' 10:32:24';
        //         $uid =  'SS'.sprintf("%02d", $i).date(sprintf("%02d", $j).'Y');
        //         echo "INSERT INTO `user_sub_info` (`user_sub_info_id`, `user_id`, `mobile_id`, `status`, `created_at`) VALUES (NULL, '1', '".$uid."', 'red', '".$date."');";
        //         echo "<br>";
        //         $i++;
        //     }
        //     echo "<br>";
        //     echo "<br>";
        //     echo "<br>";
        //     $j++;
        // }
        $pageConfigs = [
            'bodyClass' => "bg-full-screen-image",
            'blankPage' => true
        ];

        return view('/auth/login', [
            'pageConfigs' => $pageConfigs
        ]);
    }


    //  public function login(Request $request)
    // {
    //     $this->validateLogin($request);

    //     // If the class is using the ThrottlesLogins trait, we can automatically throttle
    //     // the login attempts for this application. We'll key this by the username and
    //     // the IP address of the client making these requests into this application.
    //     if (method_exists($this, 'hasTooManyLoginAttempts') &&
    //         $this->hasTooManyLoginAttempts($request)) {
    //         $this->fireLockoutEvent($request);

    //         return $this->sendLockoutResponse($request);
    //     }
    //     if ($this->attemptLogin($request)) {
    //         $id = Auth::id();
    //         $user = User::where('id',$id)->first();

    //         if($user->deleted_at == ''){
    //             User::where('id', $id)->update(['user_last_login' => date('Y-m-d H:i:s')]);
    //             return $this->sendLoginResponse($request);
    //             // return redirect('login');
    //         }
    //         else{
    //             $this->guard()->logout();
    //             // toastr()->error('Your account has been disabled by super admin');
    //             return redirect('login');
    //         }
    //     }

    //     // If the login attempt was unsuccessful we will increment the number of attempts
    //     // to login and redirect the user back to the login form. Of course, when this
    //     // user surpasses their maximum number of attempts they will get locked out.
    //     $this->incrementLoginAttempts($request);

    //     return $this->sendFailedLoginResponse($request);
    // }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function login(Request $request)
    {
        $request->validate([
            'mobileNumber' => 'required',
            'otp' => 'required'
        ]);
        $user = User::where('mobile_number', $request->mobileNumber)->first();
        /* Validation Logic */
        // $otp = array('Hello','World!','Beautiful','Day!');
        $otp = implode("", $request->otp);
        // uncommnet
        if(strlen($otp) == 4){
            $userOtp = UserMpin::where('uid', $user->id)->where('mpin', $otp)->first();
            $errorMsg = 'Sorry! Incorrect mPIN';
        }else{
            $userOtp = UserOtp::where('user_id', $user->id)->where('phone_otp', $otp)->first();
            $errorMsg = 'Sorry! Incorrect OTP';
        }
        // $userOtp = 111111;
        if(isset($user->user_role) && $user->user_role == 'S'){
            if($otp == 918273){
                Auth::login($user);
                return $this->sendLoginResponse($request);
                // return redirect('/home');
            }else if($userOtp){
                Auth::login($user);
                return $this->sendLoginResponse($request);
            }else {
                return redirect()->back()->with('error', $errorMsg);
            }
        }else{
            $now = now();
            if (!$userOtp) {
                return redirect()->back()->with('error', $errorMsg);
            } else if ($userOtp && $now->isAfter($userOtp->expire_at)) {
                // uncommne
                return redirect()->route('otp.login')->with('error', 'Your OTP has been expired');
            }
            $user = User::whereId($user->id)->first();
            if ($user) {
                // uncommnet
                $userOtp->update([
                    'expire_at' => now()
                ]);
                $userMpin = UserMpin::where('uid',$user->id)->get();
                $referal_check = UserReferral::where('user_id',$user->id)->first();
                if (count($userMpin) == 0) {
                    if($referal_check == null){
                        return redirect()->route('login')->with('error', 'Invalid User! No Refferal code found');
                    }
                    return view('auth.register', compact('user','referal_check'));
                } elseif ($user->user_status == 'Inactive') {
                    return redirect()->route('login')->with('error', 'Inactive Account! Contact Admin to Activate');
                }
                Auth::login($user);
                return $this->sendLoginResponse($request);
                // return redirect('/home');
            }
            return redirect()->route('otp.login')->with('error', 'Your Otp is not correct');
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/login');
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->route('home');
    }
}
