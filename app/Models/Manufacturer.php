<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $fillable = [
        'name',
        'country',
        'founded_year',
        'website',
        'logo_url',
        'description',
    ];

    public function carModels()
    {
        return $this->hasMany(CarModel::class);
    }
}
