<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserMap;
use App\Models\User;
use App\Models\Payment;
use App\Models\UserPin;
use App\Models\UserSubInfo;
use App\Models\UserReferral;
use Illuminate\Support\Carbon;
use App\Models\PaymentDistribution;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use DB;

use Illuminate\Support\Facades\Validator;

// use App\Traits\TwoFactorTrait;
use Session;

class HelpIncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    public function shPanel(Request $request)
    {
        $sendHelpDataA = User::join('user_sub_info', 'users.id', '=', 'user_sub_info.user_id')
            ->where('users.id', Auth::user()->id)
            ->where('user_sub_info.status', 'red')
            ->orderBy('user_sub_info.created_at', 'DESC')
            ->pluck('user_sub_info.mobile_id')->toArray();
        $notInPayment = Payment::where('user_id', Auth::user()->id)->where('status', 'pending')->pluck('mobile_id')->toArray();
        $sendHelpData = UserMap::join('users', 'users.id', 'user_map_new.new_user_id')
            ->select('users.id', 'users.user_fname', 'users.upi', 'users.user_lname', 'user_map_new.user_mobile_id','user_map_new.created_at AS assigndate')
            ->whereIn('user_mobile_id', $sendHelpDataA)
            ->whereNotIn('user_mobile_id', $notInPayment)
            ->where('type', 'GH')->get();
        // $mycreatedids = User::join('user_sub_info', 'users.id', '=', 'user_sub_info.user_id')
        //             ->select('users.*', 'user_sub_info.mobile_id', 'user_sub_info.created_at as id_created_date', 'user_sub_info.status as id_status')
        //             ->where('users.id', Auth::user()->id)
        //             ->orderBy('user_sub_info.created_at', 'DESC')
        //             ->get();
        $statusOrder = [
            'red' => 1,
            'green' => 2,
            'orange' => 3,
            'gray' => 4,
        ];
        $mycreatedids = UserSubInfo::where('user_id', Auth::user()->id)->orderByRaw("FIELD(status, 'red', 'green', 'orange', 'gray')")->get();
        // $getHelpuserIds = $getGetHelpData->pluck('new_user_id')->toArray();
        // $sendHelpData = User::whereIn('id',$getHelpuserIds)->get();
        return view('admin.pincenter.sh', compact('sendHelpData', 'mycreatedids'));
    }
    public function ghPanel(Request $request)
    {
        // $notInPayment = Payment::where('receivers_id', Auth::user()->id)->where('status','pending')->pluck('mobile_id')->toArray();
        $getHelpData = User::join('user_map_new', 'users.id', '=', 'user_map_new.user_id')
            ->join('user_sub_info', 'user_sub_info.mobile_id', '=', 'user_map_new.user_mobile_id')
            ->select('users.id', 'users.user_lname', 'users.user_fname', 'users.mobile_number', 'user_map_new.user_mobile_id', 'user_map_new.new_user_id')
            ->where('user_map_new.new_user_id', Auth::user()->id)
            // ->whereNotIn('user_map_new.user_mobile_id', $notInPayment)
            ->where('user_sub_info.status', 'red')
            ->get();
        return view('admin.pincenter.gh', compact('getHelpData'));
    }
    public function myIncome(Request $request)
    {
        $allTotal = [];
        $queryArray = [];
        $condtion1 = '';
        $condtion2 = '';
        if (isset($request->Duration) && $request->Duration == 'today') {
            $queryArray['Duration'] = $request->Duration;
            $queryArray['FromDate'] = $request->FromDate;
            $condtion1 = " DATE(created_at) '=' '$request->FromDate'";
        } elseif (isset($request->Duration) && $request->Duration == 'week') {
            $queryArray['Duration'] = $request->Duration;
            $queryArray['FromDate'] = $request->FromDate;
            $queryArray['ToDate'] = $request->ToDate;
            $condtion1 = " DATE(created_at) between '$request->FromDate' AND '$request->ToDate'";
        } elseif (isset($request->Duration) && $request->Duration == 'month') {
            $queryArray['Duration'] = $request->Duration;
            $queryArray['DurationMonth'] = $request->DurationMonth;
            $condtion1 = " 'DATE(created_at)' = '$request->DurationMonth'";
        } elseif (isset($request->Duration) && $request->Duration == 'lifetime') {
            $queryArray['Duration'] = $request->Duration;
        }
        $dataGreen = UserSubInfo::where('user_id', Auth::user()->id)
            ->where('status', 'green');
        if ($condtion1) {
            $dataGreen = $dataGreen->whereRaw($condtion1);
        }
        $dataGreen = $dataGreen->count('user_sub_info_id');
        // if($dataGreen){
        $allTotal['plan_income_amt'] = $dataGreen * config('custom.custom.plan_income_amt');

        $admin_income = PaymentDistribution::where('reciver_id', Auth::user()->id)
            ->where('level', 'ADMIN');
        if ($condtion1) {
            $admin_income = $admin_income->whereRaw($condtion1);
        }
        $allTotal['admin_income'] = $admin_income->sum('amount');

        $leader_income = PaymentDistribution::where('reciver_id', Auth::user()->id)
            ->where('level', 'LEADER');
        if ($condtion1) {
            $leader_income = $leader_income->whereRaw($condtion1);
        }
        $allTotal['leader_income'] = $leader_income->sum('amount');
        $level_1 = PaymentDistribution::where('reciver_id', Auth::user()->id)
            ->where('level', 'LVL1');
        if ($condtion1) {
            $level_1 = $level_1->whereRaw($condtion1);
        }
        $allTotal['level_1'] = $level_1->sum('amount');
        $level_2 = PaymentDistribution::where('reciver_id', Auth::user()->id)
            ->where('level', 'LVL2');
        if ($condtion1) {
            $level_2 = $level_2->whereRaw($condtion1);
        }
        $allTotal['level_2'] = $level_2->sum('amount');
        $level_3 = PaymentDistribution::where('reciver_id', Auth::user()->id)
            ->where('level', 'LVL3');
        if ($condtion1) {
            $level_3 = $level_3->whereRaw($condtion1);
        }
        $allTotal['level_3'] = $level_3->sum('amount');
        $level_4 = PaymentDistribution::where('reciver_id', Auth::user()->id)
            ->where('level', 'LVL4');
        if ($condtion1) {
            $level_4 = $level_4->whereRaw($condtion1);
        }
        $allTotal['level_4'] = $level_4->sum('amount');
        $level_5 = PaymentDistribution::where('reciver_id', Auth::user()->id)
            ->where('level', 'LVL5');
        if ($condtion1) {
            $level_5 = $level_5->whereRaw($condtion1);
        }
        $allTotal['level_5'] = $level_5->sum('amount');
        // $level_6 = PaymentDistribution::where('reciver_id', Auth::user()->id)
        //     ->where('level', 'LVL6');
        // if ($condtion1) {
        //     $level_6 = $level_6->whereRaw($condtion1);
        // }
        // $allTotal['level_6'] = $level_6->sum('amount');
        // $level_7 = PaymentDistribution::where('reciver_id', Auth::user()->id)
        //     ->where('level', 'LVL7');
        // if ($condtion1) {
        //     $level_7 = $level_7->whereRaw($condtion1);
        // }
        // $allTotal['level_7'] = $level_7->sum('amount');
        // }
        $allTotalTwo['bpin_used'] = UserSubInfo::where('user_id', Auth::user()->id)->count('user_sub_info_id');
        $allTotalTwo['total_sh'] = 0;
        return view('admin.pincenter.cal', compact('allTotal', 'allTotalTwo', 'queryArray'));
    }
    public function myNetwork(Request $request)
    {
        $myReferalUser = User::join('user_referral AS ur', 'ur.user_id', 'users.id')
            ->where('ur.referral_id', Auth::user()->mobile_number)
            ->orWhere('ur.admin_slug', Auth::user()->user_slug)
            ->orderBy('users.id', 'DESC')
            ->count();
        $data = User::join('user_referral', 'users.id', '=', 'user_referral.user_id')
            ->select('users.*')
            ->where('user_referral.referral_id', Auth::user()->mobile_number)
            ->where('user_status', 'Inactive')
            ->orderBy('users.id', 'DESC')
            ->get();
        $myPinBalance_a = UserPin::where('user_id', Auth::user()->id)->sum('pins');
        $u_mn = Auth::user()->mobile_number;
        $myLveledata=[];
        $lvlOneA = UserReferral::join('users','users.id','user_referral.user_id')->select('users.mobile_number')->where('user_referral.referral_id',$u_mn)->pluck('users.mobile_number')->toArray();
        $myLveledata['level_1'] = count($lvlOneA);
        $myLveledata['level_id_1'] = $lvlOneA;
        if($lvlOneA){
            $lvlTwoA = UserReferral::join('users','users.id','user_referral.user_id')->select('users.mobile_number')->whereIn('user_referral.referral_id',$lvlOneA)->pluck('users.mobile_number')->toArray();
            $myLveledata['level_2'] = count($lvlTwoA);
            $myLveledata['level_id_2'] = $lvlTwoA;
            if($lvlTwoA){
                $lvlThreeA = UserReferral::join('users','users.id','user_referral.user_id')->select('users.mobile_number')->whereIn('user_referral.referral_id',$lvlTwoA)->pluck('users.mobile_number')->toArray();
                $myLveledata['level_3'] = count($lvlThreeA);
                $myLveledata['level_id_3'] = $lvlThreeA;
                if($lvlThreeA){
                    $lvlFourA = UserReferral::join('users','users.id','user_referral.user_id')->select('users.mobile_number')->whereIn('user_referral.referral_id',$lvlThreeA)->pluck('users.mobile_number')->toArray();
                    $myLveledata['level_4'] = count($lvlFourA);
                    $myLveledata['level_id_4'] = $lvlFourA;
                    if($lvlFourA){
                        $lvlFiveA = UserReferral::join('users','users.id','user_referral.user_id')->select('users.mobile_number')->whereIn('user_referral.referral_id',$lvlFourA)->pluck('users.mobile_number')->toArray();
                        $myLveledata['level_5'] = count($lvlFiveA);
                        $myLveledata['level_id_5'] = $lvlFiveA;
                        // if($lvlFiveA){
                        //     $lvlSixA = UserReferral::join('users','users.id','user_referral.user_id')->select('users.mobile_number')->whereIn('user_referral.referral_id',$lvlFiveA)->pluck('users.mobile_number')->toArray();
                        //     $myLveledata['level_6'] = count($lvlSixA);
                        //     $myLveledata['level_id_6'] = $lvlSixA;
                        //     if($lvlSixA){
                        //         $lvlSevenA = UserReferral::join('users','users.id','user_referral.user_id')->select('users.mobile_number')->whereIn('user_referral.referral_id',$lvlSixA)->pluck('users.mobile_number')->toArray();
                        //         $myLveledata['level_7'] = count($lvlSevenA);
                        //         $myLveledata['level_id_7'] = $lvlSevenA;
                        //     }
                        // }
                    }
                }
            }
        }
        return view('admin.pincenter.mynetwork', compact('myReferalUser', 'data', 'myPinBalance_a','myLveledata'));
    }
}
