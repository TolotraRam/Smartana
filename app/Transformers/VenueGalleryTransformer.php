<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\VenueGalleryPhoto;

class VenueGallery extends TransformerAbstract
{
	
	public function transform(VenueGallery $item)
    {
        return [
            'id'                => (int)$item->id,
            'venue_id'          => (int)$item->venue_id,
            'name'              => $item->name,
            'description'       => $item->description
        ];
    }
}