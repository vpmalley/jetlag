<?php

namespace Jetlag\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'galleries';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['description', 'authorId'];
  
  /**
   * The rules for validating input
   */
  const rules = [
        'description' => 'max:500'
    ];
}
