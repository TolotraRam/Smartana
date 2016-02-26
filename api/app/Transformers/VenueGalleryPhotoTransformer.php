<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\VenueGalleryPhoto;

class VenueGalleryPhoto extends TransformerAbstract
{
	
	public function transform(VenueGalleryPhoto $item)
    {
        return [
            'id'                => (int)$item->id,
            'venue_gallery_id' => (int)$item->venue_gallery_id,
            'path'              => $item->path,
            'url'               => $item->url,
            'filesize'          => (int)$item->filesize,
            'name'              => $item->name,
            'key'               => $item->key,
            'mime'              => $item->mime,
            'created_at'        => $item->created_at,
            'updated_at'        => $item->updated_at,
        ];
    }
}