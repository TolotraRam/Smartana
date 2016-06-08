<?php namespace App\Models;

class PostCategory extends \Baum\Node
{
    use TimestampsFormatTrait;


    protected $table = 'post_category';

    public $timestamps = true;
    protected $hidden = ['lft', 'rgt'];

    protected $with = [];

    public function posts()
    {
        return $this->hasMany('App\Models\Post', 'post_post_category');
    }

}
