<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentalIndexRequest;
use App\Http\Resources\RentalResource;
use App\Http\Requests\StoreRentalRequest;
use App\Models\Book;
use App\Models\Policy;
use App\Models\Rental;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    public function rented(RentalIndexRequest $request)
    {
        $query = Rental::with(['book', 'student', 'librarian'])
            ->whereNull('returned_at');

        if ($search = $request->input('search')) {
            $query->whereHas('book', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 20);

        $rentals = $query->paginate($perPage);

        return RentalResource::collection($rentals);
    }

    public function historyByBook(Book $book)
    {
        $rentals = Rental::with(['book', 'student', 'librarian'])
            ->where('book_id', $book->id)
            ->get();

        return RentalResource::collection($rentals);
    }

    public function returned(RentalIndexRequest $request)
    {
        $query = Rental::with(['book', 'student', 'librarian'])
            ->whereNotNull('returned_at');

        if ($search = $request->input('search')) {
            $query->whereHas('book', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 20);

        $rentals = $query->paginate($perPage);

        return RentalResource::collection($rentals);
    }

    public function overdue(RentalIndexRequest $request)
    {
        $policy = Policy::where('name', 'Rental period')->first();
        $maxDays = $policy ? $policy->period : 30;

        $query = Rental::with(['book', 'student', 'librarian'])
            ->whereNull('returned_at')
            ->where('rented_at', '<', now()->subDays($maxDays));

        if($search = $request->input('search')) {
            $query->whereHas('book', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 20);

        $rentals = $query->paginate($perPage);

        return RentalResource::collection($rentals);
    }

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
