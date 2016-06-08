<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\NotFoundException;
use Illuminate\Routing\Controller as BaseController;
use Input;

class ApiController extends BaseController
{
    public function __construct()
    {
    }

    /**
     * check model exist.
     *
     * @param Model $obj
     *
     * @return void
     */
    public function checkExist($obj)
    {
        if (is_null($obj)) {
            throw new NotFoundException();
        }
    }

    /**
     * fill nullable field from input.
     *
     * @param Model $obj
     * @param array $fields
     *
     * @return void
     */
    public function fillNullableFieldFromInput($obj, $fields = [])
    {
        foreach ($fields as $key => $field) {
            if (Input::get($field) === '') {
                $obj->{$field} = null;
            } elseif (Input::has($field)) {
                $obj->{$field} = Input::get($field);
            }
        }
    }

    /**
     * fill nullable field from json.
     *
     * @param Model $obj
     * @param array $fields
     *
     * @return void
     */
    public function fillNullableFieldFromJson($obj, $fields = [])
    {
        $input = json_decode(Input::get('data'), true);
        foreach ($fields as $key => $field) {
            if (!isset($input[$field])) {
                $obj->{$field} = null;
            } elseif ($input[$field] == '') {
                $obj->{$field} = null;
            } else {
                $obj->{$field} = $input[$field];
            }
        }
    }

    /**
     * fill field from input.
     *
     * @param Model $obj
     * @param array $fields
     *
     * @return void
     */
    public function fillFieldFromInput($obj, $fields = [])
    {
        foreach ($fields as $key => $field) {
            if (Input::has($field)) {
                $obj->{$field} = Input::get($field);
            }
        }
    }

    /**
     * fill field from json.
     *
     * @param Model $obj
     * @param array $fields
     *
     * @return void
     */
    public function fillFieldFromJson($obj, $fields = [])
    {
        $input = json_decode(Input::get('data'), true);
        foreach ($fields as $key => $field) {
            if ($input[$field] && $input[$field] !== '') {
                $obj->{$field} = $input[$field];
            }
        }
    }
}
