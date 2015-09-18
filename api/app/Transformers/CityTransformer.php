<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\City;
//use App\Models\State;

class CityTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'state',
    ];

    public function transform(City $item)
    {
        return [
            'id'         => (int)$item->id,
            'name'       => $item->name,
            'enabled'       => (boolean)$item->enabled,
        ];

    }
    public function includeState(City $item)
    {
        $state = $item->state;
        if ($state->count() > 0) {
            return $this->item($state, new StateTransformer);
        }
    }

}



