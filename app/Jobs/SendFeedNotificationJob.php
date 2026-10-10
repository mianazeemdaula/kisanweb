<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFeedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public string $title;
    public string $body;
    public array $data;
    public ?int $userId;

    // the broadcast is slow; a retry would push the same post to everyone again
    public $tries = 1;
    public $timeout = 600;

    public function __construct(string $title, string $body, array $data, ?int $userId = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        \App\Helper\FCM::sendToSetting(4, $this->title, $this->body, $this->data, $this->userId);
    }
}
