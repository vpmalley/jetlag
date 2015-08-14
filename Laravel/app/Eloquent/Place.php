<?php

namespace Jetlag;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'places';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['latitude', 'longitude', 'altitude', 'localisation'];
  
  /**
   * The rules for validating input
   */
  const rules = ['localisation' => 'min:3|max:200'];
}
