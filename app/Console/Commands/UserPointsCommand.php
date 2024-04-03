<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class UserPointsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-points';

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
        // write a query to get the users who enter the data with entries count for the previous day from croprates
        $users = \App\Models\CropRate::whereDate('rate_date', now()->subDay())
            ->select('user_id', DB::raw('count(*) as entries'))
            ->groupBy('user_id')
            ->get();
        // insert the user points in the user_points table
        foreach ($users as $user) {
            $points = new \App\Models\UserPoint();
            $points->user_id = $user->user_id;
            $points->points = $user->entries > 30 ? 30 : $user->entries;
            $points->description = 'Earned points for entering data in rates';
            $points->save();
        }
    }
}
