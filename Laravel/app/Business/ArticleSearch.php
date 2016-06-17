<?php

namespace Jetlag\Business;

use TNTSearch;
use Jetlag\Eloquent\Article;

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
    TNTSearch::selectIndex("articles.index");
    $results = TNTSearch::searchBoolean($query, 50);
    $articles = Article::whereIn('id', $results['ids'])->paginate(5);
    return $articles;
  }
}
