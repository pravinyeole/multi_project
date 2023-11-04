<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use DataTables;
use App\Models\UserSubInfo;
use App\Models\RequestPin;
use App\Models\TransferPin;
use App\Models\UserPin;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Announcement;
use DB;
use App\Traits\CommonTrait;
use App\Traits\AuditTrait;
use App\Models\UserReferral;
use App\Models\RevokePin;
use Illuminate\Support\Facades\Crypt;
use Session;

class DashboardController extends Controller
{
	use CommonTrait;

    public function __construct(){
		$this->title = "Dashboard";
    	$this->middleware(['auth'])->except(['saveUserRoleConfig']);
  	}

    public function dashboard(){   
        //$pageConfigs = ['pageHeader' => false];
        //   $userDetails = User::join('user_pins', 'users.id', '=', 'user_pins.user_id')
        //     ->select('users.*', 'user_pins.pins')
        //     ->where('users.id', Auth::user()->id)
        //     ->first();


        if(Auth::User()->user_role == 'S')
        {
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            Carbon::setWeekEndsAt(Carbon::SATURDAY);
            $activeAdmin = User::where(['user_role'=>'A','user_status'=>'Active'])->count();
            $activeUsers = User::where(['user_role'=>'U','user_status'=>'Active'])->count();
            $pinCreated = UserSubInfo::whereDate('created_at',Carbon::today())->count();
            $pinReuqest = RequestPin::where(['admin_slug'=>Auth::user()->user_slug,'status'=>'pending'])->count();
            $todaysUsers = User::where(['user_role'=>'U'])->whereDate('created_at',Carbon::today())->count();
            $weekUsers = User::where(['user_role'=>'U'])->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            
            //return view('dashboard/dashboard', compact('pageConfigs', 'userDetails'));

             return view('dashboard/new_dashboard',compact('activeAdmin','pinCreated','activeUsers','pinReuqest','todaysUsers','weekUsers'));

        }
        elseif(Auth::User()->user_role == 'A')
        {
            $data['Announcement'] = Announcement::whereIn('type',['Admin','All'])->get()->last();
            $data['myReferalUser'] = User::join('user_referral AS ur','ur.user_id','users.id')
                            ->where('ur.referral_id',Auth::user()->mobile_number)
                            ->orWhere('ur.admin_slug',Auth::user()->user_slug)
                            ->orderBy('users.id','DESC')
                            ->count();
            $data['requestedPins'] = RequestPin::select('users.*', 'request_pin.*', 'request_pin.created_at as req_created_at')->leftJoin('users', 'users.user_slug', '=', 'request_pin.admin_slug')
                            ->where('request_pin.req_user_id', Auth::user()->id)
                            ->count();
            $data['revokePins'] = RevokePin::where('revoke_by', Auth::user()->id)->sum('revoke_count');
            $cryptUrl = '';
            $myPinBalance_a = UserPin::where('user_id', Auth::user()->id)->sum('pins');
            if ($myPinBalance_a) {
                $data['myPinBalance'] = $myPinBalance_a;
            } else {
                $data['myPinBalance'] = 0;
            }
            if(Auth::user()->user_role != 'S' ){
                $data['myadminSlug']= $myadminSlug = UserReferral::where('user_id',Auth::user()->id)->first()->admin_slug;
                $cryptmobile= Crypt::encryptString(Auth::user()->mobile_number);
                $cryptSlug= Crypt::encryptString($myadminSlug);
                $data['cryptUrl']= url('/register/').'/'.$cryptmobile.'/'.$cryptSlug;
            }else{
                $data['myadminSlug']= $myadminSlug = Auth::user()->user_slug;
                $cryptmobile= Crypt::encryptString(Auth::user()->mobile_number);
                $cryptSlug= Crypt::encryptString($myadminSlug);
                $data['cryptUrl']= url('/register/').'/'.$cryptmobile.'/'.$cryptSlug;
            }
            
            return view('dashboard/admin_dashboard',compact('data'));
        }
        elseif(Auth::User()->user_role == 'U')
        {
            $data['Announcement'] = Announcement::whereIn('type',['User','All'])->get()->last();
            $data['pinTransferRequest'] = RequestPin::where('req_user_id',Auth::user()->id)->sum('no_of_pin');
            $data['pinTransferSend'] = TransferPin::where('trans_by',Auth::user()->id)->sum('trans_count');
            $data['myReferalUser'] = User::join('user_referral AS ur','ur.user_id','users.id')
                                        ->where('ur.referral_id',Auth::user()->mobile_number)
                                        ->orWhere('ur.admin_slug',Auth::user()->user_slug)
                                        ->orderBy('users.id','DESC')
                                        ->take(5)
                                        ->get();
            $myPinBalance_a = UserPin::where('user_id', Auth::user()->id)->sum('pins');
            if ($myPinBalance_a) {
                $data['myPinBalance'] = $myPinBalance_a;
            } else {
                $data['myPinBalance'] = 0;
            }
            if(Auth::user()->user_role != 'S' ){
                $data['myadminSlug'] = $myadminSlug = UserReferral::where('user_id',Auth::user()->id)->first()->admin_slug;
                $cryptmobile= Crypt::encryptString(Auth::user()->mobile_number);
                $cryptSlug= Crypt::encryptString($myadminSlug);
                $data['cryptUrl']= url('/register/').'/'.$cryptmobile.'/'.$cryptSlug;
            }else{
                $data['myadminSlug'] = $myadminSlug = Auth::user()->user_slug;
                $cryptmobile= Crypt::encryptString(Auth::user()->mobile_number);
                $cryptSlug= Crypt::encryptString($myadminSlug);
                $data['cryptUrl']= url('/register/').'/'.$cryptmobile.'/'.$cryptSlug;
            }
            return view('dashboard/user_dashboard',compact('data'));
        }
    }

