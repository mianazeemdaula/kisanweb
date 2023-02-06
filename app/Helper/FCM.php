<?php
namespace App\Helper;
use Illuminate\Support\Facades\Http;

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
            'Authorization' =>  'key=' . $SERVER_API_KEY,
            'Content-Type' => 'application/json',
        ];

        $url = "https://fcm.googleapis.com/v1/projects/". env('FCM_PROJECT_ID') . "/messages:send";
        return Http::withHeaders($headers)->post("https://fcm.googleapis.com/fcm/send", $notification);
    }

    static public function topic(String $topic, $title, $body, Array $data)
    {
        $SERVER_API_KEY = env('FCM_API_SERVER_KEY');
        $notification = [
            "topic" => $topic,
            "notification" => [
                "title" => $title,
                "body" => $body,
            ],
            "data" => $data,
        ];
        $dataString = json_encode($notification);
        $headers = [
            'Authorization' =>  'key=' . $SERVER_API_KEY,
            'Content-Type' => 'application/json',
        ];

        $url = "https://fcm.googleapis.com/v1/projects/". env('FCM_PROJECT_ID') . "/messages:send";
        return Http::withHeaders($headers)->post("https://fcm.googleapis.com/fcm/send", $notification);
    }
}
