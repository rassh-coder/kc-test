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
            'price' => $this->faker->randomFloat(2,0.1,5),
            'count' => $this->faker->numberBetween(1, 10),
            'in_use' => 0
        ];
    }
}
