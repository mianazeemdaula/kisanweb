<?php
namespace App\Helper;
use Illuminate\Support\Facades\Http;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserSetting;

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
            'Authorization' =>  'Bearer ' . $SERVER_API_KEY,
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
            'Authorization' =>  'Bearer ' . $SERVER_API_KEY,
            'Content-Type' => 'application/json',
        ];

        $url = "https://fcm.googleapis.com/v1/projects/". env('FCM_PROJECT_ID') . "/messages:send";
        return Http::withHeaders($headers)->post("https://fcm.googleapis.com/fcm/send", $notification);
    }

    static public function sendToSetting(int $settingId, $title, $body, Array $data){
        $users = User::query();
        $boolIds = [1,3,4,5,6,7,8];
        if((bool) in_array($settingId, $boolIds)){
            $notIds =  UserSetting::where('setting_id',$settingId)
            ->where('value','0')
            ->pluck('user_id')->toArray();
            // comment or like on post
            if($settingId == 3) {
                $ids = \App\Models\Bid::where('deal_id', $data['deal_id'])->pluck('buyer_id')->toArray();
                array_push($ids,\App\Models\Deal::find($data['deal_id'])->seller_id);
                $users->whereIn('id', FCM::cleanIds($notIds, $ids));
            }else if($settingId == 5) {
                $ids = \App\Models\FeedComment::where('feed_id', $data['feed_id'])->pluck('user_id')->toArray();
                array_push($ids,\App\Models\Feed::find($data['feed_id'])->user_id);
                $users->whereIn('id', FCM::cleanIds($notIds, $ids));
            }else if($settingId == 6) {
                $ids = \App\Models\User::where('city_id', $data['city_id'])->pluck('id')->toArray();
                $users->whereIn('id', FCM::cleanIds($notIds, $ids));
            }else if($settingId == 8) {
                $ids = \App\Models\CommissionShop::find($data['shop_id'])->favoritUsers()->pluck('id')->toArray();
                $users->whereIn('id', FCM::cleanIds($notIds, $ids));
            }else{
                $users->whereNotIn('id', $notIds);
            }
        }
        if(auth()->id()){
            $users->where('id','!=',auth()->id());
        }
        $tokens = $users->whereNotNull('fcm_token')->pluck('fcm_token');
        $res = array();
        foreach ($tokens->chunk(1000) as $value) {
            $keys = $value->toArray();
            $res[] =  \App\Helper\FCM::send($keys, $title,$body,$data);
            // $res[] =  count($keys);
        }
        return $res;
    }

    static public function cleanIds($notIds, $ids) : array {
        foreach ($notIds as $id) {
            $pos = array_search($id, $ids);
            if ($pos !== false) {
                unset($ids[$pos]);
            }
        }
        return array_values($ids);
    }
}
