<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\SubscriptionPackage;
use Carbon\Carbon;

use App\Jobs\ProcessExpireSubscriptionJob;


class ExpireSubscriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $packages = SubscriptionPackage::all();
        $nextMint = 0;
        foreach($packages as $package){
            $users = $package->users()->get();
            foreach($users as $user){
                $isExpired = Carbon::parse($user->pivot->end_date)->isPast();
                if($isExpired){
                    $user->subscriptions()->updateExistingPivot($package->id, ['active' => 0]);
                    ProcessExpireSubscriptionJob::dispatch($user->pivot->contact)->delay(now()->addMinutes($nextMint));
                    $nextMint += 1;
                }
            }
        }
    }
}
