<?php
namespace App\Helper;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Wappi{
    private $url = "https://waapi.app/api/v1/instances/721";
    private function getHeaders() : array {
        return [
            'content-type' => 'application/json',
            'Authorization' => 'Bearer '.env('WAAPIAPP')
        ];
    }

    function sendMessage(string $number, string $message) : array {
        try {
            $data = [
                "chatId" => $number,
                "message" => $message,
                "mentions" => []
            ];
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'Authorization' => 'Bearer '.env('WAAPIAPP')
            ])->post("https://waapi.app/api/v1/instances/721/client/action/send-message", $data);
            Log::error($response);
            return $response->json() ?? []; 
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }       
    }

    public function getAllGroups() {
        
    }
}