<?php namespace App\Models;

use URL;

class VenueCategory extends BaseModel
{
    use TimestampsFormatTrait;

    protected $table = 'venue_category';

    public $timestamps = true;
    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        if($this->attributes['image'] && !is_null($this->attributes['image'])) {
            return URL::to('api/media/category/venue/' . $this->attributes['image']);
        }
    }

    public function venues()
    {
        return $this->hasMany('App\Models\Venue', 'venue_venue_category');
    }

}