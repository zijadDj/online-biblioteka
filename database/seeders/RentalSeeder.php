<?php

namespace Database\Seeders;

use App\Models\Rental;
use App\Models\Book;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    public function run(): void
    {
        $book = \DB::table('books')->first();
        $student = \DB::table('users')->where('role', 'student')->first();
        $librarian = User::first();

        if ($book && $student && $librarian) {
            Rental::create([
                'book_id' => $book->id,
                'student_id' => $student->id,
                'librarian_id' => $librarian->id,
                'rented_at' => now()->subDays(3),
                'returned_at' => null,
            ]);
        }
    }
}
