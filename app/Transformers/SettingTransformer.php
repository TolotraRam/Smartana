<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Setting;

class SettingTransformer extends TransformerAbstract
{
    public function transform(Setting $item)
    {
        return [
            'id'      	=> (int)$item->id,
            'type'    	=> $item->type,
            'name' 		=> $item->name,
            'value' 	=> $item->value
        ];

    }

}



