<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRentalRequest;
use App\Models\Book;
use App\Models\Rental;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    public function store(StoreRentalRequest $request)
    {
        $book = Book::findOrFail($request->book_id);

        DB::transaction(function () use ($book, $request) {

            $book->decrement('available_copies');

            Rental::create([
                'book_id'      => $book->id,
                'student_id'   => $request->student_id,
                'librarian_id' => $request->librarian_id,
                'rented_at'    => $request->rented_at,
            ]);
        });

        return response()->json([
            'message' => 'Book rented successfully'
        ], 201);
    }
}
