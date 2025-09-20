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
            'category_id' => rand(1, 10),
            'product_name' => $this->faker->words(3, true),
            'product_photo' => 'https://picsum.photos/seed/' . $this->faker->uuid . '/640/480',
            'product_price' => $this->faker->numberBetween(5000, 100000),
            'product_description' => $this->faker->sentence(10),
            'product_qty' => $this->faker->numberBetween(0, 100),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
