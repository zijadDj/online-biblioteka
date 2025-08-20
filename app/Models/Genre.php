<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

   public function books(): belongsToMany{
       return $this->belongsToMany(Book::class);
   }
}
