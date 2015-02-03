<?php  namespace LaravelWebSite\Commands\Docs;

use LaravelWebSite\Commands\Command;
use LaravelWebSite\DocSearchService;

class IndexDocument extends Command/* implements SelfHandling */
{
    /**
     * @var DocSearchService
     */
    private $searcher;

    /**
     * Create a new command instance.
     */
    public function __construct(DocSearchService $searcher)
    {
        $this->searcher = $searcher;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $this->searcher->indexDocument('eloquent');
    }
}
