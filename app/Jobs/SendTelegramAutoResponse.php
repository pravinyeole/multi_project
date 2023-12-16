<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $message = 'Hello, this is your individual Telegram message!';
        // $chatId = '1539954773'; // Replace with the actual chat_id of the user
        $client = new Client();
        // Make a request to get updates
        $url = config('custom.custom.telegram_bot_API')."/getUpdates"; // ?offset=-1 = for last message
        $response = $client->get($url);
        $updates = json_decode($response->getBody(), true);
        
        // Check if there are new messages
        if (!empty($updates['result'])) {
            foreach ($updates['result'] as $update) {
                if (isset($update['message']['chat']['id'])) {
                    $chatId = $update['message']['chat']['id'];
                    $response_message = 'Hello, '.$chatId.'this is your Chat ID .Update this on your pfofile Page';
                    if($chatId == '1539954773' && isset($update['message']['text']) && $update['message']['text'] == "/start"){
                            // Send Message
                            $client = new Client();
                            $url = config('custom.custom.telegram_bot_API')."/sendMessage"; // Send Message
                            $message = (isset($message) && !empty($message)) ? $message :'Welcome INRB';        
                            $response = $client->post($url, [
                                'json' => [
                                    'chat_id' => $chatId,
                                    'text' => $response_message,
                                ],
                            ]);
                            // Handle the response as needed
                            // $statusCode = $response->getStatusCode();
                            // $responseData = json_decode($response->getBody(), true);
                            // return response()->json([
                            //     'status' => $statusCode,
                            //     'response' => $responseData,
                            // ]);
                    }
                }
            }
        }
    }
}
