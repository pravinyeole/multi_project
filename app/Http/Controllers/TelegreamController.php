<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class TelegreamController extends Controller
{
    public function getUpdates(Request $request)
    {
        $message = 'Hello, this is your individual Telegram message!';
        // $chatId = '15399547731539954773'; // Replace with the actual chat_id of the user
        $client = new Client();
        // Make a request to get updates
        $url = config('custom.custom.telegram_bot_API')."/getUpdates";
        $response = $client->get($url);
        $updates = json_decode($response->getBody(), true);
        print_r($updates);
        // Check if there are new messages
        if (!empty($updates['result'])) {
            foreach ($updates['result'] as $update) {
                if (isset($update['message'])) {
                    $message = $update['message']['text'];
                    $chatId = $update['message']['chat']['id'];
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
