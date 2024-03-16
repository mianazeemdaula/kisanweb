<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Deal;

class ExpireDealAfterTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-deal-after-time';

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
        $deals = Deal::where('status', 'open')->take(5)->get();
        foreach ($deals as $deal) {
            if ($deal->created_at->diffInDays(now()) > 60) {
                $deal->status = 'expired';
                $deal->save();
            }
        }
    }
}
