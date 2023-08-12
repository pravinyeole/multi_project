<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\SendMail;
use App\Models\User;
use App\Models\Audit;
use App\Models\Notification;
use App\Models\InsuranceAgency;
use Log;
use App\Mail\CommonMail;
use App\Traits\CommonTrait;
use App\Traits\AuditTrait;
use Config;

class AuditAlerts extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audits alerts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    use CommonTrait;
    public function __construct()
    {

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $insuranceAgency= $this->getActiveAgency();
        $totalNoOfAudits = 0;
        $auditCount=0;
        if($insuranceAgency->isNotEmpty()) {
         foreach($insuranceAgency as $agency){
                $totalNoOfAudits = $agency->no_of_audits;
                $auditCount = Audit::where('insurance_agency_id',$agency->insurance_agency_id)->count();
                $remainingCount = $totalNoOfAudits - $auditCount;
                \Log::info($remainingCount);
                \Log::info($auditCount);
                \Log::info($totalNoOfAudits);
                $other['remainingCredit'] = $remainingCount;
                if($remainingCount < Config('constants.max_no_of_audit') &&  $remainingCount > Config('constants.max_no_of_audit')){
                    if(Config('constants.email_yesno') == 'Y' && $agency->notification_alert == "Yes"){

                        $notification = Notification::where(['notification_type' => 'email', 'notification_for' => 'audit_alert'])->first();
                        if(!empty($notification)){
                            try{
                                Mail::to($agency->email)->cc($notification->cc_email)->send(new CommonMail($agency, $notification, $other));
                            }catch(\Exception $e){
                                $a2 = array("user_id" => $agency->id, "action" => 'Audit alert email failed');
                                $this->addLog($a2, 'DEBUG');
                                 \Log::DEBUG($a2);
                            }
                        }
                    }
                }

            }
        }
    }
}
