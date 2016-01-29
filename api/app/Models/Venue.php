<?php namespace App\Models;

class Venue extends BaseModel
{
    protected $table = 'venues';
    protected $fillable = ['name', 'description', 'postal_code', 'address', 'phone', 'summary', 'facebook', 'twitter', 'google', 'website', 'is_verified', 'city_id',];
    public $timestamps = true;

    public function categories()
    {
        return $this->belongsToMany('App\Models\VenueCategory', 'venue_venue_category');
    }

}