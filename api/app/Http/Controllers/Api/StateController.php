<?php namespace App\Http\Controllers\Api;

use Input;
use Validator;
use Response;

use App\Models\State;
use App\Transformers\StateTransformer;

use App\Exceptions\ResourceException;

class StateController extends ApiController
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

        $state = new State;
        //Filter
        if (Input::has('country_ids')) {
            $state = $state->whereHas('country', function ($q) {
                $q->whereIn('id', Input::get('country_ids'));
            });
        }
        if (Input::has('search')) {
            $state = $state->where('name', 'LIKE', '%' . Input::get('search') . '%');
        }
        $state = $state->simplePaginate(Input::get('limit', 50));

        return response()->paginator($state, new StateTransformer);
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
        $state = State::find($id);

        $this->checkExist($state);

        return response()->item($state, new StateTransformer);
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

        $state = new State;
        $state->name = Input::get('name');
        $state->code = Input::get('code');
        $state->enabled = Input::get('enabled');

        $state->save();

        return $this->show($state->id);

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

        $state = State::find($id);
        $this->checkExist($state);
        if (Input::has('name')) {
            $state->name = Input::get('name');
        }
        if (Input::has('code')) {
            $state->code = Input::get('code');
        }
        if (Input::has('enabled')) {
            $state->enabled = Input::get('enabled');
        }
        $state->save();

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
        $state = State::find($id);
        $this->checkExist($state);

        $state->delete();

        return response()->return();

    }

}
