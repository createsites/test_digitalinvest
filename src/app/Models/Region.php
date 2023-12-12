<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = "region";

    protected function rNameRus(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->beautifyName($value)
        );
    }

    protected function rNameEng(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->beautifyName($value)
        );
    }

    protected function rNameGer(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->beautifyName($value)
        );
    }

    // mutate the name of a region
    private function beautifyName($name): string
    {
        $regionName = $name ?? '';
        $regionName = $regionName != null ? $name : '';

        return $regionName;
    }
}