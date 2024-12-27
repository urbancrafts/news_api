<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AppServices\News\ArticleFetcherService;

class PollArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:poll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll articles from external APIs and store them in the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ArticleFetcherService $service)
    {
        $this->info('Fetching articles...');
        $service->fetchAndStoreArticles();
        $this->info('Articles fetched and stored successfully.');
    }
}
