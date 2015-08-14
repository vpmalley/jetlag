<?php

namespace Jetlag;

use Illuminate\Database\Eloquent\Model;

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
	protected $fillable = ['title', 'descriptionText', 'descriptionMediaId', 'isDraft'];
  
  /**
   * The rules for validating input
   */
  const rules = [
    'title' => 'min:3|max:200',
    'descriptionText' => 'min:3|max:500'
    ];
}
