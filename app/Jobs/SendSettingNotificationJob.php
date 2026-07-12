<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helper\FCM;

class SendSettingNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $settingId;
    public string $title;
    public string $body;
    public array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(int $settingId, string $title, string $body, array $data)
    {
        $this->settingId = $settingId;
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        FCM::sendToSetting($this->settingId, $this->title, $this->body, $this->data);
    }
}
