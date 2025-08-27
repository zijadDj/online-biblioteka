<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'path',
        'book_id',
        'type'
    ];

    public function book(){
        return $this->belongsTo(Book::class);
    }
}
