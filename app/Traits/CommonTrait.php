<?php
namespace App\Traits;
use Illuminate\Http\Request;

use App\Models\InsuranceAgency;
use App\Models\User;
use App\Models\Client;
use App\Models\Carrier;
use App\Models\InsurancePlan;
use App\Models\UserAceessTeam;
use App\Models\Team;
use App\Models\InsuranceAgencyCarrier;
use Carbon\Carbon;
use Log;
use Auth;
use DB;
use Session;

trait CommonTrait {
    // convert millies to date
    public function getDate($date, $timezone){
        $created_at = ($date/1000);
        $created_at = new \DateTime(date('F j, Y, g:i:s a', $created_at), new \DateTimeZone("UTC"));
        $created_at->setTimezone(new \DateTimeZone($timezone));

        return $created_at->format('m-d-Y H:i:s');
    }

    // update status
    public function modifyStatus($request, $model, $field){
        $prefix = '\App\Models';
        $model_name = $prefix. "\\". $model;
        $data = $model_name::findOrFail($request->id);
        $data->$field = $request->type;
        $data->save();
        $resultArr['title'] = 'Success';
        $resultArr['message'] = 'Status updated successfully';
        echo json_encode($resultArr);
        exit;
    }

    // delete entry from table
    public function deleteRecord($request, $model, $field){
        dd($model);
        $prefix = '\App\Models';
        $model_name = $prefix. "\\". $model;
        $data = $model_name::findOrFail($request->id);
        $data->$field = round(microtime(true) * 1000);
        $data->save();
        $resultArr['title'] = 'Success';
        $resultArr['message'] = 'Record deleted successfully';
        echo json_encode($resultArr);
        exit;
    }
// delete entry from table
    public function deleteItem($request, $field)
    {
        $prefix     = '\App\Models';
        $model_name = $prefix . "\\" . $request->model;
        $id         = decrypt($request->id);
        // dd($request);
    
            $data           = $model_name::findOrFail($id);
            $data->$field   =  date('Y-m-d H:i:s');
            $data->save();

        $resultArr['title']     = 'Success';

        $resultArr['message']   = (($request->model) ? $request->model : 'Record'). ' deleted successfully.';
        echo json_encode($resultArr);
        exit;
    }

    // add log
    public function addLog($input, $type){
        $isodate = Carbon::now();
        $isodate = $isodate->toIso8601String();

        $a1 = array("time" => $isodate, "loglevel" => $type);
        $logmsg = array_merge($a1, $input);

        if($type == "INFO"){
            \Log::INFO(json_encode($logmsg));
        }
        else{
            \Log::DEBUG(json_encode($logmsg));
        }
        return;
    }

    public function getAgency(){
      $agency_list = User::select('insurance_agencies.*','users.email','users.id')
            ->join('insurance_agencies','users.id','=','insurance_agencies.user_id')
            // ->where('users.user_status','Active')
            ->where('users.user_type','O')
            ->whereNull('users.deleted_at')
            ->orderBy('insurance_agencies.insurance_agency_name','ASC')->get();

      return $agency_list;
    }
    public function getActiveAgency(){
        $agency_list = User::select('insurance_agencies.*','users.email','users.id')
              ->join('insurance_agencies','users.id','=','insurance_agencies.user_id')
              ->where('users.user_status','Active')
              ->where('users.user_type','O')
              ->whereNull('users.deleted_at')
              ->orderBy('insurance_agencies.insurance_agency_name','ASC')->get();

        return $agency_list;
      }


    public function getTeams($insurance_agency_id){
        if(Session::get('USER_TYPE') == 'U'){
           $teams_list = $this->assignedTeams();

        }else{
            $teams_list = Team::
                where('team_status','Active')
                ->where('insurance_agency_id',$insurance_agency_id)
                // ->where('users.user_type','T')
                ->whereNull('deleted_at')
                ->orderBy('team_name','ASC')->get();
        }
        return $teams_list;
    }

