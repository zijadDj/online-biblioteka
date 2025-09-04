<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected  $fillable = [
        'name',
        'description',
        'page_count',
        'unit_count',
        'isbn',
        'language',
        'binding',
        'script',
        'dimensions',
        'publisher_id'
    ];
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function categories():BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
