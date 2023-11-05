<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserMap;
use App\Models\User;
use App\Models\Payment;
use App\Models\UserSubInfo;
use App\Models\UserPin;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Auth;
use DB;

use Illuminate\Support\Facades\Validator;

// use App\Traits\TwoFactorTrait;
use Session;

class HelpIncomeController extends Controller
{
    public function __construct(){
        $this->middleware(['auth']);
    }
    public function shPanel(Request $request){
        $sendHelpDataA = User::join('user_sub_info', 'users.id', '=', 'user_sub_info.user_id')
                        ->where('users.id', Auth::user()->id)
                        ->where('user_sub_info.status', 'red')
                        ->orderBy('user_sub_info.created_at', 'DESC')
                        ->pluck('user_sub_info.mobile_id')->toArray();
        $notInPayment = Payment::where('user_id', Auth::user()->id)->where('status','pending')->pluck('mobile_id')->toArray();
        $sendHelpData = UserMap::join('users','users.id','user_map_new.new_user_id')
                        ->select('users.id','users.user_fname','users.upi','users.user_lname','user_map_new.user_mobile_id')
                        ->whereIn('user_mobile_id', $sendHelpDataA)
                        ->whereNotIn('user_mobile_id', $notInPayment)
                        ->where('type', 'GH')->get();
        // $getHelpuserIds = $getGetHelpData->pluck('new_user_id')->toArray();
        // $sendHelpData = User::whereIn('id',$getHelpuserIds)->get();
        return view('admin.pincenter.sh',compact('sendHelpData'));
    }
    public function ghPanel(Request $request){
        // $notInPayment = Payment::where('receivers_id', Auth::user()->id)->where('status','pending')->pluck('mobile_id')->toArray();
        $getHelpData = User::join('user_map_new', 'users.id', '=', 'user_map_new.user_id')
                    ->join('user_sub_info', 'user_sub_info.mobile_id', '=', 'user_map_new.user_mobile_id')
                    ->select('users.id','users.user_lname','users.user_fname','users.mobile_number','user_map_new.user_mobile_id','user_map_new.new_user_id')
                    ->where('user_map_new.new_user_id',Auth::user()->id)
                    // ->whereNotIn('user_map_new.user_mobile_id', $notInPayment)
                    ->where('user_sub_info.status','red')
                    ->get();
        return view('admin.pincenter.gh',compact('getHelpData'));
    }
    public function myIncome(Request $request){
        return view('admin.pincenter.cal');
    }
    public function myNetwork(Request $request){
        $myReferalUser = User::join('user_referral AS ur','ur.user_id','users.id')
                            ->where('ur.referral_id',Auth::user()->mobile_number)
                            ->orWhere('ur.admin_slug',Auth::user()->user_slug)
                            ->orderBy('users.id','DESC')
                            ->count();
        $data = User::join('user_referral', 'users.id', '=', 'user_referral.user_id')
        ->select('users.*')
        ->where('user_referral.referral_id', Auth::user()->mobile_number)
        ->where('user_status','Inactive')
        ->orderBy('users.id', 'DESC')
        ->get();
        $myPinBalance_a = UserPin::where('user_id', Auth::user()->id)->sum('pins');
        return view('admin.pincenter.mynetwork',compact('myReferalUser','data','myPinBalance_a'));
    }
}