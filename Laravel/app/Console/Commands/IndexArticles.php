<?php

namespace Jetlag\Console\Commands;

use Illuminate\Console\Command;
use TeamTNT\TNTSearch\TNTSearch;

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
        $tnt = new TNTSearch;
        $config = [
          'driver'    => 'mysql',
          'host'      => getenv('DB_HOST'),
          'database'  => getenv('DB_DATABASE'),
          'username'  => getenv('DB_USERNAME'),
          'password'  => getenv('DB_PASSWORD'),
          'storage'   => getenv('SEARCH_INDEX_LOC') ?? storage_path()
        ];
        $tnt->loadConfig($config);
        $indexer = $tnt->createIndex('articles.index');
        $indexer->query('SELECT id, title, description_text FROM articles;');
        $indexer->run();
    }
}
