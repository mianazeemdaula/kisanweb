<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use App\Helper\FCM;
use App\Models\Message;

class ChatMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $msg = Message::find($this->id);
        $senderId = $msg->sender_id;
        $fcmToken = null;
        $user  = "";
        if($msg->chat->buyer_id == $senderId){
            $fcmToken = $msg->chat->deal->seller->fcm_token;
            $user = $msg->chat->deal->seller;
        }else{
            $user = $msg->chat->buyer;
            $fcmToken = $msg->chat->buyer->fcm_token;
        }
        $data =  [
            'type' => 'msg',
            'chat_id' => $msg->chat_id,
        ];
        FCM::send([$fcmToken], $user->name, $msg->message, $data);
    }
}
