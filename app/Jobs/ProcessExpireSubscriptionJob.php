<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use WaAPI\WaAPI\WaAPI;

class ProcessExpireSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public string $contact;
    public function __construct( string $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $waapi = new WaAPI();
        $to = $this->contact;
        $res = $waapi->getInstanceStatus();
        if(isset($res->attributes['instanceStatus']) && $res->attributes['instanceStatus'] === "ready"){
            $waapi->removeGroupParticipant("120363168242340048@g.us",$to."@c.us");
        }else{
            throw new \Exception("Instance is not ready");
        }
    }
}
