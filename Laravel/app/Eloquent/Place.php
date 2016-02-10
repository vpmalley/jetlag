<?php

namespace Jetlag\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'places';

  protected $fillable = ['latitude', 'longitude', 'altitude', 'description'];

  protected $visible = ['latitude', 'longitude', 'altitude', 'description'];
  
  static $default_fillable_values = [
    'latitude' => -200,
    'longitude' => -200,
    'altitude' => 0,
    'description' => '',
  ];

  /**
  * The rules for validating input
  */
  static $rules = [
    'latitude' => 'numeric|min:-90|max:90',
    'longitude' => 'numeric|min:-180|max:180',
    'altitude' => 'numeric|min:-1000|max:100000',
    'description' => 'string|max:200'];
  }
