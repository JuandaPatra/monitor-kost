<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Properties>
 */
class PropertiesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
    */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1,10),
            'name' => 'Kost '. fake()->name(),
            'address' => $this->faker->streetName() . ' '. $this->faker->city(),
            'price' => '700000',
            'description' => 'description kost kostan ',
        ];
    }
}
