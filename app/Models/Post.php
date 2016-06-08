<?php namespace App\Models;

use Carbon;
use App\Models\Language;

class Post extends BaseModel
{
    use TimestampsFormatTrait;
    use \Conner\Tagging\TaggableTrait;
    use PublishedAtTimestampsFormatTrait;


    protected $table = 'post';
    public $timestamps = true;

    public function categories()
    {
        return $this->belongsToMany('App\Models\PostCategory', 'post_post_category');
    }

}
