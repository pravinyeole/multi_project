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
use App\Models\Withdraw_money;
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
            ->select('users.id', 'users.user_fname', 'users.upi', 'users.user_lname', 'user_map_new.user_mobile_id', 'user_map_new.created_at AS assigndate')
            ->whereIn('user_mobile_id', $sendHelpDataA)
            ->whereNotIn('user_mobile_id', $notInPayment)
            ->where('type', 'GH')->orderBy('user_map_new.created_at', 'DESC')->get();
        $statusOrder = [
            'red' => 1,
            'green' => 2,
            'orange' => 3,
            'gray' => 4,
        ];
 
        // $mycreatedids = UserSubInfo::where('user_id', Auth::user()->id)->orderByRaw("FIELD(status, 'red', 'green', 'orange', 'gray')")->get();

        $mycreatedids = UserSubInfo::join('user_map_new', 'user_map_new.user_mobile_id', 'user_sub_info.mobile_id')
        ->join('users', 'users.id', 'user_map_new.new_user_id')
        ->select('users.id', 'users.user_fname', 'users.upi', 'users.user_lname','users.mobile_number', 'user_sub_info.mobile_id', 'user_sub_info.created_at','user_sub_info.status')
        ->where('user_sub_info.user_id', Auth::user()->id)
        ->orderByRaw("FIELD(user_sub_info.status, 'red', 'green', 'orange', 'gray')")->get();

        // $getHelpuserIds = $getGetHelpData->pluck('new_user_id')->toArray();
        // $sendHelpData = User::whereIn('id',$getHelpuserIds)->get();
        return view('admin.pincenter.sh', compact('sendHelpData', 'mycreatedids'));
    }
    public function shPayNow(Request $request)
    {
        $result_data = User::select('id', 'user_fname', 'user_lname', 'mobile_number', 'email', 'upi')
            ->where('id', $request->user_id)->first();
        $result_data['tran_inr'] = (isset($request->tran_inr) && !empty($request->tran_inr)) ? $request->tran_inr : '';
        $result_data['tran_mobile'] = (isset($request->tran_mobile) && !empty($request->tran_mobile)) ? $request->tran_mobile : '';
        return view('admin.pincenter.paynow', compact('result_data'));
    }
    public function ghPanel(Request $request)
    {
        $notInPayment = Payment::where('receivers_id', Auth::user()->id)->where('status','pending')->pluck('mobile_id')->toArray();
        $getHelpData = User::join('user_map_new', 'users.id', '=', 'user_map_new.user_id')
            ->join('user_sub_info', 'user_sub_info.mobile_id', '=', 'user_map_new.user_mobile_id')
            ->select('users.id', 'users.user_lname', 'users.user_fname', 'users.mobile_number', 'user_map_new.user_mobile_id', 'user_map_new.new_user_id','user_map_new.created_at',DB::raw("'notin' as pstatus"))
            ->where('user_map_new.new_user_id', Auth::user()->id)
            ->whereNotIn('user_map_new.user_mobile_id', $notInPayment)
            ->where('user_sub_info.status', 'red')
            ->union(User::join('user_map_new', 'users.id', '=', 'user_map_new.user_id')
            ->join('user_sub_info', 'user_sub_info.mobile_id', '=', 'user_map_new.user_mobile_id')
            ->select('users.id', 'users.user_lname', 'users.user_fname', 'users.mobile_number', 'user_map_new.user_mobile_id', 'user_map_new.new_user_id','user_map_new.created_at',DB::raw("'inpay' as pstatus"))
            ->where('user_map_new.new_user_id', Auth::user()->id)
            ->whereIn('user_map_new.user_mobile_id', $notInPayment)
            ->where('user_sub_info.status', 'red'))
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
            $condtion1 = " DATE(created_at) BETWEEN '$request->FromDate 00:00:00' AND '$request->FromDate 23:59:59'";
        } elseif (isset($request->Duration) && $request->Duration == 'week') {
            $queryArray['Duration'] = $request->Duration;
            $queryArray['FromDate'] = $request->FromDate;
            $queryArray['ToDate'] = $request->ToDate;
            $condtion1 = " DATE(created_at) between '$request->FromDate' AND '$request->ToDate'";
        } elseif (isset($request->Duration) && $request->Duration == 'month') {
            $monthA = ['Jan' => 1, 'Feb' => 2, 'Mar' => 3, 'Apr' => 4, 'May' => 5, 'Jun' => 6, 'Jul' => 7, 'Aug' => 8, 'Sep' => 9, 'Oct' => 10, 'Nov' => 11, 'Dec' => 12];
            $month = str_pad($monthA[$request->DurationMonth], 2, '0', STR_PAD_LEFT);
            $queryArray['Duration'] = $request->Duration;
            $queryArray['DurationMonth'] = $request->DurationMonth;
            $condtion1 = " MONTH(created_at) = '$month'";
        } elseif (isset($request->Duration) && $request->Duration == 'lifetime') {
            $queryArray['Duration'] = $request->Duration;
        }
        $dataGreen = UserSubInfo::where('user_id', Auth::user()->id)
            ->where('status', 'green');
        if ($condtion1) {
            $dataGreen = $dataGreen->whereRaw($condtion1);
        }
        $dataGreen = $dataGreen->count('user_sub_info_id');
        $dataAllPins = UserSubInfo::where('user_id', Auth::user()->id);
        if ($condtion1) {
            $dataAllPins = $dataAllPins->whereRaw($condtion1);
        }
        $dataAllPins = $dataAllPins->count('user_sub_info_id');
        // if($dataGreen){
        $receivedGH = Payment::where('receivers_id', Auth::user()->id)->where('status', 'completed');
        if ($condtion1) {
            $receivedGH = $receivedGH->whereRaw($condtion1);
        }
        $receivedGH = $receivedGH->count('payment_id');
        $allTotal['plan_income_amt'] = $receivedGH * config('custom.custom.plan_income_amt');

        $allTotalTwo['pin_used'] = $dataAllPins * config('custom.custom.pin_amount');
        $allTotalTwo['total_SH'] = $dataGreen * config('custom.custom.upi_pay_amount');

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
        $res = Withdraw_money::where('user_id', Auth::user()->id)->orderByDesc('id')->first();
        
        if(isset($res))
        {
            $dashboard_total = $res['money'];

        }
        else    
        {
            $dashboard_total = array_sum($allTotal);
        }
        $check = fmod($dashboard_total, 100);
        $latest_value = $dashboard_total - $check;
        $add_pin = $latest_value/config('custom.custom.withdraw_money_rpin_price');
        $trans = Withdraw_money::where('user_id', Auth::user()->id)->orderByDesc('id')->get();
        return view('admin.pincenter.cal', compact('allTotal', 'allTotalTwo', 'queryArray','dashboard_total','add_pin','trans'));
    }
    public function myNetwork(Request $request)
    {
        $all_level_count = 0;
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
        $myLveledata = [];
        $lvlOneA = UserReferral::join('users', 'users.id', 'user_referral.user_id')->select('users.mobile_number')->where('user_referral.referral_id', $u_mn)->pluck('users.mobile_number')->toArray();
        $myLveledata['level_1'] = count($lvlOneA);
        $myLveledata['level_id_1'] = $lvlOneA;
        $all_level_count += $myLveledata['level_1'];
        if ($lvlOneA) {
            $lvlTwoA = UserReferral::join('users', 'users.id', 'user_referral.user_id')->select('users.mobile_number')->whereIn('user_referral.referral_id', $lvlOneA)->pluck('users.mobile_number')->toArray();
            $myLveledata['level_2'] = count($lvlTwoA);
            $all_level_count += $myLveledata['level_2'];
            $myLveledata['level_id_2'] = $lvlTwoA;
            if ($lvlTwoA) {
                $lvlThreeA = UserReferral::join('users', 'users.id', 'user_referral.user_id')->select('users.mobile_number')->whereIn('user_referral.referral_id', $lvlTwoA)->pluck('users.mobile_number')->toArray();
                $myLveledata['level_3'] = count($lvlThreeA);
                $all_level_count += $myLveledata['level_3'];
                $myLveledata['level_id_3'] = $lvlThreeA;
                if ($lvlThreeA) {
                    $lvlFourA = UserReferral::join('users', 'users.id', 'user_referral.user_id')->select('users.mobile_number')->whereIn('user_referral.referral_id', $lvlThreeA)->pluck('users.mobile_number')->toArray();
                    $myLveledata['level_4'] = count($lvlFourA);
                    $all_level_count += $myLveledata['level_4'];
                    $myLveledata['level_id_4'] = $lvlFourA;
                    if ($lvlFourA) {
                        $lvlFiveA = UserReferral::join('users', 'users.id', 'user_referral.user_id')->select('users.mobile_number')->whereIn('user_referral.referral_id', $lvlFourA)->pluck('users.mobile_number')->toArray();
                        $myLveledata['level_5'] = count($lvlFiveA);
                        $all_level_count += $myLveledata['level_5'];
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

        $myReferalUser2 = User::join('user_referral AS ur', 'ur.user_id', 'users.id')
            ->where('ur.referral_id', Auth::user()->mobile_number)
            ->orWhere('ur.admin_slug', Auth::user()->user_slug)
            ->orderBy('users.id', 'DESC')
            ->take(5)
            ->get();
        return view('admin.pincenter.mynetwork', compact('myReferalUser', 'data', 'myPinBalance_a', 'myLveledata', 'myReferalUser2', 'all_level_count'));
    }
    public function addpin(Request $request)
    {
        try{
            $inventoryTwo = UserPin::firstOrNew(['user_id'=>Auth::user()->id]);
            $inventoryTwo->pins = $inventoryTwo->pins+$request['rpin_add'];
            $inventoryTwo->save();

            $data['user_id'] = Auth::user()->id;
            $data['withdraw_rpin'] = $request['rpin_add'];
            $data['money'] = $request['old_money'] - (config('custom.custom.withdraw_money_rpin_price') * $request['rpin_add']);

            $res = Withdraw_money::Create($data);
            return redirect()->back()->with('message', 'rPin added Successfully.');
        }
        catch(e){
            return redirect()->back()->with('message', "Somthing went wrong e");
        }
    }

    public function transactionhistory(Request $request)
    {
        $trans = Withdraw_money::where('user_id', Auth::user()->id)->orderByDesc('id')->get();
        return view('admin.pincenter.withdrow_transactionhistory', compact('trans'));
    }
}
