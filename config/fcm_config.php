<?php

return [
    'firebase_config' => [
        'apiKey' => env('FCM_API_KEY'),
        'authDomain' => env('FCM_AUTH_DOMAIN'),
        'projectId' => env('FCM_PROJECT_ID'),
        'storageBucket' => env('FCM_STORAGE_BUCKET'),
        'messagingSenderId' => env('FCM_MESSAGING_SENDER_ID', env('FCM_MESSAGIN_SENDER_ID')),
        'appId' => env('FCM_APP_ID')
    ],
    'fcm_api_url' => "https://fcm.googleapis.com/v1/projects/". env('FCM_PROJECT_ID') . "/messages:send",
    'fcm_api_server_key' => env('FCM_API_SERVER_KEY'),

    // HTTP v1 sending: project id and service account JSON path (relative to storage/)
    'project_id' => env('FCM_PROJECT_ID'),
    'credentials' => env('GOOGLE_CREDENTIALS'),
];
