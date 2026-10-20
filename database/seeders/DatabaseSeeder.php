<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'Toyota',
                'country' => 'Japán',
                'founded_year' => 1937,
                'website' => 'https://www.toyota.com',
                'description' => 'Japán autógyártó, a világ egyik legnagyobb járműgyártója, a hibrid hajtás úttörője.',
                'models' => [
                    ['name' => 'Corolla', 'model_code' => 'TY-COR-23', 'model_year' => 2023, 'price' => 8500000, 'body_type' => 'Kombi', 'fuel_type' => 'Hibrid', 'power_hp' => 140],
                    ['name' => 'Yaris', 'model_code' => 'TY-YAR-22', 'model_year' => 2022, 'price' => 6200000, 'body_type' => 'Hatchback', 'fuel_type' => 'Hibrid', 'power_hp' => 116],
                    ['name' => 'RAV4', 'model_code' => 'TY-RAV-23', 'model_year' => 2023, 'price' => 13900000, 'body_type' => 'SUV', 'fuel_type' => 'Hibrid', 'power_hp' => 222],
                ],
            ],
            [
                'name' => 'Volkswagen',
                'country' => 'Németország',
                'founded_year' => 1937,
                'website' => 'https://www.volkswagen.de',
                'description' => 'Wolfsburgi központú német autógyártó, a Volkswagen-konszern névadója.',
                'models' => [
                    ['name' => 'Golf', 'model_code' => 'VW-GLF-23', 'model_year' => 2023, 'price' => 9800000, 'body_type' => 'Hatchback', 'fuel_type' => 'Benzin', 'power_hp' => 130],
                    ['name' => 'Passat', 'model_code' => 'VW-PAS-22', 'model_year' => 2022, 'price' => 12500000, 'body_type' => 'Kombi', 'fuel_type' => 'Dízel', 'power_hp' => 150],
                    ['name' => 'Tiguan', 'model_code' => 'VW-TIG-23', 'model_year' => 2023, 'price' => 14200000, 'body_type' => 'SUV', 'fuel_type' => 'Dízel', 'power_hp' => 150],
                ],
            ],
            [
                'name' => 'Ford',
                'country' => 'USA',
                'founded_year' => 1903,
                'website' => 'https://www.ford.com',
                'description' => 'Amerikai autógyártó, a futószalagos tömeggyártás úttörője.',
                'models' => [
                    ['name' => 'Focus', 'model_code' => 'FD-FOC-21', 'model_year' => 2021, 'price' => 7200000, 'body_type' => 'Hatchback', 'fuel_type' => 'Benzin', 'power_hp' => 125],
                    ['name' => 'Fiesta', 'model_code' => 'FD-FIE-20', 'model_year' => 2020, 'price' => 5900000, 'body_type' => 'Hatchback', 'fuel_type' => 'Benzin', 'power_hp' => 100],
                    ['name' => 'Kuga', 'model_code' => 'FD-KUG-22', 'model_year' => 2022, 'price' => 11200000, 'body_type' => 'SUV', 'fuel_type' => 'Hibrid', 'power_hp' => 190],
                ],
            ],
            [
                'name' => 'Skoda',
                'country' => 'Csehország',
                'founded_year' => 1895,
                'website' => 'https://www.skoda-auto.com',
                'description' => 'Mladá Boleslav-i cseh autógyártó, a Volkswagen-csoport tagja.',
                'models' => [
                    ['name' => 'Octavia', 'model_code' => 'SK-OCT-23', 'model_year' => 2023, 'price' => 9600000, 'body_type' => 'Kombi', 'fuel_type' => 'Dízel', 'power_hp' => 116],
                    ['name' => 'Fabia', 'model_code' => 'SK-FAB-22', 'model_year' => 2022, 'price' => 5800000, 'body_type' => 'Hatchback', 'fuel_type' => 'Benzin', 'power_hp' => 95],
                    ['name' => 'Kodiaq', 'model_code' => 'SK-KOD-23', 'model_year' => 2023, 'price' => 15100000, 'body_type' => 'SUV', 'fuel_type' => 'Dízel', 'power_hp' => 150],
                ],
            ],
            [
                'name' => 'BMW',
                'country' => 'Németország',
                'founded_year' => 1916,
                'website' => 'https://www.bmw.com',
                'description' => 'Müncheni prémium autó- és motorkerékpár-gyártó.',
                'models' => [
                    ['name' => '320i', 'model_code' => 'BM-320-23', 'model_year' => 2023, 'price' => 16900000, 'body_type' => 'Sedan', 'fuel_type' => 'Benzin', 'power_hp' => 184],
                    ['name' => 'X3', 'model_code' => 'BM-X3-22', 'model_year' => 2022, 'price' => 21500000, 'body_type' => 'SUV', 'fuel_type' => 'Dízel', 'power_hp' => 190],
                    ['name' => 'i4', 'model_code' => 'BM-I4-23', 'model_year' => 2023, 'price' => 19800000, 'body_type' => 'Sedan', 'fuel_type' => 'Elektromos', 'power_hp' => 340],
                ],
            ],
            [
                'name' => 'Suzuki',
                'country' => 'Japán',
                'founded_year' => 1909,
                'website' => 'https://www.globalsuzuki.com',
                'description' => 'Japán gyártó, kisautóiról és terepjáróiról ismert; európai gyára Esztergomban működik.',
                'models' => [
                    ['name' => 'Swift', 'model_code' => 'SZ-SWI-23', 'model_year' => 2023, 'price' => 5600000, 'body_type' => 'Hatchback', 'fuel_type' => 'Hibrid', 'power_hp' => 83],
                    ['name' => 'Vitara', 'model_code' => 'SZ-VIT-23', 'model_year' => 2023, 'price' => 8300000, 'body_type' => 'SUV', 'fuel_type' => 'Hibrid', 'power_hp' => 129],
                    ['name' => 'Jimny', 'model_code' => 'SZ-JIM-22', 'model_year' => 2022, 'price' => 7900000, 'body_type' => 'SUV', 'fuel_type' => 'Benzin', 'power_hp' => 102],
                ],
            ],
        ];

        foreach ($data as $row) {
            $models = $row['models'];
            unset($row['models']);

            $manufacturer = Manufacturer::firstOrCreate(['name' => $row['name']], $row);

            foreach ($models as $model) {
                $manufacturer->carModels()->firstOrCreate(['model_code' => $model['model_code']], $model);
            }
        }

        // REST API: teszt felhasználó a tokenes hitelesítéshez + irányítószámok
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => 'password']
        );

        $this->call(PostalCodeSeeder::class);
    }
}
