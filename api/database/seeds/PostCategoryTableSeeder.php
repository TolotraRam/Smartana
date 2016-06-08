<?php

use App\Models\PostCategory;
use Illuminate\Database\Seeder;

class PostCategoryTableSeeder extends Seeder
{
    public function run()
    {
        $postCategory = PostCategory::create([
            'parent_id' => null,
            'lft'       => 1,
            'rgt'       => 2,
            'depth'     => 0,
            'active'    => 1,
            'slug'      => 'root',
            'name'      => 'Root',
        ]);
    }
}
