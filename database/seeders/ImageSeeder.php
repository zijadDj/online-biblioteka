<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Image;
use App\Models\Book;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        Image::factory(20)->create();

    }
}
