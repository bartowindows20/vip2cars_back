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
        Client::factory()->count(5)
            ->has(Car::factory()->count(10), 'cars')
            ->create();
    }
}
