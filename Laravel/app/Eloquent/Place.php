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

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['latitude', 'longitude', 'altitude', 'description'];

  protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'id'];

  static $default_fillable_values = [
    'latitude' => -200,
    'longitude' => -200,
    'altitude' => 0,
    'description' => '',
  ];

  /**
   * The rules for validating input
   */
  static $rules = ['description' => 'max:200'];
}
