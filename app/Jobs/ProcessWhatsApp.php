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
        
        Log::debug($this->message);
        // if(!isset($this->message['media'])) {
            $res =  $waapi->sendMessage($this->message['to'], $this->message['text']);
        // } else if(isset($this->message['media'])) {
        //     $res =  $waapi->sendMediaFromUrl($this->message['to'], $this->message['media'] , $this->message['text'], "image");
        // }
        // Log::debug(['to' => $this->message['to'], 'at' => now()]);
        return;
    }
}
