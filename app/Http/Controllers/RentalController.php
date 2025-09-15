<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentalIndexRequest;
use App\Http\Resources\RentalResource;
use App\Http\Requests\StoreRentalRequest;
use App\Models\Book;
use App\Models\Policy;
use App\Models\Rental;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\warning;

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
                'book_id' => $book->id,
                'student_id' => $request->student_id,
                'librarian_id' => auth()->id(),
                'rented_at' => now(),
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
            Log::info("Rental {$rental->id} is overdue by {$overdue} days.");
            $response['overdue'] = $overdue . " days";
        }

        return response()->json($response, 200);
    }
}
