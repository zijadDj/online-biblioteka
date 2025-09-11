<?php

namespace Database\Factories;

use App\Models\Rental;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RentalFactory extends Factory
{
    protected $model = Rental::class;

    public function definition(): array
    {
        // Student -> is_librarian = '0'
        $student = User::where('is_librarian', '0')->inRandomOrder()->first();

        // Librarian -> is_librarian = '1'
        $librarian = User::where('is_librarian', '1')->inRandomOrder()->first();

        $book = Book::inRandomOrder()->first();

        return [
            'book_id'      => $book ? $book->id : Book::factory(),
            'student_id'   => $student ? $student->id : User::factory()->create(['is_librarian' => '0'])->id,
            'librarian_id' => $librarian ? $librarian->id : User::factory()->create(['is_librarian' => '1'])->id,
            'rented_at'    => $this->faker->dateTimeBetween('-6 months', 'now'),
            'returned_at'  => $this->faker->optional()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
