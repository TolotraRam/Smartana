<?php

namespace App\Models;

use URL;

class VenueCategory extends BaseModel
{
    use TimestampsFormatTrait;

    protected $table = 'venue_category';

    public $timestamps = true;
    protected $appends = ['url'];

    protected $fillable = ['name', 'description', 'image', 'enabled', 'is_featured'];

    protected $with = [];

    public function getUrlAttribute()
    {
        if (isset($this->attributes['image']) && $this->attributes['image'] && !is_null($this->attributes['image'])) {
            return URL::to('api/media/category/venue/'.$this->attributes['image']);
        }
    }

    public function venues()
    {
        return $this->belongsToMany('App\Models\Venue');
    }
}
