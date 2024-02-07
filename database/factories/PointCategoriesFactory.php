<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PointCategories>
 */
class PointCategoriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pointCategoryName' => $this->faker->randomLetter(),
            'pointCategorySlug' => $this->faker->unique()->slug(),
            'pointCategoryDescription' => $this->faker->text(),
        ];
    }
}
