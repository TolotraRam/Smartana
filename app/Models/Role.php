<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    use TimestampsFormatTrait;

    protected $fillable = ['name', 'display_name', 'description'];
}
