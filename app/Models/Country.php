<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends BaseModel
{
  protected $table = 'countries';

  protected $fillable = ['code', 'name', 'enabled'];

  public $timestamps = false;

  public function states() {
    return $this->hasMany('App\Models\State');
  }
}
