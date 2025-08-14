<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;

class ImageFactory extends Factory
{
    protected $model = \App\Models\Image::class;

    public function definition()
    {
        return [
            'book_id' => Book::factory(), // create a new book if none exists
            'path' => $this->faker->word() . '.jpg', // random word + .jpg
        ];
    }
}
