<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name() ,
            'category_id' => $this->faker->numberBetween(1,2) ,
            'price' => $this->faker->randomFloat(2 ,1000 ,100000) ,
            'description' => $this->faker->text() ,
            'img' => fake()->randomElement([
                'https://plus.unsplash.com/premium_photo-1694708455249-992010f9db32',
                'http://images.unsplash.com/photo-1717603545758-88cc454db69b',
                'https://images.unsplash.com/photo-1563612116625-3012372fccce',
            ]), 
            'is_active' => $this->faker->boolean()
        ];
    }
}
