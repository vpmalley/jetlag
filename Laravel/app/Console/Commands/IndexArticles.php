<?php

namespace Jetlag\Console\Commands;

use Illuminate\Console\Command;
use TNTSearch;

class IndexArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index the articles';

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
     * @return mixed
     */
    public function handle()
    {
        $indexer = TNTSearch::createIndex('articles.index');
        $indexer->query('SELECT id, title, description_text FROM articles;');
        $indexer->run();
    }
}
