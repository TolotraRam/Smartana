<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'city',
        'roles'
    ];

    public function transform(User $item)
    {
        return [
            'id'                => (int)$item->id,
            'email'             => $item->email,
            'lastname'          => $item->lastname,
            'firstname'         => $item->firstname,
            'active'            => (boolean)$item->active,
            'avatar'            => $item->avatar,
            'avatar_url'        => $item->url,
            'facebook'          => $item->facebook,
            'twitter'           => $item->twitter,
            'google'            => $item->google,
            'biography'         => $item->biography,
            'last_login'        => $item->last_login,
            'phone'             => $item->phone,
            'postal_code'       => $item->postal_code,
            'address'           => $item->address,
            'created_at'        => $item->created_at,
            'updated_at'        => $item->updated_at,
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
        
        return $this->item($city, new CityTransformer, null);
    }
}


