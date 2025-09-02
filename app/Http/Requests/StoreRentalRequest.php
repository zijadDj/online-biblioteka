<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Book;

class StoreRentalRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'book_id'      => ['required', 'exists:books,id'],
            'student_id'   => ['required', 'exists:users,id'],
            'librarian_id' => ['required', 'exists:users,id'],
            'rented_at'    => ['required', 'date'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $book = Book::find($this->book_id);

            if ($book && $book->available_copies < 1) {
                $validator->errors()->add(
                    'book_id',
                    "You can’t rent out books if there are none in the library as they were all rented out"
                );
            }
        });
    }
}

