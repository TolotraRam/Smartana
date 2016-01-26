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
            'name'       => $item->name,
        ];

    }
    /*public function includeState(Venue $item)
    {
        $state = $item->state;
        if ($state->count() > 0) {
            return $this->item($state, new StateTransformer);
        }
    }*/

}