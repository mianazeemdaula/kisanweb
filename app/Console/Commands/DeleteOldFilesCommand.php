<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\Finder;
use Carbon\Carbon;

class DeleteOldFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old files from temp folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directory = public_path('temp'); // Replace with your directory path
    
        $files = Finder::create()
            ->in($directory)
            ->files()
            ->date('< 7 days ago'); // This filters files modified more than 5 days ago
        
        foreach ($files as $file) {
            File::delete($file);
        } 
    }
}
