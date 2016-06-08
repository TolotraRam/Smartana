<?php

namespace App\Transformers;

use App\Models\State;
use League\Fractal\TransformerAbstract;

class StateTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'country',
    ];

    public function transform(State $item)
    {
        return [
            'id'         => (int)$item->id,
            'name'       => $item->name,
            'enabled'    => (bool)$item->enabled,
        ];
    }

    public function includeCountry(State $item)
    {
        $country = $item->country;
        if ($country->count() > 0) {
            return $this->item($country, new CountryTransformer());
        }
    }
}
