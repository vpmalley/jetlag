<?php

namespace Jetlag\Business;

use TeamTNT\TNTSearch\TNTSearch;
use Jetlag\Eloquent\Article as StoredArticle;

class ArticleSearch
{

  /**
  * Search for the
  *
  * @param  string $query a validated query
  * @return [Article] the 50 ffirst articles matching the query
  */
  public function search($query)
  {
    $tnt = new TNTSearch;
    $config = [
      'driver'    => 'mysql',
      'host'      => getenv('DB_HOST'),
      'database'  => getenv('DB_DATABASE'),
      'username'  => getenv('DB_USERNAME'),
      'password'  => getenv('DB_PASSWORD'),
      'storage'   => env('SEARCH_INDEX_LOC', storage_path())
    ];
    $tnt->loadConfig($config);
    $tnt->selectIndex("articles.index");
    $results = $tnt->searchBoolean($query, 50);
    $articles = StoredArticle::whereIn('id', $results['ids'])->paginate(5);
    return $articles;
  }
}
