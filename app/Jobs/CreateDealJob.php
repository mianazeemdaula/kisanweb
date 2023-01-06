<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Appy\FcmHttpV1\FcmNotification;


use App\Models\User;
use App\Models\Deal;
use App\Models\Notification;

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
        $deal = Deal::with(['bids' => function($q){
            $q->with(['buyer']);
        }, 'seller', 'packing', 'weight', 'media', 'type.crop'])
        ->find($this->dealId);
        $users = User::where('id','!=',$deal->seller_id)->whereNotNull('fcm_token')->get();
        foreach ($users as $user) {
            $notif =  Notification::create([
                'user_id' => $user->id,
                'title' => "Hurry Up!",
                'body' => "New Deal for ".$deal->type->crop->name,
                'data' => json_encode(['id' => $deal->id, 'type' => 'deal', 'data'=> $deal]),
            ]);
            $fcm = new FcmNotification();
            $fcm->setTitle($notif->title)->setBody($notif->body)->setToken($user->fcm_token)->send();
        }
    }
}
