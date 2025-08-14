<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use hasFactory;
    protected $fillable = [
        'name',
        'description',
        'book_id',
    ];

    public function book():belongsToMany{
        return $this->belongsToMany(Book::class);
    }
}
