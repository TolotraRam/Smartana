<?php

namespace App\Transformers;

use App\Models\VenueGalleryPhoto;
use League\Fractal\TransformerAbstract;

class VenueGalleryPhotoTransformer extends TransformerAbstract
{
    public function transform(VenueGalleryPhoto $item)
    {
        return [
            'id'                => (int) $item->id,
            'venue_gallery_id'  => (int) $item->venue_gallery_id,
            'path'              => $item->path,
            'url'               => $item->url,
            'filesize'          => (int) $item->filesize,
            'name'              => $item->name,
            'key'               => $item->key,
            'mime'              => $item->mime,
            'created_at'        => $item->created_at,
            'updated_at'        => $item->updated_at,
        ];
    }
}
