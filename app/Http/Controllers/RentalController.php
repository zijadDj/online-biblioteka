<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentalIndexRequest;
use App\Http\Resources\RentalResource;
use App\Models\Rental;

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
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 20);

        $rentals = $query->paginate($perPage);

        return RentalResource::collection($rentals);
    }

    public function overdue(RentalIndexRequest $request)
    {
        $maxDays = 14;

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

}
