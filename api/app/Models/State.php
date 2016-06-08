<?php

namespace App\Models;

class State extends BaseModel
{
    protected $table = 'states';

    protected $fillable = ['name', 'enabled', 'country_id'];

    public $timestamps = false;

    public function cities()
    {
        return $this->hasMany('App\Models\City');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
}
