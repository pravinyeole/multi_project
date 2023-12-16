<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class TelegreamController extends Controller
{
    public function setWebHook(Request $request)
    {
        $botToken = config('custom.custom.telegram_bot_token');
        $webhookUrl = 'https://testinrb.in/telegram/get-updates'; // Replace with your actual webhook URL
        $webhookUrl = 'https://testinrb.in/telegram//start-command'; // Replace with your actual webhook URL
        $apiUrl = config('custom.custom.telegram_bot_API');
        $response = file_get_contents($apiUrl . '/setWebhook?url=' . urlencode($webhookUrl));
        // Handle the response as needed
        echo $response;
        dd();
    }
    public function deleteWebHook(Request $request)
    {
        $botToken = config('custom.custom.telegram_bot_token');
        $apiUrl = config('custom.custom.telegram_bot_API').'/setWebhook?url=';
        // To disable the webhook, set the URL to an empty string
        $webhookUrl = '';
        $apiUrl .= urlencode($webhookUrl);
        $response = file_get_contents($apiUrl);
        // Handle the response as needed
        echo $response;
        dd();
    }
    public function getUpdates(Request $request)
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
                            $this->sendMessage($chatId,$response_message);
                    }
                }
            }
        }
    }
    public function sendMessage($chatId, $message = '')
    {
        // Send Message
        $client = new Client();
        $url = config('custom.custom.telegram_bot_API')."/sendMessage"; // Send Message
        $message = (isset($message) && !empty($message)) ? $message :'Welcome INRB';        
        $response = $client->post($url, [
            'json' => [
                'chat_id' => $chatId,
                'text' => $message,
            ],
        ]);

        // Handle the response as needed
        $statusCode = $response->getStatusCode();
        $responseData = json_decode($response->getBody(), true);

        return response()->json([
            'status' => $statusCode,
            'response' => $responseData,
        ]);
    }
    public function handleStartCommand(Request $request)
    {
        $update = json_decode($request->getContent(), true);

        // Check if the update contains the /start command
        if (isset($update['message']['text']) && $update['message']['text'] === '/start') {
            $chatId = $update['message']['chat']['id'];

            // Send a welcome message or any auto-response
            $this->sendAutoResponse($chatId);
        }

        return response()->json(['status' => 'success']);
    }

    private function sendAutoResponse($chatId)
    {
        $token = config('custom.custom.telegram_bot_token');
        $message = 'Thank you for starting the bot! This is an automatic response.';

        $client = new Client();
        $url = config('custom.custom.telegram_bot_API')."/sendMessage";

        $response = $client->post($url, [
            'json' => [
                'chat_id' => $chatId,
                'text' => $message,
            ],
        ]);

        // Handle the response as needed
        $statusCode = $response->getStatusCode();
        $responseData = json_decode($response->getBody(), true);

        // Log or handle the response as needed
        // ...

        return response()->json([
            'status' => $statusCode,
            'response' => $responseData,
        ]);
    }
}
