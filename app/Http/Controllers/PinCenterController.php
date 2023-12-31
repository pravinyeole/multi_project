<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPin;
use DataTables;
use DB;
use Auth;
use Redirect;
class PinCenterController extends Controller
{
    //
    public function __construct(){
        $this->title        = "Pin Center";
        $this->name         = "Pin Center";
        $this->title_msg    = "Pin Ceter";
        // $this->middleware(['auth']);
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
                    $id  = encrypt($row->id);
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
        
        $getNoOfPins = UserPin::where('user_id',Auth::user()->id)->first();
        return view('admin.pincenter.index', compact('title','getNoOfPins'));
    }
    

    public function edit($id) {
        try {
            $title  = $this->title;
            $id     = decrypt($id); 
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
           // Validate the form data
           $validatedData = request()->validate([
            'no_of_pins' => 'required|integer',
        ]);
    try {
        $id = decrypt($id);

        $loginUser = Auth::user();
        $loginUsreId = $loginUser->id;
        // dd($loginUser);
        // Find the user record by ID and deduct pins from current login user;
        $user = User::findOrFail($id);
        // $loginUserPin  = UserPin::where('user_id',$loginUsreId)->first();
        if ($loginUser->user_role === 'S') {
            // If the user role is 'S' (superadmin), do not deduct pins
            // Handle the scenario where 'S' user has unlimited pins
        } else {
            
            // Find the user record by ID and deduct pins from the current login user
            $loginUserPin = UserPin::where('user_id', $loginUsreId)->first();
            if($loginUserPin->pins < $validatedData['no_of_pins']){
                toastr()->error("Your Available Pins Balance is low. Please connect to Superadmin");
                return redirect()->back();
            }
            $loginUserPinBalances = $loginUserPin->pins - $validatedData['no_of_pins'];
            $loginUserPin->pins = $loginUserPinBalances;
            $loginUserPin->save();
        }
        // Create a new user pin record
        $userPin = new UserPin();
        $userPin->user_id = $id;
        $userPin->pins = $validatedData['no_of_pins'];
        // Save the new user pin record
        $userPin->save();

        // Update the user status to 'Active'
        $getUser = User::findOrFail($id);
        $getUser->user_status = 'Active';
        $getUser->save();

        toastr()->success('User approved successfully!!');
        return redirect('pin_center');
        // return redirect('pin_center')->with('success', 'User information updated successfully');
    } catch (\Exception $e) {
        toastr()->error(Config('messages.500'));
        // Handle any exceptions or errors that occur during the update process
        return redirect()->route('pin_center.edit', $id);
    }
    }
}
