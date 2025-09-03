<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rental;

class RentalSeeder extends Seeder
{
    public function run(): void
    {
        Rental::factory()->count(20)->create();
    }
}
