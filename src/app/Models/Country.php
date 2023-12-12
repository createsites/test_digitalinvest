<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $table = "country";

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'c_country_id');
    }
}