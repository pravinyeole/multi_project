<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserMap;
use App\Models\UserPin;
use App\Models\UserRole;
use App\Models\RevokePin;
use App\Models\UserReferral;
use App\Models\Announcement;
use DataTables;
use DB;
use Auth;
use Redirect;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class SuperAdminController extends Controller
{
    //
    public function __construct()
    {
        $this->title        = "Super Admin";
        $this->name         = "Super Admin";
        $this->title_msg    = "Super Admin";
        $this->middleware(['auth']);
    }

    public function index()
    {
    }

    // admin listing
    public function admins(Request $request)
    {
        $admin_data = User::join('user_referral AS ur','ur.user_id','users.id')
                        ->select('users.*','ur.admin_slug')
                        ->where('users.user_role', 'A')
                        ->orderBy('users.id', 'DESC')
                        ->get();
        return view('superadmin.admins.index',compact('admin_data'));
    }

    //show crete admin form 
    public function showAdminCreateFrom(Request $request)
    {
        $title = $this->title;
        return view('superadmin.admins.create', compact('title'));
    }
    //show store admin into db  
    public function saveAdmin(Request $request)
    {
        $title = $this->title;
        try {
            // dd($request->all()   );
            // Validate the request data
            // $validatedData = $request->validate([
            //     'first_name' => 'required|string|max:100',
            //     'last_name' => 'required|string|max:100',
            //     'mobile_number' => 'required|digits:10|unique:users,mobile_number',
            //     'email' => 'required|email|unique:users,email',  
            //     'no_of_pins' => 'required',    
            // ]);
            // dd($request->no_of_pins);
            $NoOfpins = $request->no_of_pins;
            if ($request->no_of_pins = Null || empty($request->no_of_pins)) {
                toastr()->error('Please enter correct no of pins');
                return redirect()->back();
            }
            $checkRequestAdminAlreadyExits = User::where('mobile_number', $request->mobile_number)->first();
            if (isset($checkRequestAdminAlreadyExits)) {
                if ($checkRequestAdminAlreadyExits->mobile_number == $request->mobile_number) {
                    toastr()->error('Mobile Number Already exits');
                    return redirect()->back();
                }
                if ($checkRequestAdminAlreadyExits->email == $request->email) {
                    toastr()->error('Email Already exits');
                    return redirect()->back();
                }
            }

            // Create a new user instance
            $user = new User();
            $user->user_fname = $request['first_name'];
            $user->user_lname = $request['last_name'];
            $user->mobile_number = $request['mobile_number'];
            $user->email = $request['email'];
            $admin_slug = substr($request['first_name'], 0, 1) . substr($request['last_name'], 0, 1) . substr($request['mobile_number'], 0, 4);
            $user->user_slug = $admin_slug;
            $user->user_role = 'A';
            // Save the user in the database
            $user->save();
            $user_role = new UserRole();
            $user_role->user_id = $user->id;
            $user_role->role = 'A';
            // Save the user in the database
            $user_role->save();

            $loginSuperAdmin = User::where('id', Auth::user()->id)->first();
            $superAdminSlug = substr($loginSuperAdmin->user_fname, 0, 1) . substr($loginSuperAdmin->user_lname, 0, 1) . substr($loginSuperAdmin->mobile_number, 0, 4);
            $userReferral = new UserReferral();
            $userReferral->user_id = $user->id;
            $userReferral->referral_id = $loginSuperAdmin->mobile_number;
            $userReferral->admin_slug = $superAdminSlug;
            $userReferral->save();

            $user_pin = new UserPin();
            $user_pin->user_id = $user->id;
            $user_pin->pins = $NoOfpins;
            $user_pin->save();

            toastr()->success('Admin created successfully');
            return redirect('superadmin/admin');
        } catch (Exception $e) {
            toastr()->error('Something went wrong');
            return redirect()->back();
        }
    }
    //show edit adminform
    public function showEditAdminFrom(string $crypid=null,Request $request)
    {
        $title = $this->title;
        $id     = Crypt::decryptString($crypid);
        $admin = User::where('id', $id)->first();
        return view('superadmin.admins.edit', compact('title', 'admin'));
    }

    //update admin
    public function updateAdmin(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'id' => 'required|numeric',
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'mobile_number' => 'required|digits:10',
                'email' => 'required|email',
            ]);

            // Find the admin by ID
            $admin = User::findOrFail($validatedData['id']);

            // Check if mobile number or email already exist for other users
            $existingAdmin = User::where('id', '!=', $admin->id)
                ->where(function ($query) use ($validatedData) {
                    $query->where('mobile_number', $validatedData['mobile_number'])
                        ->orWhere('email', $validatedData['email']);
                })
                ->first();

            if ($existingAdmin) {
                $errorMsg = [];

                if ($existingAdmin->mobile_number === $validatedData['mobile_number']) {
                    $errorMsg[] = 'Mobile number already exists.';
                }

                if ($existingAdmin->email === $validatedData['email']) {
                    $errorMsg[] = 'Email already exists.';
                }

                toastr()->error(implode(' ', $errorMsg));
                return back()->withInput();
            }
            // Update the admin's information
            $admin->user_fname = $validatedData['first_name'];
            $admin->user_lname = $validatedData['last_name'];
            $admin->mobile_number = $validatedData['mobile_number'];
            $admin->user_status = 'Active';
            $admin->email = $validatedData['email'];

            // Save the updated admin in the database
            $admin->save();

            toastr()->success('Admin updated successfully');
            return redirect('superadmin/admin');
        } catch (Exception $e) {
            toastr()->error('Something went wrong');
            return back();
        }
    }

    // user listing who dont have any refral code 
    public function userWithOutRefral(Request $request)
    {

        $title = $this->title;
        try {
            if ($request->ajax()) {
                $data = User::select('*')
                    ->where('user_status', 'Inactive')
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('user_referral')
                            ->whereRaw('user_referral.user_id = users.id');
                    })
                    ->orderBy('id', 'DESC')
                    ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('user_name', function ($row) {
                        $user_name = $row->user_fname . ' ' . $row->user_lname;
                        // dd($user_name);
                        return $user_name;
                    })
                    ->addColumn('action', function ($row) {
                        $id  = Crypt::encryptString($row->id);
                        $btn = "<a href='" . url('/superadmin/admin/edit/' . $id) . "' class='item-edit text-blue'  title='Edit'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit font-small-4'><path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path><path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path></svg></a>";
                        return $btn;
                    })
                    ->rawColumns(['user_name', 'action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            // DB::rollback();
            dd($e);
            toastr()->error(Config('messages.500'));
        }

        return view('superadmin.users_without_referral.index', compact('title'));
    }
    
    //show all user list
    public function showAllUser(Request $request){
        $title = $this->title;
        try {
            if ($request->ajax()) {
                $data = User::select('*')->orderBy('id','DESC')->get();
                
        
                return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('user_name', function ($row) {
                    $user_name = $row->user_fname.' '.$row->user_lname;
                    // dd($user_name);
                    return $user_name;
                })
                ->addColumn('action', function ($row) {
                    $id  = Crypt::encryptString($row->id);
                    $userStatus = $row->user_status;
                    $blockIcon = $userStatus == 'Inactive' ? 'fas fa-lock' : 'fas fa-lock-open';
                    $blockColor = $userStatus == 'Inactive' ? 'red' : 'green';
                    
                    // $btn = "<a href='".url('/superadmin/admin/block/'.$id)."' class='delete-record item-block' style='color: $blockColor;' title='".($userStatus == 'Inactive' ? 'Unlock' : 'Block')."' data-model='Department'><i class='$blockIcon'></i></a>";
                    if($userStatus == 'Active'){
                        $status = "<button title='Active' data-id='$row->id' data-type='Active' data-model='User' class='btn btn-success status'>Active</button>";
                    }else{
                        $status = "<button title='Inactive' data-id='$row->id' data-type='Inactive' data-model='User' class='btn btn-danger status'>Inactive</button>";
                    }
                    return $status;
                    // return $btn;
                })
                ->rawColumns(['user_name','action'])
                ->make(true);
            }
        }catch(\Exception $e) {
            // DB::rollback();
            dd($e);
            toastr()->error(Config('messages.500'));
        }
        
        return view('superadmin.allusers.index', compact('title'));
    }

    //show all user list
    public function showRevokePin(Request $request)
    {
        $title = $this->title;
        if(Auth::user()->user_role == 'U'){
            $getAllUser = User::join('user_referral AS ur','ur.user_id','users.id')
                        ->join('user_pins AS up','up.user_id','users.id')
                        ->where('ur.admin_slug',Auth::user()->user_slug)
                        ->OrWhere('ur.referral_id',Auth::user()->mobile_number)
                        ->where('users.user_role','U')
                        ->where('up.pins','>',0)
                        ->where('users.user_status','Active')
                        ->get();
        }else if(Auth::user()->user_role == 'A'){
            $getAllUser = User::join('user_referral AS ur','ur.user_id','users.id')
                        ->join('user_pins AS up','up.user_id','users.id')
                        ->where('ur.admin_slug',Auth::user()->user_slug)
                        ->OrWhere('ur.referral_id',Auth::user()->mobile_number)
                        ->where('users.user_status','Active')
                        ->where('up.pins','>',0)
                        ->get();
        }else if(Auth::user()->user_role == 'S'){
            $getAllUser = User::join('user_pins AS up','up.user_id','users.id')
                        ->where('users.user_status','Active')
                        ->where('up.pins','>',0)->get(); 
        }
        $revokeHistory=RevokePin::join('users','users.id','revoke_from')->select('users.user_fname','users.user_lname','revoke_count','revoke_pin_history.revoke_reason','revoke_pin_history.created_at')->where('revoke_by',Auth::user()->id)->get();
        return view('superadmin.revokepin.index', compact('title', 'getAllUser','revokeHistory'));
    }

    //show all user list
    public function getRevokePinUserDetails(Request $request)
    {
        try {
            if ($request->ajax()) {
                $userId = $request->id;
                $getSelectedUserDetails = User::find($userId);
                $getNoOfPins = UserPin::where('user_id', $userId)->first();
                // Set the number of pins to 0 if it is null or not found
                $noOfPins = $getNoOfPins ? $getNoOfPins->pins : 0;

                // Check if the user details exist
                if ($getSelectedUserDetails) {
                    // Return the user details as a JSON response
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'user_id' => $getSelectedUserDetails->id,
                            'user_fname' => $getSelectedUserDetails->user_fname,
                            'user_lname' => $getSelectedUserDetails->user_lname,
                            'email' => $getSelectedUserDetails->email,
                            'mobile' => $getSelectedUserDetails->mobile_number,
                            'no_of_pins' => $noOfPins
                        ]
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'User details not found.'
                    ]);
                }
            }
        } catch (Exception $e) {
            toastr()->error('Something went wrong');
            return back();
        }
    }

    public function saveRevoke(Request $request)
    {
        try {
            $getUserPins = UserPin::where('user_id', $request->user_id)->first();
            if (empty($request->revoke_pins)) {
                return back()->with('error','Please enter the number of pins to revoke');
            }
            if ($getUserPins) {
                $updatedPins = $getUserPins->pins - $request->revoke_pins;
                if ($updatedPins < 0) {
                    toastr()->error('User has ' . $getUserPins->pins . ' pins and you are trying to revoke ' . $request->revoke_pins . ' pins.');
                    return back();
                }
                $getUserPins->pins = $updatedPins;
                $getUserPins->update();
                RevokePin::create(['revoke_by'=>Auth::user()->id,'revoke_from'=>$request->user_id,'revoke_count'=>$request->revoke_pins]);
                $userPins = UserPin::where('user_id', Auth::user()->id)->first();
                if ($userPins) {
                    $userPins->pins += $request->revoke_pins;
                    $userPins->update();
                } else {
                    $userpinstbl = new UserPin();
                    $userpinstbl->user_id = $request->req_user_id;
                    $userpinstbl->pins = $request->revoke_pins;
                    $userpinstbl->save();
                }
                return back()->with('success','Pins revoked successfully');
            } else {
                return back()->with('error','User pins not found');
            }
        } catch (Exception $e) {
            return back()->with('error','Something went wrong');
        }
    }

    public function showAssignUserFrom(Request $request)
    {
        date_default_timezone_set( 'Asia/Kolkata');
        // $getOldUser = User::all();
        $title = $this->title;
        $interval = now()->subDays(2)->endOfDay();
        $from_date = \Carbon\Carbon::today()->subDays(8);
        $to_date = \Carbon\Carbon::today()->subDays(8);
        $to_date = str_replace('00:00:00','23:59:59',$to_date);
        $userIds_res = UserMap::pluck('mobile_id')->all();
        $userIds = $userIds_res;
        
        $getOldUser = User::join('user_sub_info', 'users.id', '=', 'user_sub_info.user_id')
            ->select('users.*', 'user_sub_info.mobile_id')
            ->whereBetween('user_sub_info.created_at',[$from_date,$to_date])
            ->get();
        
        // $getOldUser = User::whereDate('users.created_at', '=', date('Y-m-d', strtotime('-7 days')))
        //     ->where('user_role', '!=', 'S')
        //     ->whereNotIn('users.id',[$userIds])
        //     ->get();  
        
        // $getOldUser = User::join('user_sub_info', 'users.id', '=', 'user_sub_info.user_id')
        // ->join('payments', 'user_sub_info.mobile_id', '=', 'payments.mobile_id')
        // ->select('users.*', 'user_sub_info.mobile_id')
        // ->where('payments.status','completed')
        // ->get();
        $from_date_one = \Carbon\Carbon::today()->subDays(1);
        $to_date_one = \Carbon\Carbon::today()->subDays(1);
        $to_date_one = str_replace('00:00:00','23:59:59',$to_date_one);
        // Retrieve recently joined users from "users" table
        $getRecentlyJoinUser = User::whereBetween('created_at',[$from_date_one,$to_date_one])
        ->where('user_role','!=','S')
        ->where('user_status','Active')->get();
        // $getRecentlyJoinUser = User::where('user_role', '!=', 'S')->get();

        // $getRecentlyJoinUser = User::whereDate('created_at', '=', now()->toDateString())->get();

        return view('superadmin.assignuser.index', compact('title', 'userIds','getOldUser', 'getRecentlyJoinUser','from_date','from_date_one','to_date','to_date_one'));
    }

    // public function saveAssignUsers(Request $request){
    //     $data = request()->all();
    //    // Assuming you have the above array stored in the variable $data
    //     $sendHelp = $data['sendHelp'];
    //     $getHelp = $data['getHelp'];

    //     $assignedUsers = [];

    //     foreach ($sendHelp as $sendUser) {
    //         $sendUserId = $sendUser['id'];
    //         $sendUserName = $sendUser['name'];

    //         // Find the corresponding Get Help user by matching IDs
    //         foreach ($getHelp as $getUser) {
    //             if ($getUser['id'] === $sendUserId) {
    //                 $getUserId = $getUser['id'];
    //                 $getUserName = $getUser['name'];

    //                 // Store the mapped data in the assignedUsers array
    //                 $assignedUsers[] = [
    //                     'sendHelpId' => $sendUserId,
    //                     'sendHelpName' => $sendUserName,
    //                     'getHelpId' => $getUserId,
    //                     'getHelpName' => $getUserName
    //                 ];

    //                 // Break out of the inner loop since the match is found
    //                 break;
    //             }
    //         }
    //     }

    //     // Now you have the mapped data in the $assignedUsers array
    //     // You can use this array to perform further operations or display the data as needed
    //     foreach ($assignedUsers as $assignedUser) {
    //         echo "Send Help User: {$assignedUser['sendHelpName']}, Get Help User: {$assignedUser['getHelpName']}" . PHP_EOL;
    //     }
    //     dd($assignedUsers);
    //     // $userMappings will contain an associative array where the key is the getHelp user ID and the value is an array of corresponding sendHelp users

    // }
    public function saveAssignUsers(Request $request)
    {
        try {
            $formData = $request->all();
            // Remove unwanted keys
            unset($formData['type'], $formData['_token']);
            
            // Extract the mobile ID key
            // $mobileIdKey = key($formData);
            $mobileIdKey = array_keys($formData);
            // Validate if the mobile ID key exists
            if (count($mobileIdKey) <= 0) {
                toastr()->error('Invalid data');
                return back();
            }
            $invalidId = [];
            $alreadyMapped = [];
            $validMapped = [];
            $rows = [];
            for ($i = 0; $i < count($mobileIdKey); $i++) {
                // Extract the mobile ID from the key
                $exploded = explode('_', $mobileIdKey[$i]);

                // Validate if the mobile ID exists
                if (!isset($exploded[1])) {
                    array_push($invalidId, $exploded[1]);
                    // toastr()->error('Invalid mobile ID');
                    // return back();
                }

                $mobileID = $exploded[1];
                // $userIds = $formData[$mobileIdKey[$i]] ?: [];
                $userIds = explode(',',$formData[$mobileIdKey[$i]][0]);
                // Check if the user mapping already exists
                $existingUserMap = UserMap::where('mobile_id', $mobileID)
                    ->whereIn('user_id', $userIds)
                    ->exists();

                if ($existingUserMap) {
                    array_push($alreadyMapped, $exploded[1]);
                    // toastr()->error('User is already mapped');
                    // return back();
                }else{
                    // Create an array to hold the rows for insertion
    
                    foreach ($userIds as $userId) {
                        // Create a row array for each user ID
                        if($userId>0){
                            $row = [
                                'mobile_id' => $mobileID,
                                'new_user_id' => $exploded[0],
                                'user_id' => $userId,
                                'type' => 'GH', // Hard-coded type as 'GH'
                            ];
        
                            // Add the row to the rows array
                            $rows[] = $row;
                        }
                    }
                    array_push($validMapped, $mobileID);
                    // Insert the rows into the "user_map_new" table
                }
            }
            if (count($invalidId)) {
                $resultArray['invalidId'] = json_encode($invalidId);
            }
            if (count($validMapped)) {
                $resultArray['validMapped'] = json_encode($validMapped); /*User Map Successfully*/
            }
            if (count($alreadyMapped)) {
                $resultArray['alreadyMapped'] = json_encode($alreadyMapped);
            }
            UserMap::insert($rows);

            // toastr()->success('User Map Successfully');
            return redirect()->back()->with($resultArray);
        } catch (Exception $e) {
            toastr()->error('Something went wrong');
            return back();
        }
    }

    public function showannouncement(Request $request)
    {
        $title = $this->title;
        try {
            if ($request->ajax()) {
                $data = Announcement::select('*')->orderBy('id', 'DESC')->get();


                return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('type', function ($row) {
                        $user_name = $row->type;
                        return $user_name;
                    })
                    ->editColumn('announce', function ($row) {
                        $user_role = $row->announce;
                        return $user_role;
                    })
                    ->editColumn('start_date', function ($row) {
                        $start_date = $row->start_date;
                        return $start_date;
                    })
                    ->editColumn('end_date', function ($row) {
                        $end_date = $row->end_date;
                        return $end_date;
                    })
                    ->addColumn('action', function ($row) {
                        $id  = Crypt::encryptString($row->id);
                        $btn = "<a href='" . url('/superadmin/delete/' . $id) . "' class='item-edit text-blue'  title='Delete'><svg xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' width='50' height='50' viewBox='0 0 48 48'>
                        <path fill='#F44336' d='M21.5 4.5H26.501V43.5H21.5z' transform='rotate(45.001 24 24)'></path><path fill='#F44336' d='M21.5 4.5H26.5V43.501H21.5z' transform='rotate(135.008 24 24)'></path>
                        </svg></a>";
                        return $btn;
                    })
                    ->rawColumns(['action'])        
                    ->make(true);
            }
        } catch (\Exception $e) {
            // DB::rollback();
            dd($e);
            toastr()->error(Config('messages.500'));
        }

        return view('superadmin.annoucement.index', compact('title'));
    }
    public function announce_create(Request $request)
    {
        $data['type'] = "'".$request->type."'";
        $data['start_date'] = date('Y-m-d',strtotime($request->start_date));
        $data['end_date'] = date('Y-m-d',strtotime($request->end_date));
        $data['announce'] = $request->anno;
        $res = Announcement::Create($data);
        return back();
    }
    public function delete(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        $res = Announcement::where('id',$id)->delete();
        return back();
    }
}
