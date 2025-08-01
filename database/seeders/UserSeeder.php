<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::factory()->count(25)->state([
            'is_librarian' => '1',
        ])->create();


        User::factory()->count(75)->state([
            'is_librarian' => '0',
        ])->create();
    }
}
