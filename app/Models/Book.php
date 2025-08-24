<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    ];



    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function categories():belongsToMany{
        return $this->belongsToMany(Category::class);
    }
}
