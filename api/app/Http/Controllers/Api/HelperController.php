<?php

namespace App\Http\Controllers\Api;

use App\Helpers\StrHelper;
use Input;

class HelperController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gen slug by string.
     *
     * @return Response
     */
    public function slug()
    {
        $slug = '';
        if (Input::has('slug')) {
            $slug = StrHelper::slug(Input::get('slug'));
        }

        return response()->return(['slug' => $slug]);
    }
}
