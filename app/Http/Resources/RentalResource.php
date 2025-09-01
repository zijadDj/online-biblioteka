<?php

namespace App\Http\Resources;

use App\Models\Policy;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class RentalResource extends JsonResource
{
    public function toArray($request)
    {
        $policy = Policy::where('name', 'Rental period')->first();
        $maxDays = $policy ? $policy->period : 30;

        $daysRented = Carbon::parse($this->rented_at)->diffInDays(now());
        $overdue = $daysRented - $maxDays;

        return [
            'id' => $this->id,
            'book_title' => $this->book->name,
            'rented_by' => $this->student->name,
            'librarian' => $this->librarian->name,
            'rented_at' => $this->rented_at,
            'active_days' => $daysRented,
            'returned_at' => $this->returned_at,
            'days_overdue' => $overdue > 0 ? $overdue : 0,
        ];
    }
}
