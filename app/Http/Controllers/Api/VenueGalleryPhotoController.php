<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\NotFoundException;
use App\Exceptions\ResourceException;
use App\Models\VenueGalleryPhoto;
use App\Transformers\VenueGalleryPhotoTransformer;
use DB;
use File;
use Input;
use Response;
use Storage;
use Validator;

class VenueGalleryPhotoController extends ApiController
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
            'limit'            => 'integer|min:1|max:250',
            'search'           => 'max:255',
            'gallery_ids'     => 'array|integerInArray',
        ]);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $VenueGalleryPhoto = new VenueGalleryPhoto();

        //Filter
        if (Input::has('category_ids')) {
            $VenueGalleryPhoto = $VenueGalleryPhoto->whereIn('venue_gallery_id', Input::get('gallery_ids'));
        }
        if (Input::has('search')) {
            $VenueGalleryPhoto = $VenueGalleryPhoto->where('name', 'LIKE', '%' . Input::get('search') . '%');
        }

        $VenueGalleryPhoto = $VenueGalleryPhoto->simplePaginate(Input::get('limit', 50));

        return response()->paginator($VenueGalleryPhoto, new VenueGalleryPhotoTransformer());
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
        $VenueGalleryPhoto = VenueGalleryPhoto::find($id);

        $this->checkExist($VenueGalleryPhoto);

        return response()->item($VenueGalleryPhoto, new VenueGalleryPhotoTransformer());
    }

    public function get($type, $yearAndMonth, $day, $filename)
    {
        $VenueGalleryPhoto = VenueGalleryPhoto::where('key', '=', $filename)->first();
        if (!is_null($VenueGalleryPhoto)) {
            $file = Storage::get('uploads/' . $type . '/' . $yearAndMonth . '/' . $day . '/' . $VenueGalleryPhoto->key);
            if ($file) {
                return Response($file, 200)->header('Content-Type', $VenueGalleryPhoto->mime);
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
        $rules = [
            'attachment'        => 'required|mimes:jpeg,png,pdf,docx,xlsx,pptx,txt,zip,rar|max:10000',
            'name'              => 'max:255',
            'venue_gallery_id' => 'exists:venue_gallery,id',
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $VenueGalleryPhoto = new VenueGalleryPhoto();
            if (Input::has('venue_gallery_id')) {
                $VenueGalleryPhoto->venue_gallery_id = Input::get('venue_gallery_id');
            }
            $VenueGalleryPhoto->name = Input::get('name', '');
            $file = Input::file('attachment');
            $extension = $file->getClientOriginalExtension();
            $VenueGalleryPhoto->filesize = $file->getSize();
            $VenueGalleryPhoto->mime = $file->getClientMimeType();
            $VenueGalleryPhoto->key = strtolower(md5(uniqid($VenueGalleryPhoto->id . rand()))) . '.' . $extension;
            $VenueGalleryPhoto->path = $this->generateDateFolder();

            //upload file
            Storage::put('uploads/m/' . $VenueGalleryPhoto->path . $VenueGalleryPhoto->key, File::get($file));
            $VenueGalleryPhoto->save();
            DB::commit();

            return $this->show($VenueGalleryPhoto->id);
        } catch (\Exception $e) {
            DB::rollback();
            throw new ResourceException('Invalid request parameters');
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
        $rules = [
            'name'              => 'max:255',
            'venue_gallery_id' => 'exists:venue_gallery,id',
        ];

        $validator = Validator::make(Input::only(array_keys($rules)), $rules);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $VenueGalleryPhoto = VenueGalleryPhoto::find($id);
        $this->checkExist($VenueGalleryPhoto);
        if (Input::has('venue_gallery_id')) {
            $VenueGalleryPhoto->venue_gallery_id = Input::get('venue_gallery_id');
        }
        if (Input::has('name')) {
            $VenueGalleryPhoto->name = Input::get('name');
        }
        $VenueGalleryPhoto->save();

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
        $VenueGalleryPhoto = VenueGalleryPhoto::find($id);
        $this->checkExist($VenueGalleryPhoto);

        $VenueGalleryPhoto->delete();

        return response()->return();
    }
}
