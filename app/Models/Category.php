<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function books():hasMany{
        return $this->hasMany(Book::class);
    }
}
