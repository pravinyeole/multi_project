<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SendTelegramAutoResponse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        // Make a request to get updates
        $url = config('custom.custom.telegram_bot_API') . "/getUpdates"; // ?offset=-1 = for last message
        $response = $client->get($url);
        $updates = json_decode($response->getBody(), true);
        $emAry = [];
        // Check if there are new messages
        if (!empty($updates['result'])) {
            foreach ($updates['result'] as $update) {
                if (isset($update['message']['chat']['id']) && !empty($update['message']['chat']['id'])) {
                    $chatId = $update['message']['chat']['id'];
                    $checkExist = User::where('tel_chat_Id', $chatId)->first();
                    if ($checkExist == null) {
                        if (isset($update['message']['text']) && $update['message']['text'] == "/start") {
                            $response_message = 'Welcome to INR Bharat. Please copy the below Chat ID and update on Registration/Profile page.';
                            if (isset($update['message']['chat']['id'])) {
                                if (!in_array($chatId, $emAry)) {
                                    array_push($emAry, $chatId);
                                    $url = config('custom.custom.telegram_bot_API') . "/sendMessage"; // Send Message     
                                    $response = $client->post($url, [
                                        'json' => [
                                            'chat_id' => $chatId,
                                            'text' => $response_message,
                                        ],
                                    ]);
                                    $response = $client->post($url, [
                                        'json' => [
                                            'chat_id' => $chatId,
                                            'text' => $chatId,
                                        ],
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
