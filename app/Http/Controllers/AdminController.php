<?php

namespace App\Http\Controllers;

use App\Mail\CreateAdminUser;
use App\Models\Notification;
use App\Models\InsuranceAgency;
use App\Models\Team;
use App\Models\UserAceessTeam;
use App\Models\UsersRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use DataTables;
use Auth;
use Hash;
use Mail;
use DB;
use Config;
use Session;


use App\Traits\CommonTrait;

class AdminController extends Controller
{
    use CommonTrait;

    public function __construct(){
        $this->title = "Admins";
        $this->middleware(['auth']);
    }

    public function index(Request $request){
        $title = $this->title;
        $agency_list =   $this->getAgency();
        
        if ($request->ajax()) {
            //Check is agency admin  or not
            // if($this->isAdmin()){
            //     $insurance_agency_id =    Auth::user()->insurance_agency_id;
            // }else{
            //     $insurance_agency_id =    Auth::user()->insurance_agencies->insurance_agency_id;
            // }
            $insurance_agency_id = Auth::user()->getInsuranceAgencyID();
            $data = User::with(['role','createdby','get_org_name','insuranceTeam'])->where(['is_role_assign'=>'Y','insurance_agency_id' => $insurance_agency_id])->whereNull('deleted_at')->orderBy('id', 'DESC')->where('id','!=',Auth::id())->get();
            // return $data;
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('user_status', function ($row) {
                    if($row->user_status == 'Active'){
                        $status = "<button title='Active' data-id='$row->id'   data-url='admins' data-type='Inactive' class='btn btn-success status'>Active</button>";
                    }else{
                        $status = "<button title='Inactive' data-id='$row->id' data-url='admins' data-type='Active' class='btn btn-danger status'>Inactive</button>";
                    }
                    return $status;
                })

                ->addColumn('action', function($row){
                    $id = Crypt::encryptString($row->id);
                    $btn = "<a href='admins/view/$id' class='item-edit  text-warning'  title='View Admin'><svg xmlns='http://www.w3.org/2000/svg' width=24 height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-eye font-small-4'><path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'></path><circle cx='12' cy='12' r='3''></circle></svg></a> &nbsp;" ;

                    // if(Session::get('USER_TYPE') == 'U' || Session::get('USER_TYPE') == 'T'){
                        $btn .="<a href='admins/edit/$id' class='item-edit'  title='Edit Admin'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit font-small-4'><path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path><path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path></svg></a>
                        <a class='delete-record delete item-edit text-danger' data-id='$row->id' data-url='admins' title='Delete Admin'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2 font-small-4'><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></a>&nbsp;";
                        if(Session::get('USER_TYPE')!='U'){
                            if($row->user_status == 'Active') {
                                // if(!empty($row->role)){
                                    // if($row->role->role_name == "TA"){
                                        $btn .= "<a  data-id='$row->id' class='org_login ' title='User Login'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-log-in font-small-4'><path d='M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4'></path><polyline points='10 17 15 12 10 7'></polyline><line x1='15' y1='12' x2='3' y2='12'></line></svg></a>";
                                    // }
                                // }
                            }
                        }
                    // }


                    return $btn;
                })
                ->addColumn('userOnlyName', function($row){
                    if(!empty($row->createdby)){
                     return   $row->createdby->userOnlyName;
                    }
                     return  '';
                })
                ->addColumn('role_name', function($row){
                    if(!empty($row->role)){
                        if($row->role->role_name == "IA"){
                         return   "Insurance Agency Admin";
                        }else{
                        return   "Team Admin";
                        }
                    }
                     return  '';
                })
                ->editColumn('created_at', function ($row) use ($request) {
                    return $this->getDate($row->created_at, $request->timeZone);
                   })
                ->addColumn('status', function ($row) {
                    return $row->user_status;
                })
                ->rawColumns(['user_status','created_at','action'])
                ->make(true);
        }

        return view('admins.index',compact('title'));
    }

    public function create(Request $request){
        try {
            $title = $this->title;
             //Check is agency admin  or not
            // if(!$this->isAdmin()){
            //     $insurance_agency_id =    Auth::user()->insurance_agencies->insurance_agency_id;
            // }else{
            //     $insurance_agency_id =    Auth::user()->insurance_agency_id;
            // }
            $insurance_agency_id = Auth::user()->getInsuranceAgencyID();
            $teams = $this->getTeams($insurance_agency_id);
            return view('admins.create',compact('title','teams'));
        }catch (\Exception $e){
            return redirect('admins/create');
        }
    }

