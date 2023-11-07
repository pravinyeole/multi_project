<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPin;
use App\Models\UserReferral;
use App\Models\RequestPin;
use App\Models\TransferPin;
use DataTables;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Redirect;
class PinCenterController extends Controller
{
    //
    public function __construct(){
        $this->title        = "Pin Center";
        $this->name         = "Pin Center";
        $this->title_msg    = "Pin Ceter";
        $this->middleware(['auth']);
    }
     
    public function index(Request $request) {
       
        $title = $this->title;
        try {
            if ($request->ajax()) {
                $data = User::join('user_referral', 'users.id', '=', 'user_referral.user_id')
                ->select('users.*')
                ->where('user_referral.referral_id', Auth::user()->mobile_number)
                ->where('user_status','Inactive')
                ->orderBy('users.id', 'DESC')
                ->get();
                return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('user_name', function ($row) {
                    $user_name = $row->user_fname.' '.$row->user_lname;
                    // dd($user_name);
                    return $user_name;
                })
                ->addColumn('pins', function ($row) {
                    return '<input type="number" name="pins[]" class="form-control pins-input" disabled>';
                })
                ->addColumn('event', function ($row) {
                    return '<input type="checkbox" name="event[]" class="form-check-input event-checkbox" disabled>';
                })
                ->addColumn('action', function ($row) {
                    $id  = Crypt::encryptString($row->id);
                    $btn = "<a href='".url('/pin_center/edit/'.$id)."' class='item-edit text-dark'  title='View'><svg xmlns='http://www.w3.org/2000/svg' width=24 height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-eye font-small-4'><path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'></path><circle cx='12' cy='12' r='3''></circle></svg></a>"; 
                    // $btn = "<a href='".url('/pin_center/edit/'.$id)."' class='item-edit text-blue'  title='Edit Class'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit font-small-4'><path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path><path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path></svg></a>";
                    return $btn;
                })
                ->rawColumns(['user_name','pins', 'event','action'])
                ->make(true);
            }
        }catch(\Exception $e) {
            // DB::rollback();
            toastr()->error(Config('messages.500'));
        }
        
        $getNoOfPins = UserPin::where('user_id',Auth::user()->id)->sum('pins');
        $adminAssingToLoginUser = UserReferral::where('user_id', Auth::user()->id)->first();
        // $requestedPins = RequestPin::select('users.*', 'request_pin.*', 'request_pin.created_at as req_created_at')->leftJoin('users', 'users.user_slug', '=', 'request_pin.admin_slug')
        // ->where('request_pin.req_user_id', Auth::user()->id)->limit(5)->get();

        $requestedPins = DB::select('select CONCAT(`users`.user_fname,`users`.user_lname) AS user_name, `request_pin`.*, `request_pin`.`created_at` as `req_created_at`,"inpin" AS requesttype from `request_pin` left join `users` on `users`.`user_slug` = `request_pin`.`admin_slug` where `request_pin`.`req_user_id` = '.Auth::user()->id.' AND `request_pin`.`status` = "Pending" UNION select CONCAT(`users`.user_fname,`users`.user_lname) AS user_name, `request_pin`.*, `request_pin`.`created_at` as `req_created_at`,"outpin" AS requesttype from `request_pin` left join `users` on `users`.`id` = `request_pin`.`req_user_id` where `request_pin`.`admin_slug` = "'.Auth::user()->user_slug.'"  AND `request_pin`.`status` = "Pending"');
        $tarnsferHistory = TransferPin::join('users', 'users.id', '=', 'transfer_pin_history.trans_to')
        ->select('users.user_fname', 'users.user_lname','users.mobile_number', 'transfer_pin_history.trans_count', 'transfer_pin_history.trans_reason', 'transfer_pin_history.created_at')
        ->where('transfer_pin_history.trans_by', Auth::user()->id)->limit(5)->get();

        return view('admin.pincenter.index', compact('title','getNoOfPins','adminAssingToLoginUser','requestedPins','tarnsferHistory'));
    }
    

    public function edit($id) {
        try {
            $title  = $this->title;
            $id     = Crypt::decryptString($id); 
            // $countryAll = Department::all();
             $user = User::select('*')
                ->where('id', $id)
                ->first();

            return view('admin.pincenter.edit',compact('title', 'user'));
        }catch(\Exception $e) {
            toastr()->error('Something went wrong');
            return Redirect::back();
        }
    }

    public function update($id){
        $validatedData = request()->validate([
            'no_of_pins' => 'required|integer',
        ]);
        try {
            $id = decrypt($id);
            $loginUser = Auth::user()->id;
            // Find the user record by ID and deduct pins from current login user;
            $user = User::findOrFail($id);
            if (Auth::user()->user_role === 'S') {
                // If the user role is 'S' (superadmin), do not deduct pins
                // Handle the scenario where 'S' user has unlimited pins
            } else {
                $loginUserPin = UserPin::where('user_id', $loginUser)->first();
                if($loginUserPin){
                    if($loginUserPin->pins < $validatedData['no_of_pins']){
                        return redirect()->back()->with('error','Your Available Pins Balance is low. Please connect to Superadmin');
                    }
                    $inventory = UserPin::firstOrNew(['user_id'=>$loginUser]);
                    if($loginUserPin->pins >= $validatedData['no_of_pins']){
                        $inventory->pins = ($loginUserPin->pins - $validatedData['no_of_pins']);
                    }else{
                        return redirect()->back()->with('error','Your Available Pins Balance is low. Please connect to Superadmin');
                    }
                    $inventory->save();
                    
                    $inventoryTwo = UserPin::firstOrNew(['user_id'=>$id]);
                    $inventoryTwo->pins = $inventoryTwo->pins+$validatedData['no_of_pins'];
                    $inventoryTwo->save();
    
                    $getUser = User::findOrFail($id);
                    $getUser->user_status = 'Active';
                    $getUser->save();
                }else{
                    return redirect()->back()->with('error','Your Available Pins Balance is low. Please connect to Superadmin');
                }
            }
            return redirect('pin_center')->with('success','User approved successfully!!');
            // return redirect('pin_center')->with('success', 'User information updated successfully');
        } catch (\Exception $e) {
            // Handle any exceptions or errors that occur during the update process
            return redirect()->route('pin_center.edit', $id)->with('error',$e);
        }
    }
}