    // get users according to insurance agency
    public function getUsersList($insurance_agency_id){
        if(Session::get('USER_TYPE') == 'O'){
           $users = User::join('user_roles','user_roles.user_id','users.id')->where('user_roles.insurance_agency_id',$insurance_agency_id)->where('user_status','Active')->where('user_type','U')->whereNull('deleted_at')->orderBy('user_fname','ASC')->get();
        }else if(Session::get('USER_TYPE') == 'T'){
            // if(!$this->isAdmin()){
            //     $users = User::select('users.*')
            //         ->join('user_roles','user_roles.user_id','users.id')
            //         ->join('user_aceess_teams','users.id','user_aceess_teams.user_id')
            //         ->where('user_roles.insurance_agency_id',$insurance_agency_id)
            //         ->where('users.user_status','Active')
            //         ->where('users.user_type','U')
            //         ->whereNull('deleted_at')
            //         ->where('user_aceess_teams.team_id',Auth::user()->team->team_id)->get();
            // }else{
                $users = User::select('users.*')
                ->join('user_roles','user_roles.user_id','users.id')
                ->join('user_aceess_teams','users.id','user_aceess_teams.user_id')
                ->where('user_roles.insurance_agency_id',$insurance_agency_id)
                ->where('users.user_status','Active')
                ->where('users.user_type','U')
                ->whereNull('deleted_at')
                ->whereIn('user_aceess_teams.team_id',$this->getTeamsArr())->get();
            // }

        }else{
            $users = User::join('user_roles','user_roles.user_id','users.id')->where('user_roles.insurance_agency_id',$insurance_agency_id)->where('user_status','Active')->whereNull('deleted_at')->orderBy('user_fname','ASC')->get();
        }
       return $users;
    }

    // get clients which is present in db according to user type
    public function getClients(){
        $insurance_agency_id = Auth::user()->getInsuranceAgencyID();
        if(Session::get('USER_TYPE') == 'O' ){
            // if($this->isAdmin()){
            //     $insurance_agency_id =    Auth::user()->insurance_agency_id;
            // }else{
            //     $insurance_agency_id =    Auth::user()->insurance_agencies->insurance_agency_id;
            // }

            $clients = Client::where('client_status','Active')->where('insurance_agency_id',$insurance_agency_id)->whereNull('deleted_at')->orderBy('client_name','ASC')->get();
        }else if(Session::get('USER_TYPE') == 'T' || Session::get('USER_TYPE') == 'U'){
            $clients = Client::where('client_status','Active')->where('insurance_agency_id',$insurance_agency_id)->whereNull('deleted_at')->orderBy('client_name','ASC')->get();
        }else{
            $clients = Client::where('client_status','Active')->whereNull('deleted_at')->orderBy('client_name','ASC')->get();
        }

        return $clients;
    }

    // get carrier which is present in db according to user type
    public function getCarrier(){
        $insurance_agency_id = Auth::user()->getInsuranceAgencyID();
        if(Session::get('USER_TYPE') == 'O' ){
            //Check is agency admin  or not
            // if($this->isAdmin()){
            //     $insurance_agency_id =    Auth::user()->insurance_agency_id;
            // }else{
            //     $insurance_agency_id =    Auth::user()->insurance_agencies->insurance_agency_id;
            // }

            $carrier = Carrier::select('carriers.*')
            ->join('insurance_agency_carriers','carriers.carrier_id','=','insurance_agency_carriers.carrier_id')
            ->where('insurance_agency_carriers.insurance_agency_id',$insurance_agency_id)->where('carriers.carrier_status','Active')->whereNull('carriers.deleted_at')->orderBy('carriers.carrier_name','ASC')->get();
        }else if(Session::get('USER_TYPE') == 'T' || Session::get('USER_TYPE') == 'U'){
            $carrier = Carrier::select('carriers.*')
            ->join('insurance_agency_carriers','carriers.carrier_id','=','insurance_agency_carriers.carrier_id')
            ->where('insurance_agency_carriers.insurance_agency_id', $insurance_agency_id)->where('carriers.carrier_status','Active')->whereNull('carriers.deleted_at')->orderBy('carriers.carrier_name','ASC')->get();
        }else{
            $carrier = Carrier::where('carrier_status','Active')->whereNull('deleted_at')->orderBy('carrier_name','ASC')->get();
        }
        return $carrier;
     }


     //Check is admin or not
     public function isAdmin(){
        $data = User::where('id',Auth::id())->where('is_role_assign','Y')->first();
        if(!empty($data)){
            return 1;
        }else{
            return 0 ;
        }
    }