	public function getInsuranceAgency(Request $request){
		if ($request->ajax()) {
			$data = User::selectRaw('concat(user_fname," ",user_lname) as username,users.*')->where('user_type','O')->whereRaw('from_unixtime(floor(created_at/1000)) >= DATE(NOW()) - INTERVAL 20 DAY')->where('is_role_assign','N')->whereNull('deleted_at')->with('insurance_agencies')->latest()->take(10)->orderBy('id','DESC')->get();

			return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) use ($request) {
                return $this->getDate($row->created_at, $request->timeZone);
            })
            ->make(true);
        }
	}

	public function getUsers(Request $request){
		if ($request->ajax()) {

			$data=User::selectRaw('concat(user_fname," ",user_lname) as username,users.*')->where('user_type','U')->whereRaw('from_unixtime(floor(created_at/1000)) >= DATE(NOW()) - INTERVAL 20 DAY')->where('is_role_assign','N')->whereNull('deleted_at')->latest()->take(10)->orderBy('id','DESC')->get();
			if(!empty($data)){
			foreach($data as $dt){
				$dt->team=Team::select('team_name','team_id')->where('team_id',$dt->team_id)->first();
				$dt->insurance_agency=InsuranceAgency::select('insurance_agency_name','insurance_agency_id')->where('insurance_agency_id',$dt->insurance_agency_id)->first();
			}}


            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('viewteam', function($row){
                $btn = "<a class='item-edit text-warning viewTeam' data-id='$row->id'  title='View Teams'><u>View</u></a>" ;
                return $btn;

            })
            ->editColumn('created_at', function ($row) use ($request) {
                return $this->getDate($row->created_at, $request->timeZone);
            })
            ->rawColumns(['viewteam'])
            ->make(true);
        }
	}

	public function getTeams(Request $request){
		if ($request->ajax()) {
            // if($this->isAdmin()){
            //     $insurance_agency_id =    Auth::user()->insurance_agency_id;
            // }else{
            //     $insurance_agency_id =    Auth::user()->insurance_agencies->insurance_agency_id;
            // }
            $insurance_agency_id = Session::get('INSURANCE_AGENCY_ID');
			// $data = User::selectRaw('concat(user_fname," ",user_lname) as username,users.*')->where('insurance_agency_id',$insurance_agency_id)->where('is_role_assign','N')->where('user_type','T')->with('team')->whereNull('deleted_at')->latest()->take(5)->get();
            $data = Team::where('insurance_agency_id',$insurance_agency_id)->whereNull('deleted_at')->latest()->take(5)->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) use ($request) {
                return $this->getDate($row->created_at, $request->timeZone);
            })
            ->make(true);
        }
	}

	public function getUsersByinsurance(Request $request){
		if ($request->ajax()) {
            $InsuranceAgencyID = Session('INSURANCE_AGENCY_ID');
            // if($this->isAdmin()){
            //     $insurance_agency_id =    Auth::user()->insurance_agency_id;
            // }else{
                // $insurance_agency_id = Session::get('INSURANCE_AGENCY_ID');
            // }
            // $data = User::selectRaw('concat(user_fname," ",user_lname) as username, users.*')->insuranceAgencyScope()->with('insuranceTeam')->whereNull('deleted_at');
            $data = User::join('user_roles', function ($join) use ($InsuranceAgencyID) {
                    $join->on('users.id', '=', 'user_roles.user_id')
                        ->where(['user_roles.insurance_agency_id' => $InsuranceAgencyID])
                        ->whereNotIn('user_roles.role',['O']);
                    })
                    ->whereNull('users.deleted_at')
                    ->select('users.*','user_roles.created_at as ucreated_at')
                    ->selectRaw('concat(user_fname," ",user_lname) as username')
                    ->orderBy('id', 'DESC');

            if(Session('USER_TYPE') == 'OA'){
                $data = $data->IgnoreSelf();
            }
			
            $data = $data->latest()->take(5)->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) use ($request) {
                return $this->getDate($row->ucreated_at, $request->timeZone);
            })
            ->addColumn('viewteam', function($row){
                $btn = "<a class='item-edit text-warning viewTeam' data-id='$row->id'  title='View Teams'><u>View</u></a>" ;
                return $btn;

            })
            ->rawColumns(['viewteam'])
            ->make(true);
        }
	}

	public function getAuditHistory(){
		$auditData =  Audit::selectRaw('count(audit_id) as audit_count, DATE_FORMAT(FROM_UNIXTIME(created_at/1000, "%Y-%m-%d"), "%Y-%m-%d") as date_created_at');
		if(in_array(Session('USER_TYPE'),['O','OA']) ){
            // if($this->isAdmin()){
            //     $insurance_agency_id =    Auth::user()->insurance_agency_id;
            // }else{
            //     $insurance_agency_id =    Auth::user()->insurance_agencies->insurance_agency_id;
            // }
            $insurance_agency_id = Session::get('INSURANCE_AGENCY_ID');
			$auditData = $auditData->where(['insurance_agency_id' => $insurance_agency_id]);
		}
		elseif(Session::get('USER_TYPE') == 'T'){
            // if(!$this->isAdmin()){
            //     $auditData = $auditData->where(['team_id' => Auth::user()->team->team_id]);
            // }else{
                $auditData = $auditData->whereIn('team_id',$this->getTeamsArr());
            // }
		}
        elseif(Session::get('USER_TYPE') == 'U'){
            $auditData = $auditData->whereIn('team_id' , $this->getTeamsArr());
        }

        $auditData = $auditData->groupBy('date_created_at')->orderby('created_at')->withTrashed()->get();

        $timeu = array();
        $count = array();

        if (count($auditData)) {
            foreach ($auditData as $data) {
                $timeu[] = strtotime($data->date_created_at) * 1000;
                $count[] = $data->audit_count;
            }
        }
        $auditarray = array_map(null, $timeu, $count);
        return $auditarray;
	}

    public function getUserHistory()
    {
        // $userData =  User::whereHas('userRole', function ($q) {
        //     $q->whereIn('role', ['OA', 'U', 'T']);
        // })->selectRaw('count(id) as user_count, DATE_FORMAT(FROM_UNIXTIME(users.created_at/1000, "%Y-%m-%d"), "%Y-%m-%d") as date_created_at')->whereNull('deleted_at');

        if (in_array(Session('USER_TYPE'),['O','OA'])) {
            $InsuranceAgencyID = Session('INSURANCE_AGENCY_ID');
            $userData = User::join('user_roles', function ($join) use ($InsuranceAgencyID) {
                    $join->on('users.id', '=', 'user_roles.user_id')
                        ->where(['user_roles.insurance_agency_id' => $InsuranceAgencyID])
                        ->whereNotIn('user_roles.role',['O']);
                    })
                    ->whereNull('users.deleted_at')
                    ->select('users.*','user_roles.created_at as ucreated_at')
                    ->selectRaw('count(id) as user_count, DATE_FORMAT(FROM_UNIXTIME(user_roles.created_at/1000, "%Y-%m-%d"), "%Y-%m-%d") as date_created_at')
                    ->IgnoreSelf()
                    ->groupBy('date_created_at')->orderby('users.created_at')->get();

            // $userData = $userData->insuranceAgencyScope()->IgnoreSelf();
        } elseif(in_array(Session('USER_TYPE'),['SA','A'])){
            $userData = User::with(['createdby','get_org_name','insuranceTeam'])->whereHas('userRole', function($q){
                $q->whereIn('role',['OA','U','T']);
            })->whereNull('deleted_at')->selectRaw('count(id) as user_count, DATE_FORMAT(FROM_UNIXTIME(users.created_at/1000, "%Y-%m-%d"), "%Y-%m-%d") as date_created_at')
            ->groupBy('date_created_at')->orderby('users.created_at')->get();

        }
        elseif (Session('USER_TYPE') == 'T') {
            $userData = $userData->TeamAccessScope()->InsuranceAgencyScope()->IgnoreSelf();
        }

        // $userData = $userData->groupBy('date_created_at')->orderby('users.created_at')->get();

        $timeu = array();
        $user = array();

        if (count($userData)) {
            foreach ($userData as $data) {
                $timeu[] = strtotime($data->date_created_at) * 1000;
                $user[] = $data->user_count;
            }
        }
        $userarray = array_map(null, $timeu, $user);

        return $userarray;
    }

    public function saveUserRoleConfig($id){
        try{
            $id = Crypt::decryptString($id);
            $userRoleData = UserRole::where('user_role_id',$id)->first();

            Session::put(['INSURANCE_AGENCY_ID' => $userRoleData->insurance_agency_id, 'USER_TYPE' => $userRoleData->role, 'SHOW_MENU' => 'Yes']);
            Session::put('USER_ORG_ID', $userRoleData->user_id);

            // $uid = Auth::id();
            $uid = $userRoleData->user_id;
            Auth::logout();
            Auth::loginUsingId($uid);
            // dd($uid);
            $user = User::where('id',$uid)->first();
            // check according to user type
            $user_type = Session::get('USER_TYPE');
            $insurance_agency_id = Session::get('INSURANCE_AGENCY_ID');
            User::where('id',$uid)->update(['user_last_login' => round(microtime(true) * 1000)]);
            if($user_type == "T"){
                     // take active teams which is assigned to user
                    $teams = $this->countOfActiveTeam($user->id);
                    if($teams > 0){
                        $agency = InsuranceAgency::where('insurance_agency_id', $insurance_agency_id)->first();
                        if(!empty($agency)){
                            $agencyuser = User::where('id',$agency->user_id)->where('user_type','O')->whereNull('deleted_at')->first();
                            if($agencyuser->user_status == "Active"){
                                // User::where('id',$id)->update(['user_last_login' => round(microtime(true) * 1000)]);
                                if($user->user_phone_no !=''){
                                    return $this->checkMfa($user->user_phone_no);
                                }else{
                                    return  redirect('two-fact-auth/updateProfile');
                                }
                            }
                        }
                    }else{
                        Auth::logout();
                        Auth::loginUsingId(Session::get('USER_ORG_ID'));

                        $userRoleData =  UserRole::select('user_roles.*', 'insurance_agencies.insurance_agency_name')
                            ->join('insurance_agencies', 'insurance_agencies.insurance_agency_id', 'user_roles.insurance_agency_id')
                            ->where('user_roles.user_id', Session::get('USER_ORG_ID'))->get();

                        $pageConfigs = [
                            'bodyClass' => "bg-full-screen-image",
                            'blankPage' => true
                        ];
                        toastr()->error('You have not part of any team. Please contact to Admin.');
                        return view('/auth/orgType', [
                            'pageConfigs' => $pageConfigs,
                            'userRoleData' => $userRoleData
                        ]);
                    }

            }else if($user_type == "U"){
                // take active teams which is assigned to user
                $teams = $this->countOfActiveTeam($user->id);
                if($teams > 0 ){
                    $agency = InsuranceAgency::where('insurance_agency_id',$insurance_agency_id)->first();

                    if(!empty($agency) ){
                        $agencyuser = User::where('id',$agency->user_id)->where('user_type','O')->whereNull('deleted_at')->first();
                        if(!empty($agencyuser) ){
                            if($agencyuser->user_status == "Active" ){
                                
                                User::where('id',$id)->update(['user_last_login' => round(microtime(true) * 1000)]);
                                if($user->user_phone_no!=''){
                                    return $this->checkMfa($user->user_phone_no);
                                }else{
                                    //return  redirect('two-fact-auth/updateProfile');
                                     return view('/auth/updateProfile');
                                }
                            }
                        }
                    }
                }else{
                    Auth::logout();
                    Auth::loginUsingId(Session::get('USER_ORG_ID'));

                    $userRoleData =  UserRole::select('user_roles.*', 'insurance_agencies.insurance_agency_name')
                        ->join('insurance_agencies', 'insurance_agencies.insurance_agency_id', 'user_roles.insurance_agency_id')
                        ->where('user_roles.user_id', Session::get('USER_ORG_ID'))->get();

                    $pageConfigs = [
                        'bodyClass' => "bg-full-screen-image",
                        'blankPage' => true
                    ];
                    toastr()->error('You have not part of any team. Please contact to Admin.');
                    return view('/auth/orgType', [
                        'pageConfigs' => $pageConfigs,
                        'userRoleData' => $userRoleData
                    ]);
                }
            }else{
                if($user->user_phone_no !=''){
                    return $this->checkMfa($user->user_phone_no);
                }else{
                    return  redirect('two-fact-auth/updateProfile');
                }
            }
        }catch(\Exception $e){
            Auth::logout();
            toastr()->error('Something went wrong');
            return redirect('login');
        }
    }
}
