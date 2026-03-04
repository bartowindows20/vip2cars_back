<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id'         => Client::factory(),
            'car_model_id'      => CarModel::inRandomOrder()->value('id'),
            'plate'             => strtoupper($this->faker->unique()->bothify('???-###')),
            'year_manufacture'  => $this->faker->numberBetween(1995, (int) date('Y')),
        ];
    }
}
