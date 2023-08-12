<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Config;
use Session;
use App\Traits\CommonTrait;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    use CommonTrait;
    public function register(){
        if (\Schema::hasTable('parameters')) {
            $system_parameters = DB::table('parameters')->get();
            if ($system_parameters && $system_parameters->isNotEmpty()){
                foreach ($system_parameters as $data) {
                    if($data->parameter_key == "mail_driver"){
                        $mail_driver = $data->parameter_value;
                    }elseif($data->parameter_key == "mail_host"){
                        $mail_host = $data->parameter_value;
                    }elseif($data->parameter_key == "mail_port"){
                        $mail_port = $data->parameter_value;
                    }elseif($data->parameter_key == "mail_username"){
                        $mail_username = $data->parameter_value;
                    }elseif($data->parameter_key == "mail_password"){
                        $mail_pwd = $data->parameter_value;
                    }elseif($data->parameter_key == "mail_encryption"){
                        $mail_encryption = $data->parameter_value;
                    }elseif($data->parameter_key == 'mail_from_address'){
                        $mail_from_address = $data->parameter_value;
                    }elseif($data->parameter_key == 'mail_from_name') {
                        $mail_from_name = $data->parameter_value;
                    }elseif($data->parameter_key == 'aws_access_key_id') {
                        $aws_access_key_id = $data->parameter_value;
                        Config::set('aws.credentials.key',$data->parameter_value);
                    }elseif($data->parameter_key == 'aws_secret_access_key') {
                        $aws_secret_access_key = $data->parameter_value;
                        Config::set('aws.credentials.secret',$data->parameter_value);
                    }elseif($data->parameter_key == 'aws_region') {
                        $aws_region = $data->parameter_value;
                        Config::set('aws.region',$data->parameter_value);
                    }elseif($data->parameter_key == 'aws_bucket') {
                        $aws_bucket = $data->parameter_value;
                    }elseif($data->parameter_key == 'stripe_key') {
                        Config::set('cashier.key', $data->parameter_value);
                    }elseif($data->parameter_key == 'stripe_secret') {
                        Config::set('cashier.secret', $data->parameter_value);
                    }else{
                        Config::set('constants.'.$data->parameter_key, $data->parameter_value);
                    }
                }

                $aws = array(
                    'driver' => 's3',
                    'key'    => $aws_access_key_id??'',
                    'secret' => $aws_secret_access_key??'',
                    'region' => $aws_region??'',
                    'bucket' => $aws_bucket??''
                );

                $mailSetup = array(
                    'transport'     => $mail_driver,
                    'host'          => $mail_host??'',
                    'port'          => $mail_port??'',
                    'encryption'    => $mail_encryption,
                    'username'      => $mail_username??'',
                    'password'      => $mail_pwd??'',
                    'timeout'       => null,
                    'auth_mode'     => null,
                );
                $mail_form = array('address' => $mail_from_address??'', 'name' => $mail_from_name);

                // setup filesystem
                Config::set('filesystems.disks.s3', $aws);
                // setup mail
                Config::set('mail.mailers.smtp', $mailSetup);
                Config::set('mail.from', $mail_form);
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){
        //Cashier::calculateTaxes();
        if($this->app->environment('prod')) {
            \URL::forceScheme('https');
        }

        View::composer('*', function($view){
            $iname = $tname = $groupLog = $uname = "";
            if (Auth::check()){
                if(Session::get('USER_TYPE') == 'O' || Session::get('USER_TYPE') == 'OA'){
                    $data = \App\Models\InsuranceAgency::where('user_id', Auth::id())->first();

                    // if($this->isAdmin() == '0'){
                    //     $iname = $data->insurance_agency_name;

                    //     $user = User::where('id',Auth::id())->first();
                    //     $uname = $user->user_fname.' '.$user->user_lname;
                    // }else{
                        $data = \App\Models\InsuranceAgency::where('insurance_agency_id', Auth::user()->getInsuranceAgencyID())->first();
                        $iname =  $data->insurance_agency_name;

                        $user = User::where('id',Auth::id())->first();
                        $uname = $user->user_fname.' '.$user->user_lname;
                    // }

                    $groupLog = \App\Models\InsuranceAgencyGroupHistory::join('group', 'group.group_id', '=', 'insurance_agencies_group_history.group_id')->whereNull('end_date')->where('insurance_agency_id', $data->insurance_agency_id)->pluck('group_logo')->first();
                }
                elseif (Session::get('USER_TYPE') == 'T') {
                    $data1 = \App\Models\InsuranceAgency::where('insurance_agency_id', Auth::user()->getInsuranceAgencyID())->first();
                    $iname = $data1->insurance_agency_name;

                    $sid      = Session::get('sub_org_id');
                    if($sid == ""){
                        $sid = Session::get('org_id');
                    }

                    if($sid!="" && $sid!= $data1->user_id){
                        $user = User::where('id',$sid)->first();
                        if(!empty($user)){
                            $tname = $user->user_fname.' '.$user->user_lname;
                        }
                    }

                    $groupLog = \App\Models\InsuranceAgencyGroupHistory::join('group', 'group.group_id', '=', 'insurance_agencies_group_history.group_id')->whereNull('end_date')->where('insurance_agency_id', Auth::user()->getInsuranceAgencyID())->pluck('group_logo')->first();

                }elseif (Session::get('USER_TYPE') == 'U') {
                   $sid = Session::get('team_id');
                   if($sid == ""){
                    $sid = Session::get('sub_org_id');
                   }
                   if($sid == ""){
                    $sid = Session::get('org_id');
                   }

                    $data1 = \App\Models\InsuranceAgency::where('insurance_agency_id', Auth::user()->getInsuranceAgencyID())->first();
                    $iname = $data1->insurance_agency_name;
                    if($sid!='' && $sid!= $data1->user_id){
                        $user = User::where('id',$sid)->first();
                        if(!empty($user)){
                            $tname = $user->user_fname.' '.$user->user_lname;
                        }
                    }
                    $groupLog = \App\Models\InsuranceAgencyGroupHistory::join('group', 'group.group_id', '=', 'insurance_agencies_group_history.group_id')->whereNull('end_date')->where('insurance_agency_id', Auth::user()->getInsuranceAgencyID())->pluck('group_logo')->first();
                }
            }
            view()->share(['iname' => $iname, 'tname' => $tname, 'groupLog' => $groupLog, 'usname' => $uname]);
        });

    }
}
