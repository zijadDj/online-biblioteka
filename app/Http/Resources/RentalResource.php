<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class RentalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'book_title' => $this->book->name,
            'rented_by' => $this->student->name,
            'librarian' => $this->librarian->name,
            'rented_at' => $this->rented_at,
            'active_days' => Carbon::parse($this->rented_at)->diffInDays(now()),
            'returned_at' => $this->returned_at,
        ];
    }
}
