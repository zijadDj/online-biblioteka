<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected  $fillable = [
        'name',
        'description',
        'google_id',
        'publisher',
        'page_count',
        'unit_count',
        'isbn',
        'language',
        'binding',
        'script',
        'dimensions',
        'genre_id'
    ];
    public function genre(): BelongsTo{
        return $this->belongsTo(Genre::class);
    }
}
