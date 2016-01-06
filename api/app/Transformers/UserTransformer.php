<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;
use Log;

class UserTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'roles',
        'city',
    ];

    public function transform(User $item)
    {
        return [
            'id'            => (int)$item->id,
            'email'         => $item->email,
            'lastname'      => $item->lastname,
            'firstname'     => $item->firstname,
            'phone'         => $item->phone,
            'active'        => (boolean)$item->active,
            'avatar'        => $item->avatar,
            'facebook'      => $item->facebook,
            'twitter'       => $item->twitter,
            'google'        => $item->google,
            'phone'         => $item->phone,
            'address'       => $item->address,
            'biography'     => $item->biography,
            'last_login'    => $item->last_login,
            'city_id'       => $item->city_id,
            'created_at'    => $item->created_at,
            'updated_at'    => $item->updated_at,
        ];
    }

    public function includeRoles(User $item)
    {
        $roles = $item->roles;

        return $this->collection($roles, new RoleTransformer, null);
    }

    public function includeCity(User $item)
    {
        $city = $item->city;
        return $city;
        //return $this->collection($city, new CityTransformer, null);
        
    }
}


