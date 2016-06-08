<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class VenueGalleryTransformer extends TransformerAbstract
{
    public function transform(VenueGallery $item)
    {
        return [
            'id'                => (int) $item->id,
            'venue_id'          => (int) $item->venue_id,
            'name'              => $item->name,
            'description'       => $item->description,
        ];
    }
}
