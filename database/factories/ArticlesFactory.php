<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Articles>
 */
class ArticlesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'articleTitle' => $this->faker->randomLetter(),
            'articleThumbnail' => 'https://via.placeholder.com/150',
            'articleThumbnailTitle' => $this->faker->randomLetter(),
            'articleContent' => $this->faker->text(),
            'user_id' => $this->faker->numberBetween(1, 10),
            'articleSlug' => $this->faker->unique()->slug(),
            'category_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
