<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserMap;
use App\Models\User;
use App\Models\UserSubInfo;
use App\Models\PaymentDistribution;
use App\Models\UserReferral;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
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
            $payment->comments = (isset($request->comments) && !empty($request->comments)) ? $request->comments : 'No Comments';
            $payment->type = "SH";
            $payment->status = "pending";
            //$payment->payment_type = $request->payment;
            // $imagePath = $request->file('attached_screenshot')->store('public/storage/attached_screenshots');
            if ($request->hasFile('ss_payment')) {
                $image = $request->file('ss_payment');
                $name = $request->utrnumber . '.' . $image->getClientOriginalExtension();
                $destinationPath = 'public/images/paymentSS/';
                if (file_exists($destinationPath . $name)) {
                    unlink($destinationPath . $name);
                }
                $image->move($destinationPath, $name);

                $payment->attachment = $destinationPath . $name;
            } else {
                // $payment->attachment = $request->utrnumber;
                return redirect()->back()->with('error', 'Payment Screen shot required !!');
            }
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
            return redirect('/help/sh_panel')->with('success', 'Send Help Process Completed !!');
        } catch (\Exception $e) {
            return redirect('help/sh_panel')->with('error', config('messages.500'));
        }
    }
    public function requestShow(Request $request)
    {
        $getPaymentStatus = Payment::join('users', 'users.id', 'payments.user_id')
            ->select('users.id', 'users.user_fname', 'users.user_lname', 'users.mobile_number', 'users.email', 'payments.comments', 'payments.mobile_id', 'payments.payment_id', 'payments.payment_type', 'payments.attachment')
            ->where('payments.mobile_id', $request->mobile_id)
            ->where('payments.user_id', $request->user_id)
            ->first();
        return json_encode($getPaymentStatus);
    }
    public function requestUpdate(Request $request)
    {
        try {
            $data = Payment::where('payment_id', $request->row_id)->update(['status' => 'completed']);
            $dataGreen = UserSubInfo::where('mobile_id', $request->mobile_id)->update(['status' => 'green']);
            if ($dataGreen == 1) {
                $userId = UserSubInfo::where('mobile_id', $request->mobile_id)->first()->user_id;
                $userRefeDetail = UserReferral::join('users', 'users.mobile_number', 'user_referral.referral_id')
                    ->join('users AS u', 'u.user_slug', 'user_referral.admin_slug')
                    ->select('users.id AS referal_id', 'users.user_role AS referal_id_role', 'u.id AS admin_id', 'user_referral.referral_id AS level_two_mobile')
                    ->where('user_referral.user_id', $userId)->first();
                if ($userRefeDetail) {
                    $referal_id = $userRefeDetail->referal_id;
                    $admin_id = $userRefeDetail->admin_id;
                    $data = [];
                    $data[] = ['sender_id' => $userId, 'reciver_id' => $admin_id, 'mobile_id' => $request->mobile_id, 'amount' => config('custom.custom.admin_income'), 'level' => 'ADMIN'];
                    if ($userRefeDetail->referal_id_role == 'L') {
                        $data[] = ['sender_id' => $userId, 'reciver_id' => $referal_id, 'mobile_id' => $request->mobile_id, 'amount' => config('custom.custom.leader_income'), 'level' => 'LEADER'];
                    }
                    $data[] = ['sender_id' => $userId, 'reciver_id' => $referal_id, 'mobile_id' => $request->mobile_id, 'amount' => config('custom.custom.level_1'), 'level' => 'LVL1'];
                    $referal_id_2 = UserReferral::join('users', 'users.mobile_number', 'user_referral.referral_id')
                        ->join('users AS u', 'u.mobile_number', 'user_referral.referral_id')
                        ->select('u.id AS level_two')
                        ->where('user_referral.user_id', $referal_id)->first();
                    if ($referal_id_2) {
                        $data[] = ['sender_id' => $userId, 'reciver_id' => $referal_id_2->level_two, 'mobile_id' => $request->mobile_id, 'amount' => config('custom.custom.level_2'), 'level' => 'LVL2'];
                        $referal_id_3 = UserReferral::join('users', 'users.mobile_number', 'user_referral.referral_id')
                            ->join('users AS u', 'u.mobile_number', 'user_referral.referral_id')
                            ->select('u.id AS level_three')
                            ->where('user_referral.user_id', $referal_id_2->level_two)->first();
                        if ($referal_id_3) {
                            $data[] = ['sender_id' => $userId, 'reciver_id' => $referal_id_3->level_three, 'mobile_id' => $request->mobile_id, 'amount' => config('custom.custom.level_3'), 'level' => 'LVL3'];
                            $referal_id_4 = UserReferral::join('users', 'users.mobile_number', 'user_referral.referral_id')
                                ->join('users AS u', 'u.mobile_number', 'user_referral.referral_id')
                                ->select('u.id AS level_four')
                                ->where('user_referral.user_id', $referal_id_3->level_three)->first();
                            if ($referal_id_4) {
                                $data[] = ['sender_id' => $userId, 'reciver_id' => $referal_id_4->level_four, 'mobile_id' => $request->mobile_id, 'amount' => config('custom.custom.level_4'), 'level' => 'LVL4'];
                                $referal_id_5 = UserReferral::join('users', 'users.mobile_number', 'user_referral.referral_id')
                                    ->join('users AS u', 'u.mobile_number', 'user_referral.referral_id')
                                    ->select('u.id AS level_five')
                                    ->where('user_referral.user_id', $referal_id_4->level_four)->first();
                                if ($referal_id_5) {
                                    $data[] = ['sender_id' => $userId, 'reciver_id' => $referal_id_5->level_five, 'mobile_id' => $request->mobile_id, 'amount' => config('custom.custom.level_5'), 'level' => 'LVL5'];
                                    // $referal_id_6 = UserReferral::join('users', 'users.mobile_number', 'user_referral.referral_id')
                                    //     ->join('users AS u', 'u.mobile_number', 'user_referral.referral_id')
                                    //     ->select('u.id AS level_six')
                                    //     ->where('user_referral.user_id', $referal_id_5->level_five)->first();
                                    // if ($referal_id_6) {
                                    //     $data[] = ['sender_id' => $userId, 'reciver_id' => $referal_id_6->level_six, 'mobile_id' => $request->mobile_id, 'amount' => config('custom.custom.level_6'), 'level' => 'LVL6'];
                                    //     $referal_id_7 = UserReferral::join('users', 'users.mobile_number', 'user_referral.referral_id')
                                    //         ->join('users AS u', 'u.mobile_number', 'user_referral.referral_id')
                                    //         ->select('u.id AS level_seven')
                                    //         ->where('user_referral.user_id', $referal_id_6->level_six)->first();
                                    //     if ($referal_id_7) {
                                    //         $data[] = ['sender_id' => $userId, 'reciver_id' => $referal_id_7->level_seven, 'mobile_id' => $request->mobile_id, 'amount' => config('custom.custom.level_7'), 'level' => 'LVL7'];
                                    //     }
                                    // }
                                }
                            }
                        }
                    }
                    if (count($data)) {
                        PaymentDistribution::insert($data);
                    }
                }
            }
            return json_encode(['msg' => 'success']);
        } catch (\Exception $e) {
            return json_encode(['msg' => $e]);
        }
    }
}
