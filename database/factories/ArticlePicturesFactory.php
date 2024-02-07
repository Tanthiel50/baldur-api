<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticlePictures>
 */
class ArticlePicturesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pictureTitle' => $this->faker->randomLetter(),
            'picturePath' => 'https://via.placeholder.com/150',
            'article_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
