<?php namespace App\Models;

class VenueCategory extends BaseModel
{
    use TimestampsFormatTrait;

    protected $table = 'venue_category';

    public $timestamps = true;

    public function venues()
    {
        return $this->hasMany('App\Models\Venue', 'venue_venue_category');
    }

}