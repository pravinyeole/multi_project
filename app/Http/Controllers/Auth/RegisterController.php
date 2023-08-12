<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Mail\CommonMail;
use App\Models\InsuranceAgency;
use App\Models\UserRefferal;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UserInvitation;
use App\Models\UserReferral;
use App\Models\UserRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Auth;

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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile_number' => ['required'],
            'referal_code' => ['nullable', 'string', 'max:255'],
            'admin_referal_code'=>['required']
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
    public function showRegistrationForm(string $invitationID = null)
    {
        try{
            return view('auth.register');
    }catch(\Exception $e){
        // toastr()->error('Something went wrong');
        return redirect('login');
    }
        
    }

    public function showEnterOtp(Request $request, $user_id, $mobileNumber) {
        return view('auth.login_otp', compact('user_id', 'mobileNumber'));
    }
    
    public function register(Request $request)
    {    
        // $this->validator($request->all())->validate();
        $user = User::where('mobile_number',$request->mobile_number)->first();
      
        $admin = User::where('user_slug',$request->admin_referal_code)->where('user_role','A')->first();
        if (!$admin) {
            toastr()->error('Admin Referral Code does not exist');
            return redirect()->back()->withInput();
        }else{
             // Check if referral ID exists in the user table
             $referralUser = User::where('mobile_number', $request->referal_code)->first();
             if (!$referralUser) {
                 toastr()->error('Referral does not exist');
                 return redirect()->back()->withInput();
             }
            $user->user_fname = $request->user_fname;
            $user->user_lname = $request->user_lname;
            $user->email = $request->email;
            $user->user_status = 'Inactive';
            $user->user_role = 'U';
            $user_slug = substr($request->user_fname, 0, 1) . substr($request->user_lname, 0, 1) . substr($user->mobile_number, 0, 4);
            $user->user_slug = $user_slug; // Set the user_slug value
            $user->update();
            
            $userReferral = new UserReferral();
            $userReferral->user_id = $user->id;
            $userReferral->referral_id = $request->referal_code;
           
            $userReferral->admin_slug = $admin->user_slug;
            $userReferral->save();
    
            $userRole = new UserRole();
            $userRole->user_id = $user->id;
            $userRole->role = 'U';
            $userRole->save();
            if($user->user_status == 'Inactive'){
                toastr()->error('Your account is not active');
                return redirect()->route('login');
            }
            Auth::login($user);    
            return redirect('/home');
        }
    }
}
