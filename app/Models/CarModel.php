<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    public const BODY_TYPES = ['Sedan', 'Hatchback', 'Kombi', 'SUV', 'Coupé', 'Kabrió', 'Egyterű', 'Pickup'];

    public const FUEL_TYPES = ['Benzin', 'Dízel', 'Hibrid', 'Elektromos', 'LPG'];

    protected $table = 'car_models';

    protected $fillable = [
        'manufacturer_id',
        'name',
        'model_code',
        'model_year',
        'price',
        'body_type',
        'fuel_type',
        'power_hp',
        'image_url',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }
}
