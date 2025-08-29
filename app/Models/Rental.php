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
        'action_type',
        'recorded_at',
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


}
