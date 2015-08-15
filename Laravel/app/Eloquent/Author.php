<?php

namespace Jetlag\Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * An author is one or multiple users creating or managing elements
 */
class Author extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'authors';

	/**
	 * The attributes that are mass assignable.
   * authorId is the id for author(s)
   * userId is the id for a user being one of the author(s)
   * name is the optional name of the user in this author group
	 *
	 * @var array
	 */
	protected $fillable = ['authorId', 'userId', 'name'];
  
  /**
   * The rules for validating input
   */
  const rules = [
        'name' => 'min:3|max:100',
    ];
}
