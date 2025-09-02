<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRentalRequest;
use App\Models\Book;
use App\Models\Policy;
use App\Models\Rental;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function store(StoreRentalRequest $request)
    {
        $book = Book::findOrFail($request->book_id);

        DB::transaction(function () use ($book, $request) {

            $book->decrement('available_copies');

            Rental::create([
                'book_id' => $book->id,
                'student_id' => $request->student_id,
                'librarian_id' => $request->librarian_id,
                'rented_at' => $request->rented_at,
            ]);
        });

        return response()->json([
            'message' => 'Book rented successfully'
        ], 201);
    }

    public function show(Rental $rental)
    {
        return response()->json($rental);
    }

    public function returnBook(Request $request, $rentalId)
    {
        $rental = Rental::with('book')->findOrFail($rentalId);

        if (!$rental) {
            return response()->json([
                'message' => 'Book has not been rented'
            ], 404);
        }

        if ($rental->returned_at) {
            return response()->json([
                'message' => 'Book has already been returned'
            ], 400);
        }

        $policy = Policy::where('name', 'Rental period')->first();

        $policyPeriod = $policy ? (int)$policy->period : 30;

        $overdue = $rental->getDaysRentedAttribute() - $policyPeriod;

        $rental->update([
            'returned_at' => now(),
            'librarian_id' => auth()->id(),
        ]);

        $rental->book->available_copies += 1;
        $rental->book->save();

        $response = [
            'message' => 'Book returned successfully',
            'rental' => $rental,
        ];

        if ($overdue > 0) {
            $response['overdue'] = $overdue . " days";
        }

        return response()->json($response, 200);
    }
}