    public function store(Request $request){
        DB::beginTransaction();
        try {
            $input = $request->all();
            $input['created_at'] = round(microtime(true) * 1000);
            $input['modified_at'] = round(microtime(true) * 1000);
            $input['user_status'] = "Active";
            $input['plain_pwd'] = $input['password'];

            // if(!$this->isAdmin()){
            //     $input['insurance_agency_id'] =    Auth::user()->insurance_agencies->insurance_agency_id;
            // }else{
            //     $input['insurance_agency_id'] =    Auth::user()->insurance_agency_id;
            // }
            $input['insurance_agency_id'] = Auth::user()->getInsuranceAgencyID();
            // $input['team_id']=Auth::user()->team_id;
            $input['created_by_id']=Auth::user()->id;
            $input['is_role_assign']='Y';

            $input['password'] = Hash::make($input['password']);
            $checkExist = User::where('email',$input['email'])->whereNull('deleted_at')->get();
            // return $input;
            if($checkExist->isEmpty()) {
                // save data into user table
                $user = User::create($input);
                if (isset($user)) {
                    $userRole['user_id'] = $user->id;
                    if($input['user_type'] == 'T'){
                        $userRole['role_name']='TA';
                    }else{
                        $userRole['role_name']='IA';
                    }
                    $userRole['status']= "Active";
                    $userRole['created_at'] = round(microtime(true) * 1000);

                    UsersRole::create($userRole);
                   //save data in users access table
                    if($input['user_type'] == 'T'){
                        foreach ($input['teamid'] as $teamId) {
                            $userAccessInput['user_id'] = $user->id;
                            $userAccessInput['team_id'] = $teamId;
                            $userAccessInput['created_at'] = round(microtime(true) * 1000);
                            UserAceessTeam::create($userAccessInput);
                        }
                    }
                    $agency = InsuranceAgency::where('insurance_agency_id',$input['insurance_agency_id'])->first();
                    $input['agency_name'] = $agency->insurance_agency_name;
                    //Sending welcome mail
                    if(Config('constants.email_yesno') == 'Y'){
                        $notification = Notification::where(['notification_type' => 'email', 'notification_for' => 'welcome_admin_user'])->first();
                        if(!empty($notification)){
                            try{
                                Mail::to($input['email'])->cc($notification->cc_email)->send(new CreateAdminUser($input, $notification));
                            }catch(\Exception $e){
                                $a2 = array("user_id" => $user->id, "user_name" => $input['user_fname'].' '.$input['user_lname'], "user_email" => $input['email'], "action" => 'Admin Welcome email');
                                $this->addLog($a2, 'DEBUG');
                            }
                        }
                    }

                    if($input['user_type'] == 'T'){
                         // update team licence subscription by qty 1
                        $this->updateTeamLicenceQty(Auth::id(),'Increment','Team Admin',$input);
                        $msg = 'Team Admin created successfully !!';
                    }else{
                        // update team licence subscription by qty 1
                        $this->updateTeamLicenceQty(Auth::id(),'Increment','Insurance Agency Admin',$input);
                        $msg = 'Insurance Agency Admin created successfully !!';
                    }
                    DB::commit();
                    toastr()->success($msg);
                    return redirect('admins');
                }
            }else{
                if($input['user_type'] == 'T'){
                    $msg = 'Team Admin already exist with this email !!';
                }else{
                    $msg = 'Insurance Agency Admin already exist with this email !!';
                }
                toastr()->error($msg);
                return redirect('admins/create')->withInput($request->input());
            }
        }catch(\Exception $e){
            DB::rollback();
            toastr()->error('Something went wrong !!');
            return redirect('admins/create')->withInput($request->input());
        }
        toastr()->error('Something went wrong !!');
        return redirect('admins/create')->withInput($request->input());
    }

    public function edit(Request $request,$id){
        $title = $this->title;
        try {
            $id=Crypt::decryptString($id);
            //Check is agency admin  or not
            // if(!$this->isAdmin()){
            //     $insurance_agency_id =    Auth::user()->insurance_agencies->insurance_agency_id;
            // }else{
            //     $insurance_agency_id =    Auth::user()->insurance_agency_id;
            // }
            $insurance_agency_id = Auth::user()->getInsuranceAgencyID();
            //get temas by agency
            $teams = $this->getTeams($insurance_agency_id);
            $user = User::find($id);
            $useraceess = $user->userTeamAccess;
            $cids = array();
            if(!empty($useraceess)){
                foreach ($useraceess as $uat) {
                    $cids[] = $uat->team_id;
                }
            }
            return view('admins/edit',compact('title','user','teams','cids'));
        }catch (\Exception $e){
            toastr()->error('Something went wrong');
            return redirect('admins');
        }
    }

