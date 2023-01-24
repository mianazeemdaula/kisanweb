<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Deal;
use App\Helper\FCM;

class BidNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $id;
    public $bidderId;
    public function __construct($id,$bidder)
    {
        $this->id = $id;
        $this->bidderId = $bidder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $deal = Deal::find($this->id);
        $fcmTokens = [];
        foreach ($deal->bids as $bid) {
            if($bid->buyer->fcm_token!=null && $bid->buyer->id != $this->bidderId){
                $fcmTokens[] = $bid->buyer->fcm_token;
            }
        }
        $data = [
            'type' => 'bid',
            'deal_id' => $deal->id,
        ];
        FCM::send($fcmTokens, 'Deal update', 'Another bid', $data);
    }
}
