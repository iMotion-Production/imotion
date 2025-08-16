<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = Sitemap::create();

        $now = Carbon::now();
        $sitemap->add(Url::create('/')->setPriority(1)->setLastModificationDate($now));

        $links = ['digital-marketing', 'printing-design', 'web-development', 'mobile-application', 'graphic-design', 'about', 'contact'];
        foreach ($links as $link) {
            $sitemap->add(Url::create("/$link")->setPriority(0.8)->setLastModificationDate($now));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
