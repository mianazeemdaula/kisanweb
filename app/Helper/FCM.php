<?php
namespace App\Helper;
use Throwable;
use Google\Auth\OAuth2;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserSetting;

class FCM {

    static private function fakeSuccessResponse(array $message, string $reason): array
    {
        Log::warning('FCM send bypassed', [
            'reason' => $reason,
            'message' => $message,
        ]);

        return [
            'success' => true,
            'status' => 200,
            'simulated' => true,
            'data' => [
                'name' => 'projects/fake/messages/' . uniqid(),
                'reason' => $reason,
            ],
        ];
    }

    static public function getAccessToken()
    {
        $cachedToken = Cache::get('fcm_access_token');
        if ($cachedToken) {
            return $cachedToken;
        }

        $credentialsPath = storage_path(env('GOOGLE_CREDENTIALS'));
        if (!is_file($credentialsPath)) {
            return null;
        }

        $serviceAccount = json_decode(file_get_contents($credentialsPath), true);
        if (!is_array($serviceAccount)) {
            throw new \RuntimeException('Invalid FCM service account JSON.');
        }

        $oauth = new OAuth2([
            'audience' => 'https://oauth2.googleapis.com/token',
            'issuer' => $serviceAccount['client_email'],
            'signingAlgorithm' => 'RS256',
            'signingKey' => $serviceAccount['private_key'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'tokenCredentialUri' => 'https://oauth2.googleapis.com/token',
        ]);

        try {
            $token = $oauth->fetchAuthToken();
        } catch (Throwable $exception) {
            Log::warning('FCM access token fetch failed', [
                'message' => $exception->getMessage(),
            ]);

            return null;
        }

        if (!isset($token['access_token'])) {
            Log::warning('FCM access token missing from Google OAuth response.', [
                'response' => $token,
            ]);

            return null;
        }

        $expiresIn = max(60, ((int) ($token['expires_in'] ?? 3600)) - 60);
        Cache::put('fcm_access_token', $token['access_token'], now()->addSeconds($expiresIn));

        return $token['access_token'];
    }

    static private function stringifyData(array $data): array
    {
        return collect($data)->map(function ($value) {
            if (is_bool($value)) {
                return $value ? 'true' : 'false';
            }

            if (is_null($value)) {
                return '';
            }

            if (is_scalar($value)) {
                return (string) $value;
            }

            return json_encode($value);
        })->toArray();
    }

    static private function sendMessage(array $message): array
    {
        $projectId = env('FCM_PROJECT_ID');
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
        $accessToken = FCM::getAccessToken();

        if (!$accessToken) {
            return FCM::fakeSuccessResponse($message, 'GOOGLE_CREDENTIALS file not found.');
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'message' => $message,
            ]);

        if ($response->failed()) {
            Log::warning('FCM send failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'message' => $message,
            ]);

            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->json() ?: $response->body(),
            ];
        }

        return [
            'success' => true,
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }

    static public function sendNotification(Notification $notification)
    {

        if($notification->user->fcm_token == null){
            return;
        }
        $token =  $notification->user->fcm_token;
        $data = $notification->data;
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        return FCM::send([$token], $notification->title, $notification->body, (array) ($data ?? []));
    }

    static public function send(array $tokens, $title, $body, Array $data = [])
    {
        $responses = [];

        foreach (array_unique(array_filter($tokens)) as $fcmToken) {
            $responses[] = FCM::sendMessage([
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => FCM::stringifyData($data),
            ]);
        }

        return $responses;
    }

    static public function topic(String $topic, $title, $body, Array $data)
    {
        return FCM::sendMessage([
            'topic' => $topic,
            "notification" => [
                "title" => $title,
                "body" => $body,
            ],
            "data" => FCM::stringifyData($data),
        ]);
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
