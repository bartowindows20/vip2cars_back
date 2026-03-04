<?php

namespace Database\Factories;

use App\Enums\DocumentTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $docType = $this->faker->randomElement(DocumentTypeEnum::cases());
        return [
            'names'             => $this->faker->firstName() . ' ' . $this->faker->firstName(),
            'paternal_surname'  => $this->faker->lastName(),
            'maternal_surname'  => $this->faker->lastName(),
            'document_type'     => $docType->value,
            'document_number'   => $this->documentNumberFor($docType),
            'email'             => $this->faker->unique()->safeEmail(),
            'phone'             => $this->faker->numerify('9########'),
        ];
    }

    private function documentNumberFor(DocumentTypeEnum $type): string
    {
        return match ($type) {
            DocumentTypeEnum::DNI       => $this->faker->unique()->numerify('########'),
            DocumentTypeEnum::CE        => $this->faker->unique()->numerify('#########'),
            DocumentTypeEnum::PASSPORT  => strtoupper($this->faker->unique()->bothify('??######')),
            DocumentTypeEnum::RUC       => $this->faker->unique()->randomElement([
                '10' . $this->faker->numerify('#########'),
                '20' . $this->faker->numerify('#########'),
            ]),
        };
    }
}
