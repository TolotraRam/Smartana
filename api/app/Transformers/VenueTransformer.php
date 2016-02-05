<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Venue;

class VenueTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
    ];

    public function transform(Venue $item)
    {
        return [
            'id'         => (int)$item->id,
            'place_id'   => $item->place_id,
            'name'       => $item->name,
            'image'      => $item->image,
            'address'    => $item->address,
            'postal_code'=> $item->postal_code,
            'formatted_address'       => $item->formatted_address,
            'phone'      => $item->phone,
            'formatted_phone'       => $item->formatted_phone,
            'summary'       => $item->summary,
            'description'       => $item->description,
            'facebook'       => $item->facebook,
            'twitter'       => $item->twitter,
            'website'       => $item->website,
            'categories'   => array_map('intval', array_pluck($item->categories->toArray(), 'venue_category_id')),
            'is_verified'       => (boolean)$item->is_verified,
            'enabled'       => (boolean)$item->enabled,
            'city_id'         => (int)$item->city_id,
            'user_id'         => (int)$item->user_id,
        ];

    }

}