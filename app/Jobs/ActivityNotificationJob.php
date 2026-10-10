<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helper\FCM;

class ActivityNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $userIds;
    public string $title;
    public string $body;
    public array $data;
    public ?int $settingId;
    public ?int $actorId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $userIds, string $title, string $body, array $data, ?int $settingId = null, ?int $actorId = null)
    {
        $this->userIds = $userIds;
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
        $this->settingId = $settingId;
        $this->actorId = $actorId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        FCM::notifyUsers($this->userIds, $this->title, $this->body, $this->data, $this->settingId, $this->actorId);
    }
}
