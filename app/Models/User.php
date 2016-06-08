<?php

namespace App\Models;

use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use URL;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
    use TimestampsFormatTrait;
    use EntrustUserTrait;

    use Authenticatable, CanResetPassword;

    protected $table = 'user';
    protected $fillable = ['lastname', 'firstname', 'email', 'password', 'active', 'avatar', 'facebook', 'twitter', 'google', 'biography', 'city_id'];
    protected $hidden = ['password', 'remember_token'];
    public $timestamps = true;
    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        if ($this->attributes['avatar'] && !is_null($this->attributes['avatar'])) {
            return URL::to('api/media/avatar/'.$this->attributes['avatar']);
        }
    }

    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = Hash::make($pass);
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
}
