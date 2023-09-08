<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\UserReferral;
use App\Models\RequestPin;
use App\Models\User;
use App\Models\UserPin;
use App\Models\TransferPin;
use Carbon\Carbon;
use DataTables;
use DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class RequestPinController extends Controller
{
  //
  public function __construct()
  {
    $this->title = "Request Pins";
    $this->middleware(['auth'])->except(['saveUserRoleConfig']);
  }
  public function index(Request $request)
  {
    $title = $this->title;
    $adminAssingToLoginUser = UserReferral::where('user_id', Auth::user()->id)->first();
    $requestedPins = RequestPin::select('users.*', 'request_pin.*', 'request_pin.created_at as req_created_at')->leftJoin('users', 'users.user_slug', '=', 'request_pin.admin_slug')
      ->where('request_pin.req_user_id', Auth::user()->id)
      ->get();

    return view('requestpin.index', compact('title', 'adminAssingToLoginUser', 'requestedPins'));
  }

  // sendPinRequestToAdmin
  public function sendPinRequestToAdmin(Request $request)
  {
    try {
      // Get the logged-in user ID
      $user_id = Auth::user()->id;
      // Validate the request data
      $validatedData = $request->validate([
        'admin_slug' => ['required', Rule::notIn(['', '0'])],
        'no_of_pin_requested' => 'required|integer',
      ], [
        'admin_slug.required' => 'The admin slug field is required.',
        'admin_slug.not_in' => 'The admin slug field cannot be empty or 0.',
        'no_of_pin_requested.required' => 'The number of pins field is required.',
        'no_of_pin_requested.integer' => 'The number of pins must be an integer.',
      ]);
      $findAdmin = User::where('user_slug', $request->admin_slug)->count();
      if (isset($findAdmin) && ($findAdmin <= 0 || $findAdmin == null)) {
        return redirect()->back()->with('error', 'Admin Slug is invalid');
      }
      // Create a new RequestPin instance
      $requestPin = new RequestPin();
      $requestPin->admin_slug = $validatedData['admin_slug'];
      $requestPin->no_of_pin = $validatedData['no_of_pin_requested'];
      $requestPin->req_user_id = $user_id;
      $requestPin->status = 'pending';
      // Set the created_at timestamp
      $requestPin->created_at = Carbon::now();
      $requestPin->updated_at = Carbon::now();

      // Save the request pin in the database
      $requestPin->save();

      toastr()->success('Pin request sent successfully');
      return redirect()->back();
    } catch (\Exception $e) {
      // toastr()->error(Config('messages.500'));
      return redirect()->back()->with('error', 'Admin Slug is invalid');
    }
  }


  public function showAdminRequestAcceptPage(Request $request)
  {
    $title = $this->title;
    try {
      if ($request->ajax()) {
        $user = User::where('id', Auth::user()->id)->first();
        $requestedPins = RequestPin::select('users.*', 'request_pin.*', 'request_pin.created_at as req_created_at')
          ->leftJoin('users', 'users.id', '=', 'request_pin.req_user_id')
          ->where('request_pin.admin_slug', $user->user_slug)
          ->where('request_pin.status', 'pending')
          ->get();
        return Datatables::of($requestedPins)
          ->addIndexColumn()
          ->editColumn('user_name', function ($row) {
            $user_name = $row->user_fname . ' ' . $row->user_lname;
            // dd($user_name);
            return $user_name;
          })
          ->addColumn('action', function ($row) {

            $pin_request_id  = Crypt::encryptString($row->pin_request_id);
            $btn = "<a href='" . url('/pins-request/edit/' . $pin_request_id) . "' class='item-edit text-dark'  title='View'><svg xmlns='http://www.w3.org/2000/svg' width=24 height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-eye font-small-4'><path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'></path><circle cx='12' cy='12' r='3''></circle></svg></a>";
            return $btn;
          })
          ->rawColumns(['user_name', 'action'])
          ->make(true);
      }
      return view('requestpin.show_req_admin_page', compact('title'));
    } catch (\Exception $e) {
      toastr()->error(Config('messages.500'));
      return redirect()->back();
    }
  }

  public function editPinRequestToAdminPage($pin_request_id)
  {
    $title = $this->title;
    $pin_request_id = Crypt::decryptString($pin_request_id);
    $user = RequestPin::select('users.*', 'request_pin.*', 'request_pin.created_at as req_created_at')
      ->leftJoin('users', 'users.id', '=', 'request_pin.req_user_id')
      ->where('request_pin.pin_request_id', $pin_request_id)
      ->first();
    return view('requestpin.edit_req_admin_page', compact('title', 'user'));
  }

  // public function updatePinRequestToAdmin(Request $request){
  //   try{
  //     $checkIsSuperAdmin= User::where('id',Auth::user()->id)->where('user_role','S')->first();
  //     if (isset($checkIsSuperAdmin)) {
  //       // Code for Superadmin
  //       $userPins = UserPin::where('user_id',$request->req_user_id)->first();
  //       $userPins->pins = $userPins->pins + $request->no_of_pins;
  //       $userPins->update();
  //       $requestPins = RequestPin::where('pin_request_id',$request->pin_request_id)->first();
  //       $requestPins->status ='completed'; 
  //       $requestPins->updated_at = Carbon::now();
  //       $requestPins->update();
  //       toastr()->success('Pins Transfer successfully!!');
  //       return redirect('pins-request');
  //     } else {
  //       $updateSelfPins = UserPin::where('user_id',Auth::user()->id)->first();
  //       $adminAvaiablePins = $updateSelfPins->pins ;
  //       if($request->no_of_pins >= $adminAvaiablePins){
  //         toastr()->error("Your Available Pins Balances is low.Please connect to Superadmin");
  //         return redirect()->back();
  //       }
  //       $userPins = UserPin::where('user_id',$request->req_user_id)->first();
  //       $userPins->pins = $userPins->pins + $request->no_of_pins;
  //       $userPins->update();
  //       $requestPins = RequestPin::where('pin_request_id',$request->pin_request_id)->first();
  //       $requestPins->status ='completed'; 
  //       $requestPins->updated_at = Carbon::now();
  //       $requestPins->update();
  //       toastr()->success('Pins Transfer successfully!!');
  //       return redirect('pins-request');
  //     }
  //    }catch(\Exception $e) {
  //     dd($e);
  //       toastr()->error(Config('messages.500'));
  //       return redirect()->back();
  //   }
  // }

  public function updatePinRequestToAdmin(Request $request)
  {
    try {
      $checkIsSuperAdmin = User::where('id', Auth::user()->id)->where('user_role', 'S')->first();

      $userPins = UserPin::where('user_id', $request->req_user_id)->first();
      if ($userPins) {
        $userPins->pins += $request->no_of_pins;
        $userPins->update();
      } else {
        $userpinstbl = new UserPin();
        $userpinstbl->user_id = $request->req_user_id;
        $userpinstbl->pins = $request->no_of_pins;
        $userpinstbl->save();
      }
      $requestPins = RequestPin::where('pin_request_id', $request->pin_request_id)->first();
      $requestPins->status = 'completed';
      $requestPins->updated_at = Carbon::now();
      $requestPins->update();

      if (isset($checkIsSuperAdmin)) {
        // Code for Superadmin
        toastr()->success('Pins Transfer successfully!!');
        return redirect('pins-request');
      } else {
        $updateSelfPins = UserPin::where('user_id', Auth::user()->id)->first();
        if (isset($updateSelfPins->pins) && $updateSelfPins->pins > 0) {
          $adminAvailablePins = $updateSelfPins->pins;

          if ($request->no_of_pins >= $adminAvailablePins) {
            toastr()->error("Your Available Pins Balance is low. Please connect to Superadmin");
            return redirect()->back();
          }
        } else {
          toastr()->error("Your Available Pins Balance is low. Please connect to Superadmin");
          return redirect()->back();
        }

        toastr()->success('Pins Transfer successfully!!');
        return redirect('pins-request');
      }
    } catch (\Exception $e) {
      dd($e);
      toastr()->error(Config('messages.500'));
      return redirect()->back();
    }
  }
  public function direct_ref_user_list(Request $request)
  {
    $title = "Direct Referance Users List";
    if (Auth::user()->user_role == 'U') {
      $data = User::join('user_referral', 'users.id', '=', 'user_referral.user_id')
        ->select('users.*', 'users.created_at as id_created_date', 'user_status', 'user_referral.referral_id as referral_id', 'user_referral.admin_slug as admin_slug')
        ->where('user_referral.referral_id', Auth::user()->mobile_number)
        ->get();
    } else {
      $data = User::join('user_referral', 'users.id', '=', 'user_referral.user_id')
        ->select('users.*', 'users.created_at as id_created_date', 'user_status', 'user_referral.referral_id as referral_id', 'user_referral.admin_slug as admin_slug')
        ->where('user_referral.admin_slug', Auth::user()->user_slug)
        ->get();
    }
    return view('reffral.index', compact('title', 'data'));
  }
  public function adminTransferPinSubmit(Request $request)
  {
    $checkBalance  = UserPin::where('user_id', Auth::user()->id)->where('pins', '>', '0')->sum('pins');
    if ($checkBalance >= $request->trans_number && $request->trans_number > 0) {
      $tarspin = new TransferPin();
      $tarspin->trans_by = Auth::user()->id;
      $tarspin->trans_to = $request->trans_id;
      $tarspin->trans_count = $request->trans_number;
      $tarspin->trans_reason = $request->trans_reason;
      $tarspin->save();
      if (isset($tarspin->trans_id) && $tarspin->trans_id > 0) {
        UserPin::where('user_id', Auth::user()->id)->decrement('pins', $request->trans_number);
        $inventory = UserPin::firstOrNew(['user_id' => $request->trans_id]);
        $inventory->pins = ($inventory->pins + $request->trans_number);
        $inventory->save();
        return redirect()->back()->with('success', 'Pin Transfer Successfully.');
      }
    }
    return redirect()->back()->with('error', 'You Dont Have Pin Balance to Transfer OR Incorrect Count to Transfer.');
  }
  public function adminTransferPin(Request $request)
  {
    if (Auth::user()->user_role == 'U') {
      $normal_udata = User::join('user_referral', 'users.id', '=', 'user_referral.user_id')
        ->select('users.*', 'users.created_at as id_created_date', 'user_status', 'user_referral.referral_id as referral_id', 'user_referral.admin_slug as admin_slug')
        ->where('user_referral.referral_id', Auth::user()->mobile_number)
        ->get();
    } else {
      $normal_udata = User::join('user_referral', 'users.id', '=', 'user_referral.user_id')
        ->select('users.*', 'users.created_at as id_created_date', 'user_status')
        ->where('user_referral.admin_slug', Auth::user()->user_slug)
        ->get();
    }
    $tarnsferHistory = TransferPin::join('users', 'users.id', '=', 'transfer_pin_history.trans_to')
      ->select('users.user_fname', 'users.user_lname', 'transfer_pin_history.trans_count', 'transfer_pin_history.trans_reason', 'transfer_pin_history.created_at')
      ->where('transfer_pin_history.trans_by', Auth::user()->id)->get();
    return view('admin.pincenter.transfer', compact('normal_udata', 'tarnsferHistory'));
  }
}