    public function update(Request $request){
        try {
            $input = $request->all();

            if(isset($input['teamid']) && $input['teamid'] >0) {
                $teamIds =    $input['teamid']; 
            }
            unset($input['_token']);
            unset($input['teamid']);
            $input['modified_at'] = round(microtime(true) * 1000);
            if (empty($input['password'])) {
                unset($input['password']);
            } else {
                $input['password'] = Hash::make($input['password']);
            }

            $emailExist = User::where('email', $input['email'])->where('id', '!=', $input['id'])->get();
            if ($emailExist->isEmpty()) {
                $uUpdate =User::where('id',$input['id'])->first();
                if($uUpdate->user_type != $input['user_type']){
                    $updateRole = UsersRole::where('status','Active')->where('user_id',$input['id'])->first();

                    $searchinput['user_role_id'] = $updateRole->user_role_id;
                    $upDate['status'] = 'Inactive';
                    $upDate['created_at'] = round(microtime(true) * 1000);
                    UsersRole::updateorcreate($searchinput,$upDate);
                    $userRole['user_id'] = $input['id'];
                    if($input['user_type'] == 'T'){
                        $userRole['role_name'] ='TA';
                        $userRole['status'] = 'Active';
                    }else{
                        $userRole['role_name'] ='IA';
                        $userRole['status'] = 'Active';
                    }
                    UsersRole::create($userRole,$userRole);
                }
                $user = User::where('id', $input['id'])->update($input);
                if($input['user_type'] == 'T'){
                    foreach ($teamIds as $teamId) {
                        $userAccessInput['user_id'] = $input['id'];
                        $userAccessInput['team_id'] = $teamId;
                        $userAccessInput['is_updated'] = 'Y';
                        $userAccessInput['created_at'] = round(microtime(true) * 1000);
                        UserAceessTeam::updateorcreate($userAccessInput,$userAccessInput);
                    }
                       //  / delete records
                       UserAceessTeam::whereNull('is_updated')->where('user_id', $input['id'])->delete();
                       // update flag
                       UserAceessTeam::where('user_id', $input['id'])->update(['is_updated' => Null]);
                }else{
                    UserAceessTeam::where('user_id', $input['id'])->delete();
                }
                if($input['user_type'] == 'T'){
                    $msg='Team Admin Updated Successfully !!';
                }else{
                    $msg='Insurance Agency Admin Updated Successfully !!';
                }
                toastr()->success($msg);
                return redirect('admins');
            } else {
                if($input['user_type'] == 'T'){
                    $msg='Team Admin already exist with this email !!';
                }else{
                    $msg='Insurance Agency already exist with this email !!';
                }
                toastr()->error($msg);
                $id = Crypt::encryptString($input['id']);
                return redirect('admins/edit/'.$id);
            }
        }catch (\Exception $e){
            toastr()->error('Something went wrong !!');
            $id = Crypt::encryptString($input['id']);
            return redirect('admins/edit/'.$id);
        }
    }

    public function view(Request $request,$id){
        $title = $this->title;
        try {
            $id=Crypt::decryptString($id);
            //Check is agency admin  or not
            // if(!$this->isAdmin()){
            //     $insurance_agency_id =    Auth::user()->insurance_agencies->insurance_agency_id;
            // }else{
            //     $insurance_agency_id =    Auth::user()->insurance_agency_id;
            // }
            $insurance_agency_id = Auth::user()->getInsuranceAgencyID();
            $teams = $this->getTeams($insurance_agency_id);
            $user=User::find($id);
            $useraceess = $user->userTeamAccess;
            $cids = array();
            if(!empty($useraceess)){
                foreach ($useraceess as $uat) {
                    $cids[] = $uat->team_id;
                }
            }
            return view('admins/view',compact('title','user','teams','cids'));
        }catch (\Exception $e){
            toastr()->error('Something went wrong');
            return redirect('admins');
        }
    }

    //Status change of record
    public function updateStatus(Request $request){
        try{
            // if(!$this->isAdmin()){
            //     $insurance_agency_id =    Auth::user()->insurance_agencies->insurance_agency_id;
            // }else{
            //     $insurance_agency_id =    Auth::user()->insurance_agency_id;
            // }
            $insurance_agency_id = Auth::user()->getInsuranceAgencyID();

            $agency = InsuranceAgency::where('insurance_agency_id',$insurance_agency_id)->first();
            if($request->type == 'Inactive'){ // Decrement team licence subscription
                $type = 'Decrement';
            }else{ // Increment team licence subscription
                $type = 'Increment';
            }
            $this->updateTeamLicenceQty($agency->user_id, $type);

            $this->modifyStatus($request, 'User', 'user_status');
        }catch (\Exception $e){
            $resultArr['title'] = 'Error';
            $resultArr['message'] = 'Something went wrong';
            echo json_encode($resultArr);
            exit;
        }
    }

    //Delete record
    public function destroy(Request $request){
        try{
            $user = User::find($request->id);
            // Decrement team licence subscription
            if($user->user_status == 'Active'){
                // if(!$this->isAdmin()){
                //     $insurance_agency_id =    Auth::user()->insurance_agencies->insurance_agency_id;
                // }else{
                //     $insurance_agency_id =    Auth::user()->insurance_agency_id;
                // }
                $insurance_agency_id = Auth::user()->getInsuranceAgencyID();

                $agency = InsuranceAgency::where('insurance_agency_id',$insurance_agency_id)->first();
                $type = 'Decrement';
                $this->updateTeamLicenceQty($agency->user_id, $type);
            }

            User::where('id',$request->id)->update(['email' => DB::raw('CONCAT("retire#", email)')]);
            $this->deleteRecord($request, 'User', 'deleted_at');
        }catch (\Exception $e){
            $resultArr['title'] = 'Error';
            $resultArr['message'] = 'Something went wrong';
            echo json_encode($resultArr);
            exit;
        }
    }
}
