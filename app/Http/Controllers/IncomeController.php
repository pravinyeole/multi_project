<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserMap;
use App\Models\User;
use App\Models\UserSubInfo;
use App\Models\UserReferral;
use App\Models\Payment;
use Auth;
use DB;
use Exception;
use Session;
// use App\Traits\TwoFactorTrait;

class IncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    public function requestSave(Request $request)
    {
        try {
            $prv_check = Payment::where('mobile_id', $request->user_mobile_id)->where('receivers_id', $request->uid)->where('user_id', Auth::user()->id)->count();
            if ($prv_check) {
                return redirect()->back()->with('error', 'This Send help all ready Processed.');
            }
            $payment = new Payment();
            $payment->mobile_id = $request->user_mobile_id;
            $payment->user_id = Auth::user()->id;
            $payment->receivers_id = $request->uid;
            $payment->comments = (isset($request->comments) && !empty($request->comments)) ? $request->comments :'No Comments';
            $payment->type = "SH";
            $payment->status = "pending";
            // $imagePath = $request->file('attached_screenshot')->store('public/storage/attached_screenshots');
            $payment->attachment = $request->utrnumber;
            $payment->save();
            $refferalUser = UserReferral::where('user_id', Auth::user()->id)->first();
            // Increment total_invited for mobile_number referral
            $referredMobileUser = User::where('mobile_number', $refferalUser->referral_id)->first();
            if ($referredMobileUser) {
                $referredMobileUser->increment('total_invited');
            }
            // Increment total_invited for admin_slug referral
            $referredAdminUser = User::where('user_slug', $refferalUser->admin_slug)->first();
            if ($referredAdminUser) {
                $referredAdminUser->increment('total_invited');
            }
            return redirect()->back()->with('success', 'Send Help Process Completed !!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', config('messages.500'));
        }
    }
    public function requestShow(Request $request){
        $getPaymentStatus = Payment::join('users','users.id','payments.receivers_id')
                            ->select('users.id','users.user_fname','users.user_lname','users.mobile_number','users.email','payments.comments','payments.mobile_id','payments.payment_id','payments.payment_type','payments.attachment')
                            ->where('payments.mobile_id', $request->mobile_id)
                            ->where('payments.receivers_id', $request->user_id)
                            ->first();
        return json_encode($getPaymentStatus);
    }
    public function requestUpdate(Request $request){
        try{
            $data = Payment::where('payment_id', $request->row_id)->update(['status'=>'completed']);
            $data = UserSubInfo::where('mobile_id', $request->mobile_id)->update(['status'=>'green']);
            return json_encode(['msg'=>'success']);
        }catch (\Exception $e){
            return json_encode(['msg'=>$e]);
        }
    }
}