    //get teams ids
    public function getTeamsArr(){
        if( Session::get('USER_TYPE') == "T" || Session::get('USER_TYPE') == "U"){ // && Auth::user()->is_role_assign == "Y"
            $teamids = UserAceessTeam::where('user_id',Auth::id())->where('insurance_agency_id',Auth::user()->getInsuranceAgencyID())->pluck('team_id');
            if(!empty($teamids)){
                return $teamids;
            }else{
                return $teamids;
            }
        }
    }

    //get users by admin and normal team role
    public function getUsersArr(){
        if( Session::get('USER_TYPE') == "T"){
            // if(!$this->isAdmin()){
            //     $teamids = UserAceessTeam::where('team_id',Auth::user()->team->team_id)->pluck('user_id');
            // }else{
                $teamids = UserAceessTeam::whereIn('team_id',$this->getTeamsArr())->pluck('user_id');
            // }

            if(!empty($teamids)){
                return $teamids;
            }else{
                return $teamids;
            }
        }
    }

    // get teams according to logged in user id
    public function assignedTeams(){
        $teams= UserAceessTeam::select('teams.*')
            ->join('teams','user_aceess_teams.team_id','teams.team_id')
            ->whereNull('deleted_at')
            ->where('teams.team_status','Active')
            ->where('user_aceess_teams.user_id',Auth::id())
            ->where('user_aceess_teams.insurance_agency_id',Auth::user()->getInsuranceAgencyID())->get();
        return $teams;
    }

    //get assigned team list by insurance agency
    public function getInsuranceByassignedTeams($insurance_agency_id){
        $teams = Team::select('teams.*')
                    ->where('team_status','Active')
                    ->where('insurance_agency_id',$insurance_agency_id)
                    ->whereNull('deleted_at')
                    ->get();
        return $teams;
    }



    //get created by username
    public function createdByUsers(){
        // $users= DB::table('users as user')->selectRaw('user.id, concat(user.user_fname," ",user.user_lname) as user_name')
        //         ->join('users as us','user.id','us.created_by_id')
        //         ->where('user.user_status','Active')
        //         ->whereNull('user.deleted_at')->get();
        //         return $users;
        $insurance_agency_id = Auth::user()->getInsuranceAgencyID();
        // if(Session::get('USER_TYPE') == 'O' ){
            //Check is agency admin  or not
            // if($this->isAdmin()){
            //     $insurance_agency_id =    Auth::user()->insurance_agency_id;
            // }else{
            //     $insurance_agency_id =    Auth::user()->insurance_agencies->insurance_agency_id;
            // }
            
        // }
        // if(Session::get('USER_TYPE') == 'T' || Session::get('USER_TYPE') == 'U'){
        //     // $insurance_agency_id =    Auth::user()->insurance_agency_id;
        //     $insurance_agency_id = Auth::user()->getInsuranceAgencyID();
        // }
        if(Session::get('USER_TYPE') == 'T' || Session::get('USER_TYPE') == 'U' || Session::get('USER_TYPE') == 'O'){
            $users= User::selectRaw('id, concat(user_fname," ",user_lname) as user_name')
            ->where('user_status','Active')
            ->where('user_type','!=','SA')
            // ->where('insurance_agency_id', $insurance_agency_id)
            ->whereNull('deleted_at')->get();
        }
        else{
            $users= User::selectRaw('id, concat(user_fname," ",user_lname) as user_name')
                ->where('user_status','Active')
                ->where('user_type','!=','SA')
                ->whereNull('deleted_at')->get();
        }

        return $users;

    }

    public function countOfActiveTeam($id){
        $teamsCount = Team::select('teams.*')
                    ->where('team_status','Active')
                    ->whereIn('team_id',$this->getTeamsArr())
                    ->whereNull('deleted_at')
                    ->count();
        return $teamsCount;
    }

    //CHANGES BY NARSING FOR CHANGE STATUS
    public function modifyRoleStatus($request, $insurance_agency_id, $model, $field){
        $prefix = '\App\Models';
        $model_name = $prefix. "\\". $model;
        $data = $model_name::where('insurance_agency_id',$insurance_agency_id)->where('user_id',$request->id)->first();
        $data->update([$field => $request->type]);
        $resultArr['title'] = 'Success';
        $resultArr['message'] = 'Status updated successfully';
        echo json_encode($resultArr);
        exit;
    }

}
