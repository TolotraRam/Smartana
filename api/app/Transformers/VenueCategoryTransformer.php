<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\VenueCategory;

class VenueCategoryTransformer extends TransformerAbstract
{
    public function transform(VenueCategory $item)
    {
        return [
            'id'         => (int)$item->id,
            'name'       => $item->name,
            'description'=> $item->description,
            'image'		 => $item->image,
            'image_url'  => $item->url,
            'enabled'    => (boolean)$item->enabled,
            'is_featured'=> (boolean)$item->is_featured,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ];

    }

}