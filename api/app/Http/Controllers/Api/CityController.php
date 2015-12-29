<?php namespace App\Http\Controllers\Api;

use Input;
use Validator;
use Response;

use App\Models\City;
use App\Transformers\CityTransformer;

use App\Exceptions\ResourceException;

class CityController extends ApiController
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
            'state_ids'        => 'array|integerInArray'
        ]);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $city = new City;

        if (Input::has('state_ids')) {
            $city = $city->whereHas('state', function ($q) {
                $q->whereIn('id', Input::get('state_ids'));
            });
        }
        if (Input::has('search')) {
            $city = $city->where('name', 'LIKE', '%' . Input::get('search') . '%');
        }

        $city = $city->simplePaginate(Input::get('limit', 50));

        return response()->paginator($city, new CityTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $city = City::find($id);

        $this->checkExist($city);

        return response()->item($city, new CityTransformer);
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

        $city = new City;
        $city->name = Input::get('name');
        $city->code = Input::get('code');
        $city->enabled = Input::get('enabled');

        $city->save();

        return $this->show($city->id);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
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

        $city = City::find($id);
        $this->checkExist($city);
        if (Input::has('name')) {
            $city->name = Input::get('name');
        }
        if (Input::has('code')) {
            $city->code = Input::get('code');
        }
        if (Input::has('enabled')) {
            $city->enabled = Input::get('enabled');
        }
        $city->save();

        return $this->show($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $city = City::find($id);
        $this->checkExist($city);

        $city->delete();

        return response()->return();

    }

}
