<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(), // Custom method or string
            'slug' => $this->faker->randomFloat(2, 10, 1000), // Price between 10 and 1000
            'category_id' => $this->faker->numberBetween(1, 3),
            'description' => $this->faker->text(200),
            'price' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}