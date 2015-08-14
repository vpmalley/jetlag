<?php

namespace Jetlag\Eloquent;

use Illuminate\Database\Eloquent\Model;

class TextContent extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'textcontents';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['content', 'authorId'];
}
