<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ResourceException;
use App\Models\VenueGallery;
use App\Transformers\VenueGalleryTransformer;
use Input;
use Response;
use Validator;

class VenueGalleryController extends ApiController
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

        ]);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $galleries = VenueGallery::simplePaginate(Input::get('limit', 50));

        return response()->paginator($galleries, new VenueGalleryTransformer());
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
        $gallery = VenueGallery::find($id);
        $this->checkExist($gallery);

        return response()->item($gallery, new VenueGalleryTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'name' => 'required|max:255',
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $gallery = new VenueGallery();
        $gallery->name = Input::get('name');
        if (Input::has('description')) {
            $gallery->description = Input::get('description');
        }
        if (Input::has('venue_id')) {
            $media->venue_id = Input::get('venue_id');
        }
        $gallery->save();

        return $this->show($gallery->id);
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
            'name' => 'required|max:255',
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $gallery = VenueGallery::find($id);
        $this->checkExist($gallery);
        $gallery->name = Input::get('name');
        if (Input::has('description')) {
            $gallery->description = Input::get('description');
        }
        if (Input::has('venue_id')) {
            $media->venue_id = Input::get('venue_id');
        }
        $gallery->save();

        return $this->show($gallery->id);
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
        $gallery = VenueGalleryCategory::find($id);

        $this->checkExist($gallery);

        $gallery->delete();

        return response()->return();
    }
}
