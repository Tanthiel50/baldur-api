<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InterestPoints>
 */
class InterestPointsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pointName' => $this->faker->name(),
            'pointTitle' => $this->faker->randomLetter(),
            'pointSlug' => $this->faker->unique()->slug(),
            'pointDescription' => $this->faker->text(),
            'pointThumbnail' => 'https://via.placeholder.com/150',
            'pointThumbnailTitle' => $this->faker->randomLetter(),
            'user_id' => $this->faker->numberBetween(1, 10),
            'pointtips' => $this->faker->randomLetter(),
            'pointAdress' => $this->faker->address(),
            'pointSpeciality' => $this->faker->randomLetter(),
            'pointContent' => $this->faker->text(),
            'pointCategories_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
