<?php

namespace App\Models;

class VenueGalleryPhoto extends BaseModel
{
    protected $table = 'venue_gallery_photo';

    public $timestamps = false;
    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return URL::to('api/media/gallery/venue/' . $this->attributes['path'] . $this->attributes['key']);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($image) {
            Storage::delete('uploads/m/gallery/venue/' . $image->path . $image->key);
        });
    }

    public function gallery()
    {
        return $this->belongsTo('App\Models\VenueGallery');
    }
}
