<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookFactory extends Factory
{
    public function definition()
    {
        $bindings = ['hardcover', 'paperback', 'spiral_bound'];
        $scripts = ['latin', 'cyrillic', 'arabic'];
        $dimensions = ['A1', 'A2', '21cm x 29.7cm', '15cm x 21cm'];

        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'page_count' => $this->faker->numberBetween(50, 1000),
            'unit_count' => $this->faker->numberBetween(1, 100),
            'isbn' => $this->faker->unique()->isbn13(),
            'language' => $this->faker->randomElement(['English', 'French', 'German', 'Spanish', 'Italian']),
            'binding' => $this->faker->randomElement($bindings),
            'script' => $this->faker->randomElement($scripts),
            'dimensions' => $this->faker->randomElement($dimensions),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
