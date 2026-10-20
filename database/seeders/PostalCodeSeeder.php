<?php

namespace Database\Seeders;

use App\Models\County;
use App\Models\PostalCode;
use Illuminate\Database\Seeder;
use RuntimeException;

/**
 * Megyék és irányítószámok betöltése a database/data/iranyitoszamok.csv fájlból.
 * Formátum: irsz;telepules;megye (első sor fejléc). A teljes országos lista is
 * betölthető, ha ugyanilyen formátumban lecseréled a fájlt.
 */
class PostalCodeSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/iranyitoszamok.csv');
        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new RuntimeException("Nem olvasható: {$path}");
        }

        fgetcsv($handle, 0, ';'); // fejléc
        $counties = [];

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($row) < 3) {
                continue;
            }

            [$code, $city, $countyName] = array_map('trim', $row);
            $counties[$countyName] ??= County::firstOrCreate(['name' => $countyName])->id;

            PostalCode::firstOrCreate(
                ['postal_code' => $code, 'city' => $city],
                ['county_id' => $counties[$countyName]]
            );
        }

        fclose($handle);
    }
}
