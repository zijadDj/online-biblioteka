<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $fillable = [
        'full_name',
        'biography',
    ];
    public function books():hasMany{
        return $this->hasMany(Book::class);
    }
    public function images():hasMany{
        return $this->hasMany(Image::class);
    }
}
