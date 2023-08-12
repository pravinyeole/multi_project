<?php

namespace App\Observers;

use App\Mail\CreateUser;
use App\Models\InsuranceAgency;
use App\Models\Notification;
use App\Models\Team;
use App\Models\User;
use App\Models\UserAceessTeam;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use App\Traits\CommonTrait;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class UserObserver
{   
    use CommonTrait;
    public $userType;
    public $userRole;
    public $teamAccess;
    protected $request;

    public function __construct(Request $request)
    {   
        $this->userRole = isset($request['role']) ? $request['role'] : 'U' ;
        $this->userType = session('USER_TYPE');
        $this->request = $request;
    }
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {   
        if(in_array($this->userType,['O','U','T','OA'])){
            $input = $this->request;
            $authInsuranceAgencyID = Auth::user()->getInsuranceAgencyID();
            $agency = InsuranceAgency::where('insurance_agency_id', $authInsuranceAgencyID)->first();
            $this->updateTeamLicenceQty($agency->user_id, 'Increment', 'User', $user);
            $teamName = "";
            if (isset($input['team_access']) && isset($input['team_admin'])) {
                $teamids = $input['team_admin'] + $input['team_access'];
            } elseif (isset($input['team_access'])) {
                $teamids = $input['team_access'];
            } elseif (isset($input['team_admin'])) {
                $teamids = $input['team_admin'];
            }
            if (isset($teamids)) {
                if (in_array($this->userType, ['U', 'O', 'T'])) {
                    foreach ($teamids as $teamId => $value) {
                        $userAccessInput['user_id'] = $user['id'];
                        $userAccessInput['team_id'] = $teamId;
                        $userAccessInput['created_at'] = round(microtime(true) * 1000);
                        $userAccessInput['insurance_agency_id'] = $authInsuranceAgencyID;
                        $userAccessInput['is_admin'] = isset($input['team_admin'][$teamId]) ? '1' : '0';
                        $userTeamAccess[] = new UserAceessTeam($userAccessInput);
                       
                        $team = Team::where('team_id', $teamId)->first();
                        $teamName .= $team->team_name . ",";
                    }
                    $user->userTeamAccess()->saveMany($userTeamAccess);
                }
            }
            $input['team_name'] = rtrim($teamName, ',');
            $input['agency_name'] = $agency->insurance_agency_name;

            // Add entry in user_roles table
            UserRole::create(
                [
                    'user_id' => $user['id'],
                    'insurance_agency_id' => $agency->insurance_agency_id,
                    'role' => $this->userRole,
                    'created_at' => round(microtime(true) * 1000)
                ]
            );

            // send welcome email to user
            if (Config('constants.email_yesno') == 'Y' && !app()->isLocal()) {
                $notification = Notification::where(['notification_type' => 'email', 'notification_for' => 'welcome_user'])->first();
                if (!empty($notification)) {
                    try {
                        Mail::to($user['email'])->cc($notification->cc_email)->send(new CreateUser($input, $notification));
                    } catch (\Exception $e) {
                        $a2 = array("user_id" => $user->id, "user_name" => $user['user_fname'] . ' ' . $user['user_lname'], "user_email" => $user['email'], "action" => 'User Welcome email failed');
                        $this->addLog($a2, 'DEBUG');
                    }
                }
            }
        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
