<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model
{
    protected $fillable = [
        'book_id',
        'student_id',
        'librarian_id',
        'rented_at',
        'returned_at',
    ];

    protected $casts = [
        'rented_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function librarian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'librarian_id');
    }

    public function getDaysRentedAttribute(): int
    {
        $endDate = $this->returned_at ?? now();
        return $this->rented_at->diffInDays($endDate);
    }

    public function getIsOverdueAttribute(): bool
    {
        $policy = \App\Models\Policy::where('name', 'Rental period')->first();
        $limitDays = $policy ? $policy->period : 30;

        return $this->days_rented > $limitDays;
    }
}
