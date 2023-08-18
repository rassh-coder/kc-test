<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->optional()->text(),
            'price' => $price = $this->faker->randomFloat(2, 30, 90),
            'rent_4' => $rent1x = $price / 20,
            'rent_8' => $rent1x * 2,
            'rent_12' => $rent1x * 3,
            'rent_24' => $rent1x * 6,
            'count' => $this->faker->numberBetween(1, 10),
            'in_use' => 0
        ];
    }
}
