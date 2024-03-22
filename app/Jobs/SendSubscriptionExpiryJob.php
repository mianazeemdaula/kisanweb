<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helper\WhatsApp;

class SendSubscriptionExpiryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public string $contact;
    public string $message;
    public function __construct( string $contact, string $message)
    {
        $this->contact = $contact;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        WhatsApp::sendText($this->contact, $this->message);
    }
}
