<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::factory()->count(100)
            ->has(Car::factory()->count(2), 'cars')
            ->create();
    }
}
