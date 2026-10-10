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

        $credentialsPath = storage_path(config('fcm_config.credentials'));
        if (!is_file($credentialsPath)) {
            return null;
        }

        $serviceAccount = json_decode(file_get_contents($credentialsPath), true);
        if (!is_array($serviceAccount)) {
            throw new \RuntimeException('Invalid FCM service account JSON.');
        }

        $projectId = config('fcm_config.project_id');
        if (($serviceAccount['project_id'] ?? null) !== $projectId) {
            Log::error('FCM service account project does not match FCM_PROJECT_ID', [
                'service_account_project_id' => $serviceAccount['project_id'] ?? null,
                'fcm_project_id' => $projectId,
            ]);
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
        $projectId = config('fcm_config.project_id');
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
            $errorCode = collect($response->json('error.details', []))->pluck('errorCode')->filter()->first();

            if ($errorCode === 'SENDER_ID_MISMATCH') {
                // Token was issued by a different Firebase project than FCM_PROJECT_ID.
                // Every token fails the same way, so log once per 10 minutes instead of per send.
                if (Cache::add('fcm_sender_id_mismatch_logged', true, now()->addMinutes(10))) {
                    Log::error('FCM SENDER_ID_MISMATCH: device tokens belong to a different Firebase project than FCM_PROJECT_ID. Use the service account of the project in the mobile app google-services.json.', [
                        'fcm_project_id' => $projectId,
                        'token_prefix' => isset($message['token']) ? substr($message['token'], 0, 20) : null,
                    ]);
                }
            } else {
                Log::warning('FCM send failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'message' => $message,
                ]);
            }

            if (isset($message['token'])) {
                $status = $response->status();
                $bodyStr = $response->body();
                if ($status === 404 || $errorCode === 'SENDER_ID_MISMATCH' || str_contains($bodyStr, 'UNREGISTERED') || str_contains($bodyStr, 'NotRegistered')) {
                    // SENDER_ID_MISMATCH tokens come from the old Firebase project and can never succeed
                    User::where('fcm_token', $message['token'])->update(['fcm_token' => null]);
                    Log::info('Cleared invalid FCM token from database', ['token' => $message['token'], 'error' => $errorCode]);
                }
            }

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

    static public function sendToSetting(int $settingId, $title, $body, Array $data, ?int $exceptUserId = null){
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
        // queue workers have no auth user, so the actor is passed in explicitly
        if($exceptUserId){
            $users->where('id','!=',$exceptUserId);
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

    /**
     * Notify specific users about an activity (reaction, bid, like, comment):
     * saves it to their in-app notification list and pushes it to their device.
     * The actor and users who turned the setting off are skipped.
     */
    static public function notifyUsers(array $userIds, $title, $body, Array $data = [], ?int $settingId = null, ?int $actorId = null)
    {
        $ids = array_values(array_unique(array_filter($userIds)));
        if($actorId){
            $ids = array_values(array_diff($ids, [$actorId]));
        }
        if($settingId && count($ids) > 0){
            $notIds = UserSetting::where('setting_id', $settingId)
            ->where('value', '0')
            ->whereIn('user_id', $ids)
            ->pluck('user_id')->toArray();
            $ids = FCM::cleanIds($notIds, $ids);
        }
        $res = array();
        foreach (User::whereIn('id', $ids)->get(['id', 'fcm_token']) as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => $title,
                'body' => \Illuminate\Support\Str::limit($body, 200),
                'data' => $data,
            ]);
            if($user->fcm_token != null){
                $res[] = FCM::send([$user->fcm_token], $title, $body, $data);
            }
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
