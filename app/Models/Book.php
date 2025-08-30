<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'published_year',
        'available_copies',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
