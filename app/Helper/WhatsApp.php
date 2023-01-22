<?php
namespace App\Helper;
use Illuminate\Support\Facades\Http;

use App\Models\Notification;

class WhatsApp {

    static public function sendNotification(Notification $notification)
    {

        if($notification->user->whatsapp == null){
            return;
        }
        $to =  $notification->user->whatsapp;
        return WhatsApp::send($to,'welcome','en',[],[]);
    }

    static public function sendText($to, $text)
    {
        $SERVER_API_KEY = env('WHATSAPP_KEY');
        $WHATSAPP_PHONE_ID = env('WHATSAPP_PHONE_ID');
        $notification = [
            "messaging_product" => "whatsapp",
            "to" => $to, 
            "recipient_type"=> "individual",
            "type" => "text", 
            "text" => [
                "preview_url" => false,
                "body" => $text,
            ],
        ];
        $headers = [
            'Authorization' =>  'Bearer ' . $SERVER_API_KEY,
            'Content-Type' => 'application/json',
        ];

        $url = "https://graph.facebook.com/v15.0/$WHATSAPP_PHONE_ID/messages";
        return Http::withHeaders($headers)->post($url, $notification);
    }

    static public function sendTemplate($to, $template, $lang = 'en', Array $headers, Array $body)
    {
        $SERVER_API_KEY = env('WHATSAPP_KEY');
        $WHATSAPP_PHONE_ID = env('WHATSAPP_PHONE_ID');
        $notification = [
            "messaging_product" => "whatsapp",
            "to" => $to, 
            "type" => "template", 
            "template" => [
                "name" => $template,
                "language" => ['code'=>$lang],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => $headers
                    ],
                    [
                        "type" => "body",
                        "parameters" => $body
                    ]
                ]
            ],
        ];
        $headers = [
            'Authorization' =>  'Bearer ' . $SERVER_API_KEY,
            'Content-Type' => 'application/json',
        ];

        $url = "https://graph.facebook.com/v15.0/$WHATSAPP_PHONE_ID/messages";
        return Http::withHeaders($headers)->post($url, $notification);
    }
}
