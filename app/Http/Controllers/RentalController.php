<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentalIndexRequest;
use App\Http\Resources\RentalResource;
use App\Models\Rental;

class RentalController extends Controller
{
    public function index(RentalIndexRequest $request)
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
}
