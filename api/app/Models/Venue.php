<?php namespace App\Models;

class Venue extends BaseModel
{
    protected $table = 'venues';
    protected $geofields = array('location');
    protected $fillable = ['name', 'description', 'postal_code', 'address', 'phone', 'summary', 'facebook', 'twitter', 'google', 'website', 'is_verified', 'city_id', 'active'];
    public $timestamps = true;

    public function setLocationAttribute($value) 
    {
        $this->attributes['location'] = DB::raw("POINT($value)");
    }
 
    public function getLocationAttribute($value)
    {

        $loc =  substr($value, 6);
        $loc = preg_replace('/[ ,]+/', ',', $loc, 1);
 
        return substr($loc,0,-1);
    }
 
    public function newQuery($excludeDeleted = true)
    {
        $raw='';
        foreach($this->geofields as $column){
            $raw .= ' astext('.$column.') as '.$column.' ';
        }
 
        return parent::newQuery($excludeDeleted)->addSelect('*',DB::raw($raw));
    }

    public function scopeDistance($query,$dist,$location)
    {
        return $query->whereRaw('st_distance(location,POINT('.$location.')) < '.$dist);
 
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\VenueCategory', 'venue_venue_category');
    }

}