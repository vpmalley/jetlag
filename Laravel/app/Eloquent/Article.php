<?php

namespace Jetlag\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Log;

class Article extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'articles';

	/**
	 * The attributes that are mass assignable.
	 * title is the title of the article
   * descriptionText is a text description of the article
   * descriptionMediaId is the id of the Link to a picture to illustrate the article
   * isDraft is a boolean, to indicate whether the article is a draft
   * 
   * @var array
   */
  public $fillable = ['title', 'descriptionText', 'descriptionMediaId', 'isDraft', 'authorId'];
  
  /**
   * The rules for validating input
   */
  static $rules = [
    'title' => 'min:3|max:200',
    'descriptionText' => 'min:3|max:500'
    ];
    
  public static function getById($id)
  {
    return Article::where('id', $id)->firstOrFail();
  }
  
  /*
  public static function getFromRequestBodyAndPersist(Request $request, $id)
  {
    $article = Article::firstOrNew(['id' => $id]);

    foreach ($article->fillable as $property)
    {
      Log::debug($request->json($property));
      if ($request->json($property))
      {
        $article[$property] = $request->json($property);
        Log::debug($property);
      }
    }
    
    if ($article->id)
    {
      $article->update();
    } else
    {
      $article->save();
    }
    
    return $article;
  }
  */
}
