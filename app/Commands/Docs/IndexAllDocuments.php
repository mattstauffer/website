<?php  namespace App\Commands\Docs;

use App\Commands\Command;
use App\DocSearchService;
use Illuminate\Contracts\Bus\SelfHandling;

class IndexAllDocuments extends Command implements SelfHandling {
	
    /**
     * Create a new command instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle(DocSearchService $searcher)
    {
        $searcher->indexAllDocuments();
    }
}