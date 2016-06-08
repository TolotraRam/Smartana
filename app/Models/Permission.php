<?php

namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    use TimestampsFormatTrait;

    protected $fillable = ['name', 'display_name', 'description'];
}
