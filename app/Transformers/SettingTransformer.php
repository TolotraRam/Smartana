<?php

namespace App\Transformers;

use App\Models\Setting;
use League\Fractal\TransformerAbstract;

class SettingTransformer extends TransformerAbstract
{
    public function transform(Setting $item)
    {
        return [
            'id'          => (int) $item->id,
            'type'        => $item->type,
            'name'        => $item->name,
            'value'    => $item->value,
        ];
    }
}
