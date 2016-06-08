<?php

namespace App\Transformers;

use App\Models\Venue;
use League\Fractal\TransformerAbstract;

class VenueTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'city',
    ];

    public function transform(Venue $item)
    {
        return [
            'id'                => (int)$item->id,
            'place_id'          => $item->place_id,
            'name'              => $item->name,
            'image'             => $item->image,
            'image_url'         => $item->url,
            'address'           => $item->address,
            'postal_code'       => $item->postal_code,
            'formatted_address' => $item->address . ', ' . $item->postal_code . ', ' . (int)$item->city_id,
            'phone'             => $item->phone,
            'formatted_phone'   => str_replace('+261', '0', $item->phone),
            'summary'           => $item->summary,
            'description'       => $item->description,
            'facebook'          => $item->facebook,
            'twitter'           => $item->twitter,
            'website'           => $item->website,
            'categories'        => array_map('intval', array_pluck($item->categories->toArray(), 'venue_category_id')),
            'location'          => $item->location,
            'is_verified'       => (bool)$item->is_verified,
            'enabled'           => (bool)$item->enabled,
            'user_id'           => (int)$item->user_id,
        ];
    }

    public function includeCity(Venue $item)
    {
        $city = $item->city;
        if ($city) {
            return $this->item($city, new CityTransformer(), null);
        }
    }
}
