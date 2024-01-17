<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WelcomeMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
    }

    private function readMetaData()  {
        $fileName = 'web_meta_data.json';
        $file_path = public_path($fileName);
        $data = [];
        if (file_exists($file_path)) {
            $json_data = file_get_contents($file_path);
            $data = json_decode($json_data, true);
        } else {
            $data['welcome_user_id'] = 1;
            $json_data = json_encode($groups, JSON_PRETTY_PRINT);
            $file_path = public_path($fileName);
            file_put_contents($file_path, $json_data);
            $data = collect($data);
        }
        return $data;
    }
}
