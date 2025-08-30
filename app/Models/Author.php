<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'biography',
        'picture',
    ];

    public function books():hasMany{
        return $this->hasMany(Book::class);
    }

}
