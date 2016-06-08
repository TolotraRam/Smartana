<?php

namespace App\Transformers;

use App\Models\VenueCategory;
use League\Fractal\TransformerAbstract;

class VenueCategoryTransformer extends TransformerAbstract
{
    public function transform(VenueCategory $item)
    {
        return [
            'id'         => (int)$item->id,
            'name'       => $item->name,
            'description' => $item->description,
            'image'         => $item->image,
            'image_url'  => $item->url,
            'enabled'    => (bool)$item->enabled,
            'is_featured' => (bool)$item->is_featured,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ];
    }
}
