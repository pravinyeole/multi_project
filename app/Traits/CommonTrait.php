<?php

namespace App\Traits;

use Illuminate\Http\Request;

use App\Models\InsuranceAgency;
use App\Models\User;
use GuzzleHttp\Client;
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

trait CommonTrait
{
    // convert millies to date
    public function getDate($date, $timezone)
    {
        $created_at = ($date / 1000);
        $created_at = new \DateTime(date('F j, Y, g:i:s a', $created_at), new \DateTimeZone("UTC"));
        $created_at->setTimezone(new \DateTimeZone($timezone));

        return $created_at->format('m-d-Y H:i:s');
    }

    // add log
    public function addLog($input, $type)
    {
        $isodate = Carbon::now();
        $isodate = $isodate->toIso8601String();

        $a1 = array("time" => $isodate, "loglevel" => $type);
        $logmsg = array_merge($a1, $input);

        if ($type == "INFO") {
            \Log::INFO(json_encode($logmsg));
        } else {
            \Log::DEBUG(json_encode($logmsg));
        }
        return;
    }

    //Check is admin or not
    public function isAdmin()
    {
        $data = User::where('id', Auth::id())->where('is_role_assign', 'Y')->first();
        if (!empty($data)) {
            return 1;
        } else {
            return 0;
        }
    }

    // Send Telegram Message
    public function sendTelegramMsg($chatId,$message){
        $client = new Client();
        // $url = config('custom.custom.telegram_bot_API') . "/getUpdates"; // ?offset=-1 = for last message
        $url = config('custom.custom.telegram_bot_API') . "/sendMessage"; // Send Message 
        $response = $client->post($url, [
            'json' => [
                'chat_id' => $chatId,
                'text' => $message,
            ],
        ]);
        if(isset($response->statusCode) && $response->statusCode == 200){
            return true;
        }else{
            return true;
        }
    }
}
