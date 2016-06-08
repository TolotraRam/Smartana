<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\NotFoundException;
use App\Exceptions\ResourceException;
use App\Models\User;
use App\Transformers\UserTransformer;
use DB;
use File;
use Input;
use Response;
use Storage;
use Validator;

class UserController extends ApiController
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
            'ids'            => 'array|integerInArray',
            'page'           => 'integer',
            'created_at_min' => 'date_format:"Y-m-d H:i:s"',
            'created_at_max' => 'date_format:"Y-m-d H:i:s"',
            'updated_at_min' => 'date_format:"Y-m-d H:i:s"',
            'updated_at_max' => 'date_format:"Y-m-d H:i:s"',
            'limit'          => 'integer|min:1|max:250',
            'search'         => 'string',
            'role_ids'       => 'array|integerInArray',
        ]);
        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        $users = new User();
        if (Input::has('ids')) {
            $users = $users->whereIn('id', Input::get('ids'));
        }
        //Filter
        if (Input::has('search')) {
            $users = $users->where('lastname', 'LIKE', '%'.Input::get('search').'%')->orWhere('firstname', 'LIKE', '%'.Input::get('search').'%');
        }

        if (Input::has('role_ids')) {
            $users = $users->whereHas('roles', function ($q) {
                $q->whereIn('id', Input::get('role_ids'));
            });
        }
        if (Input::has('created_at_min')) {
            $users = $users->where('created_at', '>=', Input::get('created_at_min'));
        }
        if (Input::has('created_at_max')) {
            $users = $users->where('created_at', '<=', Input::get('created_at_max'));
        }

        $users = $users->orderBy('created_at', 'DESC')->simplePaginate(Input::get('limit', 50));

        return response()->paginator($users, new UserTransformer());
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
        $user = User::find($id);
        $this->checkExist($user);

        return response()->item($user, new UserTransformer());
    }

    /**
     * Get avatar image.
     *
     * @param string $type
     * @param string $filename
     *
     * @return Response
     */
    public function get($type, $filename)
    {
        $user = User::where('avatar', '=', $filename)->first();
        if ($user->avatar && !is_null($user->avatar)) {
            $file = Storage::get('uploads/'.$type.'/'.$user->avatar);
            if ($file) {
                $extension = explode('.', $user->avatar);

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
            'lastname'  => 'string|min:1|max:255',
            'firstname' => 'string|min:1|max:255',
            'active'    => 'boolean',
            'email'     => 'required|email',
            'password'  => 'required|min:6|max:255',
            'avatar'    => 'string|min:1|max:255',
            'facebook'  => 'string|min:1|max:255',
            'twitter'   => 'string|min:1|max:255',
            'google'    => 'string|min:1|max:255',
            'phone'     => 'string|min:1|max:255',
            'address'   => 'string|min:1|max:255',
            'biography' => 'string|min:1|max:255',
            'roles'     => 'array|integerInArray|existsInArray:role,id',
            'city_id'   => 'integer',
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        if (User::where('email', '=', $input['email'])->first()) {
            throw new ResourceException('E-mail already exist');
        }

        DB::beginTransaction();
        try {
            $user = new User();

            $this->fillFieldFromJson($user, ['email', 'password']);
            $this->fillNullableFieldFromJson($user, ['lastname', 'firstname', 'active', 'facebook', 'twitter', 'google', 'phone', 'address', 'postal_code', 'biography', 'city_id']);

            if (!is_null(Input::file('attachment')) && Input::file('attachment')) {
                $file = Input::file('attachment');
                $extension = $file->getClientOriginalExtension();
                $key = strtolower(md5(uniqid($input['email']))).'.'.$extension;
                $user->avatar = $key;

                Storage::put('uploads/avatar/'.$key, File::get($file));
            }

            $user->save();
            $user->roles()->sync($input['roles']);

            DB::commit();

            return $this->show($user->id);
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
            'lastname'  => 'string|min:1|max:255',
            'firstname' => 'string|min:1|max:255',
            'active'    => 'boolean',
            'email'     => 'email',
            'password'  => 'min:6|max:255',
            'roles'     => 'array|integerInArray|existsInArray:role,id',
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $user = new User();
            $user = User::find($id);

            $this->checkExist($user);

            if (isset($input['email']) && $input['email'] != '') {
                $user->email = $input['email'];
            }
            if (isset($input['password']) && $input['password'] != '') {
                $user->password = $input['password'];
            }
            if (isset($input['lastname']) && $input['lastname'] != '') {
                $user->lastname = $input['lastname'];
            }
            if (isset($input['firstname']) && $input['firstname'] != '') {
                $user->firstname = $input['firstname'];
            }
            if (isset($input['active']) && $input['active'] != '') {
                $user->active = $input['active'];
            }
            if (isset($input['facebook']) && $input['facebook'] != '') {
                $user->facebook = $input['facebook'];
            }
            if (isset($input['twitter']) && $input['twitter'] != '') {
                $user->twitter = $input['twitter'];
            }
            if (isset($input['google']) && $input['google'] != '') {
                $user->google = $input['google'];
            }
            if (isset($input['phone']) && $input['phone'] != '') {
                $user->phone = $input['phone'];
            }
            if (isset($input['address']) && $input['address'] != '') {
                $user->address = $input['address'];
            }
            if (isset($input['postal_code']) && $input['postal_code'] != '') {
                $user->postal_code = $input['postal_code'];
            }
            if (isset($input['biography']) && $input['biography'] != '') {
                $user->biography = $input['biography'];
            }
            if (isset($input['city_id']) && $input['city_id'] != '') {
                $user->city_id = $input['city_id'];
            }
            if (!is_null(Input::file('attachment')) && Input::file('attachment')) {
                if (isset($user->avatar) && $user->avatar != '') {
                    $file = Storage::delete('uploads/avatar/'.$user->avatar);
                }
                $file = Input::file('attachment');
                $extension = $file->getClientOriginalExtension();
                $key = strtolower(md5(uniqid($user->email))).'.'.$extension;
                $user->avatar = $key;

                Storage::put('uploads/avatar/'.$key, File::get($file));
            }

            $user->save();

            if (isset($input['roles']) && $input['roles']) {
                $user->roles()->sync($input['roles']);
            }

            DB::commit();

            return $this->show($user->id);
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
        $user = User::find($id);
        $this->checkExist($user);

        $user->delete();

        return response()->return();
    }
}
