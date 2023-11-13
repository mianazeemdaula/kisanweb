<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriptionPackage;
use Carbon\Carbon;
use App\Jobs\SendSubscriptionExpiryJob;

class SendSubscriptionExpiryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-subscription-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send subscription expiry notification to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $packages = SubscriptionPackage::all();
        $nextMint = 0;
        foreach($packages as $package){
            $users = $package->users()->wherePivot('end_date','<=', now()->addDays(3))
            ->get();
            foreach($users as $user){
                // calculate the days from now to end date
                $inDays = now()->diffInDays(Carbon::parse($user->pivot->end_date));
                $userName = $user->name;
                $message = "معزز *{$userName}* صاحب! 
                منڈیوں میں فصلوں کا ڈیلی ریٹس اور رپورٹس کا سبسکرپشن کا پیکج *{$inDays}* دنوں میں ختم ہو رہا ہے 
اور ہم چاہتے آپ اپنے گھر بیٹھے پاکستانی مارکیٹ میں بغیر کسی براکر اور کمیشن شاپ پر کال کیے صرف ایک میسج میں اپنی فصلوں کے ریٹس اور رپورٹس سے باخبر رہیں واٹس ایپ میں روزانہ کی بنیاد پر۔ 
براہ کرم اپنا پیکج سبسکرا ئب کریں اور ہمارے ساتھ اپڈیٹ رہیں۔
شکریہ!
 *ٹیم کسان اسٹاک*";
                SendSubscriptionExpiryJob::dispatch($user->pivot->contact, $message)->delay(now()->addMinutes($nextMint));
                $nextMint += 1;
            }
        }
    }
}
