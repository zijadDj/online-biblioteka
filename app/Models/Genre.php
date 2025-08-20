<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Genre extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function books(): BelongsToMany{
        return $this->BelongsToMany(Book::class);
    }
}
