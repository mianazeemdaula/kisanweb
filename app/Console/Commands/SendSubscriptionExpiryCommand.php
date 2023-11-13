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
                اُمید ہے آپ خیریت سے ہوں گے
آپ کا کسان اسٹاک کا واٹس ایپ کے ریٹس اور رپوٹس کا پیکج *{$inDays}* دنوں میں ختم ہو رہا ہے 
اور ہم چاہتے آپ اپنے گھر بیٹھے پاکستان مارکیٹ میں بغیر کسی براکر اور کمیشن شاپ پر کال کیے صرف ایک میسج میں روزانہ کی بنیاد پر فصلوں کے ریٹس اور رپورٹس جانتے رہیں  
براہ کرم اپنا پیکج دوبارہ سبسکراءیب کریں اور فصلوں کے بارے میں رہنمائی، روزآنہ کے ریٹس، اور رحجان کے بارے کے آگاہ رہیں
شکریہ!
 *ٹیم کسان اسٹاک*";
                SendSubscriptionExpiryJob::dispatch($user->pivot->contact, $message)->delay(now()->addMinutes($nextMint));
                $nextMint += 1;
            }
        }
    }
}
