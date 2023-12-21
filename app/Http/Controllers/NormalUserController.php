<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserMap;
use App\Models\UserSubInfo;
use Illuminate\Support\Carbon;
use App\Models\Parameter;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Auth;
use DataTables;
use DB;
use App\Models\UserReferral;
use Redirect;
use App\Models\UserPin;
use Illuminate\Support\Facades\Storage;

class NormalUserController extends Controller
{
    public function __construct()
    {
        $this->title = "Dashboard";
        $this->middleware(['auth'])->except(['createIdClone']);
    }
    public function index(Request $request)
    {
        // $normal_udata = User::join('user_referral', 'users.id', '=', 'user_referral.user_id')
        //     ->select('users.*', 'users.created_at as id_created_date', 'user_status')
        //     ->where('user_referral.admin_slug', Auth::user()->user_slug)
        //     ->get();

        $title = $this->title;
        try {
            if ($request->ajax()) {
                // Retrieve user details and generate mobile_id
                $data = User::join('user_sub_info', 'users.id', '=', 'user_sub_info.user_id')
                    ->select('users.*', 'user_sub_info.mobile_id', 'user_sub_info.created_at as id_created_date', 'user_sub_info.status as id_status')
                    ->where('users.id', Auth::user()->id)
                    ->orderBy('user_sub_info.created_at', 'DESC')
                    ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('id_status', function ($row) {
                        $status = $row->id_status;
                        $Status = ucwords($status);
                        return $Status;
                    })
                    ->addColumn('action', function ($row) {
                        $id  = Crypt::encryptString($row->mobile_id);
                        $btn = "<a href='" . url('/help/sh_panel/') . "' class='item-edit btn btn-outline-dark btn-md px-2 py-1 text-dark'  title='View'><svg xmlns='http://www.w3.org/2000/svg' width=16 height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-eye font-small-4'><path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'></path><circle cx='12' cy='12' r='3''></circle></svg></a> 
                    ";
                        return $btn;
                    })
                    ->rawColumns(['id_status', 'action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            DB::rollback();
            toastr()->error(Config('messages.500'));
        }

        // Check if the user has already created 8 IDs today
        $today = Carbon::today();
        $userIds = UserSubInfo::where('user_id', Auth::user()->id)
            ->whereDate('created_at', $today)
            ->count();

        $allid = UserSubInfo::whereDate('created_at', $today)->count();

        $parameter = Parameter::where('parameter_key', 'starting_monday')->first();
        $startingWeek = Carbon::parse($parameter->parameter_value); // Replace with your desired starting week

        // Calculate the number of weeks since the starting week
        $currentWeek = Carbon::now()->diffInWeeks($startingWeek);
        // echo $userIds;
        // Calculate the initial number of count for the current wx`x`eek
        $initialsNoOfCount = ($currentWeek === 0) ? 10 : 10 * pow(2, $currentWeek);

        $createIdLimit = '';
        if ($allid == $initialsNoOfCount) {
            $createIdLimit = 'd-none';
        }

        $parameterStartTime = Parameter::where('parameter_key', 'starting_time')->first();
        $startingTime = Carbon::parse($parameter->parameter_value);
        $startingTime = $startingTime->format('H:i');
        $parameterEndTime = Parameter::where('parameter_key', 'end_time')->first();
        $endTime = Carbon::parse($parameterEndTime->parameter_value);

        $timer = '';
        if ($startingTime < $endTime) {
            $timer = "start";
        } else {
            $timer = "stop";
        }
        $Pins = 0;
        $userPins = UserPin::where('user_id', Auth::User()->id)->sum('pins');

        if (isset($userPins)) {
            $Pins = $userPins;
        }

        return view('normaluser.index', compact('title', 'createIdLimit', 'timer', 'Pins'));
    }

    public function createId(Request $request)
    {
        try {
            // Validate the request data
            $this->validate($request, [
                'user_id' => 'required|exists:users,id',
            ]);

            // Check if the user has already created 8 IDs today
            $today = Carbon::today();
            $userIds = UserSubInfo::where('user_id', $request->user_id)
                ->whereDate('created_at', $today)
                ->count();

            $allid = UserSubInfo::whereDate('created_at', $today)
                ->count();

            // if ($userIds >= 3 || ) {
            //     toastr()->error('You have reached the maximum limit of ID creations for today!');
            //     return redirect()->back();
            // } else {

            $parameter = Parameter::where('parameter_key', 'starting_monday')->first();
            // $startingWeek = Carbon::parse('2023-06-26'); // Replace with your desired starting week
            $startingWeek = Carbon::parse($parameter->parameter_value); // Replace with your desired starting week

            // Calculate the number of weeks since the starting week
            $currentWeek = Carbon::now()->diffInWeeks($startingWeek);

            // Calculate the initial number of count for the current wx`x`eek
            $initialsNoOfCount = ($currentWeek === 0) ? 10 : 10 * pow(2, $currentWeek);

            if ($allid == $initialsNoOfCount) {
                return redirect()->back()->with('create_id_alert', 'You have reached the maximum limit of ID creations for today!');
            } else if ($userIds >= config('custom.custom.user_id_limit')) {
                return redirect()->back()->with('create_id_alert', 'You have reached the maximum limit of ID creations for today!');
            }

            // Retrieve user details and generate mobile_id
            $userDetails = User::join('user_pins', 'users.id', '=', 'user_pins.user_id')
                ->select('users.*', 'user_pins.pins')
                ->where('users.id', $request->user_id)
                ->first();
            if (isset($userDetails->pins) && $userDetails->pins > 0) {
                // Generate the mobile_id
                $mobileIdCount = UserSubInfo::whereDate('created_at', $today)
                    // ->where('user_id', $request->user_id)
                    ->count() + 1;

                $initials = substr($userDetails->user_fname, 0, 1) . substr($userDetails->user_lname, 0, 1);
                $mobileId = $initials . str_pad($mobileIdCount, 2, '0', STR_PAD_LEFT) .
                    substr($userDetails->mobile_no, -4) .
                    $today->format('dmY');
                // Save the new UserSubInfo record
                $userSubInfo = new UserSubInfo();
                $userSubInfo->user_id = $userDetails->id;
                $userSubInfo->mobile_id = strtoupper($mobileId);
                $userSubInfo->status = 'red';
                $userSubInfo->created_at =  Carbon::now();
                $userSubInfo->save();

                // Update the pins for the user
                $userPins = UserPin::where('user_id', $request->user_id)->first();
                $userPins->pins = $userPins->pins - 1;
                $userPins->save();
                // Return a response
                return redirect('/help/sh_panel')->with('create_id_success', 'User ID created successfully!');
            } else {
                return redirect()->back()->with('create_id_error', 'You don`t have a PIN Balance.');
            }
            // }
        } catch (\Exception $e) {
            print_r($e);
            die();
            toastr()->error(config('messages.500'));
            return redirect()->back();
        }
    }

    public function createIdClone()
    {
        if (date('l') != 'Sunday') {
            $mobileNumber = ['9922020123','9860838087','9996668997','9975702645','7778882312','8149702000','7745859535','9595085302','9172811929','7620801801','9130669169'];
            $k = 1;
            $createdIds = [];
            for ($i = 0; $i < count($mobileNumber); $i++) {
                $mobilenum = $mobileNumber[$i];
                $user_id = 0;
                if (isset($mobilenum) && !empty($mobilenum)) {
                    $userdata = User::where('mobile_number', $mobilenum)->first();
                    if ($userdata == null) {
                        $mob_error[] = $mobilenum;
                    } else {
                        $user_id = $userdata->id;
                    }
                } else {
                    $mob_error[] = $mobilenum;
                }
                if ($user_id > 0) {
                    for ($j = 0; $j < config('custom.custom.user_id_limit'); $j++) {
                        $today = Carbon::today();
                        $userIds = UserSubInfo::where('user_id', $user_id)
                            ->whereDate('created_at', $today)
                            ->count();
                        $allid = UserSubInfo::whereDate('created_at', $today)->count();
                        $parameter = Parameter::where('parameter_key', 'starting_monday')->first();
                        $startingWeek = Carbon::parse($parameter->parameter_value); // Replace with your desired starting week      
                        $currentWeek = Carbon::now()->diffInWeeks($startingWeek);
                        $initialsNoOfCount = ($currentWeek === 0) ? 10 : 10 * pow(2, $currentWeek);
                        if ($allid == $initialsNoOfCount) {
                            echo $k . ' You have reached the maximum limit of ID creations for today!' . '<br>';
                        } else if ($userIds >= config('custom.custom.user_id_limit')) {
                            echo $k . ' You have reached the maximum limit of ID creations for today!' . '<br>';
                        } else {
                            $userDetails = User::join('user_pins', 'users.id', '=', 'user_pins.user_id')
                                ->select('users.*', 'user_pins.pins')
                                ->where('users.id', $user_id)
                                ->first();
                            $mobileIdCount = UserSubInfo::whereDate('created_at', $today)
                                // ->where('user_id', $user_id)
                                ->count() + 1;
                            $initials = substr($userDetails->user_fname, 0, 1) . substr($userDetails->user_lname, 0, 1);
                            $mobileId = $initials . str_pad($mobileIdCount, 2, '0', STR_PAD_LEFT) . substr($userDetails->mobile_no, -4) . $today->format('dmY');
                            // Save the new UserSubInfo record
                            $userSubInfo = new UserSubInfo();
                            $userSubInfo->user_id = $userDetails->id;
                            $userSubInfo->mobile_id = strtoupper($mobileId);
                            $userSubInfo->status = 'red';
                            $userSubInfo->created_at =  Carbon::now();
                            $userSubInfo->save();
                            $createdIds[] = $mobileId;
                            $userPins = UserPin::where('user_id', $user_id)->first();
                            $userPins->pins = $userPins->pins - 1;
                            $userPins->save();
                            echo $i . ' User ID created successfully!' . '<br>';
                        }
                        $k++;
                    }
                }
            }
            print_r($createdIds);
            exit;
        }
    }

    public function view(Request $request)
    {
        $title = $this->title;
        $userDetails = UserPin::where('user_id', Auth::user()->id)
            ->sum('pins');
        // dd($userDetails);
        $mobileId = Crypt::decryptString($request->id);
        $loggedInUserId = Auth::user()->id;
        $sendHelpData = UserMap::join('user_sub_info', 'user_sub_info.mobile_id', '=', 'user_map_new.mobile_id')
            ->join('users', 'users.id', '=', 'user_sub_info.user_id')
            ->where('user_map_new.user_id', $loggedInUserId)
            ->get();
        $getGetHelpData = UserMap::where([['mobile_id', $mobileId], ['type', 'GH']])->get();
        $getHelpuserIds = $getGetHelpData->pluck('user_id')->toArray();

        return view('normaluser.view', compact('userDetails', 'title', 'mobileId', 'sendHelpData'));
    }
    //SH User listing  
    public function getSendHelpData(Request $request)
    {
        // Retrieve the data for the "Send Help" section from your data source
        $loggedInUserId = Auth::user()->id;
        $getUserIds = UserMap::where('user_mobile_id', $request->mobileId)->where('user_map_new.type', 'GH')->pluck('new_user_id')->toArray();

        // $sendHelpData = UserMap::join('user_sub_info', 'user_sub_info.mobile_id', '=', 'user_map_new.user_mobile_id')
        //     ->join('users', 'users.id', '=', 'user_sub_info.user_id')
        //     ->where('user_map_new.user_i', $loggedInUserId)
        //     // ->where('user_map_new.user_mobile_id', $request->mobileId)
        //     // ->where('user_map_new.type', 'GH')
        //     // ->where('user_sub_info.status', 'red')
        //     ->whereIn('users.id', $getUserIds)
        //     ->get();
        if (count($getUserIds)) {
            $getUserIds = implode(',', $getUserIds);
            $sendHelpData = DB::select(DB::raw('select users.id AS sid,users.user_fname,users.mobile_number,users.user_lname,user_map_new.* from `user_map_new` left join `users` on `users`.`id` = `user_map_new`.`new_user_id` where `user_map_new`.`user_mobile_id` = "' . $request->mobileId . '" AND `user_map_new`.`user_id` = ' . $loggedInUserId . ' and `users`.`id` in (' . $getUserIds . ')'));
        } else {
            $sendHelpData = [];
        }


        return Datatables::of($sendHelpData)
            ->addIndexColumn()
            ->editColumn('user_name', function ($row) {
                $username = $row->user_fname . " " . $row->user_lname . " (" . $row->mobile_number . ")";
                return $username;
            })
            ->addColumn('action', function ($row) use ($request) {
                $id = Crypt::encryptString($row->sid);
                $mobileId = Crypt::encryptString($request->mobileId);
                $btn = "<a href='" . url('/normal_user/show-send-help-form/' . $id . '/' . $mobileId) . "' class='item-edit btn btn-outline-dark btn-md px-2 py-1 text-dark'  title='Send Help'><svg xmlns='http://www.w3.org/2000/svg' width=16 height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-eye font-small-4'><path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'></path><circle cx='12' cy='12' r='3''></circle></svg></a> ";
                return $btn;
            })
            ->rawColumns(['action', 'user_name'])
            ->make(true);
    }
    //GH User listing  
    public function getGetHelpData(Request $request)
    {
        // Retrieve the data for the "Send Help" section from your data source
        $loggedInUserId = Auth::user()->id;
        $getGetHelpData = UserMap::where([
            ['mobile_id', $request->mobileId],
            ['type', 'GH']
        ])->get();
        $userIds = $getGetHelpData->pluck('user_id')->toArray();
        $ghUsers = User::whereIn('id', $userIds)->get();

        return Datatables::of($ghUsers)
            ->addIndexColumn()
            ->editColumn('user_name', function ($row) {
                $username = $row->user_fname . " " . $row->user_lname . " (" . $row->mobile_number . ")";
                return $username;
            })
            ->addColumn('action', function ($row) use ($request) {
                $id = Crypt::encryptString($row->id);
                $mobileId = Crypt::encryptString($request->mobileId);
                //  $btn = "<a href='" . url('/normal_user/show-send-help-form/' . $id.'/'.$mobileId) . "' class='item-edit text-dark'  title='View Department'><svg xmlns='http://www.w3.org/2000/svg' width=24 height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-eye font-small-4'><path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'></path><circle cx='12' cy='12' r='3''></circle></svg></a> ";
                $btn = "<a href='" . url('/normal_user/show-get-help-form/' . $id . '/' . $mobileId) . "' class='item-edit btn btn-outline-dark btn-md px-2 py-1 text-dark'  title='View Department'><svg xmlns='http://www.w3.org/2000/svg' width=16 height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-eye font-small-4'><path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'></path><circle cx='12' cy='12' r='3''></circle></svg></a> ";

                return $btn;
            })
            ->rawColumns(['action', 'user_name'])
            ->make(true);
    }
    //Show SH  User Details   
    public function showSendHelpFrom($id, $mobileId)
    {
        // echo $mobileId;
        $mobileId1 = Crypt::decryptString(trim($mobileId));
        $id = Crypt::decryptString($id);
        $title = $this->title;
        $senderUserDetails = User::where('users.id', $id)->first();
        $getPaymentStatus = Payment::where('mobile_id', $mobileId)->where('user_id', $id)->first();
        // dd($getPaymentStatus);
        return view('normaluser.send_help_view_details', compact('title', 'senderUserDetails', 'mobileId', 'getPaymentStatus'));
    }
    //Save Action by SH
    public function saveSendHelp(Request $request)
    {
        try {
            $pay_mobile_id = Crypt::decryptString($request->user_mobile_id);
            //get login user enter refferal id at register time  
            $prv_check = Payment::where('mobile_id', $pay_mobile_id)->where('user_id', Auth::user()->id)->count();
            if ($prv_check) {
                return redirect()->back()->with('error', 'This Send help all ready Processed.');
            }
            $payment = new Payment();
            $payment->mobile_id = $pay_mobile_id;
            $payment->user_id = Auth::user()->id;
            $payment->receivers_id = $request->recevier_id;
            $payment->type = "SH";
            $payment->status = "pending";
            // $imagePath = $request->file('attached_screenshot')->store('public/storage/attached_screenshots');
            $payment->attachment = $request->transaction_number;
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

    // show get help form working 
    public function showGetHelpFrom($id, $mobileId)
    {
        $id = Crypt::decryptString($id);
        $mobileId = Crypt::decryptString($mobileId);
        $title = $this->title;
        $senderUserDetails = User::where('users.id', $id)->first();

        $getPaymentStatus = Payment::where('mobile_id', $mobileId)->where('user_id', $id)->first();
        return view('normaluser.get_help_view_details', compact('title', 'senderUserDetails', 'mobileId', 'getPaymentStatus'));
    }

    public function saveGetHelp(Request $request)
    {
        $title = $this->title;
        $getPaymentStatus = Payment::where('mobile_id', $request->mobile_id)->where('user_id', Auth::user()->id)->first();
        $getPaymentStatus->status = "completed";
        $getPaymentStatus->update();
        return view('normaluser.get_help_view_details', compact('title', 'senderUserDetails', 'mobileId', 'getPaymentStatus'));
    }

    public function paymentrequest(Request $request)
    {
        $title = "Payment Request";
        $data = Payment::with('paymentHas')->where(['receivers_id' => Auth::user()->id, 'status' => 'pending'])->get()->toArray();
        $complted = Payment::with('paymentHas')->where(['receivers_id' => Auth::user()->id, 'status' => 'completed'])->get()->toArray();
        return view('normaluser.payment_request', compact('title', 'data', 'complted'));
    }

    public function assignUserList(Request $request)
    {
        $title = "Payment Request";
        $data = Payment::with('paymentHas')->where(['receivers_id' => Auth::user()->id, 'status' => 'pending'])->get()->toArray();
        $complted = Payment::with('paymentHas')->where(['receivers_id' => Auth::user()->id, 'status' => 'completed'])->get()->toArray();
        return view('normaluser.payment_request', compact('title', 'data', 'complted'));
    }

    public function payment_accept($id, $mobileId)
    {
        $title = "Payment Request";
        $data = Payment::where('payment_id', $id)->update(['status' => 'completed']);
        $data = UserSubInfo::where('mobile_id', $mobileId)->update(['status' => 'green']);
        return back();
    }

    public function active_user($id)
    {
        $title = "Payment Request";
        $data = User::where('id', $id)->update(['user_status' => 'Active']);
        return back();
    }
}
