<?php

namespace Database\Seeders;

use App\Models\County;
use App\Models\PostalCode;
use Illuminate\Database\Seeder;

class PostalCodeSeeder extends Seeder
{
    public function run(): void
    {
        $handle = fopen(database_path('data/iranyitoszamok.csv'), 'r');
        fgetcsv($handle, 0, ';');

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            [$code, $city, $countyName] = $row;
            $county = County::firstOrCreate(['name' => $countyName]);

            PostalCode::create([
                'postal_code' => $code,
                'city' => $city,
                'county_id' => $county->id,
            ]);
        }

        fclose($handle);
    }
}
