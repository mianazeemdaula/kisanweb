<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Subscription;
use App\Jobs\ProcessWhatsApp;


class ProcessSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public int $subId;
    public string $message;
    public string $image;
    public function __construct(int $subId, string $message, string $image = null)
    {
        $this->subId = $subId;
        $this->message = $message;
        $this->image = $image;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $sub = Subscription::find($this->subId);
        $nextMinute = 1;
        if($sub){
            $userSubscriptions = $sub->users;
            foreach($userSubscriptions as $userSubscription){
                $contact = $userSubscription->pivot->contact;
                if($sub->type == 'sms'){
                    $this->sendSms($contact, $sub->message);
                }else if($sub->type == 'email'){
                    $this->sendEmail($contact, $sub->message);
                }else if($sub->type == 'whatsapp'){
                    $job = [
                        'to' => $contact,
                        'text' => $this->message,
                    ];
                    if($this->image){
                        $job['media'] = $this->image;
                    }
                    ProcessWhatsApp::dispatch($job)
                    ->delay(now()->addMinutes($nextMinute));
                    $nextMinute = $nextMinute + 1;
                }
            }
        }
    }
}
