<?php namespace App\Models;

use DB;

class Venue extends BaseModel
{
    protected $table = 'venues';
    protected $geofields = array('location');
    protected $fillable = ['name', 'description', 'postal_code', 'address', 'phone', 'summary', 'facebook', 'twitter', 'google', 'website', 'is_verified', 'city_id', 'active'];
    protected $appends = ['url'];
    public $timestamps = true;

    public function getUrlAttribute()
    {
        if($this->attributes['image'] && !is_null($this->attributes['image'])) {
            return URL::to('api/media/venue/' . $this->attributes['image']);
        }
    }
    public function setLocationAttribute($value) 
    {
        $this->attributes['location'] = DB::raw("POINT($value)");
    }
 
    public function getLocationAttribute($value)
    {

        $loc =  substr($value, 6);
        $loc = preg_replace('/[ ,]+/', ',', $loc, 1);
 
        $loc = explode(',', substr($loc,0,-1));
        $loc = ['lat' => $loc[0], 'lng' => $loc[1]];
        
        return $loc;
    }
 
    public function newQuery($excludeDeleted = true)
    {
        $raw='';
        foreach($this->geofields as $column){
            $raw .= ' astext('.$column.') as '.$column.' ';
        }
 
        return parent::newQuery($excludeDeleted)->addSelect('*', DB::raw($raw));
    }

    public function scopeDistance($query,$dist,$location)
    {
        return $query->whereRaw('st_distance(location,POINT('.$location.')) < '.$dist);
 
    }

    public function city() 
    {
        return $this->belongsTo('App\Models\City');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\VenueCategory');
    }

    public function galleries()
    {
        return $this->hasMany('App\Models\VenueGallery');
    }

}