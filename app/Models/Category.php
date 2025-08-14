<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'book_id',
    ];

    public function book():hasMany{
        return $this->hasMany(Book::class);
    }
}
