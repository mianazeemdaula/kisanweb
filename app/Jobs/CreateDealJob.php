<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

use App\Helper\FCM;


use App\Models\User;
use App\Models\Deal;
use App\Models\Notification;
use App\Models\Address;

class CreateDealJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $dealId;
    public function __construct($id)
    {
        $this->dealId= $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $deal = Deal::find($this->dealId);
        $ids = Address::query()->whereDistanceSphere('location',$deal->location,'<=' ,30 * 1000)->pluck('user_id')->toArray();
        $users = User::where('id','!=',$deal->seller_id)
        ->whereIn('id', $ids)->whereNotNull('fcm_token')->get();
        $delayMint = 1; 
        foreach ($users as $user) {
            $title = "Hurry Up!";
            $notif =  Notification::create([
                'user_id' => $user->id,
                'title' => $title,
                'body' => "New Deal for ".$deal->type->crop->name,
                'data' => json_encode(['id' => $deal->id, 'type' => 'deal']),
            ]);
            FCM::sendNotification($notif);
            if($user->email){
                Mail::to($user->email)->later(now()->addMinutes($delayMint), new \App\Mail\NewDealMail($deal));
                $delayMint++;
            }
        }
    }
}
