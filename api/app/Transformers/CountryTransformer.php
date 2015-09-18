<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Country;

class CountryTransformer extends TransformerAbstract
{
    public function transform(Country $item)
    {
        return [
            'id'         => (int)$item->id,
            'code'       => $item->code,
            'name'       => $item->name,
            'enabled'    => (boolean)$item->enabled,
        ];

    }

}



