<?php

namespace App\Models;

use Carbon\Carbon as Carbon;

trait TimestampsFormatTrait
{
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->toIso8601String();
    }

    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = Carbon::parse($value)->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->toIso8601String();
    }

    public function setUpdatedAtAttribute($value)
    {
        $this->attributes['updated_at'] = Carbon::parse($value)->toDateTimeString();
    }

    public function getLastLoginAttribute($value)
    {
        return Carbon::parse($value)->toIso8601String();
    }

    public function setLastLoginAttribute($value)
    {
        $this->attributes['last_login'] = Carbon::parse($value)->toDateTimeString();
    }
}
