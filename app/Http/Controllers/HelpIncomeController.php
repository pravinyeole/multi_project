<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserMap;
use App\Models\User;
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
        $getGetHelpData = UserMap::whereIn('user_mobile_id', $sendHelpDataA)->where('type', 'GH')->get();
        $getHelpuserIds = $getGetHelpData->pluck('new_user_id')->toArray();
        $sendHelpData = User::whereIn('id',$getHelpuserIds)->get();
        return view('admin.pincenter.sh',compact('sendHelpData'));
    }
    public function ghPanel(Request $request){
        return view('admin.pincenter.gh');
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
        return view('admin.pincenter.mynetwork',compact('myReferalUser','data'));
    }
}