<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;


use App\Helper\FCM;


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
        $deal = Deal::find($this->dealId);
        $users = User::where('id','!=',$deal->seller_id)->whereNotNull('fcm_token')->get();
        foreach ($users as $user) {
            $phone = Str::replaceFirst('03','923',$user->mobile);
            $title = "Hurry Up!";
            // $body = $deal->type->crop->name." ($deal->qty * )".")";
            $notif =  Notification::create([
                'user_id' => $user->id,
                'title' => $title,
                'body' => "New Deal for ".$deal->type->crop->name,
                'data' => json_encode(['id' => $deal->id, 'type' => 'deal']),
            ]);
            FCM::sendNotification($notif);
            // $fcm = new FcmNotification();
            // $fcm->setTitle($notif->title)->setBody($notif->body)->setToken($user->fcm_token)->send();
        }
    }
}
