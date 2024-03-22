<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use WaAPI\WaAPI\WaAPI;
use Illuminate\Support\Facades\Log;
use App\Helper\WhatsApp;

class ProcessWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $message;

    /**
     * Create a new job instance.
     */
    public function __construct(array $message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $waapi = new WaAPI();
        $res = null;
        if(!isset($this->message['media'])) {
            $res =  WhatsApp::sendText($this->message['to'], $this->message['text']);
        } else if(isset($this->message['media'])) {
            $res =  $waapi->sendMediaFromUrl($this->message['to'], $this->message['media'] , $this->message['text'], "image");
        }
        return ;
    }
}
