<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\NotFoundException;
use App\Exceptions\ResourceException;
use App\Models\Venue;
use App\Transformers\VenueTransformer;
use DB;
use File;
use Input;
use Storage;
use Validator;

class VenueController extends ApiController
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
            'ids'              => 'array|integerInArray',
            'page'             => 'integer',
            'limit'            => 'integer|min:1|max:250',
            'category_ids'     => 'array|integerInArray',
        ]);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $venues = new Venue();
        $venues = $venues->with([
            'categories' => function ($query) {
                $query->select(['venue_category_id']);
            },
        ])->orderBy('created_at', 'DESC');

        //Filter
        if (Input::has('search')) {
            $venues = $venues->where('name', 'LIKE', '%'.Input::get('search').'%');
        }

        if (Input::has('ids')) {
            $venues = $venues->whereIn('id', Input::get('ids'));
        }

        if (Input::has('category_ids')) {
            $venues = $venues->whereHas('categories', function ($q) {
                $q->whereIn('id', Input::get('category_ids'));
            });
        }

        $venues = $venues->simplePaginate(Input::get('limit', 50));

        return response()->paginator($venues, new VenueTransformer());
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
        $venue = Venue::find($id);

        $this->checkExist($venue);

        $venue = $venue->load([
            'categories' => function ($query) {
                $query->select(['venue_category_id']);
            },
        ]);

        return response()->item($venue, new VenueTransformer());
    }

    /**
     * Get venue image.
     *
     * @param string $type
     * @param string $filename
     *
     * @return Response
     */
    public function get($type, $filename)
    {
        $venue = Venue::where('image', '=', $filename)->first();
        if ($venue->image && !is_null($venue->image)) {
            $file = Storage::get('uploads/'.$type.'/'.$venue->image);
            if ($file) {
                $extension = explode('.', $venue->image);

                return Response($file, 200)->header('Content-Type', 'image/'.$extension[1]);
            }
        }

        throw new NotFoundException();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = json_decode(Input::get('data'), true);

        $rules = [
            'name'          => 'required|min:1|max:255',
            'postal_code'   => 'string|min:1|max:255',
            'address'       => 'string|min:1|max:255',
            'phone'         => 'string|min:1|max:255',
            'is_verified'   => 'boolean',
            'enabled'       => 'boolean',
            'summary'       => 'string|min:6|max:255',
            'description'   => 'string|min:1|max:255',
            'facebook'      => 'string|min:1|max:255',
            'twitter'       => 'string|min:1|max:255',
            'google'        => 'string|min:1|max:255',
            'categories'    => 'array|integerInArray|existsInArray:venue_category,id',
            'city_id'       => 'integer',
            'user_id'       => 'integer',
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $venue = new venue();

            if (isset($input['name']) && $input['name'] !== '') {
                $venue->name = $input['name'];
            }
            if (isset($input['phone']) && $input['phone'] !== '') {
                $venue->phone = $input['phone'];
            }
            if (isset($input['address']) && $input['address'] !== '') {
                $venue->address = $input['address'];
            }
            if (isset($input['postal_code']) && $input['postal_code'] !== '') {
                $venue->postal_code = $input['postal_code'];
            }
            if (isset($input['summary']) && $input['summary'] !== '') {
                $venue->summary = $input['summary'];
            }
            if (isset($input['description']) && $input['description'] !== '') {
                $venue->description = $input['description'];
            }
            if (isset($input['facebook']) && $input['facebook'] !== '') {
                $venue->facebook = $input['facebook'];
            }
            if (isset($input['twitter']) && $input['twitter'] !== '') {
                $venue->twitter = $input['twitter'];
            }
            if (isset($input['google']) && $input['google'] !== '') {
                $venue->google = $input['google'];
            }
            if (isset($input['is_verified']) && $input['is_verified'] !== '') {
                $venue->is_verified = $input['is_verified'];
            }
            if (isset($input['enabled']) && $input['enabled'] !== '') {
                $venue->enabled = $input['enabled'];
            }
            if (isset($input['location']) && $input['location'] !== '') {
                $venue->location = $input['location'];
            }
            $venue->place_id = strtolower(md5(uniqid()));

            if (!is_null(Input::file('attachment')) && Input::file('attachment')) {
                $file = Input::file('attachment');
                $extension = $file->getClientOriginalExtension();
                $key = strtolower(md5(uniqid($venue->place_id))).'.'.$extension;
                $venue->image = $key;

                Storage::put('uploads/venue/'.$key, File::get($file));
            }

            $venue->save();
            $venue->categories()->sync($input['categories']);

            DB::commit();

            return $this->show($venue->id);
        } catch (\Exception $e) {
            DB::rollback();
            throw new ResourceException($e);
        }
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
        $input = json_decode(Input::get('data'), true);

        $rules = [
            'name'          => 'required|min:1|max:255',
            'postal_code'   => 'string|min:1|max:255',
            'address'       => 'string|min:1|max:255',
            'phone'         => 'string|min:1|max:255',
            'is_verified'   => 'boolean',
            'enabled'       => 'boolean',
            'summary'       => 'string|min:6|max:255',
            'description'   => 'string|min:1|max:255',
            'facebook'      => 'string|min:1|max:255',
            'twitter'       => 'string|min:1|max:255',
            'google'        => 'string|min:1|max:255',
            'categories'    => 'array|integerInArray|existsInArray:venue_category,id',
            'city_id'       => 'integer',
            'user_id'       => 'integer',
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $venue = new venue();
            $venue = Venue::find($id);

            $this->checkExist($venue);
            if (isset($input['name']) && $input['name'] !== '') {
                $venue->name = $input['name'];
            }
            if (isset($input['phone']) && $input['phone'] !== '') {
                $venue->phone = $input['phone'];
            }
            if (isset($input['address']) && $input['address'] !== '') {
                $venue->address = $input['address'];
            }
            if (isset($input['postal_code']) && $input['postal_code'] !== '') {
                $venue->postal_code = $input['postal_code'];
            }
            if (isset($input['summary']) && $input['summary'] !== '') {
                $venue->summary = $input['summary'];
            }
            if (isset($input['description']) && $input['description'] !== '') {
                $venue->description = $input['description'];
            }
            if (isset($input['facebook']) && $input['facebook'] !== '') {
                $venue->facebook = $input['facebook'];
            }
            if (isset($input['twitter']) && $input['twitter'] !== '') {
                $venue->twitter = $input['twitter'];
            }
            if (isset($input['google']) && $input['google'] !== '') {
                $venue->google = $input['google'];
            }
            if (isset($input['is_verified']) && $input['is_verified'] !== '') {
                $venue->is_verified = $input['is_verified'];
            }
            if (isset($input['enabled']) && $input['enabled'] !== '') {
                $venue->enabled = $input['enabled'];
            }
            if (isset($input['location']) && $input['location'] !== '') {
                $venue->location = $input['location'];
            }
            $venue->place_id = strtolower(md5(uniqid()));

            if (!is_null(Input::file('attachment')) && Input::file('attachment')) {
                $file = Input::file('attachment');
                $extension = $file->getClientOriginalExtension();
                $key = strtolower(md5(uniqid($venue->place_id))).'.'.$extension;
                $venue->image = $key;

                Storage::put('uploads/venue/'.$key, File::get($file));
            }

            $venue->save();
            $venue->categories()->sync($input['categories']);

            DB::commit();

            return $this->show($venue->id);
        } catch (\Exception $e) {
            DB::rollback();
            throw new ResourceException($e);
        }
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
    }
}
