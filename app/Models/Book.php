<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
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
}
