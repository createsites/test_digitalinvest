<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $table = "city";

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'c_region_id');
    }
}