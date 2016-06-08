<?php

namespace App\Models;

class VenueGallery extends BaseModel
{
    protected $table = 'venue_gallery';

    public $timestamps = false;

    public function photos()
    {
        return $this->hasMany('App\Models\VenueGalleryPhoto');
    }

    public function venue()
    {
        return $this->belongsTo('App\Models\Venue');
    }
}
