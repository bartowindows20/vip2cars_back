<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarModelSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $brandIds = DB::table('brands')
            ->pluck('id', 'name');

        $models = [
            ['brand' => 'Toyota', 'name' => 'Corolla'],
            ['brand' => 'Toyota', 'name' => 'Yaris'],
            ['brand' => 'Toyota', 'name' => 'Hilux'],

            ['brand' => 'Honda', 'name' => 'Civic'],
            ['brand' => 'Honda', 'name' => 'CR-V'],
            ['brand' => 'Honda', 'name' => 'Fit'],

            ['brand' => 'Ford', 'name' => 'Fiesta'],
            ['brand' => 'Ford', 'name' => 'Focus'],
            ['brand' => 'Ford', 'name' => 'Ranger'],

            ['brand' => 'Chevrolet', 'name' => 'Onix'],
            ['brand' => 'Chevrolet', 'name' => 'Cruze'],
            ['brand' => 'Chevrolet', 'name' => 'S10'],

            ['brand' => 'Hyundai', 'name' => 'Accent'],
            ['brand' => 'Hyundai', 'name' => 'Elantra'],
            ['brand' => 'Hyundai', 'name' => 'Tucson'],

            ['brand' => 'Kia', 'name' => 'Rio'],
            ['brand' => 'Kia', 'name' => 'Cerato'],
            ['brand' => 'Kia', 'name' => 'Sportage'],

            ['brand' => 'Nissan', 'name' => 'Versa'],
            ['brand' => 'Nissan', 'name' => 'Sentra'],
            ['brand' => 'Nissan', 'name' => 'Frontier'],

            ['brand' => 'Mazda', 'name' => 'Mazda 2'],
            ['brand' => 'Mazda', 'name' => 'Mazda 3'],
            ['brand' => 'Mazda', 'name' => 'CX-5'],
        ];

        $rows = [];
        foreach ($models as $m) {
            $brandId = $brandIds[$m['brand']];

            $rows[] = [
                'brand_id'      => $brandId,
                'name'          => $m['name'],
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        DB::table('car_models')->upsert(
            $rows,
            ['brand_id', 'name'],
            ['updated_at']
        );
    }
}
