<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Mail\CreateUser;
use App\Models\Notification;
use App\Models\InsuranceAgency;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersRole;
use App\Models\UserAceessTeam;
use App\Models\Group;
use App\Models\InsuranceAgencyContact;
use App\Models\InsuranceAgencyGroupHistory;
use App\Models\UserInvitation;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRole;
use DataTables;
use Hash;
use Mail;
use DB;
use Config;
use Session;

use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Symfony\Component\Console\Input\Input;

class UserController extends Controller
{
    use CommonTrait;
    public function __construct(){
        $this->title = "Users";
        $this->middleware(['auth','paymentMethod']);
        $this->middleware('twoFactorAuth')->except(['checkOTPexist','resendOTP','updateProfileAction','userByID']);
    }
    
    public function index(Request $request){
        $title = $this->title;
        $agency_list = $this->getAgency();
        $InsuranceAgencyID = Session('INSURANCE_AGENCY_ID');
        if ($request->ajax()) {
            if(Session::get('USER_TYPE') == 'T'){
            //   return  $this->getUsersArr();
                // if($this->isAdmin() == '0'){
                // $data = User::with('get_org_name')->where(['user_type' => 'U', 'team_id' => Auth::user()->team->team_id])->whereNull('deleted_at')->orderBy('id', 'DESC')->get();
                // }else{
                    // $data = User::join('user_aceess_teams', function($join) use($InsuranceAgencyID){
                    //     $join->on('users.id','=','user_aceess_teams.user_id')
                    //     ->where('user_aceess_teams.insurance_agency_id',$InsuranceAgencyID)
                    //     ->whereIn('user_aceess_teams.team_id',Auth::user()->getAuthTeamsID());
                    // })
                    // ->join('user_roles', function($join) use($InsuranceAgencyID) {
                    //     $join->on('users.id','=','user_roles.user_id')
                    //     ->where(['user_roles.role' => 'U','user_roles.insurance_agency_id' => $InsuranceAgencyID ]);
                    // })
                    // ->whereNull('users.deleted_at')
                    // ->select('*')
                    // ->IgnoreSelf()
                    // ->groupBy('users.id')
                    // ->orderBy('id', 'DESC')
                    // ->get();
                    $user = User::join('user_aceess_teams', function($join) use($InsuranceAgencyID){
                        $join->on('users.id','=','user_aceess_teams.user_id')
                        ->where('user_aceess_teams.insurance_agency_id',$InsuranceAgencyID)
                        ->whereIn('user_aceess_teams.team_id',Auth::user()->getAuthTeamsID());
                    })
                    ->join('user_roles', function($join) use($InsuranceAgencyID) {
                        $join->on('users.id','=','user_roles.user_id')
                        ->whereIn('user_roles.role',['U','T'])
                        ->where('user_roles.insurance_agency_id',$InsuranceAgencyID);
                        
                    })
                    ->whereNull('users.deleted_at')
                    ->select('*')
                    ->IgnoreSelf()
                    ->groupBy('users.id');
                    $data = User::leftjoin('user_aceess_teams', function($join) use($InsuranceAgencyID){
                        $join->on('users.id','=','user_aceess_teams.user_id');
                    })
                    ->join('user_roles', function($join) use($InsuranceAgencyID) {
                        $join->on('users.id','=','user_roles.user_id')
                        ->whereIn('user_roles.role',['OA','O'])
                        ->where('user_roles.insurance_agency_id',$InsuranceAgencyID);
                        
                    })
                    ->whereNull('users.deleted_at')
                    ->select('*')
                    ->IgnoreSelf()
                    ->groupBy('users.id')
                    ->union($user);
                    
                    $data = $data->orderBy('id', 'DESC')->get();;
                // }
            }elseif(in_array(Session('USER_TYPE'), ['O'])){
                //     $data = User::with(['createdby','get_org_name','insuranceTeam','userRole'=> function($q) use( $InsuranceAgencyID){
                //         $q->where('insurance_agency_id', $InsuranceAgencyID);
                // }])->InsuranceAgencyScope()->whereNull('deleted_at')->orderBy('id', 'DESC')->get();
                // $data = User::with(['createdby','get_org_name','insuranceTeam','userRole'])->InsuranceAgencyScope()->whereNull('deleted_at')->orderBy('id', 'DESC')->get();
                $data = User::join('user_roles', function ($join) use ($InsuranceAgencyID) {
                    $join->on('users.id', '=', 'user_roles.user_id')
                        ->where(['user_roles.insurance_agency_id' => $InsuranceAgencyID])
                        // ->whereNotIn('user_roles.role',['O']);
                        ->whereIn('user_roles.role',['U','T','O','OA']);
                    })
                    ->whereNull('users.deleted_at')
                    ->select('*')
                    // ->IgnoreSelf()
                    // ->orderBy('id', 'DESC')
                    ->orderBy('user_roles.created_at', 'DESC')
                    ->get();
            }elseif(in_array(Session('USER_TYPE'), ['OA'])){
                $data = User::join('user_roles', function ($join) use ($InsuranceAgencyID) {
                    $join->on('users.id', '=', 'user_roles.user_id')
                        ->where(['user_roles.insurance_agency_id' => $InsuranceAgencyID])
                        // ->whereNotIn('user_roles.role',['O']);
                        ->whereIn('user_roles.role',['U','T','O','OA']);
                    })
                    ->whereNull('users.deleted_at')
                    ->select('*')
                    ->IgnoreSelf()
                    // ->orderBy('id', 'DESC')
                    ->orderBy('user_roles.created_at', 'DESC')
                    ->get();
            }
            elseif(in_array(Session('USER_TYPE'),['SA','A'])){
                $data = User::
                join('user_roles','user_roles.user_id','users.id')->with(['createdby','get_org_name','insuranceTeam'])->whereHas('userRole', function($q){
                    $q->whereIn('role',['OA','U','T']);
                })->whereNull('deleted_at')->orderBy('id', 'DESC')->get();

            }else{
                //$data = User::with(['createdby','get_org_name','insuranceTeam','userRole'])->TeamAccessScope()->InsuranceAgencyScope()->IgnoreSelf()->whereNull('deleted_at')->orderBy('id', 'DESC')->get();
                // $data = User::
                //             join('user_aceess_teams','users.id','user_aceess_teams.user_id')
                //             // ->where('user_aceess_teams.user_id',Auth::id())
                //             ->orWhereIn('user_aceess_teams.team_id',$this->getTeamsArr())
                //             ->with('get_org_name')->where(['user_type' => 'U'])
                //             ->groupBy('users.id')
                //             ->whereNull('deleted_at')->orderBy('id', 'DESC')->get();
                // $data = User::with(['createdby','get_org_name','insuranceTeam'])->where(['user_type' => 'U','created_by_id'=> Auth::id()])->whereNull('deleted_at')->orderBy('id', 'DESC')->get();
                // return  $data;
                // $data = User::join('user_aceess_teams', function($join){
                //     $join->on('users.id','=','user_aceess_teams.user_id')
                //     ->whereIn('user_aceess_teams.team_id',Auth::user()->getAuthTeamsID());
                // })
                // ->join('user_roles', function($join) use($InsuranceAgencyID) {
                //     $join->on('users.id','=','user_roles.user_id')
                //     ->where(['user_roles.role' => 'U','user_roles.insurance_agency_id' => $InsuranceAgencyID ]);
                // })
                // ->whereNull('users.deleted_at')
                // ->select('*')
                // ->IgnoreSelf()
                // ->orderBy('id', 'DESC')
                // ->groupBy('users.id')
                // ->get();

                $data = User::join('user_aceess_teams', function($join) use($InsuranceAgencyID){
                    $join->on('users.id','=','user_aceess_teams.user_id')
                    ->where('user_aceess_teams.insurance_agency_id',$InsuranceAgencyID)
                    ->whereIn('user_aceess_teams.team_id',Auth::user()->getAuthTeamsID());
                })
                ->join('user_roles', function($join) use($InsuranceAgencyID) {
                    $join->on('users.id','=','user_roles.user_id')
                    ->whereIn('user_roles.role',['U','T','O'])
                    ->where('user_roles.insurance_agency_id',$InsuranceAgencyID);
                    
                })
                ->whereNull('users.deleted_at')
                ->select('*')
                ->IgnoreSelf()
                ->groupBy('users.id');
                $insuranceAgencies = $data = User::leftjoin('user_aceess_teams', function($join) use($InsuranceAgencyID){
                    $join->on('users.id','=','user_aceess_teams.user_id');
                })
                ->join('user_roles', function($join) use($InsuranceAgencyID) {
                    $join->on('users.id','=','user_roles.user_id')
                    ->whereIn('user_roles.role',['OA','O'])
                    ->where('user_roles.insurance_agency_id',$InsuranceAgencyID);
                    
                })
                ->whereNull('users.deleted_at')
                ->select('*')
                ->IgnoreSelf()
                ->groupBy('users.id')
                ->orderBy('id', 'DESC')
                ->union($data)
                ->get();

            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('user_status', function ($row) {    
                if (in_array($row->role, ['OA', 'T','O']) && Session('USER_TYPE') == 'T') {
                    $status = '&nbsp&nbsp&nbsp&nbsp&nbsp'.$row->user_status;
                }
                elseif($row->role == 'O' && Session('USER_TYPE') == 'O'){
                    $status = '&nbsp&nbsp&nbsp&nbsp&nbsp'.$row->user_status;
                } 
                elseif($row->role == 'O' && Session('USER_TYPE') == 'OA'){
                    $status = '&nbsp&nbsp&nbsp&nbsp&nbsp'.$row->user_status;
                }   
                else {
                    if ($row->user_role_status == 'Active') {
                        $status = "<button title='Active' data-id='$row->id'   data-url='users' data-type='Inactive' class='btn btn-success status'>Active</button>";
                    } else {
                        $status = "<button title='Inactive' data-id='$row->id' data-url='users' data-type='Active' class='btn btn-danger status'>Inactive</button>";
                    }
                }
                    
                    return $status;
                })

                ->addColumn('action', function($row){
                    $id = Crypt::encryptString($row->id);
                    if($row->role == 'O'){
                        $btn = "<a href='users/view/$id' class='item-edit btn btn-outline-primary btn-md px-2 py-1  text-warning'  title='View User'><svg xmlns='http://www.w3.org/2000/svg' width=16 height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-eye font-small-4'><path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'></path><circle cx='12' cy='12' r='3''></circle></svg></a> &nbsp;";
                    }
                    else{
                        $btn = "<a href='users/view/$id' class='item-edit btn btn-outline-primary btn-md px-2 py-1  text-warning'  title='View User'><svg xmlns='http://www.w3.org/2000/svg' width=16 height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-eye font-small-4'><path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'></path><circle cx='12' cy='12' r='3''></circle></svg></a> &nbsp;";
                        if(Session::get('USER_TYPE')!='U'){
                            if(!(Session('USER_TYPE') == 'T' && ($row->role == 'OA'))){
                                $btn .= "<a href='users/edit/$id' class='item-edit btn btn-outline-primary btn-md px-2 py-1'  title='Edit User'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit font-small-4'><path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path><path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path></svg></a> &nbsp;" ;
                            }
                        }
                        if(Session::get('USER_TYPE') == 'A'){
                            $btn .="<a class='delete-record delete item-edit btn btn-outline-danger btn-md px-2 py-1 text-danger' data-id='$row->id' data-url='users' title='Delete User'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2 font-small-4'><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></a>&nbsp;";
                        }
                        if(Session::get('USER_TYPE') == 'T'){
                            if(!in_array($row->role,['T','OA'])){
                                $btn .="<a class='delete-record remove_user item-edit btn btn-outline-danger btn-md px-2 py-1 text-danger'  data-id='$row->id' data-url='user-access-team' title='Remove User'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2 font-small-4'><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></a>&nbsp;";
                                if($row->user_status == 'Active') {
                                    $btn .= "<a  data-id='$row->id' class='org_login ' title='User Login'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-log-in font-small-4'><path d='M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4'></path><polyline points='10 17 15 12 10 7'></polyline><line x1='15' y1='12' x2='3' y2='12'></line></svg></a>";
                                }
                            }   
                        }
                        if(Session::get('USER_TYPE') == 'O' || Session::get('USER_TYPE') == 'OA'){
                            $btn .="<a class='unmap-record item-edit btn btn-outline-danger btn-md px-2 py-1 text-danger' data-id='$row->id' data-url='users' title='Unmap User'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2 font-small-4'><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></a>&nbsp;";
                            // $btn .="<a class='delete-record delete item-edit text-danger' data-id='$row->id' data-url='users' title='Delete User'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2 font-small-4'><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></a>&nbsp;";
                            if(Session::get('USER_TYPE')!='U'){
                                if($row->user_status == 'Active') {
                                    $btn .= "<a  data-id='$row->id' class='org_login ' title='User Login'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-log-in font-small-4'><path d='M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4'></path><polyline points='10 17 15 12 10 7'></polyline><line x1='15' y1='12' x2='3' y2='12'></line></svg></a> &nbsp;";
                                }
                            }
                        }
                    }
                    


                    return $btn;
                })
                ->addColumn('userOnlyName', function($row){
                    if(!empty($row->createdby)){
                     return   $row->createdby->userOnlyName;
                    }
                     return  '';
                })
                ->addColumn('viewteam', function($row){
                    if($row->role != 'OA'){
                        $btn = "<a class='item-edit btn btn-outline-warning btn-md px-2 py-1 text-warning viewTeam' data-id='$row->id'  title='View Teams'><u>View</u></a>" ;
                        return $btn;
                    }
                    
                   

                })
                ->editColumn('created_at', function ($row) use ($request) {
                    return $this->getDate($row->created_at, $request->timeZone);
                   })
                ->editColumn('user_last_login', function ($row) use ($request) {
                    if($row->user_last_login){
                        return $this->getDate($row->user_last_login, $request->timeZone);
                    }
                })
                ->addColumn('status', function ($row) {
                    return $row->user_status;
                })
                ->editColumn('user_type', function ($row) use ($request) {
                    $userRole = $row->role;
                    if(isset($userRole)){
                        if($userRole == 'U'){
                            return 'User';
                        }elseif($userRole == 'T'){
                            return 'Team Admin';
                        }elseif($userRole == 'OA'){
                            return 'Insurance Agency Admin';
                        }
                        elseif($userRole == 'O'){
                            return 'Primary Agency Admin';
                        }
                    }
                })
                ->rawColumns(['user_status','viewteam','created_at','action'])
                ->make(true);
        }
        // $teams = '';
        // if(Session::get('USER_TYPE') == 'T'){
        //         $teams = $this->getTeamsArr();
        //         // dd($teams);
        //         $user_teams = $this->getUsersArr();
        //         // $user_teams = UserAceessTeam::where('user_id',Auth::id())->pluck('team_id');
                
        //         dd($user_teams);
        // }       
       
        if(Session::get('USER_TYPE') == 'SA' || Session::get('USER_TYPE') == 'A'){
            return view('users.index_users',compact('title','agency_list'));
        // }else if(Session::get('USER_TYPE') == 'T'){
        //     $teams = $this->assignedTeams();
        //     return view('users.index_users',compact('title','teams'));
        }else{
            return view('users.index',compact('title'));
        }
    }

    public function create(Request $request){
        try {
            $title = $this->title;
            //get teams of insurance agency
            if(in_array(Session('USER_TYPE'),['O','OA'])){
                    $teams = Team::where('insurance_agency_id' ,Auth::user()->getInsuranceAgencyID())->where('team_status','Active')->whereNull('deleted_at')->get();
            }else{
                $teams = $this->assignedTeams();
            }
            $teamPermission = '';
            return view('users.create',compact('title','teams','teamPermission'));
        }catch (\Exception $e){
            toastr()->error('Something went wrong');
            return redirect('users');
        }
    }

    public function store(StoreUserRequest $request){
        
        try {
            $input = $request->all();
            $input['created_at']  = round(microtime(true) * 1000);
            $input['modified_at'] = round(microtime(true) * 1000);
            $input['user_status'] = "Active";
            $input['user_type']   = 'U';
            $input['plain_pwd']   = $input['password'];
            $input['created_by_id'] = Auth::user()->id;
            $input['password'] = Hash::make($input['password']);

            User::create($input);
            toastr()->success('User created successfully !!');

            return redirect('users');
        } catch (\Exception $e) {
            toastr()->error('Something went wrong !!');
            return redirect('users/create')->withInput($request->input());
        }
        toastr()->error('Something went wrong !!');

        return redirect('users/create')->withInput($request->input());
    }

    public function edit(Request $request,$id){
        $title = $this->title;
        try {
            $id=Crypt::decryptString($id);
            $user = User::find($id);
            if(in_array(Session('USER_TYPE'),['SA','A','O','OA'])){
                $teams = $this->getInsuranceByassignedTeams($user->getInsuranceAgencyID());
            }else{
                $teams = $this->assignedTeams();
            }
            $useraceess = $user->userTeamAccess;
            $cids = [];
            $adminAccess = [];
            if(!empty($useraceess)){
                foreach ($useraceess as $uat) {
                    if($uat->is_admin == '1'){
                        $adminAccess[] = $uat->team_id;
                    }else{
                        $cids[] = $uat->team_id;
                    }
                   
                }
            }
            $userRole =  $user->getRole();
            $showPermission = (in_array($user->getRole(),['U','T'])) ? true : false;
            $teamAdminPermission = '';
            return view('users/edit',compact('title','user','teams','cids','userRole','showPermission', 'adminAccess','teamAdminPermission'));
        }catch (\Exception $e){
            toastr()->error('Something went wrong');
            return redirect('users');
        }
    }

    public function update(Request $request){
        try {
                $input = $request->all();
                // $promoted_as = $input['promoted'];
                $user_id = $input['user_id'];
                if (isset($input['team_access']) && isset($input['team_admin'])) {
                    $teamid = $input['team_admin'] + $input['team_access'];
                } elseif (isset($input['team_access'])) {
                    $teamid = $input['team_access'];
                } elseif (isset($input['team_admin'])) {
                    $teamid = $input['team_admin'];
                }
                if(isset($input['no_access']) && $input['no_access'] > 0){
                    $no_access = $input['no_access'];
                    unset($input['no_access']);
                }
                if(isset($input['role'])){
                    $role = $input['role'];
                    unset($input['role']);
                }
                unset($input['user_id']);
                unset($input['team_access']);
                unset($input['_token']);
                // unset($input['promoted']);

                $input['modified_at'] = round(microtime(true) * 1000);
                if (empty($input['password'])) {
                    unset($input['password']);
                } else {
                    $input['password'] = Hash::make($input['password']);
                }

                //changes by Narsing for changing role of user

                // $checkrecords =  User::where('email',$input['email'])->whereNull('deleted_at')->get();
                // if(count($checkrecords) > 1){
                //     $user = User::findOrFail($user_id);
                //     $user->update($input);
                //     $userTeamAccessModel  = new UserAceessTeam();
                //     if(isset($no_access)){
                //         $userTeamAccessModel->where(['user_id'=> $user_id,'insurance_agency_id' => Auth::user()->getInsuranceAgencyID()])->whereIn('team_id', array_keys($no_access))->delete();
                //     };
                //     if (isset($teamid) && !empty($teamid)) {
                //         foreach ($teamid as $teamId => $value) {
                //             $userAccessInput['user_id'] = $user_id;
                //             $userAccessInput['team_id'] = $teamId;
                //             $userAccessInput['is_updated'] = 'Y';
                //             $userAccessInput['created_at'] = round(microtime(true) * 1000);
                //             $userAccessInput['is_admin'] = isset($input['team_admin'][$teamId]) ? '1' : '0';
                //             $userAccessInput['insurance_agency_id'] = Auth::user()->getInsuranceAgencyID();
                //             $userTeamAccess[] = new UserAceessTeam($userAccessInput);
                //         }

                //         $user->userTeamAccess()->saveMany($userTeamAccess);
                //         //delete records
                //         $userTeamAccessModel->whereNull('is_updated')->where(['user_id'=> $user_id,'insurance_agency_id' => Auth::user()->getInsuranceAgencyID()])->delete();
                //         // update flag
                //         $userTeamAccessModel->where(['user_id'=> $user_id,'insurance_agency_id' => Auth::user()->getInsuranceAgencyID()])->update(['is_updated' => Null]);
                //     }
                //     // }else{
                //     //     $userAccessInput['user_id'] = $user_id;
                //     //     $userAccessInput['team_id'] = Auth::user()->team->team_id;
                //     //     UserAceessTeam::create($userAccessInput);
                //     // }

                //     // promoted functionality
                //     // if($promoted_as == 'IA'){
                //     //     $user = User::where('id', $user_id)->update(['user_type' => "O", "is_role_assign" => "Y"]);
                //     //     $userRole['role_name'] ='IA';
                //     // }
                //     // if($promoted_as == 'TA'){
                //     //     $user = User::where('id', $user_id)->update(['user_type' => "T", "is_role_assign" => "Y"]);
                //     //     $userRole['role_name'] ='TA';
                //     // }
                //     // add entry in role table
                //     // $userRole['user_id'] = $user_id;
                //     // $userRole['status'] = "Active";
                //     // $userRole['created_at'] = round(microtime(true) * 1000);
                //     if(isset($role)){
                //         if($role == 'OA'){
                //             UserAceessTeam::where(['user_id'=> $user_id,'insurance_agency_id' => Auth::user()->getInsuranceAgencyID()])->delete();
                //         }
                //         UserRole::updateOrCreate(['insurance_agency_id' => Auth::user()->getInsuranceAgencyID() , 'user_id' => $user_id ],['role' => $role]);
                //     }
                    

                //     toastr()->success('User Updated Successfully !!');
                //     return redirect('users');
                // }
                // else{

                // return $input;
                $emailExist = User::where('email', $input['email'])->where('id', '!=', $user_id)->get();

                //CHANGES BY NARSING FOR DUPLICATE EMAIL ISSUE
                $checkrecords =  User::where('email',$input['email'])->whereNull('deleted_at')->get();
                if(count($checkrecords) > 1){
                    $user = User::findOrFail($user_id);
                    $user->update($input);
                    $userTeamAccessModel  = new UserAceessTeam();
                    if(isset($no_access)){
                        $userTeamAccessModel->where(['user_id'=> $user_id,'insurance_agency_id' => Auth::user()->getInsuranceAgencyID()])->whereIn('team_id', array_keys($no_access))->delete();
                    };
                    if (isset($teamid) && !empty($teamid)) {
                        foreach ($teamid as $teamId => $value) {
                            $userAccessInput['user_id'] = $user_id;
                            $userAccessInput['team_id'] = $teamId;
                            $userAccessInput['is_updated'] = 'Y';
                            $userAccessInput['created_at'] = round(microtime(true) * 1000);
                            $userAccessInput['is_admin'] = isset($input['team_admin'][$teamId]) ? '1' : '0';
                            $userAccessInput['insurance_agency_id'] = Auth::user()->getInsuranceAgencyID();
                            $userTeamAccess[] = new UserAceessTeam($userAccessInput);
                        }

                        $user->userTeamAccess()->saveMany($userTeamAccess);
                        //delete records
                        $userTeamAccessModel->whereNull('is_updated')->where(['user_id'=> $user_id,'insurance_agency_id' => Auth::user()->getInsuranceAgencyID()])->delete();
                        // update flag
                        $userTeamAccessModel->where(['user_id'=> $user_id,'insurance_agency_id' => Auth::user()->getInsuranceAgencyID()])->update(['is_updated' => Null]);
                    }
                    // }else{
                    //     $userAccessInput['user_id'] = $user_id;
                    //     $userAccessInput['team_id'] = Auth::user()->team->team_id;
                    //     UserAceessTeam::create($userAccessInput);
                    // }

                    // promoted functionality
                    // if($promoted_as == 'IA'){
                    //     $user = User::where('id', $user_id)->update(['user_type' => "O", "is_role_assign" => "Y"]);
                    //     $userRole['role_name'] ='IA';
                    // }
                    // if($promoted_as == 'TA'){
                    //     $user = User::where('id', $user_id)->update(['user_type' => "T", "is_role_assign" => "Y"]);
                    //     $userRole['role_name'] ='TA';
                    // }
                    // add entry in role table
                    // $userRole['user_id'] = $user_id;
                    // $userRole['status'] = "Active";
                    // $userRole['created_at'] = round(microtime(true) * 1000);
                    if(isset($role)){
                        if($role == 'OA'){
                            UserAceessTeam::where(['user_id'=> $user_id,'insurance_agency_id' => Auth::user()->getInsuranceAgencyID()])->delete();
                        }
                        UserRole::updateOrCreate(['insurance_agency_id' => Auth::user()->getInsuranceAgencyID() , 'user_id' => $user_id ],['role' => $role]);
                    }
                    

                    toastr()->success('User Updated Successfully !!');
                    return redirect('users');
                }   
                else{
                    if ($emailExist->isEmpty()) {

                        $user = User::findOrFail($user_id);
                        $user->update($input);
                        $userTeamAccessModel  = new UserAceessTeam();
                        if(isset($no_access)){
                            $userTeamAccessModel->where(['user_id'=> $user_id,'insurance_agency_id' => Auth::user()->getInsuranceAgencyID()])->whereIn('team_id', array_keys($no_access))->delete();
                        };
                        if (isset($teamid) && !empty($teamid)) {
                            foreach ($teamid as $teamId => $value) {
                                $userAccessInput['user_id'] = $user_id;
                                $userAccessInput['team_id'] = $teamId;
                                $userAccessInput['is_updated'] = 'Y';
                                $userAccessInput['created_at'] = round(microtime(true) * 1000);
                                $userAccessInput['is_admin'] = isset($input['team_admin'][$teamId]) ? '1' : '0';
                                $userAccessInput['insurance_agency_id'] = Auth::user()->getInsuranceAgencyID();
                                $userTeamAccess[] = new UserAceessTeam($userAccessInput);
                            }
    
                            $user->userTeamAccess()->saveMany($userTeamAccess);
                            //delete records
                            $userTeamAccessModel->whereNull('is_updated')->where(['user_id'=> $user_id,'insurance_agency_id' => Auth::user()->getInsuranceAgencyID()])->delete();
                            // update flag
                            $userTeamAccessModel->where(['user_id'=> $user_id,'insurance_agency_id' => Auth::user()->getInsuranceAgencyID()])->update(['is_updated' => Null]);
                        }
                        // }else{
                        //     $userAccessInput['user_id'] = $user_id;
                        //     $userAccessInput['team_id'] = Auth::user()->team->team_id;
                        //     UserAceessTeam::create($userAccessInput);
                        // }
    
                        // promoted functionality
                        // if($promoted_as == 'IA'){
                        //     $user = User::where('id', $user_id)->update(['user_type' => "O", "is_role_assign" => "Y"]);
                        //     $userRole['role_name'] ='IA';
                        // }
                        // if($promoted_as == 'TA'){
                        //     $user = User::where('id', $user_id)->update(['user_type' => "T", "is_role_assign" => "Y"]);
                        //     $userRole['role_name'] ='TA';
                        // }
                        // add entry in role table
                        // $userRole['user_id'] = $user_id;
                        // $userRole['status'] = "Active";
                        // $userRole['created_at'] = round(microtime(true) * 1000);
                        if(isset($role)){
                            if($role == 'OA'){
                                UserAceessTeam::where(['user_id'=> $user_id,'insurance_agency_id' => Auth::user()->getInsuranceAgencyID()])->delete();
                            }
                            UserRole::updateOrCreate(['insurance_agency_id' => Auth::user()->getInsuranceAgencyID() , 'user_id' => $user_id ],['role' => $role]);
                        }
                        
    
                        toastr()->success('User Updated Successfully !!');
                        return redirect('users');
                    } else {
                        toastr()->error('User already exist with this email !!');
                        $id = Crypt::encryptString($user_id);
                        return redirect('users/edit/'.$id);
                    }
                } 

                
            
        }catch (\Exception $e){
            toastr()->error('Something went wrong !!');
            $id = Crypt::encryptString($user_id);
            return redirect('users/edit/'.$id);
        }
    }

    public function view(Request $request,$id){
        $title = $this->title;
        try {
            if ($request->ajax()) {
                $id = Crypt::decryptString($id);
                $user = User::with('UserRole')->find($id);
                return Datatables::of($user->userRole)
                ->addIndexColumn()
                ->editColumn('insurance_agency_id', function ($row) {
                    return $row->InsuranceAgency->insurance_agency_name;
                })
                ->editColumn('created_at', function ($row) use($request){
                    return $this->getDate($row->created_at, $request->timeZone);
                })
                ->editColumn('role', function($row){
                    return $row->role_name;
                })
                ->make(true);
            } else {
                $id = Crypt::decryptString($id);
                $user = User::with('UserRole')->find($id);

                //get assigned teams
                if (in_array(Session('USER_TYPE'), ['SA', 'A', 'O','OA'])) {
                    $teams = $this->getInsuranceByassignedTeams($user->getInsuranceAgencyID());
                } else {
                    $teams = $this->assignedTeams();
                }


                $useraceess = $user->userTeamAccess;
                $cids = [];
                $adminAccess = [];
                if (!empty($useraceess)) {
                    foreach ($useraceess as $uat) {
                        if ($uat->is_admin == '1') {
                            $adminAccess[] = $uat->team_id;
                        } else {
                            $cids[] = $uat->team_id;
                        }
                    }
                }
                return view('users/view', compact('title', 'user', 'teams', 'cids', 'adminAccess'));
            } 
        }catch (\Exception $e){
            toastr()->error('Something went wrong');
            return redirect('users');
        }
    }

    public function destroy(Request $request)
    {
        try {
            $prefix = round(microtime(true) * 1000).'retire#';

            $user = User::find($request->id);

            // Decrement team licence subscription
            // if($user->user_status == 'Active' && (Session::get('USER_TYPE') != 'A' && Session::get('USER_TYPE') != 'SA')){
            //     if($this->isAdmin()){
            //         $insurance_agency_id =    Auth::user()->insurance_agency_id;
            //     }else{
            //         $insurance_agency_id =    Auth::user()->insurance_agencies->insurance_agency_id;
            //     }
            //     $agency = InsuranceAgency::where('insurance_agency_id',$insurance_agency_id)->first();
                
            //     $type = 'Decrement';
            //     $this->updateTeamLicenceQty($agency->user_id, $type);
            // }
            $type = 'Decrement';
            if($user->user_status == 'Active' && (Session::get('USER_TYPE') == 'A' || Session::get('USER_TYPE') == 'SA')){
                $insuranceAgencies = UserRole::with('insuranceAgency')->where('user_id',$user->id)->get()->pluck('insuranceAgency');
                foreach($insuranceAgencies as $insuranceAgency){
                    $this->updateTeamLicenceQty($insuranceAgency->user_id, $type);
                }
                 //update user email id
                 User::where('id', $request->id)->update(['email' => DB::raw("CONCAT('".$prefix."', email)")]);
                 UserAceessTeam::where('user_id',$request->id)->delete();
                 $this->deleteRecord($request, 'User', 'deleted_at');
            }elseif(in_array(Session('USER_TYPE'),['O','OA'])){
                $InsuranceAgencyID = Session('INSURANCE_AGENCY_ID');
                $insuranceAgency = InsuranceAgency::find($InsuranceAgencyID);
                $WHERE= ['user_id' => $user->id,'insurance_agency_id' =>  $InsuranceAgencyID];
                $this->updateTeamLicenceQty($insuranceAgency['user_id'], $type);
                UserRole::where($WHERE)->delete();
                UserAceessTeam::where($WHERE)->delete();
                $resultArr['title'] = 'Success';
                $resultArr['message'] = 'Record unmapped successfully';
                echo json_encode($resultArr);
            }

           
        } catch (\Exception $e) {
            echo $e->getMessage();
            $resultArr['title'] = 'Error';
            $resultArr['message'] = 'Something went wrong!';
            echo json_encode($resultArr);
            exit;
        }
    }

    public function profile(Request $request){
        $productData = $insurGroup = array();
        
        $title  = "Profile";
        $user   = User::find(Auth::id());
        $user->load('insurance_agencies');
        $user->load('get_org_name');
        
        $user->teamName = UserAceessTeam::select()
            ->join('teams','user_aceess_teams.team_id','teams.team_id')
            ->where('user_aceess_teams.user_id', Auth::id())
            ->where('user_aceess_teams.insurance_agency_id',Auth::user()->getInsuranceAgencyID())->pluck('team_name')->implode(',');
        $paymentMethod  = $user->defaultPaymentMethod();
        $subscriptions  = $user->subscriptions()->active()->get();
        if($subscriptions->isNotEmpty()){
            foreach ($subscriptions as $subscription) {
                $pdata =  $this->getPlanDetails($subscription->stripe_price);
                $subscription->amount = $pdata->amount/100;
                $subscription->interval = $pdata->interval;
            }
        }
       
        if(Session::get('USER_TYPE') == 'O'){
            if($this->isAdmin()){
                $iadata = InsuranceAgency::where('insurance_agency_id',Auth::user()->getInsuranceAgencyID())->first();
                $data           = $this->getPlanDetails($iadata->price_identifier);
                $insurGroup     = InsuranceAgencyGroupHistory::where('insurance_agency_id', Auth::user()->getInsuranceAgencyID())->orderBy('id', 'DESC')->whereNull('end_date')->first();
            }else{
                 $data           = $this->getPlanDetails($user->insurance_agencies->price_identifier);
                 $insurGroup     = InsuranceAgencyGroupHistory::where('insurance_agency_id',  Auth::user()->getInsuranceAgencyID())->orderBy('id', 'DESC')->whereNull('end_date')->first();

            }
            $productData    = $this->getProductDetails($data->product);
        }
        
        $groups         = Group::where('group_status', 'Active')->orderBy('group_name', 'ASC')->get();
        
        return view('users.update-and-change-password', compact('title', 'user', 'paymentMethod', 'subscriptions', 'productData', 'groups', 'insurGroup'));
    }

    //This function  use for update profile
    public function updateProfileAction(Request $request){
        try {
            $input = $request->except('_token');
            if(Session::get('USER_TYPE')=="O"){
                if(isset($input['is_mfa'])){
                    $is_mfa = $input['is_mfa'];
                }
                if(isset($input['mfa_type'])){
                    $mfa_type = $input['mfa_type'];
                }
                if(isset($input['insurance_agency_name'])){
                    $org_name = $input['insurance_agency_name'];
                }
                unset($input['insurance_agency_name']);
                unset($input['is_mfa']);
                unset($input['mfa_type']);
                unset($input['team_name']);
            }
            if(Session::get('USER_TYPE')=="T"){
                if(isset($input['team_name'])){
                    $team_name = $input['team_name'];
                }
                unset($input['team_name']);
            }
            $input['modified_at'] =  round(microtime(true) * 1000);
            
            $phoneExist = User::where('user_phone_no',$input['user_phone_no'])->where('id','!=',Auth::id())->get();
            //update insurance agency contact information
            InsuranceAgencyContact::updateOrCreate(['insurance_agency_id' => Auth::user()->getInsuranceAgencyID()],[
                'contact_name' => $input['contact_name'],
                'contact_email' =>  $input['contact_email'],
                'contact_no' => $input['contact_no'],
            ]);
            unset($input['contact_name']);
            unset($input['contact_email']);
            unset($input['contact_no']);
            // $emailExist = User::where('email',$input['email'])->where('id','!=',Auth::id())->get();

            $checkrecords =  User::where('email',$input['email'])->whereNull('deleted_at')->get();
            if(count($checkrecords) > 1){
                if(Session::get('USER_TYPE') == 'SA' || Session::get('USER_TYPE') == 'A'){
                    $user = User::where('id', Auth::id())->update($input);
                    return response()->json(['result' => 'success','is_changed'=>'N','message' => 'Profile Updated Successfully !!']);
                }else{
                    if($input['user_phone_no'] == Auth::user()->user_phone_no ){
                        $user = User::where('id', Auth::id())->update($input);
                    }else{
                        $phoneNo = $input['user_phone_no'];
                        unset($input['user_phone_no']);
                        $user = User::where('id', Auth::id())->update($input);
                        //Check MFA Yes or Not
                        $is_mfa = $this->isMFA($phoneNo);
                        if($is_mfa == 'Y' && Config::get('constants.mfa') == 'Y'){
                            return response()->json(['result' => 'success','is_changed'=>'Y','phoneNo'=>$phoneNo]);
                        }else{
                            User::where('id', Auth::id())->update(['user_phone_no' => $phoneNo]);
                            return response()->json(['result' => 'success','is_changed'=>'N','message' => 'Profile Updated Successfully !!']);
                        }
                    }
                }
                if(Session::get('USER_TYPE')=="O"){
                    InsuranceAgency::where('user_id', Auth::id())->update(['insurance_agency_name' => $org_name,'is_mfa' => $is_mfa , 'mfa_type' => $mfa_type ]);
                }
                if(Session::get('USER_TYPE')=="T" && Auth::user()->is_role_assign == "N"){
                    Team::where('user_id', Auth::id())->update(['team_name'=>$team_name]);
                }
                return response()->json(['result' => 'success','is_changed'=>'N','message' => 'Profile Updated Successfully !!']);
            }
            else{
                if($phoneExist->isNotEmpty()) {
                    return response()->json(['result' => 'error','is_changed'=>'N','message' => 'User already exist with this phone no !!']);
                }else{
                    // if($emailExist->isNotEmpty()) {
                    //     return response()->json(['result' => 'error','is_changed'=>'N','message' => 'User already exist with this email !!']);
                    // }else{
                        if(Session::get('USER_TYPE') == 'SA' || Session::get('USER_TYPE') == 'A'){
                            $user = User::where('id', Auth::id())->update($input);
                            return response()->json(['result' => 'success','is_changed'=>'N','message' => 'Profile Updated Successfully !!']);
                        }else{
                            if($input['user_phone_no'] == Auth::user()->user_phone_no ){
                                $user = User::where('id', Auth::id())->update($input);
                            }else{
                                $phoneNo = $input['user_phone_no'];
                                unset($input['user_phone_no']);
                                $user = User::where('id', Auth::id())->update($input);
                                //Check MFA Yes or Not
                                $is_mfa = $this->isMFA($phoneNo);
                                if($is_mfa == 'Y' && Config::get('constants.mfa') == 'Y'){
                                    return response()->json(['result' => 'success','is_changed'=>'Y','phoneNo'=>$phoneNo]);
                                }else{
                                    User::where('id', Auth::id())->update(['user_phone_no' => $phoneNo]);
                                    return response()->json(['result' => 'success','is_changed'=>'N','message' => 'Profile Updated Successfully !!']);
                                }
                            }
                        }
                        if(Session::get('USER_TYPE')=="O"){
                            InsuranceAgency::where('user_id', Auth::id())->update(['insurance_agency_name' => $org_name,'is_mfa' => $is_mfa , 'mfa_type' => $mfa_type ]);
                        }
                        if(Session::get('USER_TYPE')=="T" && Auth::user()->is_role_assign == "N"){
                            Team::where('user_id', Auth::id())->update(['team_name'=>$team_name]);
                        }
                        return response()->json(['result' => 'success','is_changed'=>'N','message' => 'Profile Updated Successfully !!']);
                    // }
                }
            }

            
        } catch (\Exception $e) {
            toastr()->error('Something went wrong');
            return redirect('users/profile');
        }
    }

    //Check Existed Otp
    public function checkOTPexist(Request $request){
        try{
            $user = Auth::user();
            if($request->otp != $user->phone_otp){
                return response()->json(['status' => false,'message' => "One-time password doesn't match. Please enter correct One-time password !!"]);
            }else{
                $user->resetTwoFactorCode('phone');
                $user = User::where('id', Auth::id())->update(['user_phone_no'=>$request->phoneNo]);
                return response()->json(['status' => true,'message' => "One-time password matched !!"]);
            }
        }catch(\Exception $e){
            return response()->json(['status' => false,'message' => "Something went wrong !!"]);
        }

    }

    //Resend OTP
    public function resendOTP(Request $request){
        try{
            $user = Auth::user();
            $count = $user->count_of_max_otp+1;
            if($user->count_of_max_otp >= config('constants.max_no_of_time')){
                return response()->json(['status' => false,'message' => "You've reached the maximum limit of resend One-time password. Please try again after 15/30 minutes !!"]);
            }else{
                User::where('id', Auth::id())->update(['count_of_max_otp' => $count]);
                $user->generateTwoFactorCode('phone');
                 // $this->sendSNS();
                return response()->json(['status' => true,'message' => "A one-time password has been sent again to your phone number. !!"]);
            }
        }catch(\Exception $e){
            return response()->json(['status' => false,'message' => "Something went wrong !!"]);
        }
    }

    public function change_password(Request $request){
        try{
            $input = $request->all();
            $searchinput['id'] = Auth::id();

            if(password_verify($input['old_password'], Auth::user()->password)){
                if($request->new_password!=''){
                    $input['password'] = Hash::make($request->new_password);
                }
                else{
                    unset($input['password']);
                }

                // User::updateorCreate($searchinput, $input);
                User::where('email', Auth::user()->email)->update(['password' => $input['password']]);

                toastr()->success('Password updated successfully!');
            }
            else{
                $request->session()->flash('statustab', 'Active');
                toastr()->error('Old password does not match');
            }
        }catch(\Exception $e){
            toastr()->error('Something went wrong');
        }
        return redirect('users/profile');
    }

    public function loginUser(Request $request, $user_id)
    {   
       
        if (isset($user_id) && $user_id > 0) {
            $currentId = Auth::id();

            if(Session::get('USER_TYPE') == 'A' || Session::get('USER_TYPE') == 'SA'){
                Session::put('super_admin_id', $currentId);
            }
            elseif(Session::get('USER_TYPE') == 'O'){
                Session::put('org_id', $currentId);
            }
            elseif(Session::get('USER_TYPE') == 'OA'){
                Session::put('sub_org_id', $currentId);
            }
            elseif(Session::get('USER_TYPE') == 'T'){
                Session::put('team_id', $currentId);
            }else{
                Session::put('user_id', $currentId);
            }
            
            Auth::loginUsingId($user_id);
            if(in_array(Auth::user()->user_type,['S','A'])){
                Session::put('USER_TYPE',Auth::user()->user_type);
            }else{
                Session::put('USER_TYPE', Auth::user()->getRole());
            }
          
            return redirect('home');
        }
    }

    public function getReturnLogin(Request $request, $user_id)
    {
        $id = $user_id;
        Auth::logout();
        Auth::loginUsingId($id);
        if(in_array(Auth::user()->user_type,['S','A'])){
            Session::put('USER_TYPE',Auth::user()->user_type);
        }else{
            $role = Auth::user()->getRole();
            if($role){
                Session::put('USER_TYPE', $role);
            }else{
                Auth::logout();
            } 
        }

        return redirect('home');
    }

    public function geTeamsbyInsuranceAgency(Request $request){
        try {
            $teams = $this->getTeams($request->insurance_agency_id);
            // return $teams;
            if(isset($teams) && \count($teams) > 0){
                echo "<option value=''>Select Team</option>";
            }else{
                echo "<option value=''>Select Team</option>";
            }
            foreach ($teams as $key => $team) {
                if($request->has('team_id') && $team->team_id==$request->team_id)
                    $selected = 'selected';
                else
                    $selected ="";

                echo "<option value='$team->team_id' $selected>$team->team_name</option>";
            }
        } catch (\Exception $e) {
            toastr()->error('Something went wrong');
            return 0 ;
        }

    }

    public function getTeamByUser(Request $request){
        try{
            if(in_array(Session('USER_TYPE') , ['A','SA'])){
                $agencyID = Crypt::decryptString($request->agencyKey);
                $insurance_agency_id = InsuranceAgency::where('user_id',$agencyID)->value('insurance_agency_id');
            }else{
                $insurance_agency_id = Session('INSURANCE_AGENCY_ID');
            }

            $user_role_data = UserRole::where('user_id',$request->id)->where('insurance_agency_id',$insurance_agency_id)->first();
            if($user_role_data->role == "O"){
                $teamsAccessData = Team::where('insurance_agency_id', $insurance_agency_id)
                ->pluck('team_name')->implode(', ');
            }
            else{
                $teamsAccessData = UserAceessTeam::select()
                ->join('teams','user_aceess_teams.team_id','teams.team_id')
                ->where('user_aceess_teams.user_id',$request->id)
                ->where('user_aceess_teams.insurance_agency_id', $insurance_agency_id)
                ->pluck('team_name')->implode(', ');
            }
            
            
            if(!empty($teamsAccessData)){
             return response()->json(['message' => 'Get successfully record','data' => $teamsAccessData,'status' => 200]);
            }
             return response()->json(['message' => 'Team Not Found','data' => $teamsAccessData,'status' => 200]);

        }catch(\Exception $e){
            return response()->json(['message' => 'Something went wrong','data' => '','status' => 500]);
        }
    }

    public function get_teams($id){
        $admin_teams = $this->getTeamsArr()->toarray();
         $user_teams = UserAceessTeam::where('user_id',$id)->pluck('team_id')->toarray();
         $common = array_intersect( $admin_teams,$user_teams);
        $teams = Team::whereIN('team_id',$common)->get();
        $html = '';
        foreach ($teams as $team)
        {
            $html .= '<div class="form-check"><input class="form-check-input" type="checkbox" name="team[]" value='.$team->team_id.' style="margin-top: 0.3em;"><label class="form-check-label" for="vehicle1"> '.$team->team_name.'</label></div> ';
        }
        $resultArr['id'] = $id;
        $resultArr['html'] = $html;
        return $resultArr; 
    }

    public function remove_user(Request $request){
        try {
             UserAceessTeam::whereIn('team_id',$request->team)->where('user_id',$request->user_id)->delete();
             return redirect('users');
        } catch (\Exception $e) {
            $resultArr['title'] = 'Error';
            $resultArr['message'] = 'Something went wrong!';
            echo json_encode($resultArr);
            exit;
        }
    }

    public function invited_users_list(Request $request){
        $title = $this->title;
        $InsuranceAgencyID = Session('INSURANCE_AGENCY_ID');
        if ($request->ajax()) {
            if(in_array(Session('USER_TYPE'),['SA','A'])){
                $data = UserInvitation::select('user_invitations.id as invitation_id','user_invitations.email as email','user_invitations.role_permission as role_permission','insurance_agencies.insurance_agency_name as insurance_agency_name','users.user_fname','users.user_lname','user_invitations.created_at')
                ->join('users','users.id','user_invitations.send_by')
                ->join('insurance_agencies','insurance_agencies.insurance_agency_id','user_invitations.insurance_agency_id')
                ->orderBy('invitation_id', 'DESC');
            }else{
                $data = UserInvitation::select('user_invitations.id as invitation_id','user_invitations.email as email','user_invitations.role_permission as role_permission','insurance_agencies.insurance_agency_name as insurance_agency_name','users.user_fname','users.user_lname','user_invitations.created_at')
                ->join('users','users.id','user_invitations.send_by')
                ->join('insurance_agencies','insurance_agencies.insurance_agency_id','user_invitations.insurance_agency_id')
                ->where('user_invitations.insurance_agency_id',$InsuranceAgencyID)
                ->orderBy('invitation_id', 'DESC');

            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('send_by', function ($row){
                    return $row->user_fname ." ".  $row->user_lname;
                   })
                ->editColumn('created_at', function ($row) use ($request) {
                    $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at, 'UTC')
                    ->setTimezone($request->timeZone);
                    $created_at = $created_at->format('m-d-Y H:i:s');
                    return $created_at;
                   })
                ->addColumn('role', function ($row) {
                    $value = json_decode($row->role_permission)->assign_role->role;
                    if($value == "U"){
                        $role = 'User';
                    }
                    if($value == "T"){
                        $role = 'Team Admin';
                    }
                    if($value == "OA"){
                        $role = 'Insurance Agency Admin';
                    }
                    return $role;
                })
                ->addColumn('team', function ($row) {
                    $team_admin_array  = [];
                    $team_access_array = [];

                    $team_admin_values = json_decode($row->role_permission)->assign_role_permission->team_admin;
                    if(!empty($team_admin_values)){
                        foreach($team_admin_values as $key =>$val){
                            array_push($team_admin_array,$key);
                        }
                    }
                   
                    $team_access_values = json_decode($row->role_permission)->assign_role_permission->team_access;
                    if($team_access_values){
                        foreach($team_access_values as $key =>$val){
                            array_push($team_access_array,$key);
                        }
                    }
                    
                    $final_team_array = array_merge($team_admin_array,$team_access_array);
                    $teams_part = Team::select('team_name')->whereIn('team_id',$final_team_array)->get();
                    $team_names_array = [];
                    $team = '';
                    if(!empty($teams_part)){
                        foreach($teams_part as $team_part_values){
                            array_push($team_names_array,$team_part_values->team_name);
                        }
                    }

                    if(!empty($team_names_array)){
                        $team = implode(', ',$team_names_array);
                    }
                    else{
                        $team = '';
                    }
                    return $team;
                })
                ->addColumn('action', function ($row) {
                    $id = Crypt::encryptString($row->invitation_id);
                    $btn = "<a  class='item-edit text-warning resend_invitaion' data-id='$id' data-url='invited_users/resend_invitation'  title='Resend Invitation'><svg viewBox='0 0 24 24' width='20' height='20' stroke='currentColor' stroke-width='2' fill='none' stroke-linecap='round' stroke-linejoin='round' class='css-i6dzq1'><polyline points='1 4 1 10 7 10'></polyline><polyline points='23 20 23 14 17 14'></polyline><path d='M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15'></path></svg></a>";
                    return $btn;
                }) 
                ->rawColumns(['created_at','action'])
                ->make(true);
        }
            
            // return view('invited-users.all_invited_users');
    }

    public function resendInvitation(Request $request){
        try{
            $input = $request->all();
            $data = UserInvitation::find(Crypt::decryptString($input['id']));
            $email = $data->email;
            $signUpURL = route('register').'/'.($input['id']);
            $template = Notification::where('notification_for','user_invitation')->first();

            //changes by Narsing 
            $insuranceAgencyID = Auth::user()->getInsuranceAgencyID();
            $agency      = InsuranceAgency::select('insurance_agency_name')->where('insurance_agency_id',$insuranceAgencyID)->first(); 
            $agency_name = $agency->insurance_agency_name;

            $team_admin_array  = [];
            $team_access_array = [];

            //get users with admin access
            $team_admin_values = json_decode($data->role_permission)->assign_role_permission->team_admin;
            if(!empty($team_admin_values)){
                foreach($team_admin_values as $key =>$val){
                    array_push($team_admin_array,$key);
                }
            }
            
            //get users with team access only
            $team_access_values = json_decode($data->role_permission)->assign_role_permission->team_access;
            if($team_access_values){
                foreach($team_access_values as $key =>$val){
                    array_push($team_access_array,$key);
                }
            }
            
            //merge both array
            $final_team_array = array_merge($team_admin_array,$team_access_array);
            $teams_part = Team::select('team_name')->whereIn('team_id',$final_team_array)->get();
            $team_names_array = [];
            $teams = '';
            if(!empty($teams_part)){
                foreach($teams_part as $team_part_values){
                    array_push($team_names_array,$team_part_values->team_name);
                }
            }
            // dd($team_names_array);
            if(!empty($team_names_array)){
                $teams = implode(', ',$team_names_array);
            }
            else{
                $teams = '';
            }
            $for = '';
            if(count($team_names_array) > 1){
                $for = 'for teams';
            }
            if(count($team_names_array) == 1){
                $for = 'for team';
            }
            //TILL HERE and also added three parameters ie $agency_name,$teams,$for in RegisterUser event

            
            Mail::to($email)->send(new \App\Mail\RegisterUser($signUpURL ,$template,$agency_name,$teams,$for));

            $data['message'] = 'Invitation sent successfully.';
            $data['title'] = 'Success';
        }
        catch(\Exception $e){
            $data['message'] = $e->getMessage();
            $data['title'] = 'Error';
        }
        echo json_encode($data);
        exit;
    }

    public function userByID(Request $request){
        $user = User::select('id','user_fname','user_lname','mobile_number','email','upi')
                ->where('id',$request->user_id)->first();
        return json_encode($user);
    }
    
}
