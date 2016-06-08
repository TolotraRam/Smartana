<?php

namespace App\Transformers;

use App\Models\City;
use League\Fractal\TransformerAbstract;

class CityTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'state',
    ];

    public function transform(City $item)
    {
        return [
            'id'         => (int) $item->id,
            'name'       => $item->name,
            'enabled'    => (bool) $item->enabled,
        ];
    }

    public function includeState(City $item)
    {
        $state = $item->state;
        if ($state->count() > 0) {
            return $this->item($state, new StateTransformer());
        }
    }
}
