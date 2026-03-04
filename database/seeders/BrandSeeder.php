<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('brands')->upsert(
            [
                ['name' => 'Toyota',    'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Honda',     'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Ford',      'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Chevrolet', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Hyundai',   'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Kia',       'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Nissan',    'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Mazda',     'created_at' => $now, 'updated_at' => $now],
            ],
            ['name'],
            ['updated_at']
        );
    }
}
