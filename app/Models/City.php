<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends BaseModel
{

  protected $table = 'cities';

  protected $fillable = ['name', 'enabled', 'state_id'];

  public $timestamps = false;

  public function state() 
  {
    return $this->belongsTo('App\Models\State');
  }

  public function users() 
  {
    return $this->hasMany('App\Models\User');
  }

  public function venues() 
  {
    return $this->hasMany('App\Models\Venue');
  }

}
