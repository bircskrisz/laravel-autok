<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    protected $table = 'postal_codes';

    protected $fillable = [
        'postal_code',
        'city',
        'county_id',
    ];

    public function county()
    {
        return $this->belongsTo(County::class);
    }
}
