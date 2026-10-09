<?php

namespace App\Console\Commands;

use App\Helper\FCM;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class FcmCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check FCM configuration (project id, service account, access token) without sending a push';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projectId = config('fcm_config.project_id');
        $credentialsPath = storage_path(config('fcm_config.credentials'));

        $this->line('FCM_PROJECT_ID: ' . ($projectId ?: '(empty)'));
        $this->line('Credentials: ' . $credentialsPath);

        if (!is_file($credentialsPath)) {
            $this->error('Service account file not found.');
            return self::FAILURE;
        }

        $serviceAccount = json_decode(file_get_contents($credentialsPath), true) ?: [];
        $this->line('Service account project_id: ' . ($serviceAccount['project_id'] ?? '(missing)'));
        $this->line('Service account client_email: ' . ($serviceAccount['client_email'] ?? '(missing)'));

        if (($serviceAccount['project_id'] ?? null) !== $projectId) {
            $this->error('Service account project_id does not match FCM_PROJECT_ID.');
            return self::FAILURE;
        }

        Cache::forget('fcm_access_token');
        if (!FCM::getAccessToken()) {
            $this->error('Could not fetch an access token, see laravel.log.');
            return self::FAILURE;
        }

        $this->info('Access token OK. Device tokens must come from an app built with this project\'s google-services.json / GoogleService-Info.plist.');
        return self::SUCCESS;
    }
}
