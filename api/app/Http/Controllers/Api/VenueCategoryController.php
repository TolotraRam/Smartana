<?php namespace App\Http\Controllers\Api;

use Input;
use Validator;
use DB;
use Storage;
use File;

use App\Models\VenueCategory;
use App\Transformers\VenueCategoryTransformer;

use App\Exceptions\NotFoundException;
use App\Exceptions\ResourceException;

class VenueCategoryController extends ApiController
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

        // Get input data
        $validator = Validator::make(Input::all(), [
            'page'           => 'integer',
            'limit'          => 'integer|min:1|max:250',
            'search'         => 'string'
        ]);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $categories = new VenueCategory;
        //Filter
        if (Input::has('search')) {
            $categories = $categories->where('name', 'LIKE', '%' . Input::get('search') . '%');
        }

        $categories = $categories->simplePaginate(Input::get('limit', 50));

        return response()->paginator($categories, new VenueCategoryTransformer);

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
        $category = VenueCategory::find($id);
        $this->checkExist($category);

        return response()->item($category, new VenueCategoryTransformer);

    }

    /**
     * Get venue category image
     *
     * @param  string $type
     * @param  string $filename
     *
     * @return Response
     */
    public function get($type, $filename)
    {

        $category = VenueCategory::where('image', '=', $filename)->first();
        if ($category->image && !is_null($category->image)) {
            $file = Storage::get('uploads/category/'. $type .'/' . $category->image);
            if ($file) {
                $extension = explode('.', $category->image);
                return Response($file, 200)->header('Content-Type', 'image/' . $extension[1]);
            }
        }

        throw new NotFoundException;
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
            'name' => 'required|max:255',
            'enabled' => 'boolean',
            'is_featured' => 'boolean'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $category = new VenueCategory;

            if(isset($input['name']) && $input['name'] !=="") {
                $category->name = $input['name'];
            }
            if(isset($input['description']) && $input['description'] !=="") {
                $category->description = $input['description'];
            }
            if(isset($input['is_featured']) && $input['is_featured'] !=="") {
                $category->is_featured = $input['is_featured'];
            }
            if(isset($input['enabled']) && $input['enabled'] !=="") {
                $category->enabled = $input['enabled'];
            }
            
            if(!is_null(Input::file('attachment')) && Input::file('attachment')) {
                
                $file = Input::file('attachment');
                $extension = $file->getClientOriginalExtension();
                $key = strtolower(md5(uniqid($category->name))) . '.' . $extension;
                $category->image = $key;

                Storage::put('uploads/category/venue/' . $key, File::get($file));
            }
            $category->save();
            DB::commit();

            return $this->show($category->id);
        } catch (\Exception $e) {
            DB::rollback();
            throw new ResourceException($e);
        }

        return $this->show($category->id);
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
        $input = json_decode(Input::get('data'), true);
        $rules = [
            'name' => 'max:255',
            'enabled' => 'boolean',
            'is_featured' => 'boolean'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $category = new VenueCategory;
            $category = VenueCategory::find($id);

            $this->checkExist($category);

            if(isset($input['name']) && $input['name'] !=="") {
                $category->name = $input['name'];
            }
            if(isset($input['description']) && $input['description'] !=="") {
                $category->description = $input['description'];
            }
            if(isset($input['is_featured']) && $input['is_featured'] !=="") {
                $category->is_featured = $input['is_featured'];
            }
            if(isset($input['enabled']) && $input['enabled'] !=="") {
                $category->enabled = $input['enabled'];
            }
            if(!is_null(Input::file('attachment')) && Input::file('attachment')) {
                if(isset($category->image) && $category->image !== "") {
                    $file = Storage::delete('uploads/category/venue/' . $category->image);
                }
                $file = Input::file('attachment');
                $extension = $file->getClientOriginalExtension();
                $key = strtolower(md5(uniqid($category->name))) . '.' . $extension;
                $category->image = $key;

                Storage::put('uploads/category/venue/' . $key, File::get($file));
            }

            $category->save();

            DB::commit();

            return $this->show($category->id);
        } catch (\Exception $e) {
            DB::rollback();
            throw new ResourceException($e);
        }
        
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
        $category = VenueCategory::find($id);

        $this->checkExist($category);

        $category->delete();

        return response()->return();

    }

}
