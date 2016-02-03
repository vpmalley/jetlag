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
  protected $fillable = ['description', 'author_id'];

  /**
   * The rules for validating input
   */
  static $rules = [
        'description' => 'max:500'
    ];
}
