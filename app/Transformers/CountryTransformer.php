<?php

namespace App\Transformers;

use App\Models\Country;
use League\Fractal\TransformerAbstract;

class CountryTransformer extends TransformerAbstract
{
    public function transform(Country $item)
    {
        return [
            'id'         => (int) $item->id,
            'code'       => $item->code,
            'name'       => $item->name,
            'enabled'    => (bool) $item->enabled,
        ];
    }
}
