<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap for the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SitemapGenerator::create('https://kisanstock.com')->writeToFile("sitemap.xml");
    }
}
