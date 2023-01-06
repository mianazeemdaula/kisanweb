<?php
namespace App\Helper;

use App\Models\Notification;

class FCM {

    static public function sendNotification(Notification $notification)
    {

        if($notification->user->fcm_token == null){
            return;
        }
        $token =  $notification->user->fcm_token;
        return FCM::send([$token],$notification->title, $notification->body,(array) json_decode($notification->data));
    }

    static public function send(Array $tokens, $title, $body, Array $data)
    {
        $SERVER_API_KEY = env('FCM_API_SERVER_KEY');
        $notification = [
            "registration_ids" => $tokens,
            "notification" => [
                "title" => $title,
                "body" => $body,
            ],
            "data" => $data,
        ];
        $dataString = json_encode($notification);
        $headers = [
            'Authorization' => 'Bearer ' . $SERVER_API_KEY,
            'Content-Type' =>  'application/json',
        ];

        $url = "https://fcm.googleapis.com/v1/projects/". env('FCM_PROJECT_ID') . "/messages:send";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        return response()->json($response);
    }
}
