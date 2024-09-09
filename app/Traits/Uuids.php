<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuids
{
  /**
   * Boot the trait and set the UUID for the primary key.
   *
   * @return void
   */
  protected static function bootUuids()
  {
    static::creating(function ($model) {
      if (empty($model->id)) {
        $model->id = (string) Str::uuid();
      }
    });
  }
}
