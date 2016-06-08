<?php

namespace App\Transformers;

use App\Models\MediaCategory;
use League\Fractal\TransformerAbstract;

class MediaCategoryTransformer extends TransformerAbstract
{
    public function transform(MediaCategory $item)
    {
        return [
            'id'         => (int)$item->id,
            'name'       => $item->name,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ];
    }
}
