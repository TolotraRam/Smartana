<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
  protected $table = 'states';

  protected $fillable = ['name', 'enabled', 'country_id'];

  public $timestamps = false;

  public function cities() {
    return $this->hasMany('App\Models\City');
  }

  public function country() {
    return $this->belongsTo('App\Models\Country', 'country_id');
  }
}
