<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ResourceException;
use App\Models\Country;
use App\Transformers\CountryTransformer;
use Input;
use Response;
use Validator;

class CountryController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $validator = Validator::make(Input::all(), [
            'page'             => 'integer',
            'limit'            => 'integer|min:1|max:10000',
            'search'           => 'max:10000',
        ]);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $country = new Country();

        //Filter
        if (Input::has('search')) {
            $country = $country->where('name', 'LIKE', '%' . Input::get('search') . '%');
        }

        $country = $country->simplePaginate(Input::get('limit', 50));

        return response()->paginator($country, new CountryTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $country = Country::find($id);

        $this->checkExist($country);

        return response()->item($country, new CountryTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'code'  => 'string|min:1|max:3',
            'name' => 'string|min:1|max:30',
            'enabled'    => 'boolean',
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $country = new Country();
        $country->name = Input::get('name');
        $country->code = Input::get('code');
        $country->enabled = Input::get('enabled');

        $country->save();

        return $this->show($country->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id)
    {
        $rules = [
            'code'  => 'string|min:1|max:3',
            'name' => 'string|min:1|max:30',
            'enabled'    => 'boolean',
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $country = Country::find($id);
        $this->checkExist($country);
        if (Input::has('name')) {
            $country->name = Input::get('name');
        }
        if (Input::has('code')) {
            $country->code = Input::get('code');
        }
        if (Input::has('enabled')) {
            $country->enabled = Input::get('enabled');
        }
        $country->save();

        return $this->show($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $country = Country::find($id);
        $this->checkExist($country);

        $country->delete();

        return response()->return();
    }
}
